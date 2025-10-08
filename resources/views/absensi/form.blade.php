@extends('layout')

@section('title', 'Absensi Siswa')

@section('content')
<div class="container-fluid">
    {{-- Informasi Jadwal --}}
    @if($jadwal)
    <div class="card mb-3 border-success">
        <div class="card-body">
            <div class="row text-sm">
                <div class="col-md-3"><strong>Kelas:</strong> {{ $jadwal->guruMapelKelas->kelas->nama ?? '-' }}</div>
                <div class="col-md-3"><strong>Mata Pelajaran:</strong> {{ $jadwal->guruMapelKelas->mataPelajaran->nama ?? '-' }}</div>
                <div class="col-md-3"><strong>Guru:</strong> {{ $jadwal->guruMapelKelas->guru->nama ?? '-' }}</div>
                <div class="col-md-3"><strong>Hari:</strong> {{ ucfirst($jadwal->hari) }} ({{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }})</div>
            </div>
        </div>
    </div>
    @endif

    {{-- Form Absensi --}}
    <div class="card border-success">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Form Absensi Siswa</h5>
        </div>

        <form action="{{ route('absensi.store') }}" method="POST">
            @csrf
            <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-success">
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($siswas as $no => $siswa)
                                <tr>
                                    <td>{{ $no + 1 }}</td>
                                    <td>{{ $siswa->nama }}</td>
                                    <td>
                                        <div class="d-flex gap-3">
                                            @foreach (['H' => 'Hadir', 'S' => 'Sakit', 'I' => 'Izin', 'A' => 'Alpa'] as $kode => $label)
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio"
                                                        name="status[{{ $siswa->id }}]" value="{{ $kode }}"
                                                        id="status_{{ $siswa->id }}_{{ $kode }}">
                                                    <label class="form-check-label" for="status_{{ $siswa->id }}_{{ $kode }}">{{ $label }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center">Tidak ada siswa di kelas ini</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer text-end">
                <button type="submit" class="btn btn-success px-4">
                    <i class="fas fa-save"></i> Simpan Absensi
                </button>
                <a href="{{ route('absensi.index') }}" class="btn btn-secondary px-4">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
