@extends('layout')
@section('title', 'Rekap Absensi Anak')

@section('content')
<section class="content">
    <div class="container-fluid">

        <div class="mb-3">
            <a href="{{ route('absensiAnakSaya.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">Rekap Absensi {{ $siswa->nama }}</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Mata Pelajaran</th>
                            <th>Guru Pengampu</th>
                            <th>Jadwal</th>
                            <th>H</th>
                            <th>I</th>
                            <th>S</th>
                            <th>A</th>
                            <th>Persentase Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rekap as $r)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $r['mapel'] }}</td>
                            <td>{{ $r['guru'] }}</td>
                            <td>{{ $r['hari'] }}, {{ $r['jam_mulai'] }} - {{ $r['jam_selesai'] }}</td>
                            <td>{{ $r['hadir'] }}</td>
                            <td>{{ $r['izin'] }}</td>
                            <td>{{ $r['sakit'] }}</td>
                            <td>{{ $r['alpa'] }}</td>
                            <td><strong>{{ $r['persentase'] }}%</strong></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">Belum ada data absensi untuk anak ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
