@extends('layout')
@section('title', 'Rekap Absensi Saya')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="card shadow-sm border-success">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-clipboard-list me-1"></i> Rekap Absensi Saya</h5>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="tabel-rekap" class="table table-bordered table-striped align-middle">
                        <thead class="table-success text-center">
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
                            @forelse($rekap as $r)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $r['mapel'] }}</td>
                                <td>{{ $r['guru'] }}</td>
                                <td>{{ $r['hari'] }}, {{ $r['jam_mulai'] }} - {{ $r['jam_selesai'] }}</td>
                                <td class="text-center text-success fw-bold">{{ $r['hadir'] }}</td>
                                <td class="text-center text-primary fw-bold">{{ $r['izin'] }}</td>
                                <td class="text-center text-warning fw-bold">{{ $r['sakit'] }}</td>
                                <td class="text-center text-danger fw-bold">{{ $r['alpa'] }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1" style="height: 8px;">
                                            <div class="progress-bar 
                                                @if($r['persentase'] >= 90) bg-success 
                                                @elseif($r['persentase'] >= 75) bg-info 
                                                @elseif($r['persentase'] >= 50) bg-warning 
                                                @else bg-danger @endif" 
                                                style="width: {{ $r['persentase'] }}%">
                                            </div>
                                        </div>
                                        <span class="ms-2 fw-bold">{{ $r['persentase'] }}%</span>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">Belum ada data absensi</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
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
