@extends('layout')
@section('title', 'Dashboard Siswa')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-success">
        <div class="card-body">
            <h4 class="mb-2 text-success">Halo, {{ $siswa->nama }}</h4>
            <p class="text-muted">Kelas: {{ $siswa->kelas->nama }} | Jurusan: {{ $siswa->jurusan->nama }}</p>

            <hr>
            <h6 class="fw-bold">Riwayat Absensi Terbaru:</h6>

            <div class="table-responsive mt-3">
                <table id="tabel-absensi-siswa" class="table table-bordered table-striped align-middle">
                    <thead class="table-success">
                        <tr>
                            <th>Tanggal</th>
                            <th>Mata Pelajaran</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absensi as $a)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($a->tanggal)->format('d-m-Y') }}</td>
                            <td>{{ $a->jadwal->guruMapelKelas->mataPelajaran->nama ?? '-' }}</td>
                            <td>{{ $a->status }}</td>
                            <td>{{ $a->keterangan ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Belum ada data absensi.</td>
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
        $('#tabel-absensi-siswa').DataTable({
            "pageLength": 10,
            "lengthMenu": [5, 10, 25, 50],
            "language": {
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Tidak ada data ditemukan",
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
