@extends('layout')
@section('title', 'Wali - Edit')

@section('breadcumb')
  <div class="row">
    <div class="col-sm-6"><h3 class="mb-0">Edit Wali</h3></div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-end">
        <li class="breadcrumb-item"><a href="{{ route('wali.index') }}">Home</a></li>
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
        <form method="POST" action="{{ route('wali.update', $wali->id) }}" onsubmit="disableSubmitButton()">
          @csrf
          @method('PUT')

          {{-- user_id --}}
          <div class="mb-3">
            <label for="user_id" class="form-label">Akun User <span class="text-danger">*</span></label>
            <select id="user_id" name="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
              <option value="">-- Pilih Akun --</option>
              @php $usersList = $users ?? collect(); @endphp
              @foreach($usersList as $u)
                <option value="{{ $u->id }}" {{ (string)old('user_id', $wali->user_id) === (string)$u->id ? 'selected' : '' }}>
                  {{ $u->username ?? $u->name ?? $u->email ?? ('User '.$u->id) }}
                </option>
              @endforeach
            </select>
            @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- nama --}}
          <div class="mb-3">
            <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
            <input id="nama" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $wali->nama) }}" required>
            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- telepon --}}
          <div class="mb-3">
            <label for="telepon" class="form-label">Telepon</label>
            <input id="telepon" name="telepon" class="form-control @error('telepon') is-invalid @enderror" value="{{ old('telepon', $wali->telepon) }}">
            @error('telepon') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- alamat --}}
          <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea id="alamat" name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="2">{{ old('alamat', $wali->alamat) }}</textarea>
            @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- siswa multi-select --}}
          <div class="mb-3">
            <label for="siswa_ids" class="form-label">Pilih Siswa (relasi)</label>
            <select id="siswa_ids" name="siswa_ids[]" class="form-control" multiple>
              @php $sList = $siswas ?? collect(); $selected = $selectedSiswas ?? [] @endphp
              @foreach($sList as $s)
                <option value="{{ $s->id }}" {{ (in_array($s->id, old('siswa_ids', $selected)) ? 'selected' : '') }}>
                  {{ $s->nama ?? $s->nis }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="d-flex gap-2">
            <a href="{{ route('wali.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary" id="submit-btn">
              <span id="button-text">Update Wali</span>
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
  document.getElementById('submit-btn').disabled = true;
  document.getElementById('loading-spinner').style.display = 'inline-block';
  document.getElementById('button-text').textContent = 'Menyimpan...';
}
</script>
@endpush
