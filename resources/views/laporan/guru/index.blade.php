@extends('layout')
@section('title', 'Laporan Absensi Guru')

@section('content')
<div class="container py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0"><i class="fas fa-clipboard-list text-success"></i> Laporan Absensi</h3>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    <p class="text-muted mb-4">Berikut daftar laporan absensi berdasarkan kelas dan mata pelajaran yang diampu.</p>

    <div class="row g-4">
        @forelse($guruMapelKelas as $gmk)
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card shadow-sm border-0 h-100 card-hover transition-all">
                <div class="card-header bg-success text-white py-2 d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-book-open me-1"></i> {{ $gmk->mataPelajaran->nama }}</span>
                    <span class="badge bg-light text-success">{{ $gmk->kelas->nama }}</span>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>Guru:</strong> {{ $gmk->guru->nama }}</p>
                    <p class="text-muted small mb-3">Klik tombol di bawah untuk melihat rekap absensi lengkap siswa di kelas ini.</p>
                    <a href="{{ route('guru.laporan.kelas', $gmk->id) }}" class="btn btn-success btn-sm w-100">
                        <i class="fas fa-chart-bar me-1"></i> Lihat Rekap
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle"></i> Tidak ada data kelas atau mata pelajaran yang diampu.
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection

@push('styles')
<style>
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }
    .transition-all {
        transition: all 0.25s ease-in-out;
    }
</style>
@endpush
