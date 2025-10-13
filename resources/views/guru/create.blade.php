@extends('layout')
@section('title', 'Tambah Guru')

@section('content')
<div class="card col-md-6">
    <div class="card-header"><h5>Tambah Guru Baru</h5></div>
    <div class="card-body">
        <form method="POST" action="{{ route('guru.store') }}">
            @csrf

            <div class="mb-3">
                <label>Nama Guru</label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama') }}">
                @error('nama')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="mb-3">
                <label>NIP</label>
                <input type="text" name="nip" class="form-control" value="{{ old('nip') }}">
                @error('nip')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                @error('email')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                @error('password')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="mb-3">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>

            <div class="mb-3">
                <label>No Telepon</label>
                <input type="text" name="telepon" class="form-control" value="{{ old('telepon') }}">
            </div>

            <button class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@endsection
