@extends('layout')
@section('title', 'Dashboard Guru')

@section('breadcumb')
    <div class="row">
        <div class="col-sm-6">
            <h3 class="mb-0">Dashboard Guru</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard Guru</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="fw-bold mb-2">Selamat Datang, {{ $guru->nama }}</h4>
            <p class="text-muted mb-4">Berikut adalah jadwal mengajar Anda minggu ini.</p>

            <div class="table-responsive">
                <table id="jadwal-guru" class="table table-bordered table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Hari</th>
                            <th>Mata Pelajaran</th>
                            <th>Kelas</th>
                            <th>Jam</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwal as $key => $j)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ ucfirst($j->hari) }}</td>
                                <td>{{ $j->guruMapelKelas->mataPelajaran->nama }}</td>
                                <td>{{ $j->guruMapelKelas->kelas->nama }}</td>
                                <td>{{ $j->jam_mulai }} - {{ $j->jam_selesai }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada jadwal mengajar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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
            $('#jadwal-guru').DataTable({
                "pageLength": 10,
                "lengthMenu": [5, 10, 25, 50],
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
