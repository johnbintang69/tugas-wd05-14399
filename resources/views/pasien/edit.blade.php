@extends('layouts.main')

@section('content')
<div class="container">
    <h1>Edit Pasien</h1>

    <form action="{{ route('pasien.update', $pasien->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>Nama Pasien</label>
            <input type="text" name="nama_pasien" value="{{ $pasien->nama_pasien }}" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Alamat</label>
            <input type="text" name="alamat" value="{{ $pasien->alamat }}" class="form-control" required>
        </div>
        <div class="form-group">
            <label>No Telepon</label>
            <input type="text" name="no_telp" value="{{ $pasien->no_telp }}" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success mt-2">Update</button>
    </form>
</div>
@endsection
