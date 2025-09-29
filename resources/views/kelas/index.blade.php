@extends('layout')
@section('title', 'Kelas')

@section('breadcumb')
    <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">Kelas</h3></div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="{{ route('kelas.index') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Kelas</li>
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
                            <h3 class="card-title">Data Kelas</h3>
                        </div>
                        <div class="col-12 col-md-6 text-md-end">
                            <a href="{{ route('kelas.create') }}" class="btn btn-info btn-sm">
                                <i class="bi bi-folder-plus"></i> Tambah
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="kelas-table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Jurusan</th>
                                <th>Tindakan</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($kelas as $ac)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $ac->nama }}</td>
                                    <td>{{ $ac->jurusan->nama }}</td>
                                    <td>
                                        <a href="{{ route('kelas.edit', $ac['id']) }}" class="btn text-bg-dark btn-sm">
                                            <i class="bi bi-pencil-square"></i>
                                            Edit
                                        </a>

                                        <form id="delete-form-{{ $ac->id }}" action="{{ route('kelas.destroy', $ac->id) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <a href="#" class="btn btn-rounded btn-danger btn-sm" onclick="confirmDelete({{ $ac->id }})">
                                                <i class="bi bi-trash"></i>
                                                Hapus
                                            </a>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
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
            $('#kelas-table').DataTable();
        });
    </script>

    <script>
        function confirmDelete(jID) {
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
                    document.getElementById('delete-form-' + jID).submit();
                }
            });
        }
    </script>
@endpush
