
<?php
// resources/views/admin/obat/index.blade.php
?>
@extends('layout.admin')

@section('title', 'Manajemen Obat')

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Manajemen Obat</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item active">Obat</li>
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
            <h3 class="card-title" id="form-title">Tambah Obat</h3>
          </div>
          <form id="obatForm" method="POST" action="{{ route('admin.obat.store') }}">
            @csrf
            <input type="hidden" name="_method" id="method" value="POST">
            <input type="hidden" name="id" id="obat_id">
            
            <div class="card-body">
              <div class="form-group">
                <label for="nama_obat">Nama Obat</label>
                <input type="text" class="form-control @error('nama_obat') is-invalid @enderror" 
                       id="nama_obat" name="nama_obat" placeholder="Paracetamol" 
                       value="{{ old('nama_obat') }}" required>
                @error('nama_obat')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              
              <div class="form-group">
                <label for="kemasan">Kemasan</label>
                <input type="text" class="form-control @error('kemasan') is-invalid @enderror" 
                       id="kemasan" name="kemasan" placeholder="Tablet 500mg" 
                       value="{{ old('kemasan') }}">
                @error('kemasan')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              
              <div class="form-group">
                <label for="harga">Harga (Rp)</label>
                <input type="number" class="form-control @error('harga') is-invalid @enderror" 
                       id="harga" name="harga" placeholder="10000" min="0"
                       value="{{ old('harga') }}" required>
                @error('harga')
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
            <h3 class="card-title">Daftar Obat</h3>
          </div>
          <div class="card-body table-responsive">
            <table class="table table-bordered table-striped" id="obatTable">
              <thead>
                <tr>
                  <th width="5%">No</th>
                  <th>Nama Obat</th>
                  <th>Kemasan</th>
                  <th>Harga</th>
                  <th width="15%">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($obats as $key => $obat)
                <tr>
                  <td>{{ $key + 1 }}</td>
                  <td>{{ $obat->nama_obat }}</td>
                  <td>{{ $obat->kemasan ?? '-' }}</td>
                  <td>Rp {{ number_format($obat->harga, 0, ',', '.') }}</td>
                  <td>
                    <button type="button" class="btn btn-sm btn-warning btn-edit"
                            data-id="{{ $obat->id }}"
                            data-nama="{{ $obat->nama_obat }}"
                            data-kemasan="{{ $obat->kemasan }}"
                            data-harga="{{ $obat->harga }}">
                      <i class="fas fa-edit"></i>
                    </button>
                    
                    <button type="button" class="btn btn-sm btn-danger btn-delete"
                            data-id="{{ $obat->id }}"
                            data-nama="{{ $obat->nama_obat }}">
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
        $('#obat_id').val($(this).data('id'));
        $('#nama_obat').val($(this).data('nama'));
        $('#kemasan').val($(this).data('kemasan'));
        $('#harga').val($(this).data('harga'));
        $('#form-title').text('Edit Obat');
        $('#method').val('PUT');
        $('#obatForm').attr('action', '/admin/obat/' + $(this).data('id'));
        $('#btnCancel').show();
        
        $('.card-primary').removeClass('card-primary').addClass('card-warning');
        $('#btnSimpan').removeClass('btn-primary').addClass('btn-warning');
    });

    // Delete button
    $(document).on('click', '.btn-delete', function () {
        if (confirm('Apakah Anda yakin ingin menghapus obat ' + $(this).data('nama') + '?')) {
            var form = $('<form>', {
                'method': 'POST',
                'action': '/admin/obat/' + $(this).data('id')
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
    });

    // Cancel edit
    $('#btnCancel').on('click', function () {
        resetForm();
    });

    function resetForm() {
        $('#obatForm')[0].reset();
        $('#form-title').text('Tambah Obat');
        $('#method').val('POST');
        $('#obatForm').attr('action', '{{ route("admin.obat.store") }}');
        $('#btnCancel').hide();
        
        $('.card-warning').removeClass('card-warning').addClass('card-primary');
        $('#btnSimpan').removeClass('btn-warning').addClass('btn-primary');
    }
});
</script>
@endsection