@extends('layout')
@section('title', 'Siswa')

@section('breadcumb')
    <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">Siswa</h3></div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item">
                    @if(\Illuminate\Support\Facades\Route::has('siswa.index'))
                        <a href="{{ route('siswa.index') }}">Home</a>
                    @else
                        <a href="{{ url('/') }}">Home</a>
                    @endif
                </li>
                <li class="breadcrumb-item active" aria-current="page">Siswa</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Card box -->
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-12 col-md-6 mb-3 mb-md-0">
                            <h3 class="card-title">Data Siswa</h3>
                        </div>
                        <div class="col-12 col-md-6 text-md-end">
                            @if(\Illuminate\Support\Facades\Route::has('siswa.create'))
                                <a href="{{ route('siswa.create') }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-folder-plus"></i> Tambah
                                </a>
                            @else
                                <a href="{{ url('/siswa/create') }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-folder-plus"></i> Tambah
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="siswa-table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th style="width:70px">No</th>
                                <th>NIS</th>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th>Jurusan</th>
                                <th>Tanggal Lahir</th>
                                <th>Alamat</th>
                                <th>Akun User</th>
                                <th style="width:140px">Tindakan</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($siswas as $s)
                                <tr>
                                    <td>
                                        {{ (isset($siswas) && method_exists($siswas, 'firstItem') && $siswas->firstItem() !== null)
                                            ? $siswas->firstItem() + $loop->index
                                            : $loop->iteration }}
                                    </td>
                                    <td>{{ $s->nis }}</td>
                                    <td>{{ $s->nama }}</td>
                                    <td>{{ optional($s->kelas)->nama ?? optional($s->kelas)->nama_kelas ?? '-' }}</td>
                                    <td>{{ optional($s->jurusan)->nama ?? optional($s->jurusan)->nama_jurusan ?? '-' }}</td>
                                    <td>
                                        @if($s->tanggal_lahir)
                                            {{ \Carbon\Carbon::parse($s->tanggal_lahir)->format('Y-m-d') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $s->alamat ?? '-' }}</td>
                                    <td>{{ optional($s->user)->name ?? optional($s->user)->username ?? '-' }}</td>
                                    <td>
                                        @if(\Illuminate\Support\Facades\Route::has('siswa.edit'))
                                            <a href="{{ route('siswa.edit', $s->id) }}" class="btn text-bg-dark btn-sm">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </a>
                                        @else
                                            <a href="{{ url('/siswa/'.$s->id.'/edit') }}" class="btn text-bg-dark btn-sm">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </a>
                                        @endif

                                        <form id="delete-form-{{ $s->id }}"
                                              action="{{ \Illuminate\Support\Facades\Route::has('siswa.destroy') ? route('siswa.destroy', $s->id) : url('/siswa/'.$s->id) }}"
                                              method="POST"
                                              class="d-inline-block ms-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $s->id }})">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">Belum ada data siswa.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                        {{-- Pagination jika ada --}}
                        @if(isset($siswas) && method_exists($siswas, 'links'))
                            <div class="mt-3">
                                {{ $siswas->links() }}
                            </div>
                        @endif
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
    <!-- SweetAlert2 CDN (dipakai oleh confirmDelete) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('#siswa-table').DataTable({
                order: [],
                columnDefs: [
                    { orderable: false, targets: [0, 8] } // No & Aksi tidak bisa di-sort
                ],
                // Optional: sesuaikan bahasa / pageLength dst sesuai kebutuhan
            }).on('draw.dt', function () {
                // Re-numbering jika menggunakan paging client-side
                var table = $('#siswa-table').DataTable();
                var pageInfo = table.page.info();
                table.column(0, {page: 'current'}).nodes().each(function(cell, i) {
                    cell.innerHTML = pageInfo.start + i + 1;
                });
            });
        });

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
