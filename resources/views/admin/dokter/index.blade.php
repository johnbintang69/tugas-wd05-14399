<?php
// resources/views/admin/dokter/index.blade.php (FIXED)
?>
@extends('layout.admin')

@section('title', 'Manajemen Dokter')

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Manajemen Dokter</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item active">Dokter</li>
        </ol>
      </div>
    </div>
  </div>
</div>

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
      <!-- Form Tambah/Edit -->
      <div class="col-md-4">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title" id="form-title">Tambah Dokter</h3>
          </div>
          <form id="dokterForm" method="POST" action="{{ route('admin.dokter.store') }}">
            @csrf
            <input type="hidden" name="_method" id="method" value="POST">
            <input type="hidden" name="id" id="dokter_id">
            
            <div class="card-body">
              <div class="form-group">
                <label for="nama">Nama Dokter</label>
                <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                       id="nama" name="nama" placeholder="Dr. John Doe, Sp.PD" 
                       value="{{ old('nama') }}" required>
                @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              
              <div class="form-group">
                <label for="alamat">Alamat</label>
                <textarea class="form-control @error('alamat') is-invalid @enderror" 
                          id="alamat" name="alamat" rows="2" 
                          placeholder="Alamat lengkap dokter">{{ old('alamat') }}</textarea>
                @error('alamat')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              
              <div class="form-group">
                <label for="no_hp">No. HP</label>
                <input type="text" class="form-control @error('no_hp') is-invalid @enderror" 
                       id="no_hp" name="no_hp" placeholder="081234567890" 
                       value="{{ old('no_hp') }}" required>
                @error('no_hp')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              
              <div class="form-group">
                <label for="id_poli">Poliklinik</label>
                <select class="form-control @error('id_poli') is-invalid @enderror" 
                        id="id_poli" name="id_poli" required>
                  <option value="">-- Pilih Poliklinik --</option>
                  @foreach($polis as $poli)
                  <option value="{{ $poli->id }}" {{ old('id_poli') == $poli->id ? 'selected' : '' }}>
                    {{ $poli->nama_poli }}
                  </option>
                  @endforeach
                </select>
                @error('id_poli')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            
            <div class="card-footer">
              <button type="submit" class="btn btn-primary" id="btnSimpan">
                <i class="fas fa-save"></i> Simpan
              </button>
              <button type="button" id="btnCancel" class="btn btn-default" style="display: none;">
                <i class="fas fa-times"></i> Batal
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Table List -->
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Daftar Dokter</h3>
          </div>
          <div class="card-body table-responsive">
            <table class="table table-bordered table-striped" id="dokterTable">
              <thead>
                <tr>
                  <th width="5%">No</th>
                  <th>Nama Dokter</th>
                  <th>Poliklinik</th>
                  <th>No. HP</th>
                  <th width="15%">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($dokters as $key => $dokter)
                <tr>
                  <td>{{ $key + 1 }}</td>
                  <td>{{ $dokter->nama }}</td>
                  <td>
                    <span class="badge badge-info">{{ $dokter->poli->nama_poli }}</span>
                  </td>
                  <td>{{ $dokter->no_hp }}</td>
                  <td>
                    <button type="button" class="btn btn-sm btn-warning btn-edit"
                            data-id="{{ $dokter->id }}"
                            data-nama="{{ $dokter->nama }}"
                            data-alamat="{{ $dokter->alamat }}"
                            data-no_hp="{{ $dokter->no_hp }}"
                            data-id_poli="{{ $dokter->id_poli }}">
                      <i class="fas fa-edit"></i>
                    </button>
                    
                    <button type="button" class="btn btn-sm btn-danger btn-delete"
                            data-id="{{ $dokter->id }}"
                            data-nama="{{ $dokter->nama }}">
                      <i class="fas fa-trash"></i>
                    </button>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Konfirmasi Hapus -->
  <div class="modal fade" id="deleteModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-danger">
          <h4 class="modal-title">Konfirmasi Hapus</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <p>Apakah Anda yakin ingin menghapus dokter <strong id="delete-dokter-nama"></strong>?</p>
          <p class="text-danger">Akun login dokter juga akan terhapus!</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Hapus</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@section('scripts')
<script>
$(function () {
    // DataTable
    $('#dokterTable').DataTable();

    // Edit button
    $(document).on('click', '.btn-edit', function () {
        $('#dokter_id').val($(this).data('id'));
        $('#nama').val($(this).data('nama'));
        $('#alamat').val($(this).data('alamat'));
        $('#no_hp').val($(this).data('no_hp'));
        $('#id_poli').val($(this).data('id_poli'));
        $('#form-title').text('Edit Dokter');
        $('#method').val('PUT');
        $('#dokterForm').attr('action', '/admin/dokter/' + $(this).data('id'));
        $('#btnCancel').show();
        
        $('.card-primary').removeClass('card-primary').addClass('card-warning');
        $('#btnSimpan').removeClass('btn-primary').addClass('btn-warning');
    });

    // Delete button
    $(document).on('click', '.btn-delete', function () {
        $('#delete-dokter-nama').text($(this).data('nama'));
        $('#deleteForm').attr('action', '/admin/dokter/' + $(this).data('id'));
        $('#deleteModal').modal('show');
    });

    // Cancel edit
    $('#btnCancel').on('click', function () {
        resetForm();
    });

    function resetForm() {
        $('#dokterForm')[0].reset();
        $('#form-title').text('Tambah Dokter');
        $('#method').val('POST');
        $('#dokterForm').attr('action', '{{ route("admin.dokter.store") }}');
        $('#btnCancel').hide();
        
        $('.card-warning').removeClass('card-warning').addClass('card-primary');
        $('#btnSimpan').removeClass('btn-warning').addClass('btn-primary');
    }
});
</script>
@endsection