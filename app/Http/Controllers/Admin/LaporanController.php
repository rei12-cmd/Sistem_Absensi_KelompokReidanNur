<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\Kelas;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $kelasList = Kelas::all();

        $query = Absensi::with([
            'siswa.kelas',
            'siswa.jurusan',
            'jadwal.guruMapelKelas.mataPelajaran',
            'jadwal.guruMapelKelas.guru'
        ]);

        if ($request->filled('kelas_id')) {
            $query->whereHas('siswa', fn($q) => $q->where('kelas_id', $request->kelas_id));
        }
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        $absensi = $query->orderBy('tanggal', 'desc')->get();

        return view('laporan.admin.index', compact('absensi', 'kelasList'));
    }

    public function siswa($siswa_id)
    {
        $siswa = Siswa::with('kelas', 'jurusan')->findOrFail($siswa_id);

        $absensi = Absensi::with([
            'jadwal.guruMapelKelas.mataPelajaran',
            'jadwal.guruMapelKelas.guru'
        ])
        ->where('siswa_id', $siswa_id)
        ->orderBy('tanggal', 'desc')
        ->get();

        return view('laporan.admin.siswa', compact('siswa', 'absensi'));
    }

}
