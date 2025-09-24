@extends('layouts.admin')

@section('title','Dashboard Admin')

@section('content')
  <div class="max-w-7xl mx-auto">
    <div class="bg-white p-6 rounded-lg shadow-sm">
      <h2 class="text-2xl font-semibold mb-4">Rekap Data</h2>

      <div class="grid grid-cols-3 gap-4">
        <div class="p-4 border rounded">
          <div class="text-sm text-slate-500">Jurusan</div>
          <div class="text-2xl font-bold">{{ $counts['jurusan'] ?? 0 }}</div>
        </div>
        <div class="p-4 border rounded">
          <div class="text-sm text-slate-500">Kelas</div>
          <div class="text-2xl font-bold">{{ $counts['kelas'] ?? 0 }}</div>
        </div>
        <div class="p-4 border rounded">
          <div class="text-sm text-slate-500">Guru</div>
          <div class="text-2xl font-bold">{{ $counts['guru'] ?? 0 }}</div>
        </div>

        <div class="p-4 border rounded">
          <div class="text-sm text-slate-500">Siswa</div>
          <div class="text-2xl font-bold">{{ $counts['siswa'] ?? 0 }}</div>
        </div>
        <div class="p-4 border rounded">
          <div class="text-sm text-slate-500">Mata Pelajaran</div>
          <div class="text-2xl font-bold">{{ $counts['mapel'] ?? 0 }}</div>
        </div>
        <div class="p-4 border rounded">
          <div class="text-sm text-slate-500">Jadwal</div>
          <div class="text-2xl font-bold">{{ $counts['jadwal'] ?? 0 }}</div>
        </div>
      </div>
    </div>
  </div>
@endsection
