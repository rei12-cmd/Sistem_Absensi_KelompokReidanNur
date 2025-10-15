@extends('layout')
@section('title', 'Edit Wali')

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
        <li class="breadcrumb-item active">Edit</li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
<div class="row">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header"><h3 class="card-title">Edit Wali</h3></div>
      <div class="card-body">

        <form method="POST"
              action="{{ \Illuminate\Support\Facades\Route::has('wali.update') ? route('wali.update', $wali->id) : url('/wali/'.$wali->id) }}"
              onsubmit="disableSubmitButton()">
          @csrf
          @method('PUT')

          {{-- Nama --}}
          <div class="mb-3">
            <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
            <input id="nama" name="nama" type="text"
                   class="form-control @error('nama') is-invalid @enderror"
                   value="{{ old('nama', $wali->nama) }}" required>
            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- Email (user terkait) --}}
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" name="email" type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email', optional($wali->user)->email) }}"
                   placeholder="Masukkan email">
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <small class="form-text text-muted">Mengubah email akan memperbarui akun user terkait (jika ada).</small>
          </div>

          {{-- Password baru (opsional) --}}
          <div class="mb-3">
            <label for="password" class="form-label">Password Baru (opsional)</label>
            <input id="password" name="password" type="password" autocomplete="new-password"
                   class="form-control @error('password') is-invalid @enderror"
                   placeholder="Kosongkan jika tidak ingin mengganti password">
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="mb-3">
            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                   class="form-control @error('password_confirmation') is-invalid @enderror"
                   placeholder="Ketik ulang password baru">
            @error('password_confirmation') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- Telepon --}}
          <div class="mb-3">
            <label for="telepon" class="form-label">Telepon</label>
            <input id="telepon" name="telepon" type="text"
                   class="form-control @error('telepon') is-invalid @enderror"
                   value="{{ old('telepon', $wali->telepon) }}">
            @error('telepon') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- Alamat --}}
          <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea id="alamat" name="alamat" rows="3"
                      class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $wali->alamat) }}</textarea>
            @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- Multi-select siswa --}}
          <div class="mb-3">
            <label for="siswa_ids" class="form-label">Pilih Siswa (relasi)</label>

            @php
              $sList = $siswas ?? collect();
              // selected siswas: prefer passed $selectedSiswas, fallback to current relation
              $selected = old('siswa_ids', $selectedSiswas ?? $wali->siswa()->pluck('siswa.id')->toArray() ?? []);
            @endphp

            @if($sList->isEmpty())
              <select id="siswa_ids" name="siswa_ids[]" class="form-control" multiple disabled>
                <option value="">-- Tidak ada data siswa --</option>
              </select>
              <div class="form-text text-muted">Belum ada siswa untuk direlasikan.</div>
            @else
              <select id="siswa_ids" name="siswa_ids[]" class="form-control" multiple>
                @foreach($sList as $s)
                  <option value="{{ $s->id }}" {{ (is_array($selected) && in_array($s->id, $selected)) ? 'selected' : '' }}>
                    {{ $s->nama ?? $s->nis }}
                  </option>
                @endforeach
              </select>
              <small class="text-muted">Tekan Ctrl (Cmd) untuk pilih lebih dari satu.</small>
            @endif
          </div>

          {{-- Buttons --}}
          <div class="d-flex gap-2">
            @if(\Illuminate\Support\Facades\Route::has('wali.index'))
              <a href="{{ route('wali.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
            @else
              <a href="{{ url('/wali') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
            @endif

            <button type="submit" class="btn btn-primary" id="submit-btn">
              <i class="bi bi-floppy"></i>
              <span id="button-text">Update Wali</span>
              <span id="loading-spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:none"></span>
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

    if (submitButton) submitButton.disabled = true;
    if (spinner) spinner.style.display = 'inline-block';
    if (buttonText) buttonText.textContent = 'Menyimpan...';
  }
</script>
@endpush
