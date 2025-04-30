<!-- resources/views/dokter/periksa-edit.blade.php -->
@extends('layout.dokter')

@section('title', 'Pemeriksaan Pasien')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Pemeriksaan Pasien</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dokter.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('dokter.periksa') }}">Daftar Periksa</a></li>
          <li class="breadcrumb-item active">Pemeriksaan</li>
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
      <div class="col-md-12">
        <div class="card card-primary card-outline">
          <div class="card-header">
            <h3 class="card-title">Form Pemeriksaan Pasien</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <div class="callout callout-info">
                  <h5>Informasi Pasien</h5>
                  <dl class="row">
                    <dt class="col-sm-4">Nama Pasien</dt>
                    <dd class="col-sm-8">{{ $periksa->pasien->nama }}</dd>
                    
                    <dt class="col-sm-4">Email</dt>
                    <dd class="col-sm-8">{{ $periksa->pasien->email }}</dd>
                    
                    <dt class="col-sm-4">No. HP</dt>
                    <dd class="col-sm-8">{{ $periksa->pasien->no_hp }}</dd>
                    
                    <dt class="col-sm-4">Alamat</dt>
                    <dd class="col-sm-8">{{ $periksa->pasien->alamat }}</dd>
                    
                    <dt class="col-sm-4">Tanggal Periksa</dt>
                    <dd class="col-sm-8">{{ \Carbon\Carbon::parse($periksa->tgl_periksa)->format('d M Y H:i') }}</dd>
                    
                    <dt class="col-sm-4">Keluhan</dt>
                    <dd class="col-sm-8">{{ $periksa->keluhan ?? '-' }}</dd>
                  </dl>
                </div>
              </div>
              <div class="col-md-6">
                <!-- Riwayat Pemeriksaan Sebelumnya -->
                <div class="card card-outline card-warning">
                  <div class="card-header">
                    <h3 class="card-title">Riwayat Pemeriksaan Sebelumnya</h3>
                  </div>
                  <div class="card-body p-0">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>Tanggal</th>
                          <th>Diagnosa</th>
                          <th>Obat</th>
                        </tr>
                      </thead>
                      <tbody>
                        @php
                          $riwayats = App\Models\Periksa::where('id_pasien', $periksa->id_pasien)
                            ->where('id', '!=', $periksa->id)
                            ->where('biaya_periksa', '>', 0)
                            ->orderBy('tgl_periksa', 'desc')
                            ->limit(3)
                            ->get();
                        @endphp
                        
                        @if(count($riwayats) > 0)
                          @foreach($riwayats as $riwayat)
                          <tr>
                            <td>{{ \Carbon\Carbon::parse($riwayat->tgl_periksa)->format('d/m/Y') }}</td>
                            <td>{{ $riwayat->catatan }}</td>
                            <td>
                              <ul class="mb-0 pl-3">
                                @foreach($riwayat->obat as $obat)
                                  <li>{{ $obat->nama_obat }}</li>
                                @endforeach
                              </ul>
                            </td>
                          </tr>
                          @endforeach
                        @else
                          <tr>
                            <td colspan="3" class="text-center">Tidak ada riwayat pemeriksaan sebelumnya</td>
                          </tr>
                        @endif
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
    
    <div class="row">
      <div class="col-md-12">
        <form action="{{ route('dokter.periksa.update', $periksa->id) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Input Hasil Pemeriksaan</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="catatan">Diagnosa & Catatan Pemeriksaan</label>
                    <textarea class="form-control @error('catatan') is-invalid @enderror" id="catatan" name="catatan" rows="4" required>{{ old('catatan') }}</textarea>
                    @error('catatan')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                    @enderror
                  </div>
                  <div class="form-group">
                    <label for="biaya_periksa">Biaya Pemeriksaan (Rp)</label>
                    <input type="number" class="form-control @error('biaya_periksa') is-invalid @enderror" id="biaya_periksa" name="biaya_periksa" value="{{ old('biaya_periksa', 150000) }}" required>
                    @error('biaya_periksa')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                    @enderror
                    <small class="text-muted">*Belum termasuk harga obat</small>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Obat yang Diresepkan</label>
                    <select class="select2" multiple="multiple" name="obat_ids[]" data-placeholder="Pilih obat" style="width: 100%;">
                      @foreach($obats as $obat)
                        <option value="{{ $obat->id }}">{{ $obat->nama_obat }} ({{ $obat->kemasan }}) - Rp {{ number_format($obat->harga, 0, ',', '.') }}</option>
                      @endforeach
                    </select>
                    <small class="text-muted">*Pilih beberapa obat yang diresepkan</small>
                  </div>
                  <div class="alert alert-info mt-3">
                    <i class="icon fas fa-info-circle"></i> Harga obat akan ditambahkan otomatis ke biaya pemeriksaan.
                  </div>
                </div>
              </div>
              <!-- /.row -->
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
              <button type="submit" class="btn btn-primary">Simpan Hasil Pemeriksaan</button>
              <a href="{{ route('dokter.periksa') }}" class="btn btn-default">Kembali</a>
            </div>
          </div>
          <!-- /.card -->
        </form>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('styles')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('scripts')
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2({
      theme: 'bootstrap4'
    });
  });
</script>
@endsection