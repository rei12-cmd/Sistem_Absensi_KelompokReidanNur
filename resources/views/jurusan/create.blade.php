@extends('layout')

@section('title', 'Tambah Jurusan')

@section('breadcumb')
    <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">Tambah Jurusan</h3></div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('jurusan.index') }}">Jurusan</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah Data</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Tambah Jurusan</h3>
            </div>
            <div class="card-body">
                {{-- Menampilkan pesan error validasi --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> Ada beberapa masalah dengan input Anda.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('jurusan.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Jurusan:</label>
                        <input type="text" name="nama" class="form-control" placeholder="Contoh: Pengembangan Perangkat Lunak & Gim" value="{{ old('nama') }}">
                    </div>
                    <div class="d-flex justify-content-end">
                         <a class="btn btn-secondary me-2" href="{{ route('jurusan.index') }}">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
