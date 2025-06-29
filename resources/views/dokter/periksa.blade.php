<!-- resources/views/dokter/periksa.blade.php -->
@extends('layout.dokter')

@section('title', 'Periksa Pasien')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Periksa Pasien</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dokter.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item active">Periksa Pasien</li>
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

    <!-- Info Cards -->
    <div class="row">
      <div class="col-lg-6 col-6">
        <div class="small-box bg-info">
          <div class="inner">
            <h3>{{ $total_antrian_hari_ini ?? 0 }}</h3>
            <p>Total Antrian Hari Ini</p>
          </div>
          <div class="icon">
            <i class="ion ion-clock"></i>
          </div>
        </div>
      </div>
      
      <div class="col-lg-6 col-6">
        <div class="small-box bg-success">
          <div class="inner">
            <h3>{{ $total_selesai_hari_ini ?? 0 }}</h3>
            <p>Sudah Diperiksa Hari Ini</p>
          </div>
          <div class="icon">
            <i class="ion ion-checkmark"></i>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Daftar Pasien Yang Perlu Diperiksa -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-stethoscope mr-2"></i>
              Daftar Pasien Yang Perlu Diperiksa
            </h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" onclick="location.reload();">
                <i class="fas fa-sync-alt"></i> Refresh
              </button>
            </div>
          </div>
          <div class="card-body table-responsive">
            <table class="table table-bordered table-striped" id="periksaTable">
              <thead>
                <tr>
                  <th width="5%">No</th>
                  <th>ID Daftar</th>
                  <th>Pasien</th>
                  <th>Tanggal Daftar</th>
                  <th>No. Antrian</th>
                  <th>Keluhan</th>
                  <th>Status</th>
                  <th width="15%">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @if(isset($periksa_pasiens) && count($periksa_pasiens) > 0)
                  @foreach($periksa_pasiens as $key => $daftarPoli)
                    <tr>
                      <td>{{ $key + 1 }}</td>
                      <td>
                        <span class="badge badge-primary">#{{ $daftarPoli->id }}</span>
                      </td>
                      <td>
                        <strong>{{ $daftarPoli->pasien->nama }}</strong><br>
                        <small class="text-muted">{{ $daftarPoli->pasien->no_rm }}</small>
                      </td>
                      <td>{{ \Carbon\Carbon::parse($daftarPoli->tanggal_daftar)->format('d M Y') }}</td>
                      <td>
                        <span class="badge badge-warning badge-lg">
                          <i class="fas fa-hashtag"></i> {{ $daftarPoli->no_antrian }}
                        </span>
                      </td>
                      <td>
                        <div style="max-width: 200px;">
                          {{ Str::limit($daftarPoli->keluhan, 100) }}
                          @if(strlen($daftarPoli->keluhan) > 100)
                            <br><small><a href="#" class="text-primary" data-toggle="modal" data-target="#modal-keluhan-{{ $daftarPoli->id }}">Lihat selengkapnya</a></small>
                          @endif
                        </div>
                      </td>
                      <td>
                        @if($daftarPoli->status == 'menunggu')
                          <span class="badge badge-warning">
                            <i class="fas fa-clock"></i> Menunggu
                          </span>
                        @elseif($daftarPoli->status == 'sedang_diperiksa')
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
                        @if($daftarPoli->status == 'menunggu' || $daftarPoli->status == 'sedang_diperiksa')
                          <a href="{{ route('dokter.periksa.edit', $daftarPoli->id) }}" 
                             class="btn btn-primary btn-sm" title="Mulai Pemeriksaan">
                            <i class="fas fa-stethoscope"></i> Periksa
                          </a>
                        @else
                          @if($daftarPoli->periksa)
                            <a href="{{ route('dokter.periksa.show', $daftarPoli->periksa->id) }}" 
                               class="btn btn-info btn-sm" title="Lihat Detail">
                              <i class="fas fa-eye"></i> Detail
                            </a>
                          @endif
                        @endif
                      </td>
                    </tr>
                  @endforeach
                @else
                  <tr>
                    <td colspan="8" class="text-center py-4">
                      <div class="text-muted">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <p class="mb-2">Tidak ada pasien yang perlu diperiksa saat ini.</p>
                        <small>Pasien akan muncul di sini setelah mendaftar ke jadwal Anda yang aktif.</small>
                        <br><br>
                        <a href="{{ route('dokter.jadwal') }}" class="btn btn-primary btn-sm">
                          <i class="fas fa-calendar-alt"></i> Kelola Jadwal Praktik
                        </a>
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
    <!-- /.row -->
    
    <!-- Riwayat Pemeriksaan -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-history mr-2"></i>
              Riwayat Pemeriksaan Terbaru (10 Terakhir)
            </h3>
          </div>
          <div class="card-body table-responsive">
            <table class="table table-bordered table-hover" id="riwayatTable">
              <thead>
                <tr>
                  <th width="5%">No</th>
                  <th>ID Periksa</th>
                  <th>Pasien</th>
                  <th>Tanggal Periksa</th>
                  <th>Catatan Dokter</th>
                  <th>Obat</th>
                  <th>Biaya Periksa</th>
                  <th width="10%">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @if(isset($riwayat_periksa) && count($riwayat_periksa) > 0)
                  @foreach($riwayat_periksa as $key => $riwayat)
                    <tr>
                      <td>{{ $key + 1 }}</td>
                      <td>
                        <span class="badge badge-success">#{{ $riwayat->id }}</span>
                      </td>
                      <td>
                        <strong>{{ $riwayat->daftarPoli->pasien->nama }}</strong><br>
                        <small class="text-muted">{{ $riwayat->daftarPoli->pasien->no_rm }}</small>
                      </td>
                      <td>{{ \Carbon\Carbon::parse($riwayat->tgl_periksa)->format('d M Y') }}</td>
                      <td>
                        <div style="max-width: 200px;">
                          {{ Str::limit($riwayat->catatan, 80) }}
                        </div>
                      </td>
                      <td>
                        @if(isset($riwayat->obat) && count($riwayat->obat) > 0)
                          <span class="badge badge-info">{{ count($riwayat->obat) }} obat</span>
                        @else
                          <span class="text-muted">-</span>
                        @endif
                      </td>
                      <td>
                        <strong>Rp {{ number_format($riwayat->biaya_periksa, 0, ',', '.') }}</strong>
                      </td>
                      <td>
                        <a href="{{ route('dokter.periksa.show', $riwayat->id) }}" 
                           class="btn btn-info btn-sm" title="Lihat Detail">
                          <i class="fas fa-eye"></i>
                        </a>
                      </td>
                    </tr>
                  @endforeach
                @else
                  <tr>
                    <td colspan="8" class="text-center py-4">
                      <div class="text-muted">
                        <i class="fas fa-file-medical fa-3x mb-3"></i>
                        <p>Belum ada riwayat pemeriksaan.</p>
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
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
  
  <!-- Modal Detail Keluhan -->
  @if(isset($periksa_pasiens) && count($periksa_pasiens) > 0)
    @foreach($periksa_pasiens as $daftarPoli)
      @if(strlen($daftarPoli->keluhan) > 100)
      <div class="modal fade" id="modal-keluhan-{{ $daftarPoli->id }}" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header bg-info">
              <h5 class="modal-title">Detail Keluhan Pasien</h5>
              <button type="button" class="close" data-dismiss="modal">
                <span>&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <dl class="row">
                <dt class="col-sm-3">Pasien:</dt>
                <dd class="col-sm-9">{{ $daftarPoli->pasien->nama }}</dd>
                <dt class="col-sm-3">No. RM:</dt>
                <dd class="col-sm-9">{{ $daftarPoli->pasien->no_rm }}</dd>
                <dt class="col-sm-3">Keluhan:</dt>
                <dd class="col-sm-9">{{ $daftarPoli->keluhan }}</dd>
              </dl>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
              <a href="{{ route('dokter.periksa.edit', $daftarPoli->id) }}" class="btn btn-primary">
                <i class="fas fa-stethoscope"></i> Mulai Periksa
              </a>
            </div>
          </div>
        </div>
      </div>
      @endif
    @endforeach
  @endif
</section>
<!-- /.content -->
@endsection

@section('styles')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('scripts')
<!-- DataTables  & Plugins -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>

<script>
$(function () {
    // Auto-close alerts after 5 seconds
    $(".alert").not('.alert-info').fadeTo(5000, 500).slideUp(500);
    
    // DataTable untuk daftar periksa
    $("#periksaTable").DataTable({
      responsive: true,
      lengthChange: false,
      autoWidth: false,
      order: [[ 3, "asc" ], [ 4, "asc" ]], // Sort by tanggal & antrian
      pageLength: 10,
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
    
    // DataTable untuk riwayat
    $("#riwayatTable").DataTable({
      responsive: true,
      lengthChange: false,
      autoWidth: false,
      order: [[ 3, "desc" ]], // Sort by tanggal periksa desc
      pageLength: 5,
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
    
    // Auto refresh setiap 2 menit untuk update antrian
    setInterval(function() {
        // Optional: auto refresh page untuk update real-time
        // location.reload();
    }, 120000); // 2 minutes
});
</script>
@endsection