@extends('layout')
@section('title', 'Edit Guru')

@section('content')
<div class="card col-md-6">
    <div class="card-header"><h5>Edit Data Guru</h5></div>
    <div class="card-body">
        <form method="POST" action="{{ route('guru.update', $guru->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Nama Guru</label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama', $guru->nama) }}">
                @error('nama')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="mb-3">
                <label>NIP</label>
                <input type="text" name="nip" class="form-control" value="{{ old('nip', $guru->nip) }}">
                @error('nip')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $guru->user->email) }}">
                @error('email')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="mb-3">
                <label>Password Baru (opsional)</label>
                <input type="password" name="password" class="form-control">
            </div>

            <div class="mb-3">
                <label>Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>

            <div class="mb-3">
                <label>No Telepon</label>
                <input type="text" name="telepon" class="form-control" value="{{ old('telepon', $guru->telepon) }}">
            </div>

            <button class="btn btn-success">Update</button>
        </form>
    </div>
</div>
@endsection
