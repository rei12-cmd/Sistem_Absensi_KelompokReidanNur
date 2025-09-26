@extends('layout')

@section('title', 'Tambah Kelas')

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Tambah Kelas</h3></div>
    <div class="card-body">
        <form action="{{ route('kelas.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="nama" class="form-label">Nama Kelas</label>
                <input
                    type="text"
                    name="nama"
                    id="nama"
                    class="form-control @error('nama') is-invalid @enderror"
                    value="{{ old('nama') }}"
                >
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="jurusan_id" class="form-label">Jurusan</label>
                <select
                    name="jurusan_id"
                    id="jurusan_id"
                    class="form-select @error('jurusan_id') is-invalid @enderror"
                >
                    <option value="">-- Pilih Jurusan --</option>

                    @if(isset($jurusans) && $jurusans->count())
                        @foreach($jurusans as $j)
                            {{-- pastikan atribut nama di tabel jurusan adalah "nama" --}}
                            <option value="{{ $j->id }}" {{ old('jurusan_id') == $j->id ? 'selected' : '' }}>
                                {{ $j->nama }}
                            </option>
                        @endforeach
                    @else
                        <option disabled>Belum ada data jurusan</option>
                    @endif

                </select>
                @error('jurusan_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button class="btn btn-success">Simpan</button>

            {{-- Tombol Batal: pakai route('kelas.index') jika ada, kalau tidak fallback ke route('kelas') atau url('/kelas') --}}
            <a href="{{ Route::has('kelas.index') ? route('kelas.index') : (Route::has('kelas') ? route('kelas') : url('/kelas')) }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
