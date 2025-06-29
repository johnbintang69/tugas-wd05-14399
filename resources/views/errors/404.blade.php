<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Poliklinik | 404 Page not found</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="margin-left: 0;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>404 Error Page</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
              <li class="breadcrumb-item active">404 Error</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="error-page">
        <h2 class="headline text-warning"> 404</h2>

        <div class="error-content">
          <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Halaman tidak ditemukan.</h3>

          <p>
            Halaman yang Anda cari tidak dapat ditemukan.
            Sementara itu, Anda dapat <a href="{{ url('/') }}">kembali ke halaman utama</a> atau coba gunakan form pencarian.
          </p>

          <div class="input-group">
            <input type="search" class="form-control form-control-lg" placeholder="Kata kunci">

            <div class="input-group-append">
              <button type="button" class="btn btn-lg btn-warning">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>
          
          <div class="mt-4">
            @auth
              @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                  <i class="fas fa-home"></i> Dashboard Admin
                </a>
              @elseif(auth()->user()->isDokter())
                <a href="{{ route('dokter.dashboard') }}" class="btn btn-primary">
                  <i class="fas fa-home"></i> Dashboard Dokter
                </a>
              @elseif(auth()->user()->isPasien())
                <a href="{{ route('pasien.dashboard') }}" class="btn btn-primary">
                  <i class="fas fa-home"></i> Dashboard Pasien
                </a>
              @endif
            @else
              <a href="{{ route('login') }}" class="btn btn-primary">
                <i class="fas fa-sign-in-alt"></i> Login
              </a>
              <a href="{{ route('register') }}" class="btn btn-success">
                <i class="fas fa-user-plus"></i> Daftar
              </a>
            @endauth
          </div>
        </div>
        <!-- /.error-content -->
      </div>
      <!-- /.error-page -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
</body>
</html>