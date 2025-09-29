@extends('layout')
@section('title', 'Mata Pelajaran')

@section('breadcumb')
  <div class="row">
    <div class="col-sm-6"><h3 class="mb-0">Mata Pelajaran</h3></div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-end">
        <li class="breadcrumb-item"><a href="{{ route('mapel.index') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Mata Pelajaran</li>
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
              <h3 class="card-title">Data Mata Pelajaran</h3>
            </div>
            <div class="col-12 col-md-6 text-md-end">
              <a href="{{ route('mapel.create') }}" class="btn btn-info btn-sm">
                <i class="bi bi-folder-plus"></i> Tambah
              </a>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="mapel-table" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Tindakan</th>
              </tr>
              </thead>
              <tbody>
              @foreach($mapels as $mp)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $mp->nama }}</td>
                  <td>
                    <a href="{{ route('mapel.edit', $mp->id) }}" class="btn text-bg-dark btn-sm">
                      <i class="bi bi-pencil-square"></i>
                      Edit
                    </a>

                    <form id="delete-form-{{ $mp->id }}" action="{{ route('mapel.destroy', $mp->id) }}" method="POST" class="d-inline-block">
                      @csrf
                      @method('DELETE')
                      <a href="#" class="btn btn-rounded btn-danger btn-sm" onclick="confirmDelete({{ $mp->id }})">
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
      $('#mapel-table').DataTable();
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
