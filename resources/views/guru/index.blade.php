@extends('layout')
@section('title', 'Guru')

@section('breadcumb')
    <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">Guru</h3></div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                {{-- pakai route('guru') supaya cocok dengan web.php yang menyediakan alias 'guru' --}}
                <li class="breadcrumb-item"><a href="{{ route('guru.index') }}">Home</a></li>

                <li class="breadcrumb-item active" aria-current="page">Guru</li>
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
                            <h3 class="card-title">Data Guru</h3>
                        </div>
                        <div class="col-12 col-md-6 text-md-end">
                            <a href="{{ route('guru.create') }}" class="btn btn-info btn-sm">
                                <i class="bi bi-folder-plus"></i> Tambah
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="guru-table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>NIP</th>
                                <th>Akun User</th>
                                <th>Telepon</th>
                                <th>Tindakan</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($gurus as $g)
                                <tr>
                                    {{-- nomor yang aman: jika $gurus ter-paginate gunakan firstItem(), kalau tidak pakai loop->iteration --}}
                                    <td>
                                      {{ (isset($gurus) && method_exists($gurus, 'firstItem') && $gurus->firstItem() !== null)
                                          ? $gurus->firstItem() + $loop->index
                                          : $loop->iteration }}
                                    </td>
                                    <td>{{ $g->nama }}</td>
                                    <td>{{ $g->nip ?? '-' }}</td>
                                    <td>{{ optional($g->user)->name ?? '-' }}</td>
                                    <td>{{ $g->telepon ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('guru.edit', $g->id) }}" class="btn text-bg-dark btn-sm">
                                            <i class="bi bi-pencil-square"></i>
                                            Edit
                                        </a>

                                        <form id="delete-form-{{ $g->id }}" action="{{ route('guru.destroy', $g->id) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <a href="#" class="btn btn-rounded btn-danger btn-sm" onclick="confirmDelete({{ $g->id }})">
                                                <i class="bi bi-trash"></i>
                                                Hapus
                                            </a>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada data guru.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                        {{-- jika pake pagination, tampilkan link --}}
                        @if(isset($gurus) && method_exists($gurus, 'links'))
                            <div class="mt-3">
                                {{ $gurus->links() }}
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
    <script>
        $(document).ready(function() {
            $('#guru-table').DataTable();
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
