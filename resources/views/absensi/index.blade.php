@extends('layout')
@section('title', 'Absensi')

@section('breadcumb')
    <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">Absensi</h3></div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Absensi</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
@php
    // safe fallbacks: jika controller belum mengirim variabel
    $siswas = $siswas ?? collect();
    $jadwal = $jadwal ?? null;
    $kelasList = $kelas ?? collect();
    // form action fallback
    $storeAction = \Illuminate\Support\Facades\Route::has('absensi.store') ? route('absensi.store') : url('/absensi/store');
@endphp

<div class="card">
    <div class="card-header" style="background:#2d6a5f;color:#fff;">
        <h5 class="mb-0 text-center">Absensi Siswa</h5>
    </div>

    <div class="card-body">
        {{-- Header / controls --}}
        <div class="row mb-3 align-items-center">
            <div class="col-md-4">
                <label>Show
                    <select id="lengthSelect" class="form-select form-select-sm d-inline-block" style="width:auto; margin-left:.5rem;">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                    </select>
                    entries
                </label>
            </div>

            <div class="col-md-4 text-center">
                <button id="btnCancel" class="btn btn-danger btn-sm me-2">Batal Absen</button>
                <button id="btnAllPresent" class="btn btn-warning btn-sm me-2">Ceklis Hadir Semua</button>
            </div>

            <div class="col-md-4 text-end">
                <input id="globalSearch" type="text" class="form-control form-control-sm" placeholder="Search...">
            </div>
        </div>

        {{-- Form absensi --}}
        <form id="absensiForm" method="POST" action="{{ $storeAction }}">
            @csrf

            {{-- Optional: kirim jadwal_id bila ada --}}
            @if($jadwal && isset($jadwal->id))
                <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">
            @endif

            <div class="table-responsive">
                <table id="absensi-table" class="table table-bordered table-striped">
                    <thead class="table-light text-center align-middle">
                        <tr>
                            <th style="width:50px">No</th>
                            <th style="min-width:120px">NIS</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th style="width:120px">Jenis Kelamin</th>
                            <th style="width:220px">Aksi (H / I / S / A)</th>
                            <th style="width:120px">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($siswas->isEmpty())
                            {{-- Tampilkan 5 baris placeholder agar tampilan konsisten --}}
                            @for($i=1; $i<=5; $i++)
                                <tr class="text-center">
                                    <td>{{ $i }}.</td>
                                    <td>xxx-xxx-xxx</td>
                                    <td>Nama Contoh</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>
                                        <small class="text-muted">Tidak ada data siswa</small>
                                    </td>
                                    <td>-</td>
                                </tr>
                            @endfor
                        @else
                            @foreach($siswas as $index => $s)
                                <tr data-siswa-id="{{ $s->id }}">
                                    <td class="text-center">{{ $loop->iteration }}.</td>
                                    <td class="text-center">{{ $s->nis ?? '-' }}</td>
                                    <td>{{ $s->nama ?? '-' }}</td>
                                    <td>{{ optional($s->kelas)->nama ?? optional($s->kelas)->nama_kelas ?? '-' }}</td>
                                    <td class="text-center">{{ $s->jenis_kelamin ?? ($s->jk ?? '-') }}</td>

                                    {{-- Aksi: 4 checkbox yang dikontrol oleh JS, + hidden input untuk dikirim --}}
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2 align-items-center">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input status-checkbox status-H" data-val="H" id="h_{{ $s->id }}">
                                                <label class="form-check-label" for="h_{{ $s->id }}">H</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input status-checkbox status-I" data-val="I" id="i_{{ $s->id }}">
                                                <label class="form-check-label" for="i_{{ $s->id }}">I</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input status-checkbox status-S" data-val="S" id="s_{{ $s->id }}">
                                                <label class="form-check-label" for="s_{{ $s->id }}">S</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input status-checkbox status-A" data-val="A" id="a_{{ $s->id }}">
                                                <label class="form-check-label" for="a_{{ $s->id }}">A</label>
                                            </div>
                                        </div>

                                        {{-- field yang dikirim ke server --}}
                                        <input type="hidden" name="status[{{ $s->id }}]" id="status_input_{{ $s->id }}" value="{{ old('status.'.$s->id, '') }}">
                                    </td>

                                    <td class="text-center status-text" id="status_text_{{ $s->id }}">
                                        {{ old('status.'.$s->id) ? old('status.'.$s->id) : '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-success" id="btnSave">Simpan Data Absen</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<style>
    /* visual tweaks to match mockup feel */
    #absensi-table thead th { vertical-align: middle; text-align: center; }
    .status-text { font-weight:600; }
    .form-check-label { margin-left: .25rem; margin-right: .5rem; }
    .btn-warning { background:#f0ad4e; border-color:#eea236; color:#fff; }
    .btn-danger { background:#d9534f; border-color:#d43f3a; color:#fff; }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
    $(function() {
        // DataTable init with length control linked to select
        var table = $('#absensi-table').DataTable({
            "pageLength": parseInt($('#lengthSelect').val() || 10),
            "lengthChange": false,
            "ordering": false,
            "info": true,
            "dom": 'lrtip' // remove default search box (we use custom)
        });

        // custom length select
        $('#lengthSelect').on('change', function(){
            table.page.len( parseInt(this.value) ).draw();
        });

        // custom search input
        $('#globalSearch').on('keyup', function(){
            table.search(this.value).draw();
        });

        // status checkbox logic: only one per row can be selected
        $(document).on('change', '.status-checkbox', function() {
            var $row = $(this).closest('tr');
            var siswaId = $row.data('siswa-id');

            // uncheck all checkboxes in this row, then check the clicked one (if it was checked)
            var wasChecked = $(this).is(':checked');
            $row.find('.status-checkbox').prop('checked', false);

            if (wasChecked) {
                $(this).prop('checked', true);
                var value = $(this).data('val');
                $('#status_input_' + siswaId).val(value);
                $('#status_text_' + siswaId).text(value);
            } else {
                // none selected
                $('#status_input_' + siswaId).val('');
                $('#status_text_' + siswaId).text('-');
            }
        });

        // "Ceklis Hadir Semua" button
        $('#btnAllPresent').on('click', function(e){
            e.preventDefault();
            $('#absensi-table tbody tr').each(function(){
                var siswaId = $(this).data('siswa-id');
                // check H checkbox
                $(this).find('.status-checkbox').prop('checked', false);
                $(this).find('.status-H').prop('checked', true);
                $('#status_input_' + siswaId).val('H');
                $('#status_text_' + siswaId).text('H');
            });
        });

        // "Batal Absen" button — clear all selections
        $('#btnCancel').on('click', function(e){
            e.preventDefault();
            $('#absensi-table tbody tr').each(function(){
                var siswaId = $(this).data('siswa-id');
                $(this).find('.status-checkbox').prop('checked', false);
                $('#status_input_' + siswaId).val('');
                $('#status_text_' + siswaId).text('-');
            });
        });

        // prevent double submit
        $('#absensiForm').on('submit', function(){
            $('#btnSave').prop('disabled', true).text('Menyimpan...');
        });
    });
</script>
@endpush
