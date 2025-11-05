@extends('layout')

@section('title', 'Absensi Siswa')

@section('breadcumb')
<div class="row">
    <div class="col-sm-6">
        <h3 class="mb-0">Absensi Siswa</h3>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Absensi Siswa</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    @if($jadwal)
    <div class="card mb-3 border-success">
        <div class="card-body">
            <div class="row text-sm">
                <div class="col-md-3"><strong>Kelas:</strong> {{ $jadwal->guruMapelKelas->kelas->nama ?? '-' }}</div>
                <div class="col-md-3"><strong>Mapel:</strong> {{ $jadwal->guruMapelKelas->mataPelajaran->nama ?? '-' }}</div>
                <div class="col-md-3"><strong>Guru:</strong> {{ $jadwal->guruMapelKelas->guru->nama ?? '-' }}</div>
                <div class="col-md-3"><strong>Hari:</strong> {{ ucfirst($jadwal->hari) }} ({{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }})</div>
            </div>
        </div>
    </div>
    @endif

    @if(!$bisaAbsensi)
        <div class="alert alert-warning text-center">
            <i class="fas fa-clock"></i> Absensi hanya bisa dilakukan pada hari dan jam pelajaran.
        </div>
    @else
    <div class="card border-success shadow-sm">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Form Absensi Siswa</h5>
            <button type="button" class="btn btn-light btn-sm text-success" id="hadirkan-semua">
                <i class="fas fa-user-check"></i> Hadirkan Semua
            </button>
        </div>

        <form action="{{ route('absensi.store') }}" method="POST">
            @csrf
            <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">

            <div class="card-body">
                <div class="table-responsive">
                    <table id="tabel-absensi" class="table table-bordered table-striped align-middle">
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
                                    <td>{{ $siswas->firstItem() + $no }}</td>
                                    <td>{{ $siswa->nama }}</td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-3">
                                            @foreach (['H' => 'Hadir', 'S' => 'Sakit', 'I' => 'Izin', 'A' => 'Alpa'] as $kode => $label)
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input status-radio" type="radio"
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

            <div class="card-footer d-flex justify-content-between align-items-center">
                {{ $siswas->links() }}
                <div>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-save"></i> Simpan Absensi
                    </button>
                    <a href="{{ route('absensi.index') }}" class="btn btn-secondary px-4">Kembali</a>
                </div>
            </div>
        </form>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#hadirkan-semua').on('click', function() {
            $('.status-radio[value="H"]').each(function() {
                $(this).prop('checked', true);
            });
        });
    });
</script>
@endpush
