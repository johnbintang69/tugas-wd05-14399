@extends('layout.dashboard') {{-- atau layout universal baru --}}

@section('title', 'Dashboard')

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">
          Dashboard {{ ucfirst(Auth::user()->role) }}
        </h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      @if(Auth::user()->role == 'dokter')
        {{-- Statistik & menu khusus dokter --}}
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3>{{ $pasien_count ?? 0 }}</h3>
              <p>Pasien Hari Ini</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="{{ route('dokter.periksa') }}" class="small-box-footer">Lihat semua <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3>{{ $obat_count ?? 0 }}</h3>
              <p>Jumlah Obat</p>
            </div>
            <div class="icon">
              <i class="ion ion-medkit"></i>
            </div>
            <a href="{{ route('dokter.obat') }}" class="small-box-footer">Lihat semua <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3>{{ $total_pasien ?? 0 }}</h3>
              <p>Total Pasien</p>
            </div>
            <div class="icon">
              <i class="ion ion-ios-people"></i>
            </div>
            <a href="#" class="small-box-footer">Info lainnya <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-danger">
            <div class="inner">
              <h3>{{ $total_periksa ?? 0 }}</h3>
              <p>Total Pemeriksaan</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">Info lainnya <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
      @elseif(Auth::user()->role == 'pasien')
        {{-- Statistik & menu khusus pasien --}}
        <div class="col-lg-3 col-6">
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
        <div class="col-lg-3 col-6">
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
        <div class="col-lg-3 col-6">
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
        <div class="col-lg-3 col-6">
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
      @endif
    </div>
  </div>
</section>
@endsection