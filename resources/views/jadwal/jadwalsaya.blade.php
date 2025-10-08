{{-- resources/views/jadwal/jadwalsaya.blade.php --}}
@extends('layout')
@section('title', 'Jadwal Saya')

@section('breadcumb')
<div class="row">
    <div class="col-sm-6"><h3 class="mb-0">Jadwal Saya</h3></div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Jadwal Saya</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
@php
    $jadwalsaya = $jadwalsaya ?? collect();
    // fallback lists (controller mungkin tidak mengirim)
    $kelasList = $kelas ?? collect();
    $jurusanList = $jurusan ?? collect();
@endphp

<div class="row justify-content-center">
    <div class="col-md-8">

        {{-- jika kosong --}}
        @if($jadwalsaya->isEmpty())
            <div class="card">
                <div class="card-body text-center py-5">
                    <h4 class="mb-3">Belum ada jadwal mengajar untuk Anda.</h4>
                    <p class="text-muted">Jika Anda seorang guru, pastikan relasi Guru - Mapel - Kelas telah diatur oleh admin.</p>
                </div>
            </div>
        @endif

        {{-- tampilkan setiap jadwal sebagai card --}}
        @foreach($jadwalsaya as $j)
            @php
                // fallback naming karena struktur model kamu sebelumnya memakai nested relations
                $mapelNama = optional($j->guruMapelKelas->mataPelajaran)->nama
                             ?? optional($j->mata_pelajaran)->nama
                             ?? ($j->mata_pelajaran_nama ?? null)
                             ?? 'Mata Pelajaran';

                $kelasNama = optional($j->guruMapelKelas->kelas)->nama ?? optional($j->kelas)->nama_kelas ?? ($j->kelas_nama ?? '-');
                $ruang     = $j->ruang ?? '-';
                $hari      = $j->hari ?? ($j->tanggal ?? '-');
                $jamMulai  = $j->jam_mulai ?? ($j->jam ?? '-');
                $jamSelesai= $j->jam_selesai ?? '-';
            @endphp

            <div class="card mb-4 shadow-sm">
                <div class="card-header" style="background:#2d6a5f; color:#fff; border-top-left-radius: .25rem; border-top-right-radius: .25rem;">
                    <h5 class="mb-0">Jadwal Mengajar</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="border p-3 mb-3">
                                <h5 class="mb-2" style="font-weight:600;">{{ $mapelNama }}</h5>
                                <ul class="mb-0" style="list-style: none; padding-left:0;">
                                    <li><strong>Hari :</strong> {{ $hari }}</li>
                                    <li><strong>Jam :</strong> {{ $jamMulai }} @if($jamSelesai) s.d {{ $jamSelesai }} @endif WIB</li>
                                    <li><strong>Kelas :</strong> {{ $kelasNama }}</li>
                                    <li><strong>Ruang :</strong> {{ $ruang }}</li>
                                </ul>
                            </div>
                        </div>

                        {{-- Kontrol: tanggal, pilih kelas/jurusan, mulai absen --}}
                        <div class="col-12">
                            <div class="d-grid gap-2">
                                {{-- Pilih Tanggal --}}
                                <div class="input-group">
                                    <input type="date" id="tanggal_{{ $j->id }}" class="form-control" value="{{ \Carbon\Carbon::now()->toDateString() }}">
                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Pilih Tanggal</button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="setToday('{{ $j->id }}')">Hari Ini</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="setTomorrow('{{ $j->id }}')">Besok</a></li>
                                    </ul>
                                </div>

                                {{-- Pilih Kelas dan Jurusan (dropdown besar biru) --}}
                                <div class="d-flex">
                                    <select id="kelas_select_{{ $j->id }}" class="form-select me-2">
                                        <option value="">{{ $kelasNama ?: '-- Pilih Kelas --' }}</option>
                                        @foreach($kelasList as $k)
                                            <option value="{{ $k->id }}">{{ $k->nama ?? $k->nama_kelas ?? $k->kode ?? 'Kelas '.$k->id }}</option>
                                        @endforeach
                                    </select>

                                    <select id="jurusan_select_{{ $j->id }}" class="form-select">
                                        <option value="">{{ $jurusanList->isNotEmpty() ? '-- Pilih Jurusan --' : 'Semua Jurusan' }}</option>
                                        @foreach($jurusanList as $jr)
                                            <option value="{{ $jr->id }}">{{ $jr->nama ?? $jr->nama_jurusan ?? 'Jurusan '.$jr->id }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Tombol Mulai Absen --}}
                                <div class="text-center">
                                    <button type="button" class="btn btn-success btn-lg mt-2" onclick="mulaiAbsen('{{ $j->id }}')">
                                        Mulai Absen
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div> {{-- row --}}
                </div> {{-- card-body --}}
            </div> {{-- card --}}
        @endforeach

    </div>
</div>

@endsection

@push('styles')
<style>
    /* sedikit custom agar mirip mockup */
    .card-header h5 { font-weight:700; }
    .card { border-radius: .5rem; }
    .form-select, .form-control { min-height: calc(1.5em + .75rem + 2px); }
    /* tombol besar biru di mockup: handled by Bootstrap default btn classes + custom width if needed */
</style>
@endpush

@push('scripts')
<script>
    function setToday(id) {
        document.getElementById('tanggal_' + id).value = new Date().toISOString().slice(0,10);
    }
    function setTomorrow(id) {
        const d = new Date();
        d.setDate(d.getDate() + 1);
        document.getElementById('tanggal_' + id).value = d.toISOString().slice(0,10);
    }

    function mulaiAbsen(id) {
        const tanggal = document.getElementById('tanggal_' + id).value;
        const kelas = document.getElementById('kelas_select_' + id)?.value || '';
        const jurusan = document.getElementById('jurusan_select_' + id)?.value || '';

        // contoh: lakukan request atau arahkan ke route absensi
        // jika ingin men-submit ke server, ganti alert dengan fetch/post ke route yang sesuai.
        let info = `Mulai absen untuk jadwal id ${id}\nTanggal: ${tanggal}\nKelas: ${kelas || 'default'}\nJurusan: ${jurusan || 'default'}`;
        alert(info);

        // contoh redirect (opsional)
        // window.location.href = '/absensi/create?jadwal_id=' + id + '&tanggal=' + tanggal + '&kelas=' + kelas;
    }
</script>
@endpush
