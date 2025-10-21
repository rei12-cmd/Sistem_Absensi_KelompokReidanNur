@extends('layout')
@section('title', 'Dashboard Guru')

@section('content')
<div class="card">
<div class="card-body">
     <h4>Selamat datang, {{ $guru->nama }}</h4>
    <h6>Jadwal Mengajar Anda:</h6>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Hari</th>
                <th>Mata Pelajaran</th>
                <th>Kelas</th>
                <th>Jam</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jadwal as $j)
            <tr>
                <td>{{ $j->hari }}</td>
                <td>{{ $j->guruMapelKelas->mataPelajaran->nama }}</td>
                <td>{{ $j->guruMapelKelas->kelas->nama }}</td>
                <td>{{ $j->jam_mulai }} - {{ $j->jam_selesai }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>
@endsection
