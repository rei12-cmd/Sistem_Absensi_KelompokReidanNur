@extends('layout')

@section('title', 'Daftar Jadwal Mengajar')

@section('breadcumb')
    <div class="row">
        <div class="col-sm-6">
            <h3 class="mb-0">Daftar Jadwal Mengajar</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Jadwal Mengajar</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<div class="card shadow-sm border-success">
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Jadwal Mengajar</h5>
    </div>

    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($jadwals->count() > 0)
            <div class="table-responsive">
                <table id="jadwal-mengajar" class="table table-bordered table-striped align-middle">
                    <thead class="table-success">
                        <tr>
                            <th>No</th>
                            <th>Kelas</th>
                            <th>Mata Pelajaran</th>
                            <th>Hari</th>
                            <th>Jam</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jadwals as $no => $jadwal)
                            <tr>
                                <td>{{ $no + 1 }}</td>
                                <td>{{ $jadwal->guruMapelKelas->kelas->nama ?? '-' }}</td>
                                <td>{{ $jadwal->guruMapelKelas->mataPelajaran->nama ?? '-' }}</td>
                                <td>{{ ucfirst($jadwal->hari) }}</td>
                                <td>{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
                                <td class="text-center">
                                    <a href="{{ route('absensi.show', $jadwal->id) }}" class="btn btn-success btn-sm">
                                        <i class="fas fa-clipboard-check"></i> Mulai Absen
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info text-center">Tidak ada jadwal mengajar yang tersedia untuk Anda.</div>
        @endif
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#jadwal-mengajar').DataTable({
                "pageLength": 10,
                "lengthMenu": [5, 10, 25, 50],
                "order": [[3, 'asc']], // urutkan berdasarkan hari
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Tidak ada jadwal ditemukan",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Tidak ada data tersedia",
                    "infoFiltered": "(disaring dari _MAX_ total data)",
                    "search": "Cari:",
                    "paginate": {
                        "first": "Awal",
                        "last": "Akhir",
                        "next": "›",
                        "previous": "‹"
                    }
                }
            });
        });
    </script>
@endpush
