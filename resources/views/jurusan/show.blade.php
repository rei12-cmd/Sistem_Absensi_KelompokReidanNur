@extends('layout')

@section('title', 'Detail Jurusan')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Detail Jurusan</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th>ID</th>
                    <td>{{ $jurusan->id }}</td>
                </tr>
                <tr>
                    <th>Nama Jurusan</th>
                    <td>{{ $jurusan->nama }}</td>
                </tr>
                <tr>
                    <th>Singkatan</th>
                    <td>{{ $jurusan->singkatan }}</td>
                </tr>
                <tr>
                    <th>Deskripsi</th>
                    <td>{{ $jurusan->deskripsi }}</td>
                </tr>
               
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        <a href="{{ route('jurusan.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@endsection
