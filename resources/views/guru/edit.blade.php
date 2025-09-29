@extends('layout')
@section('title', 'Guru - Edit')

@section('breadcumb')
    <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">Edit Guru</h3></div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="{{ route('guru.index') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Guru</h3>
                </div>
                <div class="card-body">

                    <form method="POST" action="{{ route('guru.update', $guru->id) }}" onsubmit="disableSubmitButton()">
                        @csrf
                        @method('PUT')

                        {{-- user_id --}}
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Akun User <span class="text-danger">*</span></label>
                            <select class="form-control @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                                <option value="">-- Pilih Akun --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id', $guru->user_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->username ?? $user->email }}
                                        @if(isset($user->email))
                                            ({{ $user->email }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- nip --}}
                        <div class="mb-3">
                            <label for="nip" class="form-label">NIP</label>
                            <input type="text" class="form-control @error('nip') is-invalid @enderror" id="nip" name="nip"
                                   value="{{ old('nip', $guru->nip) }}">
                            @error('nip')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- nama --}}
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama"
                                   value="{{ old('nama', $guru->nama) }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- telepon --}}
                        <div class="mb-3">
                            <label for="telepon" class="form-label">Telepon</label>
                            <input type="text" class="form-control @error('telepon') is-invalid @enderror" id="telepon" name="telepon"
                                   value="{{ old('telepon', $guru->telepon) }}">
                            @error('telepon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('guru.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>

                            <button type="submit" class="btn btn-primary" id="submit-btn">
                                <i class="bi bi-floppy"></i>
                                <span id="button-text">Update Guru</span>
                                <span id="loading-spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
            <!-- /.card -->
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
