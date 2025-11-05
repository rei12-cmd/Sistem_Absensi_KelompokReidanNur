@extends('layout')
@section('title', 'Atur Mengajar')

@section('breadcumb')
<div class="row">
    <div class="col-sm-6"><h3 class="mb-0">Atur Mengajar</h3></div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="{{ route('jadwal.index') }}">Home</a></li>
            <li class="breadcrumb-item active">Atur Mengajar</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Daftar Relasi Guru - Mapel - Kelas</h3>
        <div>
            <a href="{{ route('jadwal.index') }}" class="btn btn-sm btn-secondary me-2">Kembali ke Jadwal</a>
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahRelasi">
                <i class="bi bi-plus-circle"></i> Tambah
            </button>
        </div>
    </div>

    <div class="card-body">
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div> @endif

        <div class="table-responsive">
            <table id="index-table" class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th style="width:50px">No</th>
                        <th>Kelas</th>
                        <th>Guru</th>
                        <th>Mata Pelajaran</th>
                        <th style="width:150px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($relations as $r)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ optional($r->kelas)->nama ?? (optional($r->kelas)->nama_kelas ?? '-') }}</td>
                            <td>{{ optional($r->guru)->nama ?? (optional($r->guru)->nama_guru ?? '-') }}</td>
                            <td>{{ optional($r->mataPelajaran)->nama ?? (optional($r->mataPelajaran)->nama_mapel ?? '-') }}</td>
                            <td>
                                <form action="{{ route('atur-mengajar.destroy', $r->id) }}" method="POST" onsubmit="return confirm('Hapus relasi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">Belum ada relasi. Silakan tambahkan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-2">
            {{-- Hanya tampilkan links() jika $relations adalah paginator --}}
            @if(method_exists($relations, 'links'))
                {{ $relations->links() }}
            @endif
        </div>
    </div>
</div>

<!-- Modal Tambah Relasi -->
<div class="modal fade" id="modalTambahRelasi" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('atur-mengajar.store') }}" method="POST" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Tambah Relasi Guru - Mapel - Kelas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {{-- Kelas --}}
        <div class="mb-3">
            <label for="kelas_id" class="form-label">Kelas</label>
            <select name="kelas_id" id="kelas_id" class="form-control @error('kelas_id') is-invalid @enderror" required>
                <option value="">-- Pilih Kelas --</option>
                @foreach($kelas as $k)
                    <option value="{{ $k->id }}">{{ $k->nama ?? $k->nama_kelas ?? $k->kode ?? 'Kelas' }}</option>
                @endforeach
            </select>
            @error('kelas_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Mapel --}}
        <div class="mb-3">
            <label for="mata_pelajaran_id" class="form-label">Mata Pelajaran</label>
            <select name="mata_pelajaran_id" id="mata_pelajaran_id" class="form-control @error('mata_pelajaran_id') is-invalid @enderror" required>
                <option value="">-- Pilih Mata Pelajaran --</option>
                @foreach($mapels as $m)
                    <option value="{{ $m->id }}">{{ $m->nama ?? $m->nama_mapel ?? 'Mapel' }}</option>
                @endforeach
            </select>
            @error('mata_pelajaran_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Guru --}}
        <div class="mb-3">
            <label for="guru_id" class="form-label">Guru</label>
            <select name="guru_id" id="guru_id" class="form-control @error('guru_id') is-invalid @enderror" required>
                <option value="">-- Pilih Guru --</option>
                @foreach($gurus as $g)
                    <option value="{{ $g->id }}">{{ $g->nama ?? $g->nama_guru ?? 'Guru' }}</option>
                @endforeach
            </select>
            @error('guru_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
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
            $('#index-table').DataTable();
        });
    </script>
@endpush
