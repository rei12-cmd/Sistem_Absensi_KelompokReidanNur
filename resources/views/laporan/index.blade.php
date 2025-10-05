@extends('layout')
@section('title', 'Laporan Absensi')

{{-- ===================== BREADCRUMB ===================== --}}
@section('breadcumb')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h3 class="mb-0">Laporan Absensi</h3>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item active">Laporan Absensi</li>
        </ol>
      </div>
    </div>
  </div>
</div>
@endsection

{{-- ===================== ISI KONTEN ===================== --}}
@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="card shadow-sm">
      <div class="card-header bg-primary text-white">
        <h3 class="card-title mb-0">Daftar Laporan Absensi</h3>
      </div>
      <div class="card-body">
        <p>Menampilkan seluruh laporan absensi siswa (khusus Admin & Guru).</p>
        <div class="table-responsive">
          <table class="table table-bordered table-striped">
            <thead class="table-light">
              <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Tanggal</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              {{-- Contoh data sementara --}}
              <tr>
                <td>1</td>
                <td>Rina Puspita</td>
                <td>XI RPL 1</td>
                <td>2025-10-05</td>
                <td><span class="badge bg-success">Hadir</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
