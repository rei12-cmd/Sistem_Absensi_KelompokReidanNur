@extends('layout')
@section('title', 'Rekap Absensi Siswa')

@section('content')
<div class="container-fluid">
  <div class="mb-3">
    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">← Kembali</a>
  </div>

  <div class="card">
    <div class="card-header">
      <h5 class="card-title mb-0">Rekap Absensi — {{ $siswa->nama }} ({{ $siswa->nis }})</h5>
      <small class="text-muted">Mata Pelajaran: {{ $mapel->nama }}</small>
    </div>

    <div class="card-body">
      <p>
        Total Pertemuan: <strong>{{ $total }}</strong> |
        Hadir: <strong>{{ $hadir }}</strong> |
        Tidak Hadir: <strong>{{ $tidakHadir }}</strong> |
        Presentase: <strong>{{ $persentase }}%</strong>
      </p>

      <div class="table-responsive">
        <table class="table table-sm table-bordered">
          <thead>
            <tr>
              <th style="width:160px">Tanggal</th>
              <th style="width:120px">Status</th>
              <th>Keterangan</th>
            </tr>
          </thead>
          <tbody>
            @forelse($absensiList as $a)
              <tr>
                <td>{{ \Carbon\Carbon::parse($a->tanggal)->format('Y-m-d') }}</td>
                <td>{{ ucfirst($a->status) }}</td>
                <td>{{ $a->keterangan }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="3" class="text-center">Belum ada data absensi untuk siswa ini pada mapel ini.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>
@endsection
