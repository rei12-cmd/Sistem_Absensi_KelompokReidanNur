@extends('layout')
@section('title', 'Wali')

@section('breadcumb')
    <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">Wali</h3></div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                @if(\Illuminate\Support\Facades\Route::has('wali.index'))
                    <li class="breadcrumb-item"><a href="{{ route('wali.index') }}">Home</a></li>
                @else
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                @endif
                <li class="breadcrumb-item active" aria-current="page">Wali</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Data Wali</h3>
        @if(\Illuminate\Support\Facades\Route::has('wali.create'))
          <a href="{{ route('wali.create') }}" class="btn btn-info btn-sm"><i class="bi bi-folder-plus"></i> Tambah</a>
        @endif
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table id="wali-table" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Telepon</th>
                <th>Alamat</th>
                <th>Siswa Terkait</th>
                <th>Akun User</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($walis as $w)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $w->nama }}</td>
                  <td>{{ $w->telepon ?? '-' }}</td>
                  <td>{{ $w->alamat ?? '-' }}</td>
                  <td>
                    @if($w->siswa && $w->siswa->count())
                      <ul class="mb-0">
                        @foreach($w->siswa as $s)
                          <li>{{ $s->nama ?? $s->nis }}</li>
                        @endforeach
                      </ul>
                    @else
                      -
                    @endif
                  </td>
                  <td>{{ optional($w->user)->name ?? optional($w->user)->username ?? optional($w->user)->email ?? '-' }}</td>
                  <td>
                    @if(\Illuminate\Support\Facades\Route::has('wali.edit'))
                      <a href="{{ route('wali.edit', $w->id) }}" class="btn text-bg-dark btn-sm">Edit</a>
                    @endif
                    <form id="delete-form-{{ $w->id }}" action="{{ \Illuminate\Support\Facades\Route::has('wali.destroy') ? route('wali.destroy', $w->id) : url('/wali/'.$w->id) }}" method="POST" class="d-inline-block ms-1">
                      @csrf
                      @method('DELETE')
                      <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $w->id }})">Hapus</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr><td colspan="7" class="text-center">Belum ada data wali.</td></tr>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function(){ $('#wali-table').DataTable({ order: [], columnDefs:[{orderable:false, targets:[0,6]}] }); });

function confirmDelete(id){
  Swal.fire({
    title: "Apakah Anda yakin?",
    text: "Data yang dihapus tidak bisa dikembalikan!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Ya, hapus!",
    cancelButtonText: "Batal"
  }).then((result) => { if(result.isConfirmed){ document.getElementById('delete-form-'+id).submit(); } });
}
</script>
@endpush
