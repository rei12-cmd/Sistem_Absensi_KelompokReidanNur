@extends('layout')
@section('title', 'Dashboard Siswa')

@section('content')
<div class="card">
<div class="card-body">
     <h4>Halo, {{ $siswa->nama }}</h4>
    <p>Kelas: {{ $siswa->kelas->nama }} | Jurusan: {{ $siswa->jurusan->nama }}</p>

    <h6>Riwayat Absensi Terbaru:</h6>
    <table class="table table-sm">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($absensi as $a)
            <tr>
                <td>{{ $a->tanggal }}</td>
                <td>{{ $a->status }}</td>
                <td>{{ $a->keterangan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>
@endsection
