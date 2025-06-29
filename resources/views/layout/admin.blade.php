<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Poliklinik Admin | @yield('title')</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
  <!-- Custom Admin Styles -->
  <style>
    /* Quick Action Cards */
    .quick-action-card {
      border: none;
      border-radius: 10px;
      transition: all 0.3s ease;
      overflow: hidden;
      height: 100%;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      position: relative;
      z-index: 1;
    }

    .quick-action-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(45deg, rgba(255,255,255,0.1), rgba(255,255,255,0.2));
      opacity: 0;
      transition: opacity 0.3s ease;
      z-index: -1;
    }

    .quick-action-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
      text-decoration: none;
    }

    .quick-action-card:hover::before {
      opacity: 1;
    }

    .quick-action-icon {
      width: 60px;
      height: 60px;
      margin: 0 auto;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: rgba(255, 255, 255, 0.2);
      border-radius: 50%;
      transition: all 0.3s ease;
    }

    .quick-action-card:hover .quick-action-icon {
      transform: scale(1.1);
      background-color: rgba(255, 255, 255, 0.3);
    }

    /* Gradient Backgrounds */
    .bg-gradient-primary {
      background: linear-gradient(45deg, #4e73df, #224abe);
    }

    .bg-gradient-success {
      background: linear-gradient(45deg, #1cc88a, #13855c);
    }

    .bg-gradient-warning {
      background: linear-gradient(45deg, #f6c23e, #dda20a);
    }

    .bg-gradient-danger {
      background: linear-gradient(45deg, #e74a3b, #be2617);
    }
  </style>
  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
  @yield('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <div class="spinner-border text-primary" role="status">
      <span class="sr-only">Loading...</span>
    </div>
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-user"></i> Administrator
        </a>
        <div class="dropdown-menu dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <i class="fas fa-user mr-2"></i> Profil
          </a>
          <div class="dropdown-divider"></div>
          <a href="{{ route('logout') }}" class="dropdown-item" 
             onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt mr-2"></i> Keluar
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
      <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="Poliklinik Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Admin Panel</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="Admin Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Administrator</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Dashboard -->
          <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Request::is('admin/dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          
          <!-- Master Data -->
          <li class="nav-item {{ Request::is('admin/poli*') || Request::is('admin/dokter*') || Request::is('admin/pasien*') || Request::is('admin/obat*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('admin/poli*') || Request::is('admin/dokter*') || Request::is('admin/pasien*') || Request::is('admin/obat*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-database"></i>
              <p>
                Master Data
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('admin.poli.index') }}" class="nav-link {{ Request::is('admin/poli*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Poliklinik</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.dokter.index') }}" class="nav-link {{ Request::is('admin/dokter*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Dokter</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.pasien.index') }}" class="nav-link {{ Request::is('admin/pasien*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Pasien</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.obat.index') }}" class="nav-link {{ Request::is('admin/obat*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Obat</p>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    @yield('content')
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <strong>Copyright &copy; 2025 <a href="https://github.com/Hessaajipradana/tugas-wd05-14393">Poliklinik</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0.0
    </div>
  </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- DataTables & Plugins -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.js') }}"></script>

<!-- Global JS for admin -->
<script>
$(function () {
    // Auto-close alerts after 5 seconds
    $(".alert").fadeTo(5000, 500).slideUp(500);
    
    // DataTable default configuration
    $.extend(true, $.fn.dataTable.defaults, {
        responsive: true,
        lengthChange: false,
        autoWidth: false,
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
    
    // Initialize DataTables
    $('table[id$="Table"]').DataTable();
});
</script>
@yield('scripts')
</body>
</html>