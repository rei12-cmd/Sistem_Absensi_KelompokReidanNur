@extends('layout')
@section('title', 'Siswa - Create')

@section('breadcumb')
    <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">Siswa</h3></div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item">
                    @if(\Illuminate\Support\Facades\Route::has('siswa.index'))
                        <a href="{{ route('siswa.index') }}">Home</a>
                    @else
                        <a href="{{ url('/') }}">Home</a>
                    @endif
                </li>
                <li class="breadcrumb-item active" aria-current="page">Create</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <!-- Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tambah Siswa Baru</h3>
                </div>
                <div class="card-body">

                    <form method="POST"
                          action="{{ \Illuminate\Support\Facades\Route::has('siswa.store') ? route('siswa.store') : url('/siswa') }}"
                          onsubmit="disableSubmitButton()">
                        @csrf

                        {{-- user_id dropdown --}}
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Akun User <span class="text-danger">*</span></label>
                            <select class="form-control @error('user_id') is-invalid @enderror"
                                    id="user_id" name="user_id" required>
                                <option value="">-- Pilih Akun --</option>

                                @php $usersList = $users ?? collect(); @endphp
                                @if($usersList->count())
                                    @foreach($usersList as $user)
                                        <option value="{{ $user->id }}"
                                            {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->username ?? $user->name ?? $user->email ?? ('User '.$user->id) }}
                                            @if(isset($user->email)) ({{ $user->email }}) @endif
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- kelas_id dropdown --}}
                        <div class="mb-3">
                            <label for="kelas_id" class="form-label">Kelas <span class="text-danger">*</span></label>
                            <select class="form-control @error('kelas_id') is-invalid @enderror"
                                    id="kelas_id" name="kelas_id" required>
                                <option value="">-- Pilih Kelas --</option>

                                @php $kelasList = $kelas ?? collect(); @endphp
                                @if($kelasList->count())
                                    @foreach($kelasList as $k)
                                        <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                            {{ $k->nama ?? $k->nama_kelas ?? $k->kode ?? ('Kelas '.$k->id) }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('kelas_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- jurusan_id dropdown --}}
                        <div class="mb-3">
                            <label for="jurusan_id" class="form-label">Jurusan <span class="text-danger">*</span></label>
                            <select class="form-control @error('jurusan_id') is-invalid @enderror"
                                    id="jurusan_id" name="jurusan_id" required>
                                <option value="">-- Pilih Jurusan --</option>

                                @php $jurusanList = $jurusan ?? collect(); @endphp
                                @if($jurusanList->count())
                                    @foreach($jurusanList as $j)
                                        <option value="{{ $j->id }}" {{ old('jurusan_id') == $j->id ? 'selected' : '' }}>
                                            {{ $j->nama ?? $j->nama_jurusan ?? ('Jurusan '.$j->id) }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('jurusan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- nis --}}
                        <div class="mb-3">
                            <label for="nis" class="form-label">NIS <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nis') is-invalid @enderror"
                                   id="nis" name="nis" value="{{ old('nis') }}" required>
                            @error('nis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- nama --}}
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                   id="nama" name="nama" value="{{ old('nama') }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- tanggal_lahir --}}
                        <div class="mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                   id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}">
                            @error('tanggal_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- alamat --}}
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror"
                                      id="alamat" name="alamat" rows="2">{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- tombol --}}
                        <div class="d-flex gap-2">
                            @if(\Illuminate\Support\Facades\Route::has('siswa.index'))
                                <a href="{{ route('siswa.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Kembali
                                </a>
                            @else
                                <a href="{{ url('/siswa') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Kembali
                                </a>
                            @endif

                            <button type="submit" class="btn btn-primary" id="submit-btn">
                                <i class="bi bi-floppy"></i>
                                <span id="button-text">Simpan Siswa</span>
                                <span id="loading-spinner" class="spinner-border spinner-border-sm"
                                      role="status" aria-hidden="true" style="display: none;"></span>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function disableSubmitButton() {
            const submitButton = document.getElementById('submit-btn');
            const spinner = document.getElementById('loading-spinner');
            const buttonText = document.getElementById('button-text');

            submitButton.disabled = true;
            spinner.style.display = 'inline-block';
            buttonText.textContent = 'Menyimpan...';
        }
    </script>
@endpush
