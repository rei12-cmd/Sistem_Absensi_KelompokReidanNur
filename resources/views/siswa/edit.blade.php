@extends('layout')
@section('title', 'Edit Siswa')

@section('content')
<div class="card col-md-6">
    <div class="card-header"><h5>Edit Data Siswa</h5></div>
    <div class="card-body">
        <form method="POST" action="{{ route('siswa.update', $siswa->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>NIS</label>
                <input type="text" name="nis" class="form-control" 
                       value="{{ old('nis', $siswa->nis) }}">
                @error('nis')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="mb-3">
                <label>Nama Siswa</label>
                <input type="text" name="nama" class="form-control" 
                       value="{{ old('nama', $siswa->nama) }}">
                @error('nama')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" 
                       value="{{ old('username', $siswa->user->username) }}">
                @error('username')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" 
                       value="{{ old('email', $siswa->user->email) }}">
                @error('email')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="mb-3">
                <label for="kelas_id" class="form-label">Kelas</label>
                <select class="form-control" id="kelas_id" name="kelas_id">
                    <option value="">Pilih kelas</option>
                    @foreach($kelass as $kelas)
                        <option value="{{ $kelas->id }}" 
                            {{ old('kelas_id', $siswa->kelas_id) == $kelas->id ? 'selected' : '' }}>
                            {{ $kelas->nama }}
                        </option>
                    @endforeach
                </select>
                @error('kelas_id')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="mb-3">
                <label>Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" class="form-control" 
                       value="{{ old('tanggal_lahir', $siswa->tanggal_lahir) }}">
                @error('tanggal_lahir')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="mb-3">
                <label>Alamat</label>
                <input type="text" name="alamat" class="form-control" 
                       value="{{ old('alamat', $siswa->alamat) }}">
                @error('alamat')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="mb-3">
                <label>Password (opsional, isi jika ingin mengganti)</label>
                <input type="password" name="password" class="form-control">
                @error('password')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="mb-3">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>

            <button class="btn btn-primary">Update</button>
        </form>
    </div>
</div>
@endsection
