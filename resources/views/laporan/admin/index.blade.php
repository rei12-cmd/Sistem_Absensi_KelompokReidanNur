@extends('layout')
@section('title', 'Laporan Absensi Admin')

@section('content')
<div class="card">
 <div class="card-body">
    <h3>Laporan Absensi - Admin</h3>
    <hr>

    <form method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-3">
                <select name="kelas_id" class="form-control">
                    <option value="">-- Semua Kelas --</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                            {{ $kelas->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" name="tanggal_mulai" class="form-control" value="{{ request('tanggal_mulai') }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="tanggal_selesai" class="form-control" value="{{ request('tanggal_selesai') }}">
            </div>
            <div class="col-md-3">
                <button class="btn btn-info">Filter</button>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Siswa</th>
                    <th>Kelas</th>
                    <th>Jurusan</th>
                    <th>Mapel</th>
                    <th>Guru</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($absensi as $key => $a)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $a->tanggal->format('d-m-Y') }}</td>
                        <td>
                            <a href="{{ route('laporan.siswa', $a->siswa->id) }}">
                                {{ $a->siswa->nama }}
                            </a>
                        </td>
                        <td>{{ $a->siswa->kelas->nama }}</td>
                        <td>{{ $a->siswa->jurusan->nama }}</td>
                        <td>{{ $a->jadwal->guruMapelKelas->mataPelajaran->nama }}</td>
                        <td>{{ $a->jadwal->guruMapelKelas->guru->nama }}</td>
                        <td>{{ $a->status }}</td>
                        <td>{{ $a->keterangan ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>
</div>
@endsection
