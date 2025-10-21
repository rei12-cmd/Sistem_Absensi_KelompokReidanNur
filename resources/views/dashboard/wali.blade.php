@extends('layout')
@section('title', 'Dashboard Wali')

@section('content')
<div class="card">
<div class="card-body">
     <h4>Selamat datang, {{ $wali->nama }}</h4>
    <h6>Data Anak Anda:</h6>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Jurusan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($siswaList as $s)
            <tr>
                <td>{{ $s->nama }}</td>
                <td>{{ $s->kelas->nama }}</td>
                <td>{{ $s->jurusan->nama }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>
@endsection
