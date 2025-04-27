@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h1>Edit Obat</h1>

    <form action="{{ route('obat.update', $obat->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>Nama Obat</label>
            <input type="text" name="nama_obat" class="form-control" value="{{ $obat->nama_obat }}" required>
        </div>
        <div class="form-group">
            <label>Harga</label>
            <input type="number" name="harga" class="form-control" value="{{ $obat->harga }}" required>
        </div>
        <div class="form-group">
            <label>Stok</label>
            <input type="number" name="stok" class="form-control" value="{{ $obat->stok }}" required>
        </div>
        <button class="btn btn-primary mt-3">Update</button>
    </form>
</div>
@endsection
