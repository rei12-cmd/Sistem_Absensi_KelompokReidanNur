@extends('layout')
@section('title', 'Detail Absensi Siswa')

@section('content')
<div class="container">
    <h3>Detail Absensi Siswa</h3>
    <hr>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Siswa: {{ $absensi->first()->siswa->nama ?? '-' }}</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Mapel</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($absensi as $key => $a)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($a->tanggal)->format('d-m-Y') }}</td>
                        <td>{{ $a->jadwal->guruMapelKelas->mataPelajaran->nama }}</td>
                        <td>{{ $a->status }}</td>
                        <td>{{ $a->keterangan ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <a href="{{ url()->previous() }}" class="btn btn-sm btn-secondary">Kembali</a>
        </div>
    </div>
</div>
@endsection
