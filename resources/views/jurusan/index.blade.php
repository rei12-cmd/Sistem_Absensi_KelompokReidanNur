@extends('layout')

@section('title', 'Jurusan')

@section('breadcumb')
    <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">Jurusan</h3></div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Jurusan</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Data Jurusan</h3>
                {{-- TOMBOL TAMBAH DATA --}}
                <a href="{{ route('jurusan.create') }}" class="btn btn-success ms-auto">
                    <i class="bi bi-plus-lg"></i> Tambah Jurusan
                </a>
            </div>
            <div class="card-body">
                {{-- MENAMPILKAN PESAN SUKSES --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table id="jurusan-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th width="150px">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- PERBAIKAN: Menggunakan @forelse untuk penanganan data kosong --}}
                            @forelse($jurusan as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    {{-- Mengakses properti objek Model Jurusan --}}
                                    <td>{{ $item->nama }}</td>
                                    <td>
                                        <div class="d-flex">
                                            {{-- TOMBOL SHOW --}}
                                            <a href="{{ route('jurusan.show', $item->id) }}" class="btn btn-info btn-sm btn-crud me-2" title="Lihat">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            {{-- TOMBOL EDIT --}}
                                            <a href="{{ route('jurusan.edit', $item->id) }}" class="btn btn-primary btn-sm btn-crud me-2" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            {{-- FORM UNTUK HAPUS DATA --}}
                                            <form id="delete-form-{{ $item->id }}" action="{{ route('jurusan.destroy', $item->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')

                                                {{-- TOMBOL HAPUS DENGAN KONFIRMASI --}}
                                                <button type="button" class="btn btn-danger btn-sm btn-crud" onclick="confirmDelete({{ $item->id }})" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Data Jurusan belum tersedia.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

---

@push('styles')
    {{-- Tambahkan link CSS untuk Bootstrap Icons dan DataTables --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    {{-- Jika Anda menggunakan SweetAlert dari CDN, pastikan CSS-nya ter-load --}}

    {{-- Custom kecil supaya tombol CRUD ukurannya sama dan icon ter-center --}}
    <style>
        /* Sesuaikan nilai width/height sesuai preferensi (ubah jika ingin lebih kecil/besar) */
        .btn-crud {
            width: 38px !important;
            height: 34px !important;
            padding: 0 !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 6px !important;
            line-height: 1 !important;
        }
        /* Jika tema lain menimpa, pakai selector lebih spesifik */
        table#jurusan-table .btn-crud {
            width: 38px !important;
            height: 34px !important;
        }
        /* Spasi antar tombol tetap rapi */
        .btn-crud.me-2 { margin-right: .5rem !important; }
    </style>
@endpush

@push('scripts')
    {{-- jQuery dan DataTables --}}
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    {{-- SweetAlert2 (Hanya jika belum di-load di layout) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Inisialisasi DataTables
            $('#jurusan-table').DataTable({
                // Opsi DataTables tambahan jika diperlukan
            });
        });

        // Fungsi konfirmasi hapus menggunakan SweetAlert2
        function confirmDelete(jurusanId) {
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
                    // Submit form penghapusan yang sesuai
                    document.getElementById('delete-form-' + jurusanId).submit();
                }
            });
        }
    </script>
@endpush
