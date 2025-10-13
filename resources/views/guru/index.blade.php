@extends('layout')
@section('title', 'Guru')

@section('breadcumb')
<div class="row">
    <div class="col-sm-6"><h3 class="mb-0">Guru</h3></div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Guru</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('guru.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> Tambah Guru
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
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Telepon</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gurus as $guru)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $guru->user->email }}</td>
                        <td>{{ $guru->nip ?? '-' }}</td>
                        <td>{{ $guru->nama }}</td>
                        <td>{{ $guru->telepon ?? '-' }}</td>
                        <td>
                            <a href="{{ route('guru.edit', $guru->id) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('guru.destroy', $guru->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin ingin menghapus data ini?')" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">Belum ada data guru.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
