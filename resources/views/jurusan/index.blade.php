@extends('layout')
@section('title', 'Jurusan')

@section('breadcumb')
  <div class="row">
    <div class="col-sm-6"><h3 class="mb-0">Jurusan</h3></div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-end">
        <li class="breadcrumb-item"><a href="{{ route('jurusan.index') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Jurusan</li>
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
              <h3 class="card-title">Data Jurusan</h3>
            </div>
            <div class="col-12 col-md-6 text-md-end">
              <a href="{{ route('jurusan.create') }}" class="btn btn-info btn-sm">
                <i class="bi bi-folder-plus"></i> Tambah
              </a>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="jurusan-table" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th style="width:70px">No</th>
                <th>Nama</th>
                <th style="width:200px">Tindakan</th>
              </tr>
              </thead>
              <tbody>
              @foreach($jurusan as $ac)
                <tr>
                  {{-- Nomor awal dari server (DOM order) —
                      DataTables akan menggantikannya saat draw agar tetap konsisten --}}
                  <td>{{ $loop->iteration }}</td>

                  {{-- Gunakan akses property object --}}
                  <td>{{ $ac->nama }}</td>

                  <td>
                    <a href="{{ route('jurusan.edit', $ac->id) }}" class="btn text-bg-dark btn-sm">
                      <i class="bi bi-pencil-square"></i>
                      Edit
                    </a>

                    <form id="delete-form-{{ $ac->id }}" action="{{ route('jurusan.destroy', $ac->id) }}" method="POST" class="d-inline-block">
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
  <!-- jQuery & DataTables -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <!-- SweetAlert2 (dipakai oleh confirmDelete) -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    $(document).ready(function() {
      // Inisialisasi DataTable
      var table = $('#jurusan-table').DataTable({
        // Biarkan DOM order (order: []) - agar urutan dari server (controller) tetap dipakai
        order: [],
        paging: true,
        info: true,
        searching: true,
        lengthChange: true,
        // Matikan ordering pada kolom No dan Tindakan
        columnDefs: [
          { orderable: false, targets: [0, 2] }
        ],
        // Jangan tambahkan ordering default agar DOM order tetap
      });

      // Re-number kolom No setiap kali draw (paging/search/order)
      table.on('draw.dt', function () {
        var pageInfo = table.page.info();
        table.column(0, { page: 'current' }).nodes().each(function(cell, i) {
          // index global: page start + row index + 1
          cell.innerHTML = pageInfo.start + i + 1;
        });
      });

      // Trigger draw agar nomor ter-update di inisialisasi pertama
      table.draw();
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
