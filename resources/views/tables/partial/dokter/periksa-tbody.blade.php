@if(isset($periksa_pasiens) && count($periksa_pasiens) > 0)
    @foreach($periksa_pasiens as $key => $daftarPoli)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $daftarPoli->id }}</td>
            <td>{{ $daftarPoli->pasien->nama }}</td>
            <td>{{ \Carbon\Carbon::parse($daftarPoli->tanggal_daftar)->format('d M Y') }}</td>
            <td>
                <span class="badge badge-primary">#{{ $daftarPoli->no_antrian }}</span>
            </td>
            <td>{{ $daftarPoli->keluhan }}</td>
            <td>
                @if($daftarPoli->status == 'menunggu')
                    <span class="badge bg-warning">Menunggu</span>
                @elseif($daftarPoli->status == 'sedang_diperiksa')
                    <span class="badge bg-info">Sedang Diperiksa</span>
                @else
                    <span class="badge bg-success">Selesai</span>
                @endif
            </td>
            <td>
                @if($daftarPoli->status == 'menunggu' || $daftarPoli->status == 'sedang_diperiksa')
                <a href="{{ route('dokter.periksa.edit', $daftarPoli->id) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-stethoscope"></i> Periksa
                </a>
                @else
                @if($daftarPoli->periksa)
                <a href="{{ route('dokter.periksa.show', $daftarPoli->periksa->id) }}" class="btn btn-sm btn-info">
                    <i class="fas fa-eye"></i> Detail
                </a>
                @endif
                @endif
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="8" class="text-center">Tidak ada pasien yang perlu diperiksa.</td>
    </tr>
@endif