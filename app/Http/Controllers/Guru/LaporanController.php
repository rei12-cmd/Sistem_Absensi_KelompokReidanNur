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
        $gmk = GuruMapelKelas::with('kelas','mataPelajaran','guru')->findOrFail($id);
        $absensi = $gmk->jadwal()->with('absensi.siswa')->get()->flatMap->absensi;

        return view('laporan.guru.kelas', compact('gmk','absensi'));
    }

    public function siswa($id)
    {
        $absensi = Absensi::with('jadwal.guruMapelKelas.mataPelajaran')
            ->where('siswa_id', $id)
            ->whereHas('jadwal.guruMapelKelas', function($q){
                $q->where('guru_id', auth()->user()->guru->id);
            })->get();

        return view('laporan.guru.siswa', compact('absensi'));
    }
}
