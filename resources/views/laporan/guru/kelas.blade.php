@extends('layout')
@section('title', 'Rekap Absensi Kelas')

@section('content')
<div class="container">
    <h3>Rekap Absensi</h3>
    <p>Mapel: {{ $gmk->mataPelajaran->nama }} | Kelas: {{ $gmk->kelas->nama }} | Guru: {{ $gmk->guru->nama }}</p>
    <hr>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Daftar Siswa</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>H</th>
                        <th>I</th>
                        <th>S</th>
                        <th>A</th>
                        <th>Persentase Kehadiran</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($gmk->kelas->siswa as $key => $siswa)
                    @php
                        $hadir = $absensi->where('siswa_id',$siswa->id)->where('status','H')->count();
                        $izin = $absensi->where('siswa_id',$siswa->id)->where('status','I')->count();
                        $sakit = $absensi->where('siswa_id',$siswa->id)->where('status','S')->count();
                        $alpa = $absensi->where('siswa_id',$siswa->id)->where('status','A')->count();
                        $total = $hadir + $izin + $sakit + $alpa;
                        $persen = $total ? round(($hadir/$total)*100,2) : 0;
                    @endphp
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $siswa->nama }}</td>
                        <td>{{ $hadir }}</td>
                        <td>{{ $izin }}</td>
                        <td>{{ $sakit }}</td>
                        <td>{{ $alpa }}</td>
                        <td>{{ $persen }}%</td>
                        <td>
                            <a href="{{ route('guru.laporan.siswa', $siswa->id) }}" class="btn btn-sm btn-info">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
