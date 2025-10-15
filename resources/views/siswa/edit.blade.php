@extends('layout')
@section('title', 'Edit Siswa')

@section('content')
<div class="card col-md-6">
    <div class="card-header"><h5>Edit Siswa</h5></div>
    <div class="card-body">
        <form method="POST" action="{{ route('siswa.store') }}">
            @csrf

             {{-- <input type="text" name="nama" class="form-control" value="{{ old('nama', $guru->nama) }}"> --}}
            <div class="mb-3">
                <label>nis</label>
                <input type="text" name="nis" class="form-control" value="{{ old('nis', $siswa->nis) }}">
                @error('nis')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="mb-3">
                <label>Nama Siswa</label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama', $siswa->nama) }}">
                @error('nama')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="mb-3">
                <label>Username</label>
                <input type="username" name="username" class="form-control" value="{{ old('username', default: $siswa->username) }}">
                @error('username')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', default:$siswa->email) }}">
                @error('email')<small class="text-danger">{{ $message }}</small>@enderror
            </div>


            </div>
             @error('kelas_id')
                        <span class="badge bg-danger">{{ $message }}</span>
                        @enderror
                        <div class="mb-3">
                            <label for="kelas_id" class="form-label">Kelas</label>
                            <select class="form-control" id="kelas_id" name="kelas_id">
                                <option value="">Pilih Kelas</option>
                                @foreach($kelass as $k)
                                    <option value="{{ $k->id }}" {{ old('kelas_id', $k->kelas_id) == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control"> value="{{ old('password', $siswa->password) }}">
                @error('password')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="mb-3">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control"> value="{{ old('password', $siswa->password) }}">
            </div>

            <div class="mb-3">
                <label>Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', default: $siswa->tanggal_lahir ) }}">
            </div>

            <div class="mb-3">
                <label>Alamat</label>
                <input type="text" name="alamat" class="form-control" value="{{ old('alamat', default: $siswa->alamat) }}">
            </div>

            <button class="btn btn-primary">Edit</button>
        </form>
    </div>
</div>
@endsection
