@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h1>Data Obat</h1>
    <a href="{{ route('obat.create') }}" class="btn btn-primary mb-3">Tambah Obat</a>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Obat</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($obats as $obat)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $obat->nama_obat }}</td>
                        <td>{{ $obat->harga }}</td>
                        <td>{{ $obat->stok }}</td>
                        <td>
                            <a href="{{ route('obat.edit', $obat->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('obat.destroy', $obat->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Yakin hapus?')" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
