@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h1>Tambah Obat</h1>

    <form action="{{ route('obat.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Nama Obat</label>
            <input type="text" name="nama_obat" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Harga</label>
            <input type="number" name="harga" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Stok</label>
            <input type="number" name="stok" class="form-control" required>
        </div>
        <button class="btn btn-primary mt-3">Simpan</button>
    </form>
</div>
@endsection
