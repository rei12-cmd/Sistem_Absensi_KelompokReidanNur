@extends('layout')
@section('title', 'Rekap Absensi Kelas')

@section('content')
<div class="container-fluid">
    <div class="card mb-3 shadow-sm border-success">
        <div class="card-body">
            <h4 class="mb-2 text-success"><i class="fas fa-clipboard-list"></i> Rekap Absensi Kelas</h4>
            <p class="mb-0">
                <strong>Mata Pelajaran:</strong> {{ $gmk->mataPelajaran->nama }} |
                <strong>Kelas:</strong> {{ $gmk->kelas->nama }} |
                <strong>Guru:</strong> {{ $gmk->guru->nama }}
            </p>
        </div>
    </div>

    <div class="card border-success shadow-sm">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Daftar Siswa dan Rekap Kehadiran</h5>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-success text-center">
                    <tr>
                        <th rowspan="2" style="width: 50px;">No</th>
                        <th rowspan="2">Nama Siswa</th>
                        <th colspan="4">Jumlah</th>
                        <th rowspan="2">Persentase Kehadiran</th>
                        <th rowspan="2">Detail</th>
                    </tr>
                    <tr>
                        <th>H</th>
                        <th>I</th>
                        <th>S</th>
                        <th>A</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswa as $key => $item)
                        @php
                            $hadir = $absensi->where('siswa_id', $item->id)->where('status', 'H')->count();
                            $izin = $absensi->where('siswa_id', $item->id)->where('status', 'I')->count();
                            $sakit = $absensi->where('siswa_id', $item->id)->where('status', 'S')->count();
                            $alpa = $absensi->where('siswa_id', $item->id)->where('status', 'A')->count();
                            $total = $hadir + $izin + $sakit + $alpa;
                            $persen = $total ? round(($hadir / $total) * 100, 2) : 0;
                        @endphp
                        <tr class="text-center">
                            <td>{{ $siswa->firstItem() + $key }}</td>
                            <td class="text-start">{{ $item->nama }}</td>
                            <td class="text-success fw-bold">{{ $hadir }}</td>
                            <td class="text-primary">{{ $izin }}</td>
                            <td class="text-warning">{{ $sakit }}</td>
                            <td class="text-danger">{{ $alpa }}</td>
                            <td>
                                <span class="badge bg-{{ $persen >= 90 ? 'success' : ($persen >= 75 ? 'warning' : 'danger') }}">
                                    {{ $persen }}%
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('guru.laporan.siswa', $item->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Belum ada data siswa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-end mt-3">
                {{ $siswa->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
