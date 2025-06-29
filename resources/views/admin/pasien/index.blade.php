
<?php
// resources/views/admin/pasien/index.blade.php
?>
@extends('layout.admin')

@section('title', 'Manajemen Pasien')

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Manajemen Pasien</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item active">Pasien</li>
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
            <h3 class="card-title" id="form-title">Tambah Pasien</h3>
          </div>
          <form id="pasienForm" method="POST" action="{{ route('admin.pasien.store') }}">
            @csrf
            <input type="hidden" name="_method" id="method" value="POST">
            <input type="hidden" name="id" id="pasien_id">
            
            <div class="card-body">
              <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                       id="nama" name="nama" placeholder="John Doe" 
                       value="{{ old('nama') }}" required>
                @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              
              <div class="form-group">
                <label for="alamat">Alamat</label>
                <textarea class="form-control @error('alamat') is-invalid @enderror" 
                          id="alamat" name="alamat" rows="3" 
                          placeholder="Alamat lengkap pasien" required>{{ old('alamat') }}</textarea>
                @error('alamat')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              
              <div class="form-group">
                <label for="no_ktp">No. KTP</label>
                <input type="text" class="form-control @error('no_ktp') is-invalid @enderror" 
                       id="no_ktp" name="no_ktp" placeholder="1234567890123456" 
                       value="{{ old('no_ktp') }}" maxlength="16" required>
                @error('no_ktp')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">16 digit NIK</small>
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
            <h3 class="card-title">Daftar Pasien</h3>
          </div>
          <div class="card-body table-responsive">
            <table class="table table-bordered table-striped" id="pasienTable">
              <thead>
                <tr>
                  <th width="5%">No</th>
                  <th>No. RM</th>
                  <th>Nama Pasien</th>
                  <th>No. KTP</th>
                  <th>No. HP</th>
                  <th width="10%">Kunjungan</th>
                  <th width="15%">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($pasiens as $key => $pasien)
                <tr>
                  <td>{{ $key + 1 }}</td>
                  <td>
                    <span class="badge badge-success">{{ $pasien->no_rm }}</span>
                  </td>
                  <td>{{ $pasien->nama }}</td>
                  <td>{{ $pasien->no_ktp }}</td>
                  <td>{{ $pasien->no_hp }}</td>
                  <td>
                    <span class="badge badge-info">{{ $pasien->daftar_poli_count }}x</span>
                  </td>
                  <td>
                    <button type="button" class="btn btn-sm btn-warning btn-edit"
                            data-id="{{ $pasien->id }}"
                            data-nama="{{ $pasien->nama }}"
                            data-alamat="{{ $pasien->alamat }}"
                            data-no_ktp="{{ $pasien->no_ktp }}"
                            data-no_hp="{{ $pasien->no_hp }}">
                      <i class="fas fa-edit"></i>
                    </button>
                    
                    <button type="button" class="btn btn-sm btn-danger btn-delete"
                            data-id="{{ $pasien->id }}"
                            data-nama="{{ $pasien->nama }}"
                            {{ $pasien->daftar_poli_count > 0 ? 'disabled title="Tidak dapat dihapus karena memiliki riwayat"' : '' }}>
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
</section>
@endsection

@section('scripts')
<script>
$(function () {
    // Validasi No KTP hanya angka
    $('#no_ktp').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // Edit button
    $(document).on('click', '.btn-edit', function () {
        $('#pasien_id').val($(this).data('id'));
        $('#nama').val($(this).data('nama'));
        $('#alamat').val($(this).data('alamat'));
        $('#no_ktp').val($(this).data('no_ktp'));
        $('#no_hp').val($(this).data('no_hp'));
        $('#form-title').text('Edit Pasien');
        $('#method').val('PUT');
        $('#pasienForm').attr('action', '/admin/pasien/' + $(this).data('id'));
        $('#btnCancel').show();
        
        $('.card-primary').removeClass('card-primary').addClass('card-warning');
        $('#btnSimpan').removeClass('btn-primary').addClass('btn-warning');
    });

    // Delete button
    $(document).on('click', '.btn-delete', function () {
        if (!$(this).prop('disabled')) {
            if (confirm('Apakah Anda yakin ingin menghapus pasien ' + $(this).data('nama') + '?')) {
                var form = $('<form>', {
                    'method': 'POST',
                    'action': '/admin/pasien/' + $(this).data('id')
                });
                
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': '_token',
                    'value': '{{ csrf_token() }}'
                }));
                
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': '_method',
                    'value': 'DELETE'
                }));
                
                $('body').append(form);
                form.submit();
            }
        }
    });

    // Cancel edit
    $('#btnCancel').on('click', function () {
        resetForm();
    });

    function resetForm() {
        $('#pasienForm')[0].reset();
        $('#form-title').text('Tambah Pasien');
        $('#method').val('POST');
        $('#pasienForm').attr('action', '{{ route("admin.pasien.store") }}');
        $('#btnCancel').hide();
        
        $('.card-warning').removeClass('card-warning').addClass('card-primary');
        $('#btnSimpan').removeClass('btn-warning').addClass('btn-primary');
    }
});
</script>
@endsection
