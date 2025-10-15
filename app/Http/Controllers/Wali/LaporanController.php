<?php
namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\Wali;
use App\Models\Absensi;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        $wali = auth()->user()->wali;

        $anak = $wali->siswa()->with('kelas', 'jurusan')->get();

        return view('laporan.wali.index', compact('anak'));
    }

    public function show($id)
    {
        $wali = auth()->user()->wali;

        $siswa = $wali->siswa()->findOrFail($id);

        $absensi = Absensi::with([
            'jadwal.guruMapelKelas.mataPelajaran',
            'jadwal.guruMapelKelas.guru',
            'jadwal'
        ])
        ->where('siswa_id', $siswa->id)
        ->get();

        $rekap = $absensi->groupBy(fn($a) => $a->jadwal->guruMapelKelas->mata_pelajaran_id)
            ->map(function ($group) {
                $hadir = $group->where('status', 'H')->count();
                $izin  = $group->where('status', 'I')->count();
                $sakit = $group->where('status', 'S')->count();
                $alpa  = $group->where('status', 'A')->count();
                $total = $group->count();
                $persentase = $total > 0 ? round(($hadir / $total) * 100, 2) : 0;

                return [
                    'mapel'      => $group->first()->jadwal->guruMapelKelas->mataPelajaran->nama,
                    'guru'       => $group->first()->jadwal->guruMapelKelas->guru->nama,
                    'hari'       => $group->first()->jadwal->hari,
                    'jam_mulai'  => $group->first()->jadwal->jam_mulai,
                    'jam_selesai'=> $group->first()->jadwal->jam_selesai,
                    'hadir'      => $hadir,
                    'izin'       => $izin,
                    'sakit'      => $sakit,
                    'alpa'       => $alpa,
                    'persentase' => $persentase,
                ];
            });

        return view('laporan.wali.show', compact('siswa', 'rekap'));
    }
}
