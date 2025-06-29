@if(isset($obats) && count($obats) > 0)
    @foreach($obats as $key => $obat)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $obat->nama_obat }}</td>
            <td>{{ $obat->kemasan }}</td>
            <td>Rp {{ number_format($obat->harga, 0, ',', '.') }}</td>
            <td>
                <button type="button" class="btn btn-sm btn-warning btn-edit"
    data-id="{{ $obat->id }}"
    data-nama="{{ $obat->nama_obat }}"
    data-kemasan="{{ $obat->kemasan }}"
    data-harga="{{ $obat->harga }}">
    <i class="fas fa-edit"></i> Edit
</button>
                <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="{{ $obat->id }}" data-nama="{{ $obat->nama_obat }}">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="5" class="text-center">Belum ada data obat.</td>
    </tr>
@endif
