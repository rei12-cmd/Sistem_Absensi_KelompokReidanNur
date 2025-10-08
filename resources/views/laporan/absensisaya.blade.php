@extends('layout')
@section('title', 'Absensi Saya')

@section('breadcumb')
<div class="row">
  <div class="col-sm-6"><h3 class="mb-0">Absensi Saya</h3></div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-end">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
      <li class="breadcrumb-item active">Absensi Saya</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<div class="card">
  <div class="card-header"><h3 class="card-title">Riwayat Absensi Anda</h3></div>
  <div class="card-body">
    <p>Menampilkan data absensi Anda (siswa yang sedang login).</p>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>No</th>
          <th>Tanggal</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        {{-- Contoh data sementara --}}
        <tr>
          <td>1</td>
          <td>2025-10-05</td>
          <td>Hadir</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
@endsection
