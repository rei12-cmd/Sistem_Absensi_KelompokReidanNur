@extends('layout')
@section('title', 'Rekap Absensi Anak')

@section('content')
<div class="container-fluid">
    <div class="mb-3">
        <a href="{{ route('absensiAnakSaya.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm border-success">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                Rekap Absensi {{ $siswa->nama }}
            </h4>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="tabel-rekap" class="table table-bordered table-striped align-middle">
                    <thead class="table-success">
                        <tr>
                            <th>No</th>
                            <th>Mata Pelajaran</th>
                            <th>Guru Pengampu</th>
                            <th>Jadwal</th>
                            <th>H</th>
                            <th>I</th>
                            <th>S</th>
                            <th>A</th>
                            <th>Persentase Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rekap as $no => $r)
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td>{{ $r['mapel'] }}</td>
                            <td>{{ $r['guru'] }}</td>
                            <td>{{ $r['hari'] }}, {{ $r['jam_mulai'] }} - {{ $r['jam_selesai'] }}</td>
                            <td><span class="badge bg-success">{{ $r['hadir'] }}</span></td>
                            <td><span class="badge bg-info text-dark">{{ $r['izin'] }}</span></td>
                            <td><span class="badge bg-warning text-dark">{{ $r['sakit'] }}</span></td>
                            <td><span class="badge bg-danger">{{ $r['alpa'] }}</span></td>
                            <td><strong>{{ $r['persentase'] }}%</strong></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">Belum ada data absensi untuk anak ini.</td>
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
        $('#tabel-rekap').DataTable({
            "pageLength": 5,
            "lengthMenu": [5, 10, 25, 50],
            "language": {
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Tidak ada data ditemukan",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ mata pelajaran",
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
