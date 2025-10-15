@extends('layout')
@section('title', 'Laporan Absensi Guru')

@section('content')
<div class="container">
    <h3>Laporan Absensi - Guru</h3>
    <hr>

    <div class="row">
        @foreach($guruMapelKelas as $gmk)
        <div class="col-md-4">
            <div class="card bg-light mb-3">
                <div class="card-header">
                    {{ $gmk->mataPelajaran->nama }}
                </div>
                <div class="card-body">
                    <h5 class="card-title">Kelas: {{ $gmk->kelas->nama }}</h5>
                    <p class="card-text">Guru: {{ $gmk->guru->nama }}</p>
                    <a href="{{ route('guru.laporan.kelas', $gmk->id) }}" class="btn btn-info btn-sm">Lihat Rekap</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
