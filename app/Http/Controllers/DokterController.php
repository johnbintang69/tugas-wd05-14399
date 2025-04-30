<?php

namespace App\Http\Controllers;

use App\Models\DetailPeriksa;
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
        
        // Mengambil data untuk statistik
        $pasien_count = Periksa::where('id_dokter', $user->id)
            ->whereDate('tgl_periksa', Carbon::today())
            ->count();
            
        $obat_count = Obat::count();
        
        $total_pasien = User::where('role', 'pasien')->count();
        
        $total_periksa = Periksa::where('id_dokter', $user->id)
            ->where('catatan_dokter', '!=', null)
            ->where('status', 'done')
            ->count();
        
        // Mengambil daftar pasien yang perlu diperiksa hari ini
        $pasiens_today = Periksa::where('id_dokter', $user->id)
            ->whereDate('tgl_periksa', Carbon::today())
            ->orderBy('tgl_periksa', 'asc')
            ->get();
        
        return view('dokter.dashboard', compact('pasien_count', 'obat_count', 'total_pasien', 'total_periksa', 'pasiens_today'));
    }

    /**
     * Menampilkan daftar pasien yang perlu diperiksa
     */
    public function periksa()
    {
        // Mengambil daftar pasien yang perlu diperiksa
        $periksa_pasiens = Periksa::where('id_dokter', Auth::id())
            ->where('status', 'pending')
            ->orderBy('tgl_periksa', 'asc')
            ->get();
        
        // Mengambil riwayat pemeriksaan yang sudah selesai
        $riwayat_periksa = Periksa::where('id_dokter', Auth::id())
            ->where('catatan_dokter', '!=', null)
            ->where('status', 'done')
            ->orderBy('tgl_periksa', 'desc')
            ->get();
        
        return view('dokter.periksa', compact('periksa_pasiens', 'riwayat_periksa'));
    }

    /**
     * Menampilkan form edit periksa
     */
    public function periksaEdit($id)
    {
        $periksa = Periksa::where('id', $id)
            ->where('id_dokter', Auth::id())
            ->firstOrFail();
            
        $obats = Obat::orderBy('nama_obat')->get();
        
        return view('dokter.periksa-edit', compact('periksa', 'obats'));
    }

    /**
     * Update hasil pemeriksaan
     */
    public function periksaUpdate(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'required|string',
            'biaya_periksa' => 'required|numeric|min:0',
            'obat_ids' => 'nullable|array',
            'obat_ids.*' => 'exists:obat,id',
        ]);

        DB::beginTransaction();
        
        try {
            $periksa = Periksa::where('id', $id)
                ->where('id_dokter', Auth::id())
                ->firstOrFail();
                
            // simpan catatan dokter dan set status selesai
            $periksa->catatan_dokter = $request->catatan;
            $periksa->biaya_periksa = $request->biaya_periksa;
            $periksa->status = 'done';
            $periksa->save();
            
            // Hapus semua obat sebelumnya jika ada
            DetailPeriksa::where('id_periksa', $periksa->id)->delete();
            
            // Tambahkan obat yang dipilih
            if ($request->has('obat_ids')) {
                foreach ($request->obat_ids as $obat_id) {
                    $detailPeriksa = new DetailPeriksa();
                    $detailPeriksa->id_periksa = $periksa->id;
                    $detailPeriksa->id_obat = $obat_id;
                    $detailPeriksa->save();
                    
                    // Tambahkan harga obat ke biaya pemeriksaan
                    $obat = Obat::findOrFail($obat_id);
                    $periksa->biaya_periksa += $obat->harga;
                }
                
                // Update biaya total (catatan dokter dan status sudah di-save)
                $periksa->save();
            }
            
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
    public function periksaShow($id)
    {
        $periksa = Periksa::with('obat')->findOrFail($id);
        
        if ($periksa->id_dokter != Auth::id()) {
            return redirect()->route('dokter.periksa')
                ->with('error', 'Anda tidak memiliki akses ke data ini!');
        }
        
        return view('dokter.periksa-show', compact('periksa'));
    }

    /**
     * Menampilkan halaman manajemen obat
     */
    public function obat()
    {
        $obats = Obat::orderBy('nama_obat')->get();
        
        return view('dokter.obat', compact('obats'));
    }

    /**
 * Menyimpan obat baru
 */
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

/**
 * Update data obat
 */
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

/**
 * Hapus data obat
 */
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
}