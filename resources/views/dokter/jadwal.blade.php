<!-- resources/views/dokter/jadwal.blade.php -->
@extends('layout.dokter')

@section('title', 'Jadwal Periksa')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Jadwal Periksa Saya</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dokter.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item active">Jadwal Periksa</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
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
      <div class="col-md-4">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title" id="form-title">Tambah Jadwal Baru</h3>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form id="jadwalForm" method="POST" action="{{ route('dokter.jadwal.store') }}">
            @csrf
            <input type="hidden" name="_method" id="method" value="POST">
            <input type="hidden" name="id" id="jadwal_id">
            
            <div class="card-body">
              <div class="form-group">
                <label for="hari">Hari Praktik</label>
                <select class="form-control @error('hari') is-invalid @enderror" id="hari" name="hari" required>
                  <option value="">-- Pilih Hari --</option>
                  <option value="Senin" {{ old('hari') == 'Senin' ? 'selected' : '' }}>Senin</option>
                  <option value="Selasa" {{ old('hari') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                  <option value="Rabu" {{ old('hari') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                  <option value="Kamis" {{ old('hari') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                  <option value="Jumat" {{ old('hari') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                  <option value="Sabtu" {{ old('hari') == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                  <option value="Minggu" {{ old('hari') == 'Minggu' ? 'selected' : '' }}>Minggu</option>
                </select>
                @error('hari')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              
              <div class="form-group">
                <label for="jam_mulai">Jam Mulai</label>
                <input type="time" class="form-control @error('jam_mulai') is-invalid @enderror" 
                       id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai') }}" required>
                @error('jam_mulai')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              
              <div class="form-group">
                <label for="jam_selesai">Jam Selesai</label>
                <input type="time" class="form-control @error('jam_selesai') is-invalid @enderror" 
                       id="jam_selesai" name="jam_selesai" value="{{ old('jam_selesai') }}" required>
                @error('jam_selesai')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
              <button type="submit" class="btn btn-primary" id="btnSimpan">
                <i class="fas fa-save"></i> Simpan Jadwal
              </button>
              <button type="button" id="btnCancel" class="btn btn-default" style="display: none;">
                <i class="fas fa-times"></i> Batal
              </button>
            </div>
          </form>
        </div>
        <!-- /.card -->

        <!-- Info Box -->
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">Informasi Jadwal</h3>
          </div>
          <div class="card-body">
            <div class="alert alert-info">
              <h5><i class="icon fas fa-info"></i> Aturan Jadwal:</h5>
              <ul class="mb-0 pl-3">
                <li>Anda dapat memiliki lebih dari satu jadwal</li>
                <li>Hanya <strong>satu jadwal</strong> yang dapat aktif dalam satu waktu</li>
                <li>Jadwal tidak dapat diubah pada hari H</li>
                <li>Jadwal yang sudah digunakan tidak dapat dihapus</li>
                <li>Pastikan tidak ada jadwal yang bentrok</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Daftar Jadwal Periksa</h3>
          </div>
          <div class="card-body table-responsive">
            <table class="table table-bordered table-striped" id="jadwalTable">
              <thead>
                <tr>
                  <th width="5%">No</th>
                  <th>Hari</th>
                  <th>Jam Mulai</th>
                  <th>Jam Selesai</th>
                  <th>Status</th>
                  <th width="20%">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($jadwals as $key => $jadwal)
                <tr>
                  <td>{{ $key + 1 }}</td>
                  <td>{{ $jadwal->hari }}</td>
                  <td>{{ $jadwal->jam_mulai->format('H:i') }}</td>
                  <td>{{ $jadwal->jam_selesai->format('H:i') }}</td>
                  <td>
                    @if($jadwal->aktif)
                      <span class="badge badge-success">
                        <i class="fas fa-check"></i> Aktif
                      </span>
                    @else
                      <span class="badge badge-secondary">
                        <i class="fas fa-times"></i> Tidak Aktif
                      </span>
                    @endif
                  </td>
                  <td class="text-nowrap">
                    @if($jadwal->aktif)
                      <span class="badge badge-warning" title="Terkunci - jadwal aktif">
                        <i class="fas fa-lock"></i> Terkunci
                      </span>
                    @else
                      <form method="POST" action="{{ route('dokter.jadwal.activate', $jadwal->id) }}" style="display:inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" 
                                class="btn btn-sm btn-primary" 
                                title="Aktifkan jadwal ini">
                          <i class="fas fa-power-off"></i> Aktifkan
                        </button>
                      </form>
                      
                      <button type="button" class="btn btn-sm btn-warning btn-edit"
                              data-id="{{ $jadwal->id }}"
                              data-hari="{{ $jadwal->hari }}"
                              data-jam_mulai="{{ $jadwal->jam_mulai->format('H:i') }}"
                              data-jam_selesai="{{ $jadwal->jam_selesai->format('H:i') }}"
                              title="Edit jadwal">
                        <i class="fas fa-edit"></i> Edit
                      </button>
                    @endif
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
<!-- /.content -->
@endsection

@section('scripts')
<script>
$(function () {
    // Auto-close alerts after 5 seconds
    $("#success-alert, #error-alert").fadeTo(5000, 500).slideUp(500);
    
    // DataTable inisialisasi
    $('#jadwalTable').DataTable({
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

    // Handler edit jadwal (event delegation)
    $(document).on('click', '.btn-edit', function () {
        $('#jadwal_id').val($(this).data('id'));
        $('#hari').val($(this).data('hari'));
        $('#jam_mulai').val($(this).data('jam_mulai'));
        $('#jam_selesai').val($(this).data('jam_selesai'));
        $('#form-title').text('Edit Jadwal');
        
        $('.card-primary').removeClass('card-primary').addClass('card-warning');
        $('#btnSimpan').removeClass('btn-primary').addClass('btn-warning').html('<i class="fas fa-save"></i> Update Jadwal');
        $('#method').val('PUT');
        $('#jadwalForm').attr('action', '/dokter/jadwal/' + $(this).data('id'));
        $('#btnCancel').show();
    });

    // Cancel edit
    $('#btnCancel').on('click', function () {
        resetForm();
    });

    function resetForm() {
        $('#jadwalForm')[0].reset();
        $('#form-title').text('Tambah Jadwal Baru');
        $('.card-warning').removeClass('card-warning').addClass('card-primary');
        $('#btnSimpan').removeClass('btn-warning').addClass('btn-primary').html('<i class="fas fa-save"></i> Simpan Jadwal');
        $('#method').val('POST');
        $('#jadwalForm').attr('action', '{{ route('dokter.jadwal.store') }}');
        $('#btnCancel').hide();
    }
    
    // Validasi jam
    $('#jam_selesai').on('change', function() {
        var jamMulai = $('#jam_mulai').val();
        var jamSelesai = $(this).val();
        
        if (jamMulai && jamSelesai && jamSelesai <= jamMulai) {
            alert('Jam selesai harus lebih besar dari jam mulai!');
            $(this).val('');
        }
    });
});
</script>
@endsection