<!-- resources/views/dokter/periksa.blade.php -->
@extends('layout.dokter')

@section('title', 'Periksa Pasien')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Periksa Pasien</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dokter.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item active">Periksa Pasien</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <!-- Daftar Pasien Yang Perlu Diperiksa -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Daftar Pasien Yang Perlu Diperiksa</h3>
            <div class="card-tools">
              <div class="input-group input-group-sm" style="width: 150px;">
                <input type="text" name="table_search" class="form-control float-right" placeholder="Search">
                <div class="input-group-append">
                  <button type="submit" class="btn btn-default">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
              <thead>
                <tr>
                  <th>No</th>
                  <th>ID Periksa</th>
                  <th>Pasien</th>
                  <th>Tanggal Periksa</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @if(isset($periksa_pasiens) && count($periksa_pasiens) > 0)
                  @foreach($periksa_pasiens as $key => $periksa)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $periksa->id }}</td>
                    <td>{{ $periksa->pasien->nama }}</td>
                    <td>{{ \Carbon\Carbon::parse($periksa->tgl_periksa)->format('d M Y H:i') }}</td>
                    <td>
                      @if($periksa->catatan)
                        <span class="badge bg-success">Selesai</span>
                      @else
                        <span class="badge bg-warning">Menunggu</span>
                      @endif
                    </td>
                    <td>
                      @if(!$periksa->catatan)
                      <a href="{{ route('dokter.periksa.edit', $periksa->id) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-stethoscope"></i> Periksa
                      </a>
                      @else
                      <a href="{{ route('dokter.periksa.show', $periksa->id) }}" class="btn btn-sm btn-info">
                        <i class="fas fa-eye"></i> Detail
                      </a>
                      @endif
                    </td>
                  </tr>
                  @endforeach
                @endif
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>
    <!-- /.row -->
    
    <!-- Riwayat Pemeriksaan -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Riwayat Pemeriksaan</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="riwayatTable" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>No</th>
                  <th>ID Periksa</th>
                  <th>Pasien</th>
                  <th>Tanggal Periksa</th>
                  <th>Catatan Dokter</th>
                  <th>Obat</th>
                  <th>Biaya</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @if(isset($riwayat_periksa) && count($riwayat_periksa) > 0)
                  @foreach($riwayat_periksa as $key => $riwayat)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $riwayat->id }}</td>
                    <td>{{ $riwayat->pasien->nama }}</td>
                    <td>{{ \Carbon\Carbon::parse($riwayat->tgl_periksa)->format('d M Y H:i') }}</td>
                    <td>{{ $riwayat->catatan_dokter }}</td>
                    <td>
                      @if(count($riwayat->obat) > 0)
                        <ul class="pl-3 mb-0">
                          @foreach($riwayat->obat as $obat)
                            <li>{{ $obat->nama_obat }}</li>
                          @endforeach
                        </ul>
                      @else
                        <span class="text-muted">-</span>
                      @endif
                    </td>
                    <td>Rp {{ number_format($riwayat->biaya_periksa, 0, ',', '.') }}</td>
                    <td>
                      <a href="{{ route('dokter.periksa.show', $riwayat->id) }}" class="btn btn-sm btn-info">
                        <i class="fas fa-eye"></i> Detail
                      </a>
                    </td>
                  </tr>
                  @endforeach
                @endif
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
</section>
<!-- /.content -->

<!-- Modal Periksa -->
<div class="modal fade" id="modal-periksa">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Periksa Pasien</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('dokter.periksa.update', 1) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="pasien">Nama Pasien</label>
                <input type="text" class="form-control" id="pasien" value="Citra Dewi" readonly>
              </div>
              <div class="form-group">
                <label for="tgl_periksa">Tanggal Periksa</label>
                <input type="text" class="form-control" id="tgl_periksa" value="18 Apr 2025 10:00" readonly>
              </div>
              <div class="form-group">
                <label for="keluhan">Keluhan</label>
                <textarea class="form-control" id="keluhan" rows="3" readonly>Demam dan sakit kepala selama 2 hari</textarea>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="catatan">Catatan Pemeriksaan</label>
                <textarea class="form-control @error('catatan') is-invalid @enderror" id="catatan" name="catatan" rows="3" required></textarea>
                @error('catatan')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>
              <div class="form-group">
                <label>Obat</label>
                <div class="select2-purple">
                  <select class="select2" multiple="multiple" name="obat_ids[]" data-placeholder="Pilih obat" style="width: 100%;">
                    <option value="1">Paracetamol (Tablet 500mg) - Rp 10.000</option>
                    <option value="2">Amoxicillin (Kapsul 500mg) - Rp 25.000</option>
                    <option value="3">Ibuprofen (Tablet 400mg) - Rp 15.000</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="biaya_periksa">Biaya Periksa (Rp)</label>
                <input type="number" class="form-control @error('biaya_periksa') is-invalid @enderror" id="biaya_periksa" name="biaya_periksa" value="150000" required>
                @error('biaya_periksa')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">Simpan Pemeriksaan</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
@endsection

@section('styles')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('scripts')
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<!-- DataTables  & Plugins -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2({
      theme: 'bootstrap4'
    });
    
    $("#riwayatTable").DataTable({
      "responsive": true,
      "lengthChange": false,
      "autoWidth": false,
      "pageLength": 5
    });
  });
</script>
@endsection