@extends('layout')

@section('title', 'Laporan Absensi')

@section('breadcumb')
    <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">Laporan Absensi</h3></div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Laporan Absensi</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Laporan Absensi</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <h1>Laporan Absensi</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
