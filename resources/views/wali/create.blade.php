@extends('layout')
@section('title', 'Wali - Create')

@section('breadcumb')
  <div class="row">
    <div class="col-sm-6"><h3 class="mb-0">Wali</h3></div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-end">
        <li class="breadcrumb-item">
          @if(\Illuminate\Support\Facades\Route::has('wali.index'))
            <a href="{{ route('wali.index') }}">Home</a>
          @else
            <a href="{{ url('/') }}">Home</a>
          @endif
        </li>
        <li class="breadcrumb-item active">Create</li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
<div class="row">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header"><h3 class="card-title">Tambah Wali</h3></div>
      <div class="card-body">

        {{-- fallback: pastikan variables tersedia agar view tidak crash --}}
        @php
          $usersList = $users ?? collect();
          $sList = $siswas ?? collect();
          $storeAction = \Illuminate\Support\Facades\Route::has('wali.store') ? route('wali.store') : url('/wali');
        @endphp

        <form method="POST" action="{{ $storeAction }}" onsubmit="disableSubmitButton()">
          @csrf

          {{-- user_id --}}
          <div class="mb-3">
            <label for="user_id" class="form-label">Akun User <span class="text-danger">*</span></label>

            @if($usersList->isEmpty())
              <select id="user_id" name="user_id" class="form-control is-invalid" required>
                <option value="">-- Tidak ada akun user tersedia --</option>
              </select>
              <div class="invalid-feedback">Belum ada akun user. Silakan buat akun terlebih dahulu.</div>
            @else
              <select id="user_id" name="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                <option value="">-- Pilih Akun --</option>
                @foreach($usersList as $u)
                  <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>
                    {{ $u->username ?? $u->name ?? $u->email ?? ('User '.$u->id) }}
                  </option>
                @endforeach
              </select>
              @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            @endif
          </div>

          {{-- nama --}}
          <div class="mb-3">
            <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
            <input id="nama" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- telepon --}}
          <div class="mb-3">
            <label for="telepon" class="form-label">Telepon</label>
            <input id="telepon" name="telepon" class="form-control @error('telepon') is-invalid @enderror" value="{{ old('telepon') }}">
            @error('telepon') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- alamat --}}
          <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea id="alamat" name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="2">{{ old('alamat') }}</textarea>
            @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- siswa multi-select --}}
          <div class="mb-3">
            <label for="siswa_ids" class="form-label">Pilih Siswa (relasi)</label>

            @if($sList->isEmpty())
              <select id="siswa_ids" name="siswa_ids[]" class="form-control" multiple disabled>
                <option value="">-- Tidak ada data siswa --</option>
              </select>
              <div class="form-text text-muted">Belum ada siswa untuk direlasikan.</div>
            @else
              <select id="siswa_ids" name="siswa_ids[]" class="form-control" multiple>
                @foreach($sList as $s)
                  <option value="{{ $s->id }}" {{ (is_array(old('siswa_ids')) && in_array($s->id, old('siswa_ids'))) ? 'selected' : '' }}>
                    {{ $s->nama ?? $s->nis }}
                  </option>
                @endforeach
              </select>
              <small class="text-muted">Tekan Ctrl (Cmd) untuk pilih lebih dari satu.</small>
            @endif
          </div>

          <div class="d-flex gap-2">
            @if(\Illuminate\Support\Facades\Route::has('wali.index'))
              <a href="{{ route('wali.index') }}" class="btn btn-secondary">Kembali</a>
            @else
              <a href="{{ url('/wali') }}" class="btn btn-secondary">Kembali</a>
            @endif

            <button type="submit" class="btn btn-primary" id="submit-btn">
              <span id="button-text">Simpan Wali</span>
              <span id="loading-spinner" class="spinner-border spinner-border-sm" style="display:none"></span>
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
function disableSubmitButton(){
  const submitButton = document.getElementById('submit-btn');
  const spinner = document.getElementById('loading-spinner');
  const buttonText = document.getElementById('button-text');

  submitButton.disabled = true;
  spinner.style.display = 'inline-block';
  buttonText.textContent = 'Menyimpan...';
}
</script>
@endpush
