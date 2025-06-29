@if(isset($riwayat_periksa) && count($riwayat_periksa) > 0)
    @foreach($riwayat_periksa as $key => $riwayat)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $riwayat->id }}</td>
            <td>{{ $riwayat->pasien->nama }}</td>
            <td>{{ \Carbon\Carbon::parse($riwayat->tgl_periksa)->format('d M Y H:i') }}</td>
            <td>{{ $riwayat->catatan_dokter }}</td>
            <td>
                @if(isset($riwayat->obat) && count($riwayat->obat) > 0)
                    <ul>
                        @foreach($riwayat->obat as $obat)
                            <li>{{ $obat->nama_obat }} ({{ $obat->kemasan }})</li>
                        @endforeach
                    </ul>
                @else
                    -
                @endif
            </td>
            <td>Rp {{ number_format($riwayat->biaya_periksa, 0, ',', '.') }}</td>
            <td>
                <a href="{{ route('dokter.periksa.show', $riwayat->id) }}" class="btn btn-sm btn-info">
                    <i class="fas fa-eye"></i> Detail
                </a>
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="8" class="text-center">Belum ada riwayat pemeriksaan.</td>
    </tr>
@endif
