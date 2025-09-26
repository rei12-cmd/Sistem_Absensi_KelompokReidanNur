@extends('layout')

@section('title', 'Detail Kelas')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Detail Kelas</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tbody>
                <tr><th>ID</th><td>{{ $kelas->id }}</td></tr>
                <tr><th>Nama Kelas</th><td>{{ $kelas->nama }}</td></tr>
                <tr><th>Jurusan</th><td>{{ $kelas->jurusan->nama ?? '-' }}</td></tr>
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        <a href="{{ route('kelas.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@endsection
