@extends('layout')
@section('title', 'Laporan Absensi')

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

@section('content')
<div class="container-fluid">
  <div class="card">
    <div class="card-header">
      <h5 class="card-title mb-0">Daftar Kelas & Mata Pelajaran yang Anda Ajar</h5>
    </div>
    <div class="card-body p-0">
      <table class="table table-bordered table-striped mb-0">
        <thead>
          <tr>
            <th style="width:50px">No</th>
            <th>Kelas</th>
            <th>Mata Pelajaran</th>
            <th style="width:120px">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($list as $i => $row)
            @php
              $kelas = optional($row->kelas);
              $mapel = optional($row->mapel);
              $kelasId = $kelas->id ?? null;
              $mapelId = $mapel->id ?? null;
              $canLink = $kelasId && $mapelId;
            @endphp
            <tr>
              <td>{{ $i + 1 }}</td>
              <td>{{ $kelas->nama ?? '-' }}</td>
              <td>{{ $mapel->nama ?? '-' }}</td>
              <td>
                @if($canLink)
                  <a href="{{ route('laporan.kelas.detail', ['kelas' => $kelasId, 'mapel' => $mapelId]) }}"
                     class="btn btn-sm btn-primary">
                    Detail
                  </a>
                @else
                  <button class="btn btn-sm btn-secondary" disabled>Detail</button>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="text-center">Belum ada data kelas / mata pelajaran.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
