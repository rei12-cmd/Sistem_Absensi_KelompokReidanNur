@extends('layout')
@section('title', 'Edit Wali')

@section('content')
<div class="row">
  <div class="card">
    <div class="card-body">
      <h3>Edit Wali</h3>
<form action="{{ route('wali.update', $wali->id) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" value="{{ $wali->nama }}" required>
    </div>
    <div class="mb-3">
        <label>Telepon</label>
        <input type="text" name="telepon" class="form-control" value="{{ $wali->telepon }}">
    </div>
    <div class="mb-3">
        <label>Alamat</label>
        <input type="text" name="alamat" class="form-control" value="{{ $wali->alamat }}">
    </div>
    <div class="mb-3">
        <label>Pilih Siswa</label>
        <select name="siswas[]" class="form-control" multiple>
            @foreach($siswas as $siswa)
                <option value="{{ $siswa->id }}" @if($wali->siswa->contains($siswa->id)) selected @endif>
                    {{ $siswa->nama }}
                </option>
            @endforeach
        </select>
    </div>
    <button class="btn btn-primary">Simpan Perubahan</button>
</form>
    </div>
  </div>
</div>
@endsection

