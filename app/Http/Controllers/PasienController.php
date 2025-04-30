<?php

namespace App\Http\Controllers;

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
        
        // Mengambil data untuk statistik
        $riwayat_count = Periksa::where('id_pasien', $user->id)->count();
        $dokter_count = User::where('role', 'dokter')->count();
        $obat_count = Obat::count();
        
        // Mengambil riwayat periksa terbaru
        $riwayats = Periksa::where('id_pasien', $user->id)
            ->where('catatan_dokter', '!=', null)
            ->orderBy('tgl_periksa', 'desc')
            ->limit(5)
            ->get();
        
        // Mengambil daftar dokter
        $dokters = User::where('role', 'dokter')->get();
        
        return view('pasien.dashboard', compact('riwayat_count', 'dokter_count', 'obat_count', 'riwayats', 'dokters'));
    }

    /**
     * Menampilkan form pendaftaran periksa
     */
    public function periksa()
    {
        $dokters = User::where('role', 'dokter')->get();
        
        // Mengambil jadwal periksa yang akan datang (pending)
        $upcoming_periksa = Periksa::where('id_pasien', Auth::id())
            ->where('status', 'pending')
            ->where('tgl_periksa', '>=', Carbon::now())
            ->orderBy('tgl_periksa', 'asc')
            ->get();
        
        return view('pasien.periksa', compact('dokters', 'upcoming_periksa'));
    }

    /**
     * Menyimpan pendaftaran periksa baru
     */
    public function periksaStore(Request $request)
    {
        $request->validate([
            'id_dokter' => 'required|exists:users,id',
            'tgl_periksa' => 'required|date|after_or_equal:today',
            'keluhan' => 'required|string',
        ]);

        $periksa = new Periksa();
        $periksa->id_pasien = Auth::id();
        $periksa->id_dokter = $request->id_dokter;
        $periksa->tgl_periksa = $request->tgl_periksa;
        $periksa->keluhan = $request->keluhan;
        $periksa->catatan_dokter = null;
        $periksa->status = 'pending';
        $periksa->biaya_periksa = 0;
        $periksa->save();

        return redirect()->route('pasien.periksa')
            ->with('success', 'Pendaftaran periksa berhasil disimpan!');
    }

    /**
     * Membatalkan pendaftaran periksa
     */
    public function periksaDestroy($id)
    {
        $periksa = Periksa::where('id', $id)
            ->where('id_pasien', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();
        
        $periksa->delete();
        
        return redirect()->route('pasien.periksa')
            ->with('success', 'Pendaftaran periksa berhasil dibatalkan!');
    }

    /**
     * Menampilkan riwayat periksa
     */
    public function riwayat()
    {
        $riwayats = Periksa::where('id_pasien', Auth::id())
            ->where('catatan_dokter', '!=', null)
            ->where('status', 'done')
            ->orderBy('tgl_periksa', 'desc')
            ->get();
        
        return view('pasien.riwayat', compact('riwayats'));
    }
}