@extends('layout')
@section('title', 'Dashboard Wali')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-success">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0">Selamat Datang, {{ $wali->nama }}</h4>
        </div>
        <div class="card-body">
            <h5 class="mb-3 text-success">Data Anak Anda:</h5>
            <div class="table-responsive">
                <table id="tabel-anak" class="table table-bordered table-striped align-middle">
                    <thead class="table-success">
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Jurusan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswaList as $no => $s)
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td>{{ $s->nama }}</td>
                            <td>{{ $s->kelas->nama }}</td>
                            <td>{{ $s->jurusan->nama }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Belum ada data siswa</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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
            $('#tabel-anak').DataTable({
                "pageLength": 5,
                "lengthMenu": [5, 10, 25, 50],
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Tidak ada data ditemukan",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ siswa",
                    "infoEmpty": "Tidak ada siswa tersedia",
                    "infoFiltered": "(disaring dari _MAX_ total siswa)",
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
