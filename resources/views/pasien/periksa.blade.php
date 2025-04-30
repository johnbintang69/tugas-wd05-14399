<!-- resources/views/pasien/periksa.blade.php -->
@extends('layout.pasien')

@section('title', 'Daftar Periksa')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Daftar Periksa</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('pasien.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item active">Daftar Periksa</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-6">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Form Daftar Periksa</h3>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form method="POST" action="{{ route('pasien.periksa.store') }}">
            @csrf
            <div class="card-body">
              <div class="form-group">
                <label for="nama">Nama Anda</label>
                <input type="text" class="form-control" id="nama" name="nama" value="{{ Auth::user()->nama }}" readonly>
              </div>
              <div class="form-group">
                <label for="id_dokter">Pilih Dokter</label>
                <select class="form-control @error('id_dokter') is-invalid @enderror" id="id_dokter" name="id_dokter" required>
                  <option value="">-- Pilih Dokter --</option>
                  @foreach($dokters ?? [] as $dokter)
                  <option value="{{ $dokter->id }}">{{ $dokter->nama }}</option>
                  @endforeach
                  @if(empty($dokters))
                  <option value="1">Dr. Andi Pratama</option>
                  <option value="2">Dr. Budi Santoso</option>
                  @endif
                </select>
                @error('id_dokter')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>
              <div class="form-group">
                <label for="tgl_periksa">Tanggal & Waktu Periksa</label>
                <div class="input-group date" id="tgl_periksa_picker" data-target-input="nearest">
                  <input type="text" class="form-control datetimepicker-input @error('tgl_periksa') is-invalid @enderror" id="tgl_periksa" name="tgl_periksa" data-target="#tgl_periksa_picker" required>
                  <div class="input-group-append" data-target="#tgl_periksa_picker" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                  </div>
                  @error('tgl_periksa')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                  @enderror
                </div>
              </div>
              <div class="form-group">
                <label for="keluhan">Keluhan</label>
                <textarea class="form-control @error('keluhan') is-invalid @enderror" id="keluhan" name="keluhan" rows="3" placeholder="Deskripsikan keluhan Anda" required></textarea>
                @error('keluhan')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
              <button type="submit" class="btn btn-primary">Daftar Periksa</button>
            </div>
          </form>
        </div>
      </div>
      
      <div class="col-md-6">
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">Informasi Dokter</h3>
          </div>
          <div class="card-body">
            <div class="alert alert-info">
              <h5><i class="icon fas fa-info"></i> Informasi Pendaftaran</h5>
              <p>Silahkan pilih dokter dan waktu untuk melakukan pendaftaran periksa. Pastikan Anda datang 15 menit sebelum jadwal yang dipilih.</p>
            </div>
            
            <h5>Jadwal Praktik Dokter</h5>
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Nama Dokter</th>
                  <th>Jadwal Praktik</th>
                </tr>
              </thead>
              <tbody>
                @foreach($dokters ?? [] as $dokter)
                <tr>
                  <td>{{ $dokter->nama }}</td>
                  <td>Senin - Jumat, 08:00 - 16:00</td>
                </tr>
                @endforeach
                @if(empty($dokters))
                <tr>
                  <td>Dr. Andi Pratama</td>
                  <td>Senin - Jumat, 08:00 - 16:00</td>
                </tr>
                <tr>
                  <td>Dr. Budi Santoso</td>
                  <td>Senin - Sabtu, 09:00 - 17:00</td>
                </tr>
                @endif
              </tbody>
            </table>
            
            <h5 class="mt-4">Biaya Periksa</h5>
            <p>Biaya dasar pemeriksaan: <strong>Rp 150.000</strong></p>
            <p>Harga obat akan ditambahkan sesuai dengan resep dokter.</p>
          </div>
        </div>
      </div>
    </div>
    
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Daftar Periksa Yang Akan Datang</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Tanggal Periksa</th>
                  <th>Dokter</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @if(isset($upcoming_periksa) && count($upcoming_periksa) > 0)
                  @foreach($upcoming_periksa as $key => $periksa)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($periksa->tgl_periksa)->format('d M Y H:i') }}</td>
                    <td>{{ $periksa->dokter->nama }}</td>
                    <td><span class="badge badge-warning">Menunggu</span></td>
                    <td>
                      <form action="{{ route('pasien.periksa.destroy', $periksa->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin membatalkan pendaftaran?')">
                          <i class="fas fa-trash"></i> Batalkan
                        </button>
                      </form>
                    </td>
                  </tr>
                  @endforeach
                @else
                  <tr>
                    <td colspan="5" class="text-center">Belum ada jadwal pemeriksaan yang akan datang.</td>
                  </tr>
                @endif
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>
  </div>
</section>
<!-- /.content -->
@endsection

@section('styles')
<!-- Tempusdominus Bootstrap 4 -->
<link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
@endsection

@section('scripts')
<!-- InputMask -->
<script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('plugins/inputmask/jquery.inputmask.min.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<script>
  $(function () {
    //Date and time picker
    $('#tgl_periksa_picker').datetimepicker({
      icons: { time: 'far fa-clock' },
      format: 'YYYY-MM-DD HH:mm:ss',
      minDate: moment().startOf('day'),
      stepping: 30,
      enabledHours: [8, 9, 10, 11, 12, 13, 14, 15, 16]
    });
    
    // Disable weekends
    $('#tgl_periksa_picker').on('dp.change', function(e) {
      var day = moment(e.date).day();
      if (day === 6 || day === 0) {
        alert('Mohon maaf, klinik tidak buka di hari Sabtu dan Minggu');
        $('#tgl_periksa_picker').data("DateTimePicker").clear();
      }
    });
  });
</script>
@endsection