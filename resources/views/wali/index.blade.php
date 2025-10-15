@extends('layout')
@section('title', 'Wali')

@section('breadcumb')
<div class="row">
    <div class="col-sm-6"><h3 class="mb-0">Wali</h3></div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Wali</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('wali.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> Tambah Wali
        </a>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Email</th>
                    <th>Nama</th>
                    <th>Telepon</th>
                    <th>Alamat</th>
                    <th>Siswa Terkait</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($walis as $wali)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $wali->user->email }}</td>
                        <td>{{ $wali->nama }}</td>
                        <td>{{ $wali->telepon ?? '-' }}</td>
                        <td>{{ $wali->alamat ?? '-' }}</td>
                        <td>{{ $wali->siswa_id }}</td>

                        <td>
                            <a href="{{ route('wali.edit', $wali->id) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('wali.destroy', $wali->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin ingin menghapus data ini?')" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">Belum ada data wali.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
