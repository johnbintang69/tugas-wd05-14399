<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    public function index()
    {
        $obats = Obat::all();
        return view('obat.index', compact('obats'));
    }

    public function create()
    {
        return view('obat.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_obat' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|numeric',
        ]);

        Obat::create($request->all());

        return redirect()->route('obat.index')->with('success', 'Obat berhasil ditambahkan.');
    }

    public function edit(Obat $obat)
    {
        return view('obat.edit', compact('obat'));
    }

    public function update(Request $request, Obat $obat)
    {
        $request->validate([
            'nama_obat' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|numeric',
        ]);

        $obat->update($request->all());

        return redirect()->route('obat.index')->with('success', 'Obat berhasil diupdate.');
    }

    public function destroy(Obat $obat)
    {
        $obat->delete();

        return redirect()->route('obat.index')->with('success', 'Obat berhasil dihapus.');
    }
}
