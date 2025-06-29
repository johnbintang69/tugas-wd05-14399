<?php
// resources/views/admin/poli/index.blade.php
?>
@extends('layout.admin')

@section('title', 'Manajemen Poliklinik')

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Manajemen Poliklinik</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item active">Poliklinik</li>
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
            <h3 class="card-title" id="form-title">Tambah Poliklinik</h3>
          </div>
          <form id="poliForm" method="POST" action="{{ route('admin.poli.store') }}">
            @csrf
            <input type="hidden" name="_method" id="method" value="POST">
            <input type="hidden" name="id" id="poli_id">
            
            <div class="card-body">
              <div class="form-group">
                <label for="nama_poli">Nama Poliklinik</label>
                <input type="text" class="form-control @error('nama_poli') is-invalid @enderror" 
                       id="nama_poli" name="nama_poli" placeholder="Contoh: Umum, Gigi, Anak" 
                       value="{{ old('nama_poli') }}" required>
                @error('nama_poli')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              
              <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                          id="keterangan" name="keterangan" rows="3" 
                          placeholder="Deskripsi poliklinik...">{{ old('keterangan') }}</textarea>
                @error('keterangan')
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
            <h3 class="card-title">Daftar Poliklinik</h3>
          </div>
          <div class="card-body table-responsive">
            <table class="table table-bordered table-striped" id="poliTable">
              <thead>
                <tr>
                  <th width="5%">No</th>
                  <th>Nama Poliklinik</th>
                  <th>Keterangan</th>
                  <th width="10%">Dokter</th>
                  <th width="15%">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($polis as $key => $poli)
                <tr>
                  <td>{{ $key + 1 }}</td>
                  <td>{{ $poli->nama_poli }}</td>
                  <td>{{ $poli->keterangan ?? '-' }}</td>
                  <td>
                    <span class="badge badge-info">{{ $poli->dokter_count }} dokter</span>
                  </td>
                  <td>
                    <button type="button" class="btn btn-sm btn-warning btn-edit"
                            data-id="{{ $poli->id }}"
                            data-nama="{{ $poli->nama_poli }}"
                            data-keterangan="{{ $poli->keterangan }}">
                      <i class="fas fa-edit"></i>
                    </button>
                    
                    <button type="button" class="btn btn-sm btn-danger btn-delete"
                            data-id="{{ $poli->id }}"
                            data-nama="{{ $poli->nama_poli }}"
                            {{ $poli->dokter_count > 0 ? 'disabled title="Tidak dapat dihapus karena ada dokter terkait"' : '' }}>
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
    // Edit button
    $(document).on('click', '.btn-edit', function () {
        $('#poli_id').val($(this).data('id'));
        $('#nama_poli').val($(this).data('nama'));
        $('#keterangan').val($(this).data('keterangan'));
        $('#form-title').text('Edit Poliklinik');
        $('#method').val('PUT');
        $('#poliForm').attr('action', '/admin/poli/' + $(this).data('id'));
        $('#btnCancel').show();
        
        $('.card-primary').removeClass('card-primary').addClass('card-warning');
        $('#btnSimpan').removeClass('btn-primary').addClass('btn-warning');
    });

    // Delete button
    $(document).on('click', '.btn-delete', function () {
        if (!$(this).prop('disabled')) {
            if (confirm('Apakah Anda yakin ingin menghapus poli ' + $(this).data('nama') + '?')) {
                var form = $('<form>', {
                    'method': 'POST',
                    'action': '/admin/poli/' + $(this).data('id')
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
        $('#poliForm')[0].reset();
        $('#form-title').text('Tambah Poliklinik');
        $('#method').val('POST');
        $('#poliForm').attr('action', '{{ route("admin.poli.store") }}');
        $('#btnCancel').hide();
        
        $('.card-warning').removeClass('card-warning').addClass('card-primary');
        $('#btnSimpan').removeClass('btn-warning').addClass('btn-primary');
    }
});
</script>
@endsection
