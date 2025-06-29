<!-- resources/views/pasien/riwayat.blade.php -->
@extends('layout.pasien')

@section('title', 'Riwayat Periksa')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Riwayat Periksa</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('pasien.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item active">Riwayat Periksa</li>
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
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-history mr-2"></i>
              Daftar Riwayat Periksa
            </h3>
          </div>
          <div class="card-body table-responsive">
            <table class="table table-bordered table-striped" id="riwayatTable">
              <thead>
                <tr>
                  <th width="5%">No</th>
                  <th>ID Periksa</th>
                  <th>Tanggal Periksa</th>
                  <th>Dokter</th>
                  <th>Poli</th>
                  <th>Catatan Dokter</th>
                  <th>Obat</th>
                  <th>Biaya Periksa</th>
                  <th width="10%">Detail</th>
                </tr>
              </thead>
              <tbody>
                @if(isset($riwayats) && count($riwayats) > 0)
                  @foreach($riwayats as $key => $riwayat)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>
                      <span class="badge badge-primary">#{{ $riwayat->id }}</span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($riwayat->tgl_periksa)->format('d M Y H:i') }}</td>
                    <td>{{ $riwayat->daftarPoli->jadwal->dokter->nama }}</td>
                    <td>
                      <span class="badge badge-info">{{ $riwayat->daftarPoli->jadwal->dokter->poli->nama_poli }}</span>
                    </td>
                    <td>{{ Str::limit($riwayat->catatan, 50) }}</td>
                    <td>
                      @if(count($riwayat->obat) > 0)
                        <ul class="pl-3 mb-0">
                          @foreach($riwayat->obat as $obat)
                            <li>{{ $obat->nama_obat }} ({{ $obat->kemasan }})</li>
                          @endforeach
                        </ul>
                      @else
                        <span class="text-muted">-</span>
                      @endif
                    </td>
                    <td>
                      <strong>Rp {{ number_format($riwayat->biaya_periksa, 0, ',', '.') }}</strong>
                    </td>
                    <td>
                      <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modal-detail-{{ $riwayat->id }}">
                        <i class="fas fa-eye"></i> Detail
                      </button>
                    </td>
                  </tr>
                  @endforeach
                @else
                  <tr>
                    <td colspan="9" class="text-center py-4">
                      <div class="text-muted">
                        <i class="fas fa-file-medical fa-3x mb-3"></i>
                        <p>Belum ada riwayat pemeriksaan.</p>
                        <a href="{{ route('pasien.periksa') }}" class="btn btn-primary">
                          <i class="fas fa-plus"></i> Daftar Periksa Sekarang
                        </a>
                      </div>
                    </td>
                  </tr>
                @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
</section>
<!-- /.content -->

<!-- Modal Detail -->
@if(isset($riwayats) && count($riwayats) > 0)
  @foreach($riwayats as $riwayat)
  <div class="modal fade" id="modal-detail-{{ $riwayat->id }}">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title">Detail Pemeriksaan #{{ $riwayat->id }}</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <dl class="row">
                <dt class="col-sm-5">ID Periksa</dt>
                <dd class="col-sm-7">{{ $riwayat->id }}</dd>
                
                <dt class="col-sm-5">Tanggal Periksa</dt>
                <dd class="col-sm-7">{{ \Carbon\Carbon::parse($riwayat->tgl_periksa)->format('d M Y H:i') }}</dd>
                
                <dt class="col-sm-5">Dokter</dt>
                <dd class="col-sm-7">{{ $riwayat->daftarPoli->jadwal->dokter->nama }}</dd>
                
                <dt class="col-sm-5">Poli</dt>
                <dd class="col-sm-7">{{ $riwayat->daftarPoli->jadwal->dokter->poli->nama_poli }}</dd>
                
                <dt class="col-sm-5">Keluhan Awal</dt>
                <dd class="col-sm-7">{{ $riwayat->daftarPoli->keluhan }}</dd>
                
                <dt class="col-sm-5">Catatan Dokter</dt>
                <dd class="col-sm-7">{{ $riwayat->catatan }}</dd>
              </dl>
            </div>
            
            <div class="col-md-6">
              <h5>Obat yang Diresepkan:</h5>
              @if(count($riwayat->obat) > 0)
                <div class="table-responsive">
                  <table class="table table-sm table-bordered">
                    <thead>
                      <tr>
                        <th>Nama Obat</th>
                        <th>Harga</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($riwayat->obat as $obat)
                        <tr>
                          <td>
                            {{ $obat->nama_obat }}<br>
                            <small class="text-muted">{{ $obat->kemasan }}</small>
                          </td>
                          <td>Rp {{ number_format($obat->harga, 0, ',', '.') }}</td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @else
                <p class="text-muted">Tidak ada obat yang diresepkan</p>
              @endif
              
              <hr>
              
              <h5>Rincian Biaya:</h5>
              @php
                $total_obat = 0;
                foreach($riwayat->obat as $obat) {
                  $total_obat += $obat->harga;
                }
                $biaya_periksa = $riwayat->biaya_periksa - $total_obat;
              @endphp
              
              <table class="table table-sm">
                <tr>
                  <td>Biaya Pemeriksaan</td>
                  <td class="text-right">Rp {{ number_format($biaya_periksa, 0, ',', '.') }}</td>
                </tr>
                <tr>
                  <td>Biaya Obat</td>
                  <td class="text-right">Rp {{ number_format($total_obat, 0, ',', '.') }}</td>
                </tr>
                <tr class="font-weight-bold">
                  <td><strong>Total Biaya</strong></td>
                  <td class="text-right"><strong>Rp {{ number_format($riwayat->biaya_periksa, 0, ',', '.') }}</strong></td>
                </tr>
              </table>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
          <button type="button" class="btn btn-primary" onclick="printModal('modal-detail-{{ $riwayat->id }}')">
            <i class="fas fa-print"></i> Cetak
          </button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
  @endforeach
@endif
@endsection

@section('styles')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('scripts')
<!-- DataTables  & Plugins -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

<script>
$(function () {
    $("#riwayatTable").DataTable({
      responsive: true, 
      lengthChange: false, 
      autoWidth: false,
      order: [[ 2, "desc" ]], // Sort by tanggal periksa desc
      buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
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
    }).buttons().container().appendTo('#riwayatTable_wrapper .col-md-6:eq(0)');
});

function printModal(modalId) {
    var printContent = document.getElementById(modalId).querySelector('.modal-body').innerHTML;
    var newWindow = window.open('', '_blank');
    newWindow.document.write(`
        <html>
        <head>
            <title>Detail Pemeriksaan</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
                .text-right { text-align: right; }
                .font-weight-bold { font-weight: bold; }
            </style>
        </head>
        <body>
            <h2>Detail Pemeriksaan</h2>
            ${printContent}
        </body>
        </html>
    `);
    newWindow.document.close();
    newWindow.print();
}
</script>
@endsection