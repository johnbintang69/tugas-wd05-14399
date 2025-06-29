<?php

namespace App\Http\Controllers;

use App\Models\Poli;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Obat;
use App\Models\DaftarPoli;
use App\Models\Periksa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Dashboard Admin
     */
    public function dashboard()
    {
        $stats = [
            'total_poli' => Poli::count(),
            'total_dokter' => Dokter::count(),
            'total_pasien' => Pasien::count(),
            'total_obat' => Obat::count(),
            'total_daftar_hari_ini' => 0, // DaftarPoli::whereDate('tanggal_daftar', today())->count(),
            'total_periksa_bulan_ini' => 0, // Periksa::whereMonth('tgl_periksa', now()->month)->count(),
        ];

        // Data untuk chart (simple version for now)
        $chartData = [
            'pendaftaran_minggu_ini' => $this->getPendaftaranMingguIni(),
            'poli_terpopuler' => [],
        ];

        return view('admin.dashboard', compact('stats', 'chartData'));
    }

    // ======================
    // POLI MANAGEMENT
    // ======================

    public function indexPoli()
    {
        $polis = Poli::withCount('dokter')->orderBy('nama_poli')->get();
        return view('admin.poli.index', compact('polis'));
    }

    public function storePoli(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_poli' => 'required|string|max:25|unique:poli,nama_poli',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Gagal menambah poli!');
        }

        Poli::create([
            'nama_poli' => $request->nama_poli,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('admin.poli.index')
            ->with('success', 'Poli berhasil ditambahkan!');
    }

    public function updatePoli(Request $request, $id)
    {
        $poli = Poli::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'nama_poli' => 'required|string|max:25|unique:poli,nama_poli,' . $id,
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('error', 'Gagal mengupdate poli!');
        }

        $poli->update([
            'nama_poli' => $request->nama_poli,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('admin.poli.index')
            ->with('success', 'Poli berhasil diupdate!');
    }

    public function destroyPoli($id)
    {
        $poli = Poli::findOrFail($id);
        
        // Cek apakah ada dokter yang masih terkait
        if ($poli->dokter()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus poli karena masih ada dokter yang terkait!');
        }

        $poli->delete();

        return redirect()->route('admin.poli.index')
            ->with('success', 'Poli berhasil dihapus!');
    }

    // ======================
    // DOKTER MANAGEMENT
    // ======================

    public function indexDokter()
    {
        $dokters = Dokter::with('poli')->orderBy('nama')->get();
        $polis = Poli::orderBy('nama_poli')->get();
        
        return view('admin.dokter.index', compact('dokters', 'polis'));
    }

    public function storeDokter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:150',
            'alamat' => 'nullable|string|max:255',
            'no_hp' => 'required|string|max:15',
            'id_poli' => 'required|exists:poli,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Gagal menambah dokter!');
        }

        $dokter = Dokter::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'id_poli' => $request->id_poli,
        ]);

        // Auto create user account for dokter
        $this->createUserForDokter($dokter);

        return redirect()->route('admin.dokter.index')
            ->with('success', 'Dokter berhasil ditambahkan!');
    }

    public function updateDokter(Request $request, $id)
    {
        $dokter = Dokter::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:150',
            'alamat' => 'nullable|string|max:255',
            'no_hp' => 'required|string|max:15',
            'id_poli' => 'required|exists:poli,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('error', 'Gagal mengupdate dokter!');
        }

        $dokter->update([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'id_poli' => $request->id_poli,
        ]);

        return redirect()->route('admin.dokter.index')
            ->with('success', 'Dokter berhasil diupdate!');
    }

    public function destroyDokter($id)
    {
        $dokter = Dokter::findOrFail($id);
        
        // Hapus user account dokter juga jika ada
        User::where('role', 'dokter')->where('entity_id', $dokter->id)->delete();
        $dokter->delete();

        return redirect()->route('admin.dokter.index')
            ->with('success', 'Dokter berhasil dihapus!');
    }

    // ======================
    // PASIEN MANAGEMENT
    // ======================

    public function indexPasien()
    {
        $pasiens = Pasien::withCount('daftarPoli')->orderBy('nama')->get();
        return view('admin.pasien.index', compact('pasiens'));
    }

    public function storePasien(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:150',
            'alamat' => 'required|string|max:255',
            'no_ktp' => 'required|string|size:16|unique:pasien,no_ktp',
            'no_hp' => 'required|string|max:15',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Gagal menambah pasien!');
        }

        try {
            $pasien = Pasien::create([
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'no_ktp' => $request->no_ktp,
                'no_hp' => $request->no_hp,
            ]);

            // Auto create user account for pasien
            $this->createUserForPasien($pasien);

            return redirect()->route('admin.pasien.index')
                ->with('success', "Pasien berhasil ditambahkan dengan No RM: {$pasien->no_rm}! Akun login telah dibuat secara otomatis.");
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function updatePasien(Request $request, $id)
    {
        $pasien = Pasien::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:150',
            'alamat' => 'required|string|max:255',
            'no_ktp' => 'required|string|size:16|unique:pasien,no_ktp,' . $id,
            'no_hp' => 'required|string|max:15',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('error', 'Gagal mengupdate pasien!');
        }

        $pasien->update([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_ktp' => $request->no_ktp,
            'no_hp' => $request->no_hp,
        ]);

        return redirect()->route('admin.pasien.index')
            ->with('success', 'Pasien berhasil diupdate!');
    }

    public function destroyPasien($id)
    {
        $pasien = Pasien::findOrFail($id);
        
        // Hapus user account pasien juga jika ada
        User::where('role', 'pasien')->where('entity_id', $pasien->id)->delete();
        $pasien->delete();

        return redirect()->route('admin.pasien.index')
            ->with('success', 'Pasien berhasil dihapus!');
    }

    // ======================
    // OBAT MANAGEMENT
    // ======================

    public function indexObat()
    {
        $obats = Obat::orderBy('nama_obat')->get();
        return view('admin.obat.index', compact('obats'));
    }

    public function storeObat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_obat' => 'required|string|max:50',
            'kemasan' => 'nullable|string|max:35',
            'harga' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Gagal menambah obat!');
        }

        Obat::create([
            'nama_obat' => $request->nama_obat,
            'kemasan' => $request->kemasan,
            'harga' => $request->harga,
        ]);

        return redirect()->route('admin.obat.index')
            ->with('success', 'Obat berhasil ditambahkan!');
    }

    public function updateObat(Request $request, $id)
    {
        $obat = Obat::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'nama_obat' => 'required|string|max:50',
            'kemasan' => 'nullable|string|max:35',
            'harga' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('error', 'Gagal mengupdate obat!');
        }

        $obat->update([
            'nama_obat' => $request->nama_obat,
            'kemasan' => $request->kemasan,
            'harga' => $request->harga,
        ]);

        return redirect()->route('admin.obat.index')
            ->with('success', 'Obat berhasil diupdate!');
    }

    public function destroyObat($id)
    {
        $obat = Obat::findOrFail($id);
        $obatName = $obat->nama_obat;
        $obat->delete();

        return redirect()->route('admin.obat.index')
            ->with('success', "Obat {$obatName} berhasil dihapus!");
    }

    // ======================
    // HELPER METHODS
    // ======================

    private function createUserForDokter($dokter)
    {
        $email = $this->generateEmailFromName($dokter->nama, 'dokter');
        
        User::create([
            'email' => $email,
            'password' => Hash::make('password123'),
            'role' => 'dokter',
            'entity_id' => $dokter->id,
        ]);
    }
    
    private function createUserForPasien($pasien)
    {
        $email = $this->generateEmailFromName($pasien->nama, 'pasien');
        
        User::create([
            'email' => $email,
            'password' => Hash::make('password123'),
            'role' => 'pasien',
            'entity_id' => $pasien->id,
        ]);
    }

    private function generateEmailFromName($nama, $role)
    {
        $cleanName = strtolower($nama);
        $cleanName = str_replace(['dr.', 'drg.', 'sp.pd', 'sp.a', 'sp.m', 'sp.og'], '', $cleanName);
        $cleanName = preg_replace('/[^a-zA-Z\s]/', '', $cleanName);
        $cleanName = trim($cleanName);
        
        $nameParts = explode(' ', $cleanName);
        $firstName = !empty($nameParts[0]) ? $nameParts[0] : 'user';
        $secondName = isset($nameParts[1]) ? $nameParts[1] : '';
        
        $baseEmail = $firstName . $secondName . '@' . $role . '.poliklinik.com';
        
        // Cek duplikasi dan tambah angka jika perlu
        $counter = 1;
        $email = $baseEmail;
        while (User::where('email', $email)->exists()) {
            $email = $firstName . $secondName . $counter . '@' . $role . '.poliklinik.com';
            $counter++;
        }
        
        return $email;
    }

    private function getPendaftaranMingguIni()
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $data[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $date->format('D'),
                'count' => rand(0, 10) // Dummy data for now
            ];
        }
        return $data;
    }
}