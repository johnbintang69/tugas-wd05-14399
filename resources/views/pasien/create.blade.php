@extends('layouts.main')

@section('content')
<div class="container">
    <h1>Tambah Pasien</h1>

    <form action="{{ route('pasien.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Nama Pasien</label>
            <input type="text" name="nama_pasien" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Alamat</label>
            <input type="text" name="alamat" class="form-control" required>
        </div>
        <div class="form-group">
            <label>No Telepon</label>
            <input type="text" name="no_telp" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Simpan</button>
    </form>
</div>
@endsection
