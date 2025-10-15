@extends('layout')
@section('title', 'Laporan Absensi Anak')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title mb-0">Daftar Anak Anda</h3>
      </div>
      <div class="card-body">
        <table class="table table-bordered table-striped align-middle">
          <thead class="table-light">
            <tr>
              <th>No</th>
              <th>Nama Anak</th>
              <th>Kelas</th>
              <th>Jurusan</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($anak as $a)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $a->nama }}</td>
              <td>{{ $a->kelas->nama }}</td>
              <td>{{ $a->jurusan->nama }}</td>
              <td>
                <a href="{{ route('absensiAnakSaya.show', $a->id) }}" class="btn btn-info btn-sm">
                  <i class="bi bi-eye"></i> Lihat Rekap
                </a>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="5" class="text-center text-muted">Belum ada data anak yang terdaftar.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>
@endsection
