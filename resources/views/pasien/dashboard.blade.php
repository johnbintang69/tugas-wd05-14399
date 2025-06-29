<!-- resources/views/pasien/dashboard.blade.php -->
@extends('layout.pasien')

@section('title', 'Dashboard Pasien')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Dashboard Pasien</h1>
        <p class="text-muted">Selamat datang, {{ Auth::user()->nama }}!</p>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
          <div class="inner">
            <h3>{{ $riwayat_count ?? 0 }}</h3>
            <p>Riwayat Periksa</p>
          </div>
          <div class="icon">
            <i class="ion ion-clipboard"></i>
          </div>
          <a href="{{ route('pasien.riwayat') }}" class="small-box-footer">Lihat semua <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
          <div class="inner">
            <h3>53<sup style="font-size: 20px">%</sup></h3>
            <p>Tingkat Kepuasan</p>
          </div>
          <div class="icon">
            <i class="ion ion-stats-bars"></i>
          </div>
          <a href="#" class="small-box-footer">Info lainnya <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-warning">
          <div class="inner">
            <h3>{{ $dokter_count ?? 0 }}</h3>
            <p>Dokter Tersedia</p>
          </div>
          <div class="icon">
            <i class="ion ion-person-add"></i>
          </div>
          <a href="{{ route('pasien.periksa') }}" class="small-box-footer">Periksa sekarang <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-danger">
          <div class="inner">
            <h3>{{ $obat_count ?? 0 }}</h3>
            <p>Obat Tersedia</p>
          </div>
          <div class="icon">
            <i class="ion ion-medkit"></i>
          </div>
          <a href="#" class="small-box-footer">Info lainnya <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
    </div>
    <!-- /.row -->
    
    <!-- Info Row -->
    <div class="row">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-history mr-2"></i>
              Riwayat Periksa Terbaru
            </h3>
          </div>
          <div class="card-body">
            @if(isset($riwayats) && count($riwayats) > 0)
              <div class="table-responsive">
                <table class="table table-sm">
                  <thead>
                    <tr>
                      <th>Tanggal</th>
                      <th>Dokter</th>
                      <th>Diagnosa</th>
                      <th>Biaya</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($riwayats as $riwayat)
                    <tr>
                      <td>{{ \Carbon\Carbon::parse($riwayat->tgl_periksa)->format('d M Y') }}</td>
                      <td>{{ $riwayat->daftarPoli->jadwal->dokter->nama }}</td>
                      <td>{{ Str::limit($riwayat->catatan, 30) }}</td>
                      <td>
                        <strong>Rp {{ number_format($riwayat->biaya_periksa, 0, ',', '.') }}</strong>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              
              <div class="text-center mt-3">
                <a href="{{ route('pasien.riwayat') }}" class="btn btn-sm btn-primary">
                  <i class="fas fa-eye"></i> Lihat Semua Riwayat
                </a>
              </div>
            @else
              <div class="text-center text-muted py-4">
                <i class="fas fa-file-medical fa-3x mb-3"></i>
                <p>Belum ada riwayat pemeriksaan.</p>
                <a href="{{ route('pasien.periksa') }}" class="btn btn-primary">
                  <i class="fas fa-plus"></i> Daftar Periksa Sekarang
                </a>
              </div>
            @endif
          </div>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-calendar-alt mr-2"></i>
              Jadwal Dokter Hari Ini
            </h3>
          </div>
          <div class="card-body">
            @if(isset($jadwals) && count($jadwals) > 0)
              @foreach($jadwals as $jadwal)
                <div class="card card-outline card-primary mb-2">
                  <div class="card-body p-2">
                    <h6 class="mb-1">{{ $jadwal->dokter->nama }}</h6>
                    <small class="text-muted">
                      <i class="fas fa-hospital"></i> {{ $jadwal->dokter->poli->nama_poli }}<br>
                      <i class="fas fa-clock"></i> {{ $jadwal->jam_mulai->format('H:i') }} - {{ $jadwal->jam_selesai->format('H:i') }}
                    </small>
                  </div>
                </div>
              @endforeach
              
              <div class="text-center mt-2">
                <a href="{{ route('pasien.periksa') }}" class="btn btn-sm btn-success">
                  <i class="fas fa-plus"></i> Daftar Sekarang
                </a>
              </div>
            @else
              <div class="text-center text-muted">
                <i class="fas fa-calendar-times fa-2x mb-2"></i>
                <p class="mb-0">Tidak ada jadwal dokter aktif untuk hari ini.</p>
              </div>
            @endif
          </div>
        </div>
        
        <!-- Info Card -->
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">Informasi Penting</h3>
          </div>
          <div class="card-body">
            <div class="alert alert-info mb-2">
              <h6><i class="icon fas fa-info"></i> Tips Sehat:</h6>
              <ul class="mb-0 pl-3">
                <li>Datang 15 menit sebelum jadwal</li>
                <li>Bawa kartu identitas</li>
                <li>Siapkan keluhan dengan jelas</li>
                <li>Minum obat sesuai resep</li>
              </ul>
            </div>
            
            <div class="text-center">
              <h5>Biaya Periksa</h5>
              <h4 class="text-primary">Rp 150.000</h4>
              <small class="text-muted">*Belum termasuk obat</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('scripts')
<script>
  $(function () {
    // Auto refresh stats setiap 5 menit
    setInterval(function() {
      // Optional: AJAX refresh untuk real-time updates
      // location.reload();
    }, 300000); // 5 minutes
  });
</script>
@endsection