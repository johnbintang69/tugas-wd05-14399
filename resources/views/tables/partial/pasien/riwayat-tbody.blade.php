<!-- resources/views/tables/partial/pasien/riwayat-tbody.blade.php -->
@if(isset($riwayats) && count($riwayats) > 0)
    @foreach($riwayats as $key => $riwayat)
    <tr>
        <td>{{ $key + 1 }}</td>
        <td>
            <span class="badge badge-primary">#{{ $riwayat->id }}</span>
        </td>
        <td>{{ \Carbon\Carbon::parse($riwayat->tgl_periksa)->format('d M Y') }}</td>
        <td>{{ $riwayat->daftarPoli->jadwal->dokter->nama }}</td>
        <td>
            <span class="badge badge-info">{{ $riwayat->daftarPoli->jadwal->dokter->poli->nama_poli }}</span>
        </td>
        <td>{{ Str::limit($riwayat->catatan, 50) }}</td>
        <td>
            @if(count($riwayat->obat) > 0)
                <span class="badge badge-success">{{ count($riwayat->obat) }} obat</span>
            @else
                <span class="text-muted">-</span>
            @endif
        </td>
        <td>
            <strong>Rp {{ number_format($riwayat->biaya_periksa, 0, ',', '.') }}</strong>
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modal-detail-{{ $riwayat->id }}">
                <i class="fas fa-eye"></i> Detail
            </button>
        </td>
    </tr>
    @endforeach
@else
    <tr>
        <td colspan="9" class="text-center py-4">
            <div class="text-muted">
                <i class="fas fa-file-medical fa-3x mb-3"></i>
                <p>Belum ada riwayat pemeriksaan.</p>
            </div>
        </td>
    </tr>
@endif