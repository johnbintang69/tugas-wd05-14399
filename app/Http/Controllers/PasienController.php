<?php

namespace App\Http\Controllers;

use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use App\Models\Obat;
use App\Models\Periksa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PasienController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:pasien');
    }

    /**
     * Menampilkan dashboard pasien
     */
    public function dashboard()
    {
        $user = Auth::user();
        $pasienId = $user->entity_id;
        
        // Mengambil data untuk statistik
        $riwayat_count = DaftarPoli::where('id_pasien', $pasienId)->count();
        $dokter_count = User::where('role', 'dokter')->count();
        $obat_count = Obat::count();
        
        // Mengambil riwayat periksa terbaru
        $riwayats = Periksa::whereHas('daftarPoli', function($q) use ($pasienId) {
                $q->where('id_pasien', $pasienId);
            })
            ->whereNotNull('catatan')
            ->with(['daftarPoli.jadwal.dokter', 'obat'])
            ->orderBy('tgl_periksa', 'desc')
            ->limit(5)
            ->get();
        
        // Mengambil daftar jadwal dokter yang aktif untuk hari ini
        $today = now()->locale('id')->dayName;
        $jadwals = JadwalPeriksa::where('aktif', true)
                                ->where('hari', $today)
                                ->with('dokter.poli')
                                ->get();
        
        return view('pasien.dashboard', compact('riwayat_count', 'dokter_count', 'obat_count', 'riwayats', 'jadwals'));
    }

    /**
     * Menampilkan form pendaftaran periksa
     */
    public function periksa()
    {
        $user = Auth::user();
        $pasienId = $user->entity_id;
        
        // DEBUGGING: Cek entity_id untuk pasien
        if (!$pasienId) {
            \Log::error('Pasien user tanpa entity_id', ['user_id' => $user->id]);
            return redirect()->route('login')->with('error', 'Data pasien tidak lengkap. Hubungi admin!');
        }
        
        // Mengambil jadwal dokter yang aktif
        $jadwals = JadwalPeriksa::where('aktif', true)
                                ->with(['dokter.poli'])
                                ->get();
        
        // Mengambil jadwal periksa yang akan datang (pending) 
        $upcoming_periksa = DaftarPoli::where('id_pasien', $pasienId)
            ->whereIn('status', ['menunggu', 'sedang_diperiksa'])
            ->where('tanggal_daftar', '>=', Carbon::now()->format('Y-m-d'))
            ->with(['jadwal.dokter.poli'])
            ->orderBy('tanggal_daftar', 'asc')
            ->orderBy('no_antrian', 'asc')
            ->get();
        
        return view('pasien.periksa', compact('jadwals', 'upcoming_periksa'));
    }

    /**
     * Menyimpan pendaftaran periksa baru
     */
    public function periksaStore(Request $request)
    {
        $request->validate([
            'id_jadwal' => 'required|exists:jadwal_periksa,id',
            'keluhan' => 'required|string',
        ]);

        $pasienId = Auth::user()->entity_id;
        $jadwal = JadwalPeriksa::findOrFail($request->id_jadwal);
        
        // Cek apakah jadwal masih aktif
        if (!$jadwal->aktif) {
            return back()->with('error', 'Jadwal dokter tidak aktif!')->withInput();
        }
        
        // Cek apakah pasien sudah daftar di hari yang sama ke dokter yang sama
        $today = now()->format('Y-m-d');
        $sudahDaftar = DaftarPoli::where('id_pasien', $pasienId)
                                ->where('tanggal_daftar', $today)
                                ->whereHas('jadwal', function($q) use ($request) {
                                    $q->where('id_dokter', JadwalPeriksa::find($request->id_jadwal)->id_dokter);
                                })
                                ->exists();
        
        if ($sudahDaftar) {
            return back()->with('error', 'Anda sudah mendaftar ke dokter ini hari ini!')->withInput();
        }

        try {
            $daftarPoli = DaftarPoli::create([
                'id_pasien' => $pasienId,
                'id_jadwal' => $request->id_jadwal,
                'keluhan' => $request->keluhan,
                'status' => 'menunggu',
                'tanggal_daftar' => $today
                // no_antrian akan auto-generated di model
            ]);

            return redirect()->route('pasien.periksa')
                ->with('success', "Pendaftaran berhasil! Nomor antrian Anda: {$daftarPoli->no_antrian}");
                
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Membatalkan pendaftaran periksa
     */
    public function periksaDestroy($id)
    {
        $pasienId = Auth::user()->entity_id;
        
        $daftarPoli = DaftarPoli::where('id', $id)
            ->where('id_pasien', $pasienId)
            ->where('status', 'menunggu')
            ->firstOrFail();
        
        // Cek apakah bisa dibatalkan (minimal 2 jam sebelum jadwal)
        try {
            $tanggalDaftar = Carbon::parse($daftarPoli->tanggal_daftar)->format('Y-m-d');
            $jamMulai = Carbon::parse($daftarPoli->jadwal->jam_mulai)->format('H:i:s');
            $jadwalMulai = Carbon::createFromFormat('Y-m-d H:i:s', $tanggalDaftar . ' ' . $jamMulai);
            $batasWaktu = $jadwalMulai->copy()->subHours(2);
            
            if (now() > $batasWaktu) {
                return redirect()->route('pasien.periksa')
                    ->with('error', 'Pendaftaran tidak dapat dibatalkan kurang dari 2 jam sebelum jadwal!');
            }
        } catch (\Exception $e) {
            \Log::error('Error parsing date in periksaDestroy', [
                'error' => $e->getMessage(),
                'tanggal_daftar' => $daftarPoli->tanggal_daftar ?? 'null',
                'jam_mulai' => $daftarPoli->jadwal->jam_mulai ?? 'null'
            ]);
            
            return redirect()->route('pasien.periksa')
                ->with('error', 'Terjadi kesalahan saat memproses pembatalan. Silakan coba lagi.');
        }
        
        $daftarPoli->delete();
        
        return redirect()->route('pasien.periksa')
            ->with('success', 'Pendaftaran periksa berhasil dibatalkan!');
    }

    /**
     * Menampilkan riwayat periksa
     */
    public function riwayat()
    {
        $pasienId = Auth::user()->entity_id;
        
        $riwayats = Periksa::whereHas('daftarPoli', function($q) use ($pasienId) {
                $q->where('id_pasien', $pasienId);
            })
            ->whereNotNull('catatan')
            ->with(['daftarPoli.jadwal.dokter', 'obat'])
            ->orderBy('tgl_periksa', 'desc')
            ->get();
        
        return view('pasien.riwayat', compact('riwayats'));
    }


    /**
     * PROFIL PASIEN MANAGEMENT
     */
    
}