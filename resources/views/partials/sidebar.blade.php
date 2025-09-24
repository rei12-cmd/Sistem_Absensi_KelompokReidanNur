{{-- resources/views/partials/sidebar.blade.php --}}
{{-- Versi ini sudah diperbaiki: jurusan -> admin.jurusan.index, kelas -> admin.kelas.index
     Baris asli yang kamu kirim sebelumnya disimpan sebagai komentar di bawah. --}}

@php
    use Illuminate\Support\Facades\Route;

    // safe route helpers
    $urlAdminDashboard = Route::has('admin.dashboard') ? route('admin.dashboard') : url('/admin/dashboard');

    // Jurusan
    if (Route::has('admin.jurusan.index')) {
        $urlJurusan = route('admin.jurusan.index');
    } elseif (Route::has('jurusan')) {
        $urlJurusan = route('jurusan');
    } else {
        $urlJurusan = url('/admin/jurusan');
    }

    // Kelas
    if (Route::has('admin.kelas.index')) {
        $urlKelas = route('admin.kelas.index');
    } elseif (Route::has('kelas')) {
        $urlKelas = route('kelas');
    } else {
        $urlKelas = url('/admin/kelas');
    }

    // Guru
    if (Route::has('admin.guru.index')) {
        $urlGuru = route('admin.guru.index');
    } elseif (Route::has('guru')) {
        $urlGuru = route('guru');
    } else {
        $urlGuru = url('/admin/guru');
    }

    // Siswa
    if (Route::has('admin.siswa.index')) {
        $urlSiswa = route('admin.siswa.index');
    } elseif (Route::has('siswa')) {
        $urlSiswa = route('siswa');
    } else {
        $urlSiswa = url('/admin/siswa');
    }

    // Mapel
    if (Route::has('admin.mapel.index')) {
        $urlMapel = route('admin.mapel.index');
    } elseif (Route::has('mapel')) {
        $urlMapel = route('mapel');
    } else {
        $urlMapel = url('/admin/mapel');
    }

    // Jadwal
    if (Route::has('admin.jadwal.index')) {
        $urlJadwal = route('admin.jadwal.index');
    } elseif (Route::has('jadwal')) {
        $urlJadwal = route('jadwal');
    } else {
        $urlJadwal = url('/admin/jadwal');
    }

    // helper sederhana untuk memberi class aktif (Tailwind)
    $activeClass = function ($pattern) {
        return request()->is($pattern) ? 'bg-slate-700' : '';
    };
@endphp

<aside class="w-64 bg-slate-800 text-slate-100 flex flex-col">
  <div class="p-6 border-b border-slate-700">
    <!-- Logo di atas -->
    <div class="flex items-center gap-3">
      <div>
        <div class="font-bold text-lg">SMK Assalam Samarang</div>
      </div>
    </div>
  </div>

  <nav class="p-4 flex-1 overflow-auto">
    <div class="text-slate-300 uppercase text-xs mb-3">Dashboard</div>
    <ul class="space-y-1 mb-6">
      <li>
        <a href="{{ $urlAdminDashboard }}"
           class="flex items-center gap-3 px-3 py-2 rounded hover:bg-slate-700 {{ $activeClass('admin/dashboard*') }}">
          <span class="text-sm">Dashboard</span>
        </a>
      </li>
    </ul>

    <div class="text-slate-300 uppercase text-xs mb-3">Data</div>
    <ul class="space-y-1">
      <li>
        <a href="{{ $urlJurusan }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-slate-700 {{ $activeClass('admin/jurusan*') }}">
          <span class="text-sm">Jurusan</span>
        </a>
      </li>

      {{-- (Baris asli kamu—disimpan sebagai komentar agar tidak hilang)
      <ul class="space-y-2">
        <li>
          <a href="{{ route('jurusan') }}"
             class="block px-4 py-2 rounded hover:bg-gray-200">
             Jurusan
          </a>
        </li>
      </ul>
      --}}

      <li>
        <a href="{{ $urlKelas }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-slate-700 {{ $activeClass('admin/kelas*') }}">
          <span class="text-sm">Kelas</span>
        </a>
      </li>

      <li>
        <a href="{{ $urlGuru }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-slate-700 {{ $activeClass('admin/guru*') }}">
          <span class="text-sm">Guru</span>
        </a>
      </li>

      <li>
        <a href="{{ $urlSiswa }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-slate-700 {{ $activeClass('admin/siswa*') }}">
          <span class="text-sm">Siswa</span>
        </a>
      </li>

      <li>
        <a href="{{ $urlMapel }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-slate-700 {{ $activeClass('admin/mapel*') }}">
          <span class="text-sm">Mata Pelajaran</span>
        </a>
      </li>

      <li class="mt-4">
        <a href="{{ $urlJadwal }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-slate-700 {{ $activeClass('admin/jadwal*') }}">
          <span class="text-sm">Jadwal</span>
        </a>
      </li>
    </ul>
  </nav>

  <div class="p-4 border-t border-slate-700 text-xs text-slate-400">
    © {{ date('Y') }} Sekolah
  </div>
</aside>
