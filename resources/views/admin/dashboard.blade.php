@extends('layout.admin')

@section('title', 'Dashboard')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Dashboard Admin</h1>
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

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
          <div class="inner">
            <h3>{{ $stats['total_poli'] }}</h3>
            <p>Total Poliklinik</p>
          </div>
          <div class="icon">
            <i class="fas fa-hospital"></i>
          </div>
          <a href="{{ route('admin.poli.index') }}" class="small-box-footer">
            Lihat semua <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
      
      <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
          <div class="inner">
            <h3>{{ $stats['total_dokter'] }}</h3>
            <p>Total Dokter</p>
          </div>
          <div class="icon">
            <i class="fas fa-user-md"></i>
          </div>
          <a href="{{ route('admin.dokter.index') }}" class="small-box-footer">
            Lihat semua <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
      
      <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
          <div class="inner">
            <h3>{{ $stats['total_pasien'] }}</h3>
            <p>Total Pasien</p>
          </div>
          <div class="icon">
            <i class="fas fa-users"></i>
          </div>
          <a href="{{ route('admin.pasien.index') }}" class="small-box-footer">
            Lihat semua <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
      
      <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
          <div class="inner">
            <h3>{{ $stats['total_obat'] }}</h3>
            <p>Total Obat</p>
          </div>
          <div class="icon">
            <i class="fas fa-pills"></i>
          </div>
          <a href="{{ route('admin.obat.index') }}" class="small-box-footer">
            Lihat semua <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
    </div>

    <!-- Info Row -->
    <div class="row">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Aktivitas Terbaru</h3>
          </div>
          <div class="card-body">
            <div class="alert alert-info">
              <h5><i class="icon fas fa-info"></i> Selamat Datang!</h5>
              <p>Anda login sebagai Administrator. Gunakan menu Master Data untuk mengelola data poliklinik.</p>
            </div>
            
            <h5 class="mb-3">Quick Actions</h5>
            <div class="row">
              <div class="col-6 mb-3">
                <a href="{{ route('admin.poli.index') }}" class="card quick-action-card bg-gradient-primary text-white">
                  <div class="card-body text-center p-3">
                    <div class="quick-action-icon mb-2">
                      <i class="fas fa-hospital fa-2x"></i>
                    </div>
                    <h6 class="mb-0 font-weight-bold">Kelola Poliklinik</h6>
                    <small class="opacity-75">Atur data poliklinik</small>
                  </div>
                </a>
              </div>
              <div class="col-6 mb-3">
                <a href="{{ route('admin.dokter.index') }}" class="card quick-action-card bg-gradient-success text-white">
                  <div class="card-body text-center p-3">
                    <div class="quick-action-icon mb-2">
                      <i class="fas fa-user-md fa-2x"></i>
                    </div>
                    <h6 class="mb-0 font-weight-bold">Kelola Dokter</h6>
                    <small class="opacity-75">Kelola data dokter</small>
                  </div>
                </a>
              </div>
              <div class="col-6">
                <a href="{{ route('admin.pasien.index') }}" class="card quick-action-card bg-gradient-warning text-white">
                  <div class="card-body text-center p-3">
                    <div class="quick-action-icon mb-2">
                      <i class="fas fa-users fa-2x"></i>
                    </div>
                    <h6 class="mb-0 font-weight-bold">Kelola Pasien</h6>
                    <small class="opacity-75">Kelola data pasien</small>
                  </div>
                </a>
              </div>
              <div class="col-6">
                <a href="{{ route('admin.obat.index') }}" class="card quick-action-card bg-gradient-danger text-white">
                  <div class="card-body text-center p-3">
                    <div class="quick-action-icon mb-2">
                      <i class="fas fa-pills fa-2x"></i>
                    </div>
                    <h6 class="mb-0 font-weight-bold">Kelola Obat</h6>
                    <small class="opacity-75">Kelola stok obat</small>
                  </div>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Info Sistem</h3>
          </div>
          <div class="card-body">
            <dl class="row">
              <dt class="col-sm-4">Laravel Version</dt>
              <dd class="col-sm-8">{{ app()->version() }}</dd>
              
              <dt class="col-sm-4">PHP Version</dt>
              <dd class="col-sm-8">{{ PHP_VERSION }}</dd>
              
              <dt class="col-sm-4">Server Time</dt>
              <dd class="col-sm-8">{{ now()->format('d/m/Y H:i:s') }}</dd>
              
              <dt class="col-sm-4">Admin Login</dt>
              <dd class="col-sm-8">
                <span class="badge badge-success">Active</span>
              </dd>
            </dl>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection