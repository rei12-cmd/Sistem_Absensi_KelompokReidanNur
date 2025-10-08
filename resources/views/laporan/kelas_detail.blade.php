@extends('layout')
@section('title', 'Daftar Siswa')

@section('content')
<div class="container-fluid">
  <div class="mb-3">
    <a href="{{ route('laporan.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
  </div>

  <div class="card">
    <div class="card-header">
      <h5 class="card-title mb-0">Daftar Siswa — Kelas: {{ $kelas->nama }} | Mapel: {{ $mapel->nama }}</h5>
    </div>
    <div class="card-body p-0">
      <table class="table table-hover mb-0">
        <thead>
          <tr>
            <th style="width:120px">NIS</th>
            <th>Nama</th>
            <th style="width:130px">Presentase</th>
            <th style="width:120px">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($siswas as $siswa)
            <tr>
              <td>{{ $siswa->nis }}</td>
              <td>{{ $siswa->nama }}</td>
              <td>{{ $siswa->rekap['persentase'] ?? 0 }}%</td>
              <td>
                <a href="{{ route('laporan.siswa.rekap', ['siswa' => $siswa->id, 'mapel' => $mapel->id]) }}"
                   class="btn btn-sm btn-info">Detail</a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="text-center">Tidak ada siswa pada kelas ini.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
