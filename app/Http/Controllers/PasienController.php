<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Illuminate\Http\Request;

class PasienController extends Controller
{
    public function index()
    {
        $pasiens = Pasien::all();
        return view('pasien.index', compact('pasiens'));
    }

    public function create()
    {
        return view('pasien.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pasien' => 'required',
            'alamat' => 'required',
            'no_telp' => 'required',
        ]);

        Pasien::create($request->all());

        return redirect()->route('pasien.index')->with('success', 'Pasien berhasil ditambahkan');
    }

    public function edit($id)
    {
        $pasien = Pasien::findOrFail($id);
        return view('pasien.edit', compact('pasien'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pasien' => 'required',
            'alamat' => 'required',
            'no_telp' => 'required',
        ]);

        $pasien = Pasien::findOrFail($id);
        $pasien->update($request->all());

        return redirect()->route('pasien.index')->with('success', 'Pasien berhasil diupdate');
    }

    public function destroy($id)
    {
        $pasien = Pasien::findOrFail($id);
        $pasien->delete();

        return redirect()->route('pasien.index')->with('success', 'Pasien berhasil dihapus');
    }
}
