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
            <!-- Info Pasien & Riwayat -->
            <div class="row g-4 mb-4">
              <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                  <div class="card-header bg-primary text-white">
                    <strong>Informasi Pasien</strong>
                  </div>
                  <div class="card-body">
                    <dl class="row mb-0">
                      <dt class="col-sm-5">Nama Pasien</dt>
                      <dd class="col-sm-7 mb-1">{{ $daftarPoli->pasien->nama }}</dd>
                      <dt class="col-sm-5">No. RM</dt>
                      <dd class="col-sm-7 mb-1">{{ $daftarPoli->pasien->no_rm }}</dd>
                      <dt class="col-sm-5">No. HP</dt>
                      <dd class="col-sm-7 mb-1">{{ $daftarPoli->pasien->no_hp }}</dd>
                      <dt class="col-sm-5">Alamat</dt>
                      <dd class="col-sm-7 mb-1">{{ $daftarPoli->pasien->alamat }}</dd>
                      <dt class="col-sm-5">Tanggal Daftar</dt>
                      <dd class="col-sm-7 mb-1">{{ \Carbon\Carbon::parse($daftarPoli->tanggal_daftar)->format('d M Y') }}</dd>
                      <dt class="col-sm-5">No. Antrian</dt>
                      <dd class="col-sm-7 mb-1">
                        <span class="badge badge-primary">#{{ $daftarPoli->no_antrian }}</span>
                      </dd>
                      <dt class="col-sm-5">Keluhan</dt>
                      <dd class="col-sm-7 mb-1">{{ $daftarPoli->keluhan ?? '-' }}</dd>
                    </dl>
                  </div>
                </div>
              </div>
              
              <div class="col-md-8">
                <div class="card h-100 shadow-sm">
                  <div class="card-header bg-warning">
                    <strong>Riwayat Pemeriksaan Sebelumnya</strong>
                  </div>
                  <div class="card-body p-0">
                    <div class="table-responsive">
                      <table class="table table-sm table-bordered mb-0">
                        <thead class="bg-light">
                          <tr>
                            <th style="width: 110px;">Tanggal</th>
                            <th>Diagnosa</th>
                            <th>Obat</th>
                          </tr>
                        </thead>
                        <tbody>
                          @php
                              $riwayats = App\Models\Periksa::whereHas('daftarPoli', function($q) use ($daftarPoli) {
                                  $q->where('id_pasien', $daftarPoli->id_pasien);
                              })
                              ->where('id', '!=', $periksa->id ?? 0)
                              ->whereNotNull('catatan')
                              ->with(['daftarPoli.jadwal.dokter', 'obat'])
                              ->orderBy('tgl_periksa', 'desc')
                              ->limit(3)
                              ->get();
                          @endphp
                          @if(count($riwayats) > 0)
                            @foreach($riwayats as $riwayat)
                              <tr>
                                <td>{{ \Carbon\Carbon::parse($riwayat->tgl_periksa)->format('d/m/Y') }}</td>
                                <td>{{ $riwayat->catatan ?? '-' }}</td>
                                <td>
                                  @if(count($riwayat->obat) > 0)
                                    <ul class="mb-0 ps-3">
                                      @foreach($riwayat->obat as $obat)
                                        <li>{{ $obat->nama_obat }}</li>
                                      @endforeach
                                    </ul>
                                  @else
                                    <span class="text-muted">-</span>
                                  @endif
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
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
    
    <!-- Form Input Hasil Pemeriksaan -->
    <div class="row g-4">
      <div class="col-md-12">
        <div class="card shadow-sm">
          <div class="card-header bg-success text-white">
            <strong>Form Input Hasil Pemeriksaan</strong>
          </div>
          <form action="{{ route('dokter.periksa.update', $daftarPoli->id) }}" method="POST" class="m-0">
            @csrf
            @method('PUT')
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="catatan" class="form-label">Diagnosa & Catatan Pemeriksaan</label>
                    <textarea class="form-control @error('catatan') is-invalid @enderror" 
                              id="catatan" name="catatan" rows="5" required 
                              placeholder="Masukkan diagnosa dan catatan pemeriksaan...">{{ old('catatan', $periksa->catatan ?? '') }}</textarea>
                    @error('catatan')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>
                  <div class="mb-3">
                    <label for="biaya_periksa" class="form-label">Biaya Pemeriksaan (Rp)</label>
                    <input type="number" class="form-control @error('biaya_periksa') is-invalid @enderror" 
                           id="biaya_periksa" name="biaya_periksa" 
                           value="{{ old('biaya_periksa', $periksa->biaya_periksa ?? 150000) }}" 
                           min="0" required>
                    @error('biaya_periksa')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                    <small class="text-muted">*Biaya jasa dokter: Rp 150.000 (belum termasuk obat)</small>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Obat yang Diresepkan</label>
                    <select class="select2" multiple="multiple" name="obat_ids[]" 
                            data-placeholder="Pilih obat yang diresepkan" style="width: 100%;">
                      @foreach($obats as $obat)
                        <option value="{{ $obat->id }}" 
                                {{ (collect(old('obat_ids', $periksa ? $periksa->obat->pluck('id')->toArray() : []))->contains($obat->id)) ? 'selected' : '' }}>
                          {{ $obat->nama_obat }} ({{ $obat->kemasan }}) - Rp {{ number_format($obat->harga, 0, ',', '.') }}
                        </option>
                      @endforeach
                    </select>
                    <small class="text-muted">*Pilih beberapa obat yang akan diresepkan</small>
                  </div>
                  
                  <!-- Preview Biaya -->
                  <div class="alert alert-info mt-3">
                    <h6><i class="icon fas fa-info-circle"></i> Preview Biaya:</h6>
                    <table class="table table-sm mb-0">
                      <tr>
                        <td>Biaya Jasa Dokter:</td>
                        <td class="text-right"><strong>Rp 150.000</strong></td>
                      </tr>
                      <tr id="biaya-obat-row" style="display: none;">
                        <td>Biaya Obat:</td>
                        <td class="text-right" id="biaya-obat"><strong>Rp 0</strong></td>
                      </tr>
                      <tr>
                        <td><strong>Total Biaya:</strong></td>
                        <td class="text-right" id="total-biaya"><strong>Rp 150.000</strong></td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer bg-light">
              <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Simpan Hasil Pemeriksaan
              </button>
              <a href="{{ route('dokter.periksa') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
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
      theme: 'bootstrap4',
      placeholder: 'Pilih obat yang diresepkan',
      allowClear: true
    });
    
    // Data obat untuk kalkulasi
    var obatData = {
        @foreach($obats as $obat)
        '{{ $obat->id }}': {{ $obat->harga }},
        @endforeach
    };
    
    // Fungsi hitung biaya
    function hitungBiaya() {
        var selectedObat = $('.select2').val() || [];
        var biayaObat = 0;
        var biayaJasaDokter = 150000;
        
        selectedObat.forEach(function(obatId) {
            biayaObat += obatData[obatId] || 0;
        });
        
        var totalBiaya = biayaJasaDokter + biayaObat;
        
        // Update display
        $('#biaya-obat').html('<strong>Rp ' + biayaObat.toLocaleString('id-ID') + '</strong>');
        $('#total-biaya').html('<strong>Rp ' + totalBiaya.toLocaleString('id-ID') + '</strong>');
        $('#biaya_periksa').val(totalBiaya);
        
        if (biayaObat > 0) {
            $('#biaya-obat-row').show();
        } else {
            $('#biaya-obat-row').hide();
        }
    }
    
    // Event listener untuk perubahan obat
    $('.select2').on('change', function() {
        hitungBiaya();
    });
    
    // Hitung biaya awal
    hitungBiaya();
});
</script>
@endsection