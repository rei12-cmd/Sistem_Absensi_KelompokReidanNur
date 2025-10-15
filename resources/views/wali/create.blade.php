@extends('layout')
@section('title', 'Tambah Wali')

@section('content')
<div class="card col-md-6">
    <div class="card-header"><h5>Tambah Wali Baru</h5></div>
    <div class="card-body">
        <form method="POST" action="{{ route('wali.store') }}">
            @csrf



            <div class="mb-3">
                <label>Nama Wali</label>
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


             @error('siswa')
            <span class="badge bg-danger">{{ $message }}</span>
            @enderror
            <div class="mb-3">
                <label for="siswa" class="form-label">siswa</label>
                <select name="siswa" class="form-select" id="multiple-select-field" data-placeholder="Choose anything" multiple>
                    <option value="">Pilih siswa</option>
                    @foreach($siswas as $siswa)
                        <option value="{{ $siswa->id }}">{{ $siswa->nama }} ( {{ $siswa->nis }} )</option>
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
                <label>Alamat</label>
                <input type="text" name="alamat" class="form-control" value="{{ old('alamat') }}">
            </div>

             <div class="mb-3">
                <label>Telepon</label>
                <input type="text" name="telepon" class="form-control" value="{{ old('telepon') }}">
            </div>

            <button class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@endsection


@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>


    $( '#multiple-select-field' ).select2( {
    theme: "bootstrap-5",
    width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
    placeholder: $( this ).data( 'placeholder' ),
    closeOnSelect: false,
} );
</script>
@endpush
