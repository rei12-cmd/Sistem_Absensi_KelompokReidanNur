@extends('layout')
@section('title', 'Jadwal')

@section('breadcumb')
    <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">Jadwal</h3></div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="{{ route('jadwal.index') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Jadwal</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-12 col-md-6 mb-3 mb-md-0">
                            <h3 class="card-title">Data Jadwal</h3>
                        </div>
                        <div class="col-12 col-md-6 text-md-end">
                            <a href="{{ route('jadwal.create') }}" class="btn btn-info btn-sm">
                                <i class="bi bi-folder-plus"></i> Tambah
                            </a>

                            <!-- Tombol baru: Tambah Atur Mengajar -->
                            <a href="{{ route('atur-mengajar.index') }}" class="btn btn-primary btn-sm ms-2">
                                <i class="bi bi-gear"></i> Tambah Atur Mengajar
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="jadwal-table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Kelas</th>
                                <th>Guru</th>
                                <th>Mata Pelajaran</th>
                                <th>Hari</th>
                                <th>Jam</th>
                                <th>Tindakan</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($jadwals as $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $row->guruMapelKelas->kelas->nama ?? '-' }}</td>
                                    <td>{{ $row->guruMapelKelas->guru->nama ?? '-' }}</td>
                                    <td>{{ $row->guruMapelKelas->mataPelajaran->nama ?? '-' }}</td>
                                    <td>{{ ucfirst($row->hari) }}</td>
                                    <td>{{ $row->jam_mulai }} - {{ $row->jam_selesai }}</td>
                                    <td>
                                        <a href="{{ route('jadwal.edit', $row->id) }}" class="btn text-bg-dark btn-sm">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>

                                        <form id="delete-form-{{ $row->id }}" action="{{ route('jadwal.destroy', $row->id) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <a href="#" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $row->id }})">
                                                <i class="bi bi-trash"></i> Hapus
                                            </a>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Belum ada data</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.card -->
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
            $('#jadwal-table').DataTable();
        });
    </script>

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
@endpush
