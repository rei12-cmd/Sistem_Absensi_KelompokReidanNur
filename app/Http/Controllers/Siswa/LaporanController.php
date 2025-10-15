<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;

class LaporanController extends Controller
{
    public function index()
    {
        $siswa_id = auth()->user()->siswa->id;

        $absensi = Absensi::with([
            'jadwal.guruMapelKelas.mataPelajaran',
            'jadwal.guruMapelKelas.guru',
            'jadwal'
        ])
        ->where('siswa_id', $siswa_id)
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

        return view('laporan.siswa', compact('rekap'));
    }
}
