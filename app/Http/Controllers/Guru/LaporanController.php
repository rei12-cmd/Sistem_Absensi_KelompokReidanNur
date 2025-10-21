<?php 
namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GuruMapelKelas;
use App\Models\Absensi;

class LaporanController extends Controller
{
    public function index()
    {
        $guru_id = auth()->user()->guru->id;

        $guruMapelKelas = GuruMapelKelas::with('mataPelajaran','kelas','guru')
            ->where('guru_id', $guru_id)
            ->get();

        return view('laporan.guru.index', compact('guruMapelKelas'));
    }

    public function kelas($id)
    {
        $gmk = GuruMapelKelas::with(['guru', 'kelas.siswa', 'mataPelajaran'])->findOrFail($id);

        $absensi = Absensi::whereHas('jadwal', function ($q) use ($id) {
            $q->where('guru_mapel_kelas_id', $id);
        })->get();

        $siswa = $gmk->kelas->siswa()->paginate(10);

        return view('laporan.guru.kelas', compact('gmk', 'absensi', 'siswa'));
    }

    public function siswa($id)
    {
        $absensi = Absensi::with(['siswa', 'jadwal.guruMapelKelas.mataPelajaran'])
            ->where('siswa_id', $id)
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return view('laporan.guru.siswa', compact('absensi'));
    }

}
