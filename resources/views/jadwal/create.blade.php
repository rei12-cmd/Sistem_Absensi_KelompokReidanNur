@extends('layout')
@section('title', 'Jadwal - Create')

@section('breadcumb')
    <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">Tambah Jadwal</h3></div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="{{ route('jadwal.index') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Buat Jadwal Baru</h3></div>
            <div class="card-body">
                <form action="{{ route('jadwal.store') }}" method="POST" onsubmit="disableSubmitButton()">
                    @csrf

                    {{-- Relasi Guru-Mapel-Kelas --}}
                    <div class="mb-3">
                        <label for="guru_mapel_kelas_id" class="form-label">Relasi (Guru - Mapel - Kelas)</label>
                        <select
                            name="guru_mapel_kelas_id"
                            id="guru_mapel_kelas_id"
                            class="form-control @error('guru_mapel_kelas_id') is-invalid @enderror"
                            {{ $relations->isEmpty() ? 'disabled' : 'required' }}
                        >
                            <option value="">-- Pilih Relasi --</option>

                            @forelse($relations as $r)
                                <option value="{{ $r->id }}"
                                    {{ old('guru_mapel_kelas_id') == $r->id ? 'selected' : '' }}>
                                    {{ optional($r->guru)->nama ?? optional($r->guru)->nama_guru ?? 'Guru' }}
                                    - {{ optional($r->mataPelajaran)->nama ?? optional($r->mataPelajaran)->nama_mapel ?? 'Mapel' }}
                                    - {{ optional($r->kelas)->nama_kelas ?? optional($r->kelas)->nama ?? 'Kelas' }}
                                </option>
                            @empty
                                <option value="">Belum ada relasi Guru-Mapel-Kelas.</option>
                            @endforelse
                        </select>
                        @error('guru_mapel_kelas_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    @if($relations->isEmpty())
                        <div class="alert alert-warning">
                            Belum ada relasi Guru-Mapel-Kelas. Silakan tambahkan dulu sebelum membuat jadwal.
                        </div>
                    @endif

                    {{-- Hari --}}
                    <div class="mb-3">
                        <label for="hari" class="form-label">Hari</label>
                        <select name="hari" id="hari" class="form-control @error('hari') is-invalid @enderror" required>
                            <option value="">-- Pilih Hari --</option>
                            @foreach(['senin','selasa','rabu','kamis','jumat','sabtu','minggu'] as $h)
                                <option value="{{ $h }}" {{ old('hari') == $h ? 'selected' : '' }}>{{ ucfirst($h) }}</option>
                            @endforeach
                        </select>
                        @error('hari')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Jam Mulai --}}
                    <div class="mb-3">
                        <label for="jam_mulai" class="form-label">Jam Mulai (HH:MM)</label>
                        <input type="time" name="jam_mulai" id="jam_mulai" class="form-control @error('jam_mulai') is-invalid @enderror" value="{{ old('jam_mulai') }}" required>
                        @error('jam_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Jam Selesai --}}
                    <div class="mb-3">
                        <label for="jam_selesai" class="form-label">Jam Selesai (HH:MM)</label>
                        <input type="time" name="jam_selesai" id="jam_selesai" class="form-control @error('jam_selesai') is-invalid @enderror" value="{{ old('jam_selesai') }}" required>
                        @error('jam_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Ruang --}}
                    <div class="mb-3">
                        <label for="ruang" class="form-label">Ruang</label>
                        <input type="text" name="ruang" id="ruang" class="form-control @error('ruang') is-invalid @enderror" value="{{ old('ruang') }}">
                        @error('ruang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Tombol --}}
                    <div class="d-flex gap-2">
                        <a href="{{ route('jadwal.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
                        <button type="submit" id="submit-btn" class="btn btn-primary" {{ $relations->isEmpty() ? 'disabled' : '' }}>
                            <i class="bi bi-floppy"></i> <span id="button-text">Simpan</span>
                            <span id="loading-spinner" class="spinner-border spinner-border-sm" style="display:none" role="status" aria-hidden="true"></span>
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

    if (!submitButton) return;
    submitButton.disabled = true;
    spinner.style.display = 'inline-block';
    buttonText.textContent = 'Menyimpan...';
}
</script>
@endpush
