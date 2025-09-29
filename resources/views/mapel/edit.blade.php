@extends('layout')
@section('title', 'Mata Pelajaran - Edit')

@section('breadcumb')
    <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">Mata Pelajaran</h3></div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="{{ route('mapel.index') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Mata Pelajaran</h3>
                </div>
                <div class="card-body">

                    <form method="POST" action="{{ route('mapel.update', $mapel->id) }}" onsubmit="disableSubmitButton()">
                        @csrf
                        @method('PATCH')

                        @error('nama')
                        <div class="mb-3">
                          <span class="badge bg-danger">{{ $message }}</span>
                        </div>
                        @enderror

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Mata Pelajaran</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $mapel->nama) }}">
                        </div>

                        <div class="d-flex gap-2">
                          <button type="submit" class="btn btn-primary" id="submit-btn">
                              <i class="bi bi-pencil-square"></i>
                              <span id="button-text">Update Mata Pelajaran</span>
                              <span id="loading-spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                          </button>

                          <a href="{{ route('mapel.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                          </a>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function disableSubmitButton() {
            const submitButton = document.getElementById('submit-btn');
            const spinner = document.getElementById('loading-spinner');
            const buttonText = document.getElementById('button-text');

            submitButton.disabled = true;
            spinner.style.display = 'inline-block';
            buttonText.textContent = 'Menyimpan...';
        }
    </script>
@endpush
