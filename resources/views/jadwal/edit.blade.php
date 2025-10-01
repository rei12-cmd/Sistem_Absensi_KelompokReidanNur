@extends('layout')
@section('title', 'Jadwal - Edit')

@section('breadcumb')
    <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">Edit Jadwal</h3></div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="{{ route('jadwal.index') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Edit Jadwal</h3></div>
            <div class="card-body">
                <form action="{{ route('jadwal.update', $jadwal->id) }}" method="POST" onsubmit="disableSubmitButton()">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="guru_mapel_kelas_id" class="form-label">Relasi (Guru - Mapel - Kelas)</label>
                        <select name="guru_mapel_kelas_id" id="guru_mapel_kelas_id" class="form-control @error('guru_mapel_kelas_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Relasi --</option>
                            @foreach($relations as $gm)
                                <option value="{{ $gm->id }}" {{ old('guru_mapel_kelas_id', $jadwal->guru_mapel_kelas_id) == $gm->id ? 'selected' : '' }}>
                                    {{ optional($gm->guru)->nama ?? '-' }} - {{ optional($gm->mataPelajaran)->nama ?? '-' }} - {{ optional($gm->kelas)->nama ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        @error('guru_mapel_kelas_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="hari" class="form-label">Hari</label>
                        <select name="hari" id="hari" class="form-control @error('hari') is-invalid @enderror" required>
                            <option value="">-- Pilih Hari --</option>
                            @foreach(['senin','selasa','rabu','kamis','jumat','sabtu','minggu'] as $h)
                                <option value="{{ $h }}" {{ old('hari', $jadwal->hari) == $h ? 'selected' : '' }}>{{ ucfirst($h) }}</option>
                            @endforeach
                        </select>
                        @error('hari')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="jam_mulai" class="form-label">Jam Mulai (HH:MM)</label>
                        <input type="time" name="jam_mulai" id="jam_mulai" class="form-control @error('jam_mulai') is-invalid @enderror" value="{{ old('jam_mulai', $jadwal->jam_mulai) }}" required>
                        @error('jam_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="jam_selesai" class="form-label">Jam Selesai (HH:MM)</label>
                        <input type="time" name="jam_selesai" id="jam_selesai" class="form-control @error('jam_selesai') is-invalid @enderror" value="{{ old('jam_selesai', $jadwal->jam_selesai) }}" required>
                        @error('jam_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="ruang" class="form-label">Ruang</label>
                        <input type="text" name="ruang" id="ruang" class="form-control @error('ruang') is-invalid @enderror" value="{{ old('ruang', $jadwal->ruang) }}">
                        @error('ruang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('jadwal.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
                        <button type="submit" id="submit-btn" class="btn btn-primary">
                            <i class="bi bi-floppy"></i> <span id="button-text">Update</span>
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

    submitButton.disabled = true;
    spinner.style.display = 'inline-block';
    buttonText.textContent = 'Menyimpan...';
}
</script>
@endpush
