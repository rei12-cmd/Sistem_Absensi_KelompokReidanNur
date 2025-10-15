@extends('layout')
@section('title', 'Edit Siswa')

@section('content')
<div class="card col-md-6">
    <div class="card-header"><h5>Edit Siswa</h5></div>
    <div class="card-body">
        {{-- Sesuaikan route jika Anda ingin tetap menggunakan store; untuk edit biasanya pakai update --}}
        <form method="POST" action="{{ route('siswa.update', $siswa->id) }}">
            @csrf
            @method('PUT')

            {{-- <input type="text" name="nama" class="form-control" value="{{ old('nama', $guru->nama) }}"> --}}

            <div class="mb-3">
                <label for="nis">NIS</label>
                <input id="nis" type="text" name="nis" class="form-control" value="{{ old('nis', $siswa->nis) }}">
                @error('nis')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="mb-3">
                <label for="nama">Nama Siswa</label>
                <input id="nama" type="text" name="nama" class="form-control" value="{{ old('nama', $siswa->nama) }}">
                @error('nama')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="mb-3">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" class="form-control" value="{{ old('email', $siswa->user->email) }}">
                @error('email')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            {{-- Kelas --}}
            @error('kelas_id')
                <span class="badge bg-danger">{{ $message }}</span>
            @enderror
            <div class="mb-3">
                <label for="kelas_id" class="form-label">Kelas</label>
                <select class="form-control" id="kelas_id" name="kelas_id">
                    <option value="">Pilih Kelas</option>
                    @foreach($kelass as $k)
                        <option value="{{ $k->id }}" {{ old('kelas_id', $siswa->kelas_id) == $k->id ? 'selected' : '' }}>
                            {{ $k->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" class="form-control">
                @error('password')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" class="form-control">
            </div>

            <div class="mb-3">
                <label for="tanggal_lahir">Tanggal Lahir</label>
                <input id="tanggal_lahir" type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $siswa->tanggal_lahir) }}">
            </div>

            <div class="mb-3">
                <label for="alamat">Alamat</label>
                <input id="alamat" type="text" name="alamat" class="form-control" value="{{ old('alamat', $siswa->alamat) }}">
            </div>

            <button class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection
