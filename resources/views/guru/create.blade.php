@extends('layout')
@section('title', 'Guru - Create')

@section('breadcumb')
    <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">Guru</h3></div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="{{ route('guru.index') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Buat Guru Baru</h3>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('guru.store') }}" onsubmit="disableSubmitButton()">
                        @csrf

                        {{-- Pilih Akun User (opsional - kosongkan jika ingin membuat akun baru melalui form di bawah) --}}
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Akun User</label>
                            <select class="form-control @error('user_id') is-invalid @enderror" id="user_id" name="user_id">
                                <option value="">-- Pilih Akun (kosongkan untuk buat akun baru) --</option>
                                @if(isset($users) && $users->count())
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->username ?? $user->email }}
                                            @if(!empty($user->email))
                                                ({{ $user->email }})
                                            @endif
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Pilih akun jika sudah tersedia. Jika tidak, isi Username/Email/Password di bawah.</small>
                        </div>

                        <hr>

                        {{-- Account fields (untuk membuat user baru jika user_id kosong) --}}
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input id="username" name="username" type="text"
                                   class="form-control @error('username') is-invalid @enderror"
                                   value="{{ old('username') }}" placeholder="Masukkan username">
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" name="email" type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" placeholder="Masukkan email">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row gx-2">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input id="password" name="password" type="password" autocomplete="new-password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Masukkan password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                                       class="form-control @error('password_confirmation') is-invalid @enderror"
                                       placeholder="Ketik ulang password">
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <small class="form-text text-muted mb-3">Password bebas panjang (hanya harus cocok dengan konfirmasi jika diisi).</small>

                        <hr>

                        {{-- Guru fields --}}
                        <div class="mb-3">
                            <label for="nip" class="form-label">NIP</label>
                            <input id="nip" name="nip" type="text"
                                   class="form-control @error('nip') is-invalid @enderror"
                                   value="{{ old('nip') }}" placeholder="Masukkan NIP">
                            @error('nip')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
                            <input id="nama" name="nama" type="text" required
                                   class="form-control @error('nama') is-invalid @enderror"
                                   value="{{ old('nama') }}" placeholder="Masukkan nama">
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="telepon" class="form-label">Telepon</label>
                            <input id="telepon" name="telepon" type="text"
                                   class="form-control @error('telepon') is-invalid @enderror"
                                   value="{{ old('telepon') }}" placeholder="Masukkan nomor telepon">
                            @error('telepon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Buttons --}}
                        <div class="d-flex gap-2">
                            <a href="{{ route('guru.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>

                            <button type="submit" class="btn btn-primary" id="submit-btn">
                                <i class="bi bi-floppy"></i>
                                <span id="button-text">Simpan Guru</span>
                                <span id="loading-spinner" class="spinner-border spinner-border-sm"
                                      role="status" aria-hidden="true" style="display: none;"></span>
                            </button>
                        </div>
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Menon-aktifkan tombol submit setelah klik (sudah ada)
        function disableSubmitButton() {
            const submitButton = document.getElementById('submit-btn');
            const spinner = document.getElementById('loading-spinner');
            const buttonText = document.getElementById('button-text');

            if (submitButton) {
                submitButton.disabled = true;
            }
            if (spinner) {
                spinner.style.display = 'inline-block';
            }
            if (buttonText) {
                buttonText.textContent = 'Menyimpan...';
            }
        }

        // Toggle account fields berdasarkan pemilihan user_id
        (function () {
            const userSelect = document.getElementById('user_id');
            const username = document.getElementById('username');
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const passwordConfirm = document.getElementById('password_confirmation');

            if (!userSelect) return;

            function toggleAccountFields() {
                const useExisting = userSelect.value !== '';

                // Jika memilih user existing -> non-required & disabled
                [username, email, password, passwordConfirm].forEach((el) => {
                    if (!el) return;
                    el.required = !useExisting;
                    // disable to avoid browser auto-fill when using existing account
                    el.disabled = useExisting;
                });

                // Optional: clear account fields when existing is selected to avoid accidental changes
                if (useExisting) {
                    if (username) username.value = '';
                    if (email) email.value = '';
                    if (password) password.value = '';
                    if (passwordConfirm) passwordConfirm.value = '';
                } else {
                    // restore old values if present via old() is already rendered in value attributes
                }
            }

            // run on change
            userSelect.addEventListener('change', toggleAccountFields);

            // run on initial load (for old values)
            document.addEventListener('DOMContentLoaded', toggleAccountFields);
            // also run immediately in case DOMContentLoaded already fired
            toggleAccountFields();
        })();
    </script>
@endpush
