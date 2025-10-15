@extends('layout')
@section('title', 'Tambah Siswa')

@section('content')
<div class="card col-md-6">
    <div class="card-header"><h5>Tambah Siswa Baru</h5></div>
    <div class="card-body">
        <form method="POST" action="{{ route('siswa.store') }}">
            @csrf

            <div class="mb-3">
                <label>nis</label>
                <input type="text" name="nis" class="form-control" value="{{ old('nis') }}">
                @error('nis')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="mb-3">
                <label>Nama Siswa</label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama') }}">
                @error('nama')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="mb-3">
                <label>Username</label>
                <input type="username" name="username" class="form-control" value="{{ old('username') }}">
                @error('username')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                @error('email')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            @error('kelas_id')
            <span class="badge bg-danger">{{ $message }}</span>
            @enderror
            <div class="mb-3">
                <label for="kelas_id" class="form-label">kelas</label>
                <select class="form-control" id="kelas_id" name="kelas_id">
                    <option value="">Pilih kelas</option>
                    @foreach($kelass as $kelas)
                        <option value="{{ $kelas->id }}">{{ $kelas->nama }}</option>
                    @endforeach
                </select>
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
                <label>Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}">
            </div>

            <div class="mb-3">
                <label>Alamat</label>
                <input type="text" name="alamat" class="form-control" value="{{ old('alamat') }}">
            </div>

            <button class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@endsection
