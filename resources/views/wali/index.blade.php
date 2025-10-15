@extends('layout')
@section('title', 'Data Wali')

@section('content')
<div class="row">
    <div class="card">
        <div class="card-body">
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Data Wali</h3>
    <a href="{{ route('wali.create') }}" class="btn btn-primary">Tambah Wali</a>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Telepon</th>
            <th>Alamat</th>
            <th>Anak (Siswa)</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($walis as $wali)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $wali->nama }}</td>
            <td>{{ $wali->user->email }}</td>
            <td>{{ $wali->telepon }}</td>
            <td>{{ $wali->alamat }}</td>
            <td>
                @foreach($wali->siswa as $s)
                    <span class="badge bg-info">{{ $s->nama }}</span>
                @endforeach
            </td>
            <td>
                <a href="{{ route('wali.edit', $wali->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('wali.destroy', $wali->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus wali ini?')">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection

        </div>
    </div>
</div>