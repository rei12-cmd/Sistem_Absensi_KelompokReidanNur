@extends('layout')

@section('title', 'Daftar Jadwal Mengajar')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-success">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Daftar Jadwal Mengajar</h5>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @elseif (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if ($jadwals->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-success">
                            <tr>
                                <th>No</th>
                                <th>Kelas</th>
                                <th>Mata Pelajaran</th>
                                <th>Hari</th>
                                <th>Jam</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jadwals as $no => $jadwal)
                                <tr>
                                    <td>{{ $no + 1 }}</td>
                                    <td>{{ $jadwal->guruMapelKelas->kelas->nama ?? '-' }}</td>
                                    <td>{{ $jadwal->guruMapelKelas->mataPelajaran->nama ?? '-' }}</td>
                                    <td>{{ ucfirst($jadwal->hari) }}</td>
                                    <td>{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
                                    <td>
                                        <a href="{{ route('absensi.show', $jadwal->id) }}" 
                                           class="btn btn-success btn-sm">
                                           <i class="fas fa-clipboard-check"></i> Mulai Absen
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">Tidak ada jadwal mengajar yang tersedia untuk Anda.</div>
            @endif
        </div>
    </div>
</div>
@endsection
