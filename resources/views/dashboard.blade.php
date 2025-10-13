@extends('layout')
@section('title', 'Dashboard Absensi')

@section('content')
<div class="container-fluid px-4">
  <h4 class="mt-3 mb-4 fw-bold">Dashboard Absensi</h4>

  {{-- Kartu Statistik (tema absensi) --}}
  <div class="row g-3 mb-4">
    <div class="col-md-3">
      <div class="card shadow-sm border-0 text-white" style="background-color:#1e7e34;">
        <div class="card-body text-center py-4">
          <h3 class="fw-bold">{{ $totalSiswa }}</h3>
          <p class="mb-0">Total Siswa</p>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card shadow-sm border-0 text-white" style="background-color:#0d6efd;">
        <div class="card-body text-center py-4">
          <h3 class="fw-bold">{{ $hadirToday }}</h3>
          <p class="mb-0">Hadir Hari Ini</p>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card shadow-sm border-0 text-white" style="background-color:#6f42c1;">
        <div class="card-body text-center py-4">
          <h3 class="fw-bold">{{ $izinSakitToday }}</h3>
          <p class="mb-0">Izin / Sakit Hari Ini</p>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card shadow-sm border-0 text-white" style="background-color:#343a40;">
        <div class="card-body text-center py-4">
          <h3 class="fw-bold">{{ $totalAbsensi }}</h3>
          <p class="mb-0">Total Absensi</p>
        </div>
      </div>
    </div>
  </div>

  {{-- Grafik dan Pengumuman --}}
  <div class="row g-4">
    <div class="col-md-8">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-dark text-white d-flex align-items-center">
          <i class="bi bi-bar-chart-fill me-2"></i> <span>Jumlah Siswa per Jurusan</span>
        </div>
        <div class="card-body">
          <canvas id="jurusanChart" height="220"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-dark text-white d-flex align-items-center">
          <i class="bi bi-megaphone-fill me-2"></i> <span>Pengumuman</span>
        </div>
        <div class="card-body">
          @forelse($pengumumans as $p)
            <h6 class="fw-bold">{{ $p->judul ?? 'Pengumuman' }}</h6>
            <p class="small mb-3">{{ \Illuminate\Support\Str::limit($p->isi ?? $p->deskripsi ?? '—', 150) }}</p>
          @empty
            <p class="text-muted">Belum ada pengumuman.</p>
          @endforelse
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const labels = {!! json_encode($chartLabels) !!} || [];
  const values = {!! json_encode($chartValues) !!} || [];

  const ctx = document.getElementById('jurusanChart');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: 'Jumlah Siswa',
        data: values,
        backgroundColor: labels.map((l, i) => {
          // buat warna sederhana (berulang)
          const palette = ['#ff52e5','#0b5cff','#00f0e0','#ffb86b','#9f7aea'];
          return palette[i % palette.length];
        }),
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
  });
</script>
@endsection

{{-- Tambahan agar bisa juga di-stack ke layout --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const labels = {!! json_encode($chartLabels ?? []) !!};
  const values = {!! json_encode($chartValues ?? []) !!};

  const ctx = document.getElementById('jurusanChart');
  if (!ctx) return;

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: 'Jumlah Siswa',
        data: values,
        backgroundColor: labels.map((l, i) => {
          const palette = ['#ff52e5','#0b5cff','#00f0e0','#ffb86b','#9f7aea'];
          return palette[i % palette.length];
        }),
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
  });
});
</script>
@endpush
