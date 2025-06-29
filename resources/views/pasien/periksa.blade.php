<!-- resources/views/pasien/periksa.blade.php -->
@extends('layout.pasien')

@section('title', 'Daftar Periksa')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Daftar Periksa</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('pasien.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item active">Daftar Periksa</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      <i class="icon fas fa-check"></i> {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      <i class="icon fas fa-ban"></i> {{ session('error') }}
    </div>
    @endif

    <div class="row">
      <div class="col-md-6">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-plus mr-2"></i>
              Form Daftar Periksa
            </h3>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form method="POST" action="{{ route('pasien.periksa.store') }}">
            @csrf
            <div class="card-body">
              <div class="form-group">
                <label for="nama">Nama Pasien</label>
                <input type="text" class="form-control" id="nama" name="nama" value="{{ Auth::user()->nama }}" readonly>
                <small class="text-muted">No. RM: {{ Auth::user()->pasien->no_rm ?? 'N/A' }}</small>
              </div>
              
              <div class="form-group">
                <label for="id_jadwal">Pilih Jadwal Dokter</label>
                <select class="form-control @error('id_jadwal') is-invalid @enderror" id="id_jadwal" name="id_jadwal" required>
                  <option value="">-- Pilih Jadwal Dokter --</option>
                  @foreach($jadwals ?? [] as $jadwal)
                  <option value="{{ $jadwal->id }}" 
                          data-dokter="{{ $jadwal->dokter->nama }}"
                          data-poli="{{ $jadwal->dokter->poli->nama_poli }}"
                          data-hari="{{ $jadwal->hari }}"
                          data-jam="{{ $jadwal->jam_mulai->format('H:i') }} - {{ $jadwal->jam_selesai->format('H:i') }}"
                          {{ old('id_jadwal') == $jadwal->id ? 'selected' : '' }}>
                    {{ $jadwal->dokter->nama }} | {{ $jadwal->dokter->poli->nama_poli }} | 
                    {{ $jadwal->hari }}, {{ $jadwal->jam_mulai->format('H:i') }}-{{ $jadwal->jam_selesai->format('H:i') }}
                  </option>
                  @endforeach
                </select>
                @error('id_jadwal')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
                <small class="text-muted">Pilih jadwal dokter sesuai dengan keluhan Anda</small>
              </div>
              
              <div class="form-group">
                <label for="keluhan">Keluhan</label>
                <textarea class="form-control @error('keluhan') is-invalid @enderror" 
                          id="keluhan" name="keluhan" rows="4" 
                          placeholder="Deskripsikan keluhan Anda secara detail (gejala, durasi, tingkat nyeri, dll)" 
                          required>{{ old('keluhan') }}</textarea>
                @error('keluhan')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
                <small class="text-muted">Jelaskan keluhan dengan detail untuk membantu dokter dalam diagnosa</small>
              </div>

              <!-- Info Jadwal Terpilih -->
              <div id="jadwal-info" class="alert alert-info" style="display: none;">
                <h6><i class="icon fas fa-info-circle"></i> Informasi Jadwal Terpilih:</h6>
                <ul class="mb-0">
                  <li><strong>Dokter:</strong> <span id="info-dokter">-</span></li>
                  <li><strong>Poliklinik:</strong> <span id="info-poli">-</span></li>
                  <li><strong>Hari & Jam:</strong> <span id="info-jadwal">-</span></li>
                  <li><strong>Estimasi Antrian:</strong> <span id="info-antrian">Akan ditentukan setelah mendaftar</span></li>
                </ul>
              </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
              <button type="submit" class="btn btn-primary btn-block">
                <i class="fas fa-plus"></i> Daftar Periksa Sekarang
              </button>
            </div>
          </form>
        </div>
      </div>
      
      <div class="col-md-6">
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-info-circle mr-2"></i>
              Informasi Pendaftaran
            </h3>
          </div>
          <div class="card-body">
            <div class="alert alert-info">
              <h5><i class="icon fas fa-lightbulb"></i> Cara Mendaftar:</h5>
              <ol class="mb-0 pl-3">
                <li>Pilih jadwal dokter sesuai dengan keluhan Anda</li>
                <li>Isi keluhan dengan detail yang jelas</li>
                <li>Klik "Daftar Periksa" untuk mendapatkan nomor antrian</li>
                <li>Datang <strong>15 menit sebelum</strong> jadwal praktik</li>
                <li>Bawa kartu identitas dan kartu peserta (jika ada)</li>
              </ol>
            </div>
            
            <h5>
              <i class="fas fa-calendar-alt mr-2"></i>
              Jadwal Dokter Tersedia
            </h5>
            @if(count($jadwals) > 0)
              <div class="table-responsive">
                <table class="table table-sm table-striped">
                  <thead class="bg-light">
                    <tr>
                      <th>Dokter</th>
                      <th>Poli</th>
                      <th>Hari</th>
                      <th>Jam</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($jadwals as $jadwal)
                    <tr>
                      <td>
                        <strong>{{ $jadwal->dokter->nama }}</strong>
                      </td>
                      <td>
                        <span class="badge badge-info">{{ $jadwal->dokter->poli->nama_poli }}</span>
                      </td>
                      <td>{{ $jadwal->hari }}</td>
                      <td>
                        <small>{{ $jadwal->jam_mulai->format('H:i') }} - {{ $jadwal->jam_selesai->format('H:i') }}</small>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @else
              <div class="alert alert-warning">
                <i class="icon fas fa-exclamation-triangle"></i>
                <strong>Tidak ada jadwal dokter yang aktif saat ini.</strong><br>
                Silakan hubungi administrator atau coba lagi nanti.
              </div>
            @endif
            
            <hr>
            
            <h5>
              <i class="fas fa-money-bill-wave mr-2"></i>
              Biaya Periksa
            </h5>
            <div class="table-responsive">
              <table class="table table-sm">
                <tr>
                  <td><strong>Biaya Konsultasi Dokter</strong></td>
                  <td class="text-right"><strong>Rp 150.000</strong></td>
                </tr>
                <tr>
                  <td>Biaya Obat</td>
                  <td class="text-right">Sesuai resep dokter</td>
                </tr>
                <tr class="bg-light">
                  <td><strong>Total Minimal</strong></td>
                  <td class="text-right"><strong>Rp 150.000</strong></td>
                </tr>
              </table>
            </div>
            <small class="text-muted">*Biaya final akan dihitung setelah pemeriksaan selesai</small>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Daftar Antrian Yang Akan Datang -->
    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-clock mr-2"></i>
              Daftar Antrian Anda
            </h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" onclick="location.reload();">
                <i class="fas fa-sync-alt"></i> Refresh
              </button>
            </div>
          </div>
          <div class="card-body table-responsive">
            <table class="table table-bordered table-striped" id="upcomingPeriksaTable">
              <thead>
                <tr>
                  <th width="5%">No</th>
                  <th>Tanggal Daftar</th>
                  <th>Dokter</th>
                  <th>Poli</th>
                  <th>Jadwal</th>
                  <th>No. Antrian</th>
                  <th>Status</th>
                  <th width="15%">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @if(isset($upcoming_periksa) && count($upcoming_periksa) > 0)
                  @foreach($upcoming_periksa as $key => $periksa)
                    <tr>
                      <td>{{ $key + 1 }}</td>
                      <td>{{ \Carbon\Carbon::parse($periksa->tanggal_daftar)->format('d M Y') }}</td>
                      <td>
                        <strong>{{ $periksa->jadwal->dokter->nama }}</strong>
                      </td>
                      <td>
                        <span class="badge badge-info">{{ $periksa->jadwal->dokter->poli->nama_poli }}</span>
                      </td>
                      <td>
                        <small>
                          {{ $periksa->jadwal->hari }}<br>
                          {{ $periksa->jadwal->jam_mulai->format('H:i') }} - {{ $periksa->jadwal->jam_selesai->format('H:i') }}
                        </small>
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
                          <button type="button" class="btn btn-sm btn-danger btn-cancel" 
                                  data-id="{{ $periksa->id }}"
                                  data-dokter="{{ $periksa->jadwal->dokter->nama }}"
                                  data-antrian="{{ $periksa->no_antrian }}">
                            <i class="fas fa-times"></i> Batalkan
                          </button>
                        @else
                          <span class="text-muted">
                            <i class="fas fa-lock"></i> Tidak dapat dibatalkan
                          </span>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                @else
                  <tr>
                    <td colspan="8" class="text-center py-4">
                      <div class="text-muted">
                        <i class="fas fa-calendar-times fa-3x mb-3"></i>
                        <p class="mb-2">Belum ada jadwal pemeriksaan yang akan datang.</p>
                        <small>Gunakan form di atas untuk mendaftar pemeriksaan baru.</small>
                      </div>
                    </td>
                  </tr>
                @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Modal Konfirmasi Batalkan -->
  <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-danger">
          <h5 class="modal-title">Konfirmasi Pembatalan</h5>
          <button type="button" class="close" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Apakah Anda yakin ingin membatalkan pendaftaran periksa?</p>
          <div class="alert alert-info">
            <strong>Detail Pendaftaran:</strong><br>
            <strong>Dokter:</strong> <span id="cancel-dokter"></span><br>
            <strong>No. Antrian:</strong> <span id="cancel-antrian"></span>
          </div>
          <p class="text-danger">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Perhatian:</strong> Pembatalan tidak dapat dilakukan kurang dari 2 jam sebelum jadwal praktek.
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
          <form id="cancelForm" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Ya, Batalkan</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- /.content -->
@endsection

@section('styles')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endsection

@section('scripts')
<!-- DataTables -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

<script>
$(function () {
    // Auto-close alerts after 5 seconds
    $(".alert").not('.alert-info').fadeTo(5000, 500).slideUp(500);
    
    // DataTable untuk upcoming periksa
    $("#upcomingPeriksaTable").DataTable({
      responsive: true,
      lengthChange: false,
      autoWidth: false,
      order: [[ 1, "asc" ], [ 5, "asc" ]], // Sort by tanggal & antrian
      language: {
        search: "Cari:",
        lengthMenu: "Tampilkan _MENU_ data per halaman",
        zeroRecords: "Data tidak ditemukan",
        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
        infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
        infoFiltered: "(difilter dari _MAX_ total data)",
        paginate: {
          first: "Pertama",
          last: "Terakhir",
          next: "Selanjutnya",
          previous: "Sebelumnya"
        }
      }
    });
    
    // Show jadwal info when selecting
    $('#id_jadwal').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        if (selectedOption.val()) {
            $('#info-dokter').text(selectedOption.data('dokter'));
            $('#info-poli').text(selectedOption.data('poli'));
            $('#info-jadwal').text(selectedOption.data('hari') + ', ' + selectedOption.data('jam'));
            $('#jadwal-info').slideDown();
        } else {
            $('#jadwal-info').slideUp();
        }
    });
    
    // Cancel button handler
    $(document).on('click', '.btn-cancel', function() {
        var id = $(this).data('id');
        var dokter = $(this).data('dokter');
        var antrian = $(this).data('antrian');
        
        $('#cancel-dokter').text(dokter);
        $('#cancel-antrian').text('#' + antrian);
        $('#cancelForm').attr('action', '/pasien/periksa/' + id);
        $('#cancelModal').modal('show');
    });
    
    // Auto refresh setiap 5 menit untuk update antrian
    setInterval(function() {
        // location.reload();
    }, 300000); // 5 minutes
});
</script>
@endsection