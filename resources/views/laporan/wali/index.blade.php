@extends('layout')
@section('title', 'Laporan Absensi Anak')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-success">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0"> Daftar Anak Anda</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabel-anak" class="table table-bordered table-striped align-middle">
                    <thead class="table-success">
                        <tr>
                            <th>No</th>
                            <th>Nama Anak</th>
                            <th>Kelas</th>
                            <th>Jurusan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($anak as $no => $a)
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td>{{ $a->nama }}</td>
                            <td>{{ $a->kelas->nama }}</td>
                            <td>{{ $a->jurusan->nama }}</td>
                            <td>
                                <a href="{{ route('absensiAnakSaya.show', $a->id) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i> Lihat Rekap
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada data anak yang terdaftar.</td>
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
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ anak",
                "infoEmpty": "Tidak ada anak tersedia",
                "infoFiltered": "(disaring dari _MAX_ total anak)",
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
