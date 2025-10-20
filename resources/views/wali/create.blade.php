@extends('layout')
@section('title', 'Tambah Wali')

@section('content')
<div class="row">
    <div class="card">
        <div class="card-body">
            <h3>Tambah Wali</h3>

<form action="{{ route('wali.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Username</label>
        <input type="text" name="username" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Telepon</label>
        <input type="text" name="telepon" class="form-control">
    </div>
    <div class="mb-3">
        <label>Alamat</label>
        <input type="text" name="alamat" class="form-control">
    </div>
    <div class="mb-3">
        <label>Pilih Siswa (bisa lebih dari satu)</label>
        <select name="siswas[]" class="form-control" multiple>
            @foreach($siswas as $siswa)
                <option value="{{ $siswa->id }}">{{ $siswa->nama }}</option>
            @endforeach
        </select>
    </div>
    <button class="btn btn-primary">Simpan</button>
</form>
        </div>
    </div>
</div>
@endsection
