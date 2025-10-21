@extends('layout')
@section('title', 'Laporan Absensi Admin')

@section('breadcumb')
    <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">Laporan Absensi Admin</h3></div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Laporan Absensi</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-12 col-md-6 mb-3 mb-md-0">
                            <h3 class="card-title">Laporan Absensi</h3>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form method="GET" class="mb-4">
                        <div class="row g-2">
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
                                <input type="date" name="tanggal_mulai" class="form-control"
                                       value="{{ request('tanggal_mulai') }}" placeholder="Tanggal Mulai">
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="tanggal_selesai" class="form-control"
                                       value="{{ request('tanggal_selesai') }}" placeholder="Tanggal Selesai">
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-info w-100"><i class="bi bi-filter"></i> Filter</button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table id="laporan-table" class="table table-bordered table-striped">
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
                                @forelse($absensi as $key => $a)
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
                                        <td>{{ ucfirst($a->status) }}</td>
                                        <td>{{ $a->keterangan ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Tidak ada data absensi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
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
            $('#laporan-table').DataTable({
                "pageLength": 10,
                "lengthMenu": [5, 10, 25, 50, 100],
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
