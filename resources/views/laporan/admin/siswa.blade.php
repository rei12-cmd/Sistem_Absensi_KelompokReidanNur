@extends('layout')
@section('title', 'Detail Absensi Siswa')

@section('content')
<div class="card">
 <div class="card-body">
    <h3>Detail Absensi: {{ $siswa->nama }}</h3>
    <p>Kelas: {{ $siswa->kelas->nama }} | Jurusan: {{ $siswa->jurusan->nama }}</p>
    <hr>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Mapel</th>
                    <th>Guru</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($absensi as $key => $a)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $a->tanggal->format('d-m-Y') }}</td>
                    <td>{{ $a->jadwal->guruMapelKelas->mataPelajaran->nama }}</td>
                    <td>{{ $a->jadwal->guruMapelKelas->guru->nama }}</td>
                    <td>{{ $a->status }}</td>
                    <td>{{ $a->keterangan ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>
</div>
@endsection
