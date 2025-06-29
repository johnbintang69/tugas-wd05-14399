<?php

namespace App\Http\Controllers;

use App\Models\DetailPeriksa;
use App\Models\JadwalPeriksa;
use App\Models\DaftarPoli;
use App\Models\Obat;
use App\Models\Periksa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DokterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:dokter');
    }

    /**
     * Menampilkan dashboard dokter
     */
    public function dashboard()
    {
        $user = Auth::user();
        $dokterId = $user->entity_id;
        
        // Mengambil data untuk statistik
        $pasien_count = DaftarPoli::whereHas('jadwal', function($q) use ($dokterId) {
                $q->where('id_dokter', $dokterId);
            })
            ->whereDate('tanggal_daftar', Carbon::today())
            ->count();
            
        $obat_count = Obat::count();
        
        $total_pasien = User::where('role', 'pasien')->count();
        
        $total_periksa = Periksa::whereHas('daftarPoli.jadwal', function($q) use ($dokterId) {
                $q->where('id_dokter', $dokterId);
            })
            ->whereNotNull('catatan')
            ->count();
        
        return view('dokter.dashboard', compact('pasien_count', 'obat_count', 'total_pasien', 'total_periksa'));
    }

    /**
 * Menampilkan daftar pasien yang perlu diperiksa
 */
public function periksa()
{
    $dokterId = Auth::user()->entity_id;
    
    // Mengambil daftar pasien yang perlu diperiksa (dari DaftarPoli)
    $periksa_pasiens = DaftarPoli::whereHas('jadwal', function($q) use ($dokterId) {
            $q->where('id_dokter', $dokterId);
        })
        ->where('status', 'menunggu')
        ->with(['pasien', 'jadwal'])
        ->orderBy('tanggal_daftar', 'asc')
        ->orderBy('no_antrian', 'asc')
        ->get();
    
    // Mengambil riwayat pemeriksaan yang sudah selesai
    $riwayat_periksa = Periksa::whereHas('daftarPoli.jadwal', function($q) use ($dokterId) {
            $q->where('id_dokter', $dokterId);
        })
        ->whereNotNull('catatan')
        ->with(['daftarPoli.pasien', 'obat'])
        ->orderBy('tgl_periksa', 'desc')
        ->limit(10)
        ->get();
    
    // Statistik untuk hari ini
    $total_antrian_hari_ini = DaftarPoli::whereHas('jadwal', function($q) use ($dokterId) {
            $q->where('id_dokter', $dokterId);
        })
        ->whereDate('tanggal_daftar', today())
        ->count();
        
    $total_selesai_hari_ini = DaftarPoli::whereHas('jadwal', function($q) use ($dokterId) {
            $q->where('id_dokter', $dokterId);
        })
        ->where('status', 'selesai')
        ->whereDate('tanggal_daftar', today())
        ->count();
    
    return view('dokter.periksa', compact(
        'periksa_pasiens', 
        'riwayat_periksa',
        'total_antrian_hari_ini',
        'total_selesai_hari_ini'
    ));
}
    /**
     * Menampilkan form edit periksa
     */
    public function periksaEdit($daftarPoliId)
    {
        $dokterId = Auth::user()->entity_id;
        
        $daftarPoli = DaftarPoli::whereHas('jadwal', function($q) use ($dokterId) {
                $q->where('id_dokter', $dokterId);
            })
            ->with(['pasien', 'jadwal.dokter'])
            ->findOrFail($daftarPoliId);
            
        $obats = Obat::orderBy('nama_obat')->get();
        
        // Cek apakah sudah ada data periksa
        $periksa = $daftarPoli->periksa;
        
        return view('dokter.periksa-edit', compact('daftarPoli', 'periksa', 'obats'));
    }

    /**
     * Update hasil pemeriksaan
     */
    public function periksaUpdate(Request $request, $daftarPoliId)
    {
        $request->validate([
            'catatan' => 'required|string',
            'biaya_periksa' => 'required|numeric|min:0',
            'obat_ids' => 'nullable|array',
            'obat_ids.*' => 'exists:obat,id',
        ]);

        DB::beginTransaction();
        
        try {
            $dokterId = Auth::user()->entity_id;
            
            $daftarPoli = DaftarPoli::whereHas('jadwal', function($q) use ($dokterId) {
                    $q->where('id_dokter', $dokterId);
                })
                ->findOrFail($daftarPoliId);
            
            // Cek apakah sudah ada periksa atau buat baru
            $periksa = $daftarPoli->periksa;
            if (!$periksa) {
                $periksa = new Periksa();
                $periksa->id_daftar_poli = $daftarPoli->id;
                $periksa->tgl_periksa = now();
            }
            
            // Update data periksa
            $periksa->catatan = $request->catatan;
            $periksa->biaya_periksa = $request->biaya_periksa;
            $periksa->save();
            
            // Hapus obat lama
            DetailPeriksa::where('id_periksa', $periksa->id)->delete();
            
            // Tambahkan obat yang dipilih
            if ($request->has('obat_ids')) {
                foreach ($request->obat_ids as $obat_id) {
                    DetailPeriksa::create([
                        'id_periksa' => $periksa->id,
                        'id_obat' => $obat_id
                    ]);
                    
                    // Tambahkan harga obat ke biaya pemeriksaan
                    $obat = Obat::findOrFail($obat_id);
                    $periksa->biaya_periksa += $obat->harga;
                }
                $periksa->save();
            }
            
            // Update status daftar poli
            $daftarPoli->status = 'selesai';
            $daftarPoli->save();
            
            DB::commit();
            
            return redirect()->route('dokter.periksa')
                ->with('success', 'Data pemeriksaan berhasil disimpan!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan detail pemeriksaan
     */
    public function periksaShow($periksaId)
    {
        $dokterId = Auth::user()->entity_id;
        
        $periksa = Periksa::whereHas('daftarPoli.jadwal', function($q) use ($dokterId) {
                $q->where('id_dokter', $dokterId);
            })
            ->with(['daftarPoli.pasien', 'obat'])
            ->findOrFail($periksaId);
        
        return view('dokter.periksa-show', compact('periksa'));
    }

    /**
     * JADWAL PERIKSA MANAGEMENT
     */
    public function jadwal()
    {
        $dokterId = Auth::user()->entity_id;
        $jadwals = JadwalPeriksa::where('id_dokter', $dokterId)
                                ->orderBy('hari')
                                ->get();
        
        return view('dokter.jadwal', compact('jadwals'));
    }

    public function jadwalStore(Request $request)
    {
        $request->validate([
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        try {
            JadwalPeriksa::create([
                'id_dokter' => Auth::user()->entity_id,
                'hari' => $request->hari,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'aktif' => false
            ]);

            return redirect()->route('dokter.jadwal')
                ->with('success', 'Jadwal berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function jadwalUpdate(Request $request, $id)
    {
        $request->validate([
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        $jadwal = JadwalPeriksa::where('id', $id)
                              ->where('id_dokter', Auth::user()->entity_id)
                              ->firstOrFail();

        // Cek apakah hari ini
        $today = now()->locale('id')->dayName;
        if ($jadwal->hari === $today) {
            return back()->with('error', 'Tidak boleh mengubah jadwal di hari H!');
        }

        try {
            $jadwal->update([
                'hari' => $request->hari,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
            ]);

            return redirect()->route('dokter.jadwal')
                ->with('success', 'Jadwal berhasil diupdate!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
    
    /**
     * Mengaktifkan jadwal periksa tertentu
     * Hanya satu jadwal yang bisa aktif dalam satu waktu
     * Tidak boleh mengaktifkan/menonaktifkan jadwal di hari H
     * 
     * @param int $id ID jadwal yang akan diaktifkan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function jadwalActivate($id)
    {
        DB::beginTransaction();
        
        try {
            // Dapatkan jadwal yang akan diaktifkan
            $jadwal = JadwalPeriksa::where('id', $id)
                                 ->where('id_dokter', Auth::user()->entity_id)
                                 ->firstOrFail();
            
            // Cek apakah ada jadwal aktif di hari yang sama dengan hari ini
            $today = now()->locale('id')->dayName;
            $activeToday = JadwalPeriksa::where('id_dokter', $jadwal->id_dokter)
                                      ->where('hari', $today)
                                      ->where('aktif', true)
                                      ->exists();
            
            // Jika ada jadwal aktif di hari ini, tolak perubahan
            if ($activeToday) {
                throw new \Exception('Tidak dapat mengubah jadwal aktif di hari yang sama!');
            }
            
            // Nonaktifkan semua jadwal dokter ini
            JadwalPeriksa::where('id_dokter', $jadwal->id_dokter)
                        ->update(['aktif' => false]);
            
            // Aktifkan jadwal yang dipilih
            $jadwal->aktif = true;
            $jadwal->save();
            
            DB::commit();
            
            return redirect()->route('dokter.jadwal')
                           ->with('success', 'Jadwal berhasil diaktifkan!');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengaktifkan jadwal: ' . $e->getMessage());
        }
    }

    /**
     * OBAT MANAGEMENT
     */
    public function obat()
    {
        $obats = Obat::orderBy('nama_obat')->get();
        return view('dokter.obat', compact('obats'));
    }

    public function obatStore(Request $request)
    {
        $request->validate([
            'nama_obat' => 'required|string|max:50',
            'kemasan' => 'required|string|max:35',
            'harga' => 'required|integer|min:0',
        ]);

        $obat = Obat::create([
            'nama_obat' => $request->nama_obat,
            'kemasan' => $request->kemasan,
            'harga' => $request->harga,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Obat berhasil ditambahkan!',
                'data' => $obat
            ]);
        }

        return redirect()->route('dokter.obat')
            ->with('success', 'Obat berhasil ditambahkan!');
    }

    public function obatUpdate(Request $request, $id)
    {
        $request->validate([
            'nama_obat' => 'required|string|max:50',
            'kemasan' => 'required|string|max:35',
            'harga' => 'required|integer|min:0',
        ]);

        $obat = Obat::findOrFail($id);
        $obat->nama_obat = $request->nama_obat;
        $obat->kemasan = $request->kemasan;
        $obat->harga = $request->harga;
        $obat->save();

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Obat berhasil diperbarui!',
                'data' => $obat
            ]);
        }

        return redirect()->route('dokter.obat')
            ->with('success', 'Obat berhasil diperbarui!');
    }

    public function obatDestroy($id)
    {
        // Cek apakah obat sedang digunakan di DetailPeriksa
        $used = DetailPeriksa::where('id_obat', $id)->exists();
        
        if ($used) {
            if (request()->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Obat tidak dapat dihapus karena sedang digunakan dalam resep!'
                ], 422);
            }
            
            return redirect()->route('dokter.obat')
                ->with('error', 'Obat tidak dapat dihapus karena sedang digunakan dalam resep!');
        }
        
        $obat = Obat::findOrFail($id);
        $obatName = $obat->nama_obat;
        $obat->delete();

        if (request()->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => "Obat {$obatName} berhasil dihapus!"
            ]);
        }

        return redirect()->route('dokter.obat')
            ->with('success', "Obat {$obatName} berhasil dihapus!");
    }

    /**
     * PROFIL DOKTER MANAGEMENT
     */
    public function profil()
    {
        $user = Auth::user();
        $dokter = $user->dokter;
        $polis = \App\Models\Poli::orderBy('nama_poli')->get();
        
        return view('dokter.profil', compact('dokter', 'polis', 'user'));
    }

    public function profilUpdate(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:150',
            'alamat' => 'nullable|string|max:255',
            'no_hp' => 'required|string|max:15',
            'id_poli' => 'required|exists:poli,id',
        ]);

        $user = Auth::user();
        $dokter = $user->dokter;

        try {
            $dokter->update([
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'id_poli' => $request->id_poli,
            ]);

            return redirect()->route('dokter.profil')
                ->with('success', 'Profil berhasil diperbarui!');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Cek password lama
        if (!\Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password lama tidak sesuai!')->withInput();
        }

        try {
            $user->update([
                'password' => \Hash::make($request->password)
            ]);

            return redirect()->route('dokter.profil')
                ->with('success', 'Password berhasil diperbarui!');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}