<!-- resources/views/dokter/periksa-show.blade.php -->
@extends('layout.dokter')

@section('title', 'Detail Pemeriksaan')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Detail Pemeriksaan</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dokter.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('dokter.periksa') }}">Daftar Periksa</a></li>
          <li class="breadcrumb-item active">Detail</li>
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
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-stethoscope mr-1"></i>
              Informasi Pemeriksaan
            </h3>
            <div class="card-tools">
              <button type="button" class="btn btn-default" onclick="window.print()">
                <i class="fas fa-print"></i> Cetak
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div id="printArea">
              <div class="row">
                <div class="col-md-6">
                  <dl class="row">
                    <dt class="col-sm-4">ID Pemeriksaan</dt>
                    <dd class="col-sm-8">#{{ $periksa->id }}</dd>
                    
                    <dt class="col-sm-4">Tanggal Periksa</dt>
                    <dd class="col-sm-8">{{ \Carbon\Carbon::parse($periksa->tgl_periksa)->format('d M Y H:i') }}</dd>
                    
                    <dt class="col-sm-4">Dokter</dt>
                    <dd class="col-sm-8">{{ $periksa->dokter->nama }}</dd>
                    
                    <dt class="col-sm-4">Pasien</dt>
                    <dd class="col-sm-8">{{ $periksa->pasien->nama }}</dd>
                    
                    <dt class="col-sm-4">Alamat Pasien</dt>
                    <dd class="col-sm-8">{{ $periksa->pasien->alamat }}</dd>
                    
                    <dt class="col-sm-4">No. HP Pasien</dt>
                    <dd class="col-sm-8">{{ $periksa->pasien->no_hp }}</dd>
                  </dl>
                </div>
                <div class="col-md-6">
                  <dl class="row">
                    <dt class="col-sm-4">Catatan/Diagnosa</dt>
                    <dd class="col-sm-8">{{ $periksa->catatan }}</dd>
                    
                    <dt class="col-sm-4">Obat</dt>
                    <dd class="col-sm-8">
                      @if(count($periksa->obat) > 0)
                        <ul class="pl-3 mb-0">
                          @foreach($periksa->obat as $obat)
                            <li>{{ $obat->nama_obat }} ({{ $obat->kemasan }}) - Rp {{ number_format($obat->harga, 0, ',', '.') }}</li>
                          @endforeach
                        </ul>
                      @else
                        <span class="text-muted">-</span>
                      @endif
                    </dd>
                    
                    @php
                      $total_obat = 0;
                      foreach($periksa->obat as $obat) {
                        $total_obat += $obat->harga;
                      }
                      $biaya_periksa = $periksa->biaya_periksa - $total_obat;
                    @endphp
                    
                    <dt class="col-sm-4">Biaya Pemeriksaan</dt>
                    <dd class="col-sm-8">Rp {{ number_format($biaya_periksa, 0, ',', '.') }}</dd>
                    
                    <dt class="col-sm-4">Biaya Obat</dt>
                    <dd class="col-sm-8">Rp {{ number_format($total_obat, 0, ',', '.') }}</dd>
                    
                    <dt class="col-sm-4">Total Biaya</dt>
                    <dd class="col-sm-8"><strong>Rp {{ number_format($periksa->biaya_periksa, 0, ',', '.') }}</strong></dd>
                  </dl>
                </div>
              </div>
              <!-- /.row -->
              
              <hr>
              
              <div class="row mt-4">
                <div class="col-md-8">
                  <h5>Petunjuk Penggunaan Obat:</h5>
                  @if(count($periksa->obat) > 0)
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th>Nama Obat</th>
                          <th>Aturan Pakai</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($periksa->obat as $obat)
                          <tr>
                            <td>{{ $obat->nama_obat }} ({{ $obat->kemasan }})</td>
                            <td>
                              @if(strpos(strtolower($obat->nama_obat), 'paracetamol') !== false)
                                3 x 1 tablet setelah makan
                              @elseif(strpos(strtolower($obat->nama_obat), 'amoxicillin') !== false)
                                3 x 1 kapsul setelah makan
                              @elseif(strpos(strtolower($obat->nama_obat), 'ibuprofen') !== false)
                                3 x 1 tablet setelah makan
                              @else
                                Sesuai petunjuk dokter
                              @endif
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  @else
                    <p class="text-muted">Tidak ada obat yang diresepkan</p>
                  @endif
                </div>
                <div class="col-md-4 text-right">
                  <p class="mb-5">Hormat Kami,</p>
                  <p class="mb-0"><strong>{{ $periksa->dokter->nama }}</strong></p>
                  <p>Dokter</p>
                </div>
              </div>
              <!-- /.row -->
            </div>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <a href="{{ route('dokter.periksa') }}" class="btn btn-default">Kembali</a>
          </div>
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
</section>
<!-- /.content -->

<!-- Print Style -->
<style type="text/css" media="print">
  @page {
    size: auto;
    margin: 10mm;
  }
  
  body {
    background-color: #fff;
    margin: 0;
    padding: 0;
  }
  
  .card {
    box-shadow: none !important;
    border: none !important;
  }
  
  .card-header, .card-footer, .main-header, .main-sidebar, .content-header {
    display: none !important;
  }
  
  .content-wrapper {
    background-color: #fff !important;
    margin-left: 0 !important;
    padding-top: 0 !important;
  }
  
  .main-footer {
    display: none !important;
  }
</style>
@endsection