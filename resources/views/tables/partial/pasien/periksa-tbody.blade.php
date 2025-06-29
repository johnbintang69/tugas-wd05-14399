<!-- resources/views/tables/partial/pasien/periksa-tbody.blade.php -->
@if(isset($upcoming_periksa) && count($upcoming_periksa) > 0)
    @foreach($upcoming_periksa as $key => $periksa)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ \Carbon\Carbon::parse($periksa->tanggal_daftar)->format('d M Y') }}</td>
            <td>{{ $periksa->jadwal->dokter->nama }}</td>
            <td>
                <span class="badge badge-info">{{ $periksa->jadwal->dokter->poli->nama_poli }}</span>
            </td>
            <td>
                <span class="badge badge-primary badge-lg">
                    <i class="fas fa-hashtag"></i> {{ $periksa->no_antrian }}
                </span>
            </td>
            <td>
                @if($periksa->status == 'menunggu')
                    <span class="badge badge-warning">
                        <i class="fas fa-clock"></i> Menunggu
                    </span>
                @elseif($periksa->status == 'sedang_diperiksa')
                    <span class="badge badge-info">
                        <i class="fas fa-stethoscope"></i> Sedang Diperiksa
                    </span>
                @else
                    <span class="badge badge-success">
                        <i class="fas fa-check"></i> Selesai
                    </span>
                @endif
            </td>
            <td>
                @if($periksa->status == 'menunggu')
                    <form action="{{ route('pasien.periksa.destroy', $periksa->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" 
                                onclick="return confirm('Apakah Anda yakin ingin membatalkan pendaftaran?')"
                                title="Batalkan pendaftaran">
                          <i class="fas fa-times"></i> Batalkan
                        </button>
                    </form>
                @else
                    <span class="text-muted">Tidak dapat dibatalkan</span>
                @endif
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="7" class="text-center py-4">
            <div class="text-muted">
                <i class="fas fa-calendar-times fa-3x mb-3"></i>
                <p>Belum ada jadwal pemeriksaan yang akan datang.</p>
                <small>Daftar periksa untuk membuat jadwal baru.</small>
            </div>
        </td>
    </tr>
@endif