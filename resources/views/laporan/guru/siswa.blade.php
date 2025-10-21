@extends('layout')
@section('title', 'Detail Absensi Siswa')

@section('content')
<div class="container">
    <h3>Detail Absensi Siswa</h3>
    <hr>

    <div class="card">
        <div class="card-header bg-success text-white">
            <h5 class="card-title mb-0">
                Siswa: {{ $absensi->first()->siswa->nama ?? '-' }}
            </h5>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-success">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Mata Pelajaran</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensi as $key => $a)
                        <tr>
                            <td>{{ $absensi->firstItem() + $key }}</td>
                            <td>{{ \Carbon\Carbon::parse($a->tanggal)->format('d-m-Y') }}</td>
                            <td>{{ $a->jadwal->guruMapelKelas->mataPelajaran->nama ?? '-' }}</td>
                            <td>{{ $a->status }}</td>
                            <td>{{ $a->keterangan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada data absensi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-center mt-3">
                {{ $absensi->links('pagination::bootstrap-5') }}
            </div>
        </div>

        <div class="card-footer text-end">
            <a href="{{ url()->previous() }}" class="btn btn-secondary px-4">Kembali</a>
        </div>
    </div>
</div>
@endsection
