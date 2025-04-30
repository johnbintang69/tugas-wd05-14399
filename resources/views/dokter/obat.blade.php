<!-- resources/views/dokter/obat.blade.php -->
@extends('layout.dokter')

@section('title', 'Manajemen Obat')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Manajemen Obat</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dokter.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item active">Manajemen Obat</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <!-- Alert Area -->
    <div class="row">
      <div class="col-12">
        <!-- Alert untuk session flash messages -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" id="success-alert">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <strong>Sukses!</strong> {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" id="error-alert">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <strong>Error!</strong> {{ session('error') }}
        </div>
        @endif
      </div>
    </div>
    <!-- /.Alert Area -->

    <div class="row">
      <div class="col-md-5">
        <!-- Form Tambah/Edit Obat -->
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title" id="form-title">Tambah Obat Baru</h3>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form id="obatForm" method="POST" action="{{ route('dokter.obat.store') }}">
            @csrf
            <input type="hidden" name="_method" id="method" value="POST">
            <input type="hidden" name="id" id="obat_id">
            <div class="card-body">
              <div class="form-group">
                <label for="nama_obat">Nama Obat</label>
                <input type="text" class="form-control @error('nama_obat') is-invalid @enderror" id="nama_obat" name="nama_obat" placeholder="Masukkan nama obat" value="{{ old('nama_obat') }}" required>
                @error('nama_obat')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>
              <div class="form-group">
                <label for="kemasan">Kemasan</label>
                <input type="text" class="form-control @error('kemasan') is-invalid @enderror" id="kemasan" name="kemasan" placeholder="Contoh: Tablet 500mg, Sirup 60ml" value="{{ old('kemasan') }}" required>
                @error('kemasan')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>
              <div class="form-group">
                <label for="harga">Harga (Rp)</label>
                <input type="number" class="form-control @error('harga') is-invalid @enderror" id="harga" name="harga" placeholder="Masukkan harga" value="{{ old('harga') }}" required>
                @error('harga')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
              <button type="submit" class="btn btn-primary">Simpan</button>
              <button type="button" id="btnCancel" class="btn btn-default" style="display: none;">Batal</button>
            </div>
          </form>
        </div>
        <!-- /.card -->
      </div>
      
      <div class="col-md-7">
        <!-- Daftar Obat -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Daftar Obat</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive">
            <table class="table table-hover text-nowrap" id="obatTable">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nama Obat</th>
                  <th>Kemasan</th>
                  <th>Harga</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @if(isset($obats) && count($obats) > 0)
                  @foreach($obats as $obat)
                  <tr>
                    <td>{{ $obat->id }}</td>
                    <td>{{ $obat->nama_obat }}</td>
                    <td>{{ $obat->kemasan }}</td>
                    <td>Rp {{ number_format($obat->harga, 0, ',', '.') }}</td>
                    <td>
                      <button class="btn btn-sm btn-info edit-obat" data-id="{{ $obat->id }}" data-nama="{{ $obat->nama_obat }}" data-kemasan="{{ $obat->kemasan }}" data-harga="{{ $obat->harga }}">
                        <i class="fas fa-edit"></i> Edit
                      </button>
                      <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $obat->id }}" data-nama="{{ $obat->nama_obat }}">
                        <i class="fas fa-trash"></i> Hapus
                      </button>
                    </td>
                  </tr>
                  @endforeach
                @else
                  <!-- Dummy data jika belum ada data -->
                  <tr>
                    <td>1</td>
                    <td>Paracetamol</td>
                    <td>Tablet 500mg</td>
                    <td>Rp 10.000</td>
                    <td>
                      <button class="btn btn-sm btn-info edit-obat" data-id="1" data-nama="Paracetamol" data-kemasan="Tablet 500mg" data-harga="10000">
                        <i class="fas fa-edit"></i> Edit
                      </button>
                      <button class="btn btn-sm btn-danger btn-delete" data-id="1" data-nama="Paracetamol">
                        <i class="fas fa-trash"></i> Hapus
                      </button>
                    </td>
                  </tr>
                  <tr>
                    <td>2</td>
                    <td>Amoxicillin</td>
                    <td>Kapsul 500mg</td>
                    <td>Rp 25.000</td>
                    <td>
                      <button class="btn btn-sm btn-info edit-obat" data-id="2" data-nama="Amoxicillin" data-kemasan="Kapsul 500mg" data-harga="25000">
                        <i class="fas fa-edit"></i> Edit
                      </button>
                      <button class="btn btn-sm btn-danger btn-delete" data-id="2" data-nama="Amoxicillin">
                        <i class="fas fa-trash"></i> Hapus
                      </button>
                    </td>
                  </tr>
                  <tr>
                    <td>3</td>
                    <td>Ibuprofen</td>
                    <td>Tablet 400mg</td>
                    <td>Rp 15.000</td>
                    <td>
                      <button class="btn btn-sm btn-info edit-obat" data-id="3" data-nama="Ibuprofen" data-kemasan="Tablet 400mg" data-harga="15000">
                        <i class="fas fa-edit"></i> Edit
                      </button>
                      <button class="btn btn-sm btn-danger btn-delete" data-id="3" data-nama="Ibuprofen">
                        <i class="fas fa-trash"></i> Hapus
                      </button>
                    </td>
                  </tr>
                @endif
              </tbody>
            </table>
          </div>
        </div>
        <!-- /.card -->
      </div>
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
  
  <!-- Modal Konfirmasi Hapus -->
  <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-danger">
          <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus Obat</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Apakah Anda yakin ingin menghapus obat <strong id="delete-obat-nama"></strong>?</p>
          <p class="text-danger">Tindakan ini tidak dapat dibatalkan!</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Hapus</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Modal Sukses Tambah Obat -->
  <div class="modal fade" id="createSuccessModal" tabindex="-1" role="dialog" aria-labelledby="createSuccessModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-success">
          <h5 class="modal-title" id="createSuccessModalLabel">Sukses Tambah Obat</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="text-center mb-4">
            <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
          </div>
          <p>Obat <strong id="created-obat-nama"></strong> berhasil ditambahkan!</p>
          <table class="table table-bordered">
            <tr>
              <th>Nama Obat</th>
              <td id="created-obat-nama-detail"></td>
            </tr>
            <tr>
              <th>Kemasan</th>
              <td id="created-obat-kemasan"></td>
            </tr>
            <tr>
              <th>Harga</th>
              <td id="created-obat-harga"></td>
            </tr>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" data-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Modal Sukses Edit Obat -->
  <div class="modal fade" id="editSuccessModal" tabindex="-1" role="dialog" aria-labelledby="editSuccessModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h5 class="modal-title" id="editSuccessModalLabel">Sukses Edit Obat</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="text-center mb-4">
            <i class="fas fa-edit text-info" style="font-size: 4rem;"></i>
          </div>
          <p>Obat <strong id="edited-obat-nama"></strong> berhasil diperbarui!</p>
          <table class="table table-bordered">
            <tr>
              <th width="30%">Nama Obat</th>
              <td id="edited-obat-nama-detail"></td>
            </tr>
            <tr>
              <th>Kemasan</th>
              <td id="edited-obat-kemasan"></td>
            </tr>
            <tr>
              <th>Harga</th>
              <td id="edited-obat-harga"></td>
            </tr>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-info" data-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Modal Sukses Hapus Obat -->
  <div class="modal fade" id="deleteSuccessModal" tabindex="-1" role="dialog" aria-labelledby="deleteSuccessModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-danger">
          <h5 class="modal-title" id="deleteSuccessModalLabel">Sukses Hapus Obat</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="text-center mb-4">
            <i class="fas fa-trash text-danger" style="font-size: 4rem;"></i>
          </div>
          <p>Obat <strong id="deleted-obat-nama"></strong> berhasil dihapus dari sistem!</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- /.content -->
@endsection

@section('scripts')
<script>
  $(function () {
    // Auto-close alerts after 5 seconds
    $("#success-alert, #error-alert").fadeTo(5000, 500).slideUp(500);
    // DataTable inisialisasi
    $('#obatTable').DataTable({
      responsive: true,
      lengthChange: false,
      autoWidth: false,
      buttons: ['copy','csv','excel','pdf','print','colvis']
    }).buttons().container().appendTo('#obatTable_wrapper .col-md-6:eq(0)');
  });

  $('.edit-obat').on('click', function () {
  $('#form-title').text('Edit Obat');
  $('#obat_id').val($(this).data('id'));
  $('#nama_obat').val($(this).data('nama'));
  $('#kemasan').val($(this).data('kemasan'));
  $('#harga').val($(this).data('harga'));
  $('#method').val('PUT');
  $('#obatForm').attr('action', `/dokter/obat/${$(this).data('id')}`);
  $('#btnCancel').show();
});

$('#btnCancel').on('click', function () {
  $('#form-title').text('Tambah Obat Baru');
  $('#obatForm')[0].reset();
  $('#method').val('POST');
  $('#obatForm').attr('action', `{{ route('dokter.obat.store') }}`);
  $('#btnCancel').hide();
});

$('.btn-delete').on('click', function () {
  $('#delete-obat-nama').text($(this).data('nama'));
  $('#deleteForm').attr('action', `/dokter/obat/${$(this).data('id')}`);
  $('#deleteModal').modal('show');
});

</script>
@endsection