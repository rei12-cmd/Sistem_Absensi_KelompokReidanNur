@extends('layout')
@section('title', 'Dashboard Admin')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header rounded-top-3">
                <h4 class="mb-0"></i>Dashboard Admin</h4>
            </div>
            <div class="card-body bg-light">
                <div class="row g-4">
                    @foreach($data as $label => $value)
                        @php
                            $name = ucfirst(str_replace('total', '', $label));
                            $icons = [
                                'siswa' => 'bi-people-fill',
                                'guru' => 'bi-person-workspace',
                                'kelas' => 'bi-building',
                                'jurusan' => 'bi-diagram-3',
                                'mapel' => 'bi-book',
                                'absensi' => 'bi-calendar-check',
                                'wali' => 'bi-person-heart'
                            ];
                            $colors = [
                                'siswa' => 'primary',
                                'guru' => 'success',
                                'kelas' => 'info',
                                'jurusan' => 'warning',
                                'mapel' => 'danger',
                                'absensi' => 'secondary',
                                'wali' => 'teal'
                            ];
                            $icon = $icons[$name] ?? 'bi-circle';
                            $color = $colors[$name] ?? 'dark';
                        @endphp

                        <div class="col-md-4 col-lg-3">
                            <div class="card border-0 shadow-sm rounded-4 hover-card">
                                <div class="card-body text-center py-4">
                                    <div class="mb-3 text-{{ $color }}">
                                        <i class="bi {{ $icon }} display-5"></i>
                                    </div>
                                    <h6 class="text-uppercase fw-semibold text-muted">{{ $name }}</h6>
                                    <h2 class="fw-bold mb-0">{{ $value }}</h2>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .hover-card {
        transition: all 0.3s ease;
        background: #fff;
    }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
    }
</style>
@endsection
