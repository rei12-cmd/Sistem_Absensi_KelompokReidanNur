@extends('layout')
@section('title', 'Detail Absensi Siswa')

@section('breadcumb')
    <div class="row">
        <div class="col-sm-6">
            <h3 class="mb-0">Detail Absensi Siswa</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('laporan.index') }}">Laporan Absensi</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Siswa</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Detail Absensi: {{ $siswa->nama }}</h4>
                    <small>
                        Kelas: <strong>{{ $siswa->kelas->nama }}</strong> | 
                        Jurusan: <strong>{{ $siswa->jurusan->nama }}</strong>
                    </small>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="absensi-detail" class="table table-bordered table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Mapel</th>
                                    <th>Guru</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($absensi as $key => $a)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $a->tanggal->format('d-m-Y') }}</td>
                                        <td>{{ $a->jadwal->guruMapelKelas->mataPelajaran->nama }}</td>
                                        <td>{{ $a->jadwal->guruMapelKelas->guru->nama }}</td>
                                        <td>{{ ucfirst($a->status) }}</td>
                                        <td>{{ $a->keterangan ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Belum ada data absensi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('laporan.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
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
            $('#absensi-detail').DataTable({
                "pageLength": 10,
                "lengthMenu": [5, 10, 25, 50],
                "order": [[1, 'desc']],
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Data tidak ditemukan",
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
