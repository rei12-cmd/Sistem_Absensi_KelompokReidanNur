<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// Models
use App\Models\Siswa;
use App\Models\Absensi;
use App\Models\GuruMapelKelas;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Jadwal;

class LaporanAbsensiController extends Controller
{
    /**
     * 1) Halaman index: tampilkan kelas + mata_pelajaran yang diajar guru.
     */
    public function index(): View
    {
        $user = Auth::user();

        $guruId = $user->guru->id ?? $user->guru_id ?? null;

        if (!$guruId) {
            $list = collect();
            return view('laporan.index', compact('list'));
        }

        // Ambil daftar kelas dan mapel dari tabel relasi guru_mapel_kelas
        $rows = DB::table('guru_mapel_kelas')
            ->join('kelas', 'guru_mapel_kelas.kelas_id', '=', 'kelas.id')
            ->join('mata_pelajaran', 'guru_mapel_kelas.mata_pelajaran_id', '=', 'mata_pelajaran.id')
            ->where('guru_mapel_kelas.guru_id', $guruId)
            ->select(
                'guru_mapel_kelas.id as id',
                'kelas.id as kelas_id',
                'kelas.nama as kelas_nama',
                'mata_pelajaran.id as mapel_id',
                'mata_pelajaran.nama as mapel_nama'
            )
            ->orderBy('kelas.nama')
            ->get();

        $list = $rows->map(function ($r) {
            return (object)[
                'id' => $r->id,
                'kelas' => (object)['id' => $r->kelas_id, 'nama' => $r->kelas_nama],
                'mapel' => (object)['id' => $r->mapel_id, 'nama' => $r->mapel_nama],
            ];
        });

        return view('laporan.index', compact('list'));
    }

    /**
     * 2) Tampilkan daftar siswa untuk kelas dan mata pelajaran tertentu.
     */
    public function kelasDetail($kelasId, $mapelId): View
    {
        $kelas = Kelas::findOrFail($kelasId);
        $mapel = MataPelajaran::findOrFail($mapelId);

        // Ambil siswa yang ada di kelas tersebut
        $siswas = Siswa::where('kelas_id', $kelas->id)->get();

        // Ambil semua jadwal dari guru_mapel_kelas untuk kombinasi kelas & mapel ini
        $jadwalIds = Jadwal::whereHas('guruMapelKelas', function ($q) use ($kelasId, $mapelId) {
            $q->where('kelas_id', $kelasId)
              ->where('mata_pelajaran_id', $mapelId);
        })->pluck('id');

        // Rekap absensi per siswa berdasarkan jadwal_id
        $siswas = $siswas->map(function ($siswa) use ($jadwalIds) {
            $total = Absensi::where('siswa_id', $siswa->id)
                ->whereIn('jadwal_id', $jadwalIds)
                ->count();

            $hadir = Absensi::where('siswa_id', $siswa->id)
                ->whereIn('jadwal_id', $jadwalIds)
                ->whereIn('status', ['H', 'hadir'])
                ->count();

            $persentase = $total > 0 ? round(($hadir / $total) * 100, 2) : 0;

            $siswa->rekap = [
                'total' => $total,
                'hadir' => $hadir,
                'persentase' => $persentase,
            ];

            return $siswa;
        });

        return view('laporan.kelas_detail', compact('kelas', 'mapel', 'siswas'));
    }

    /**
     * 3) Tampilkan rekap absensi per siswa pada mata pelajaran tertentu.
     */
    public function siswaRekap($siswaId, $mapelId): View
    {
        $siswa = Siswa::findOrFail($siswaId);
        $mapel = MataPelajaran::findOrFail($mapelId);

        // Ambil semua jadwal dari guru_mapel_kelas untuk mapel ini
        $jadwalIds = Jadwal::whereHas('guruMapelKelas', function ($q) use ($mapelId) {
            $q->where('mata_pelajaran_id', $mapelId);
        })->pluck('id');

        // Ambil absensi berdasarkan jadwal mapel
        $absensiList = Absensi::where('siswa_id', $siswa->id)
            ->whereIn('jadwal_id', $jadwalIds)
            ->orderBy('tanggal', 'asc')
            ->get();

        $total = $absensiList->count();
        $hadir = $absensiList->whereIn('status', ['H', 'hadir'])->count();
        $tidakHadir = $total - $hadir;
        $persentase = $total > 0 ? round(($hadir / $total) * 100, 2) : 0;

        return view('laporan.siswa_rekap', compact(
            'siswa',
            'mapel',
            'absensiList',
            'total',
            'hadir',
            'tidakHadir',
            'persentase'
        ));
    }

    /**
     * 4) Halaman "Absensi Saya" (khusus siswa)
     */
    public function absensisaya(): View
    {
        $user = Auth::user();
        $siswa = $user->siswa ?? null;

        if (!$siswa) {
            return view('laporan.absensisaya', ['absensiList' => collect()]);
        }

        // Ambil absensi via jadwal & mapel
        $absensiList = Absensi::where('siswa_id', $siswa->id)
            ->with(['jadwal.guruMapelKelas.mataPelajaran', 'jadwal.guruMapelKelas.kelas'])
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('laporan.absensisaya', compact('absensiList'));
    }

    /**
     * 5) Halaman "Absensi Anak Saya" (khusus wali)
     */
    public function absensianaksaya(): View
    {
        $user = Auth::user();
        $wali = $user->wali ?? null;

        if (!$wali) {
            return view('laporan.absensianaksaya', ['rekap' => collect()]);
        }

        $anak = $wali->siswa ?? null;
        if (!$anak) {
            return view('laporan.absensianaksaya', ['rekap' => collect()]);
        }

        $rekap = DB::table('absensi')
            ->join('jadwal', 'absensi.jadwal_id', '=', 'jadwal.id')
            ->join('guru_mapel_kelas', 'jadwal.guru_mapel_kelas_id', '=', 'guru_mapel_kelas.id')
            ->join('mata_pelajaran', 'guru_mapel_kelas.mata_pelajaran_id', '=', 'mata_pelajaran.id')
            ->select(
                'mata_pelajaran.id as mapel_id',
                'mata_pelajaran.nama as mapel_nama',
                DB::raw('COUNT(absensi.id) as total'),
                DB::raw("SUM(CASE WHEN absensi.status IN ('H', 'hadir') THEN 1 ELSE 0 END) as hadir")
            )
            ->where('absensi.siswa_id', $anak->id)
            ->groupBy('mata_pelajaran.id', 'mata_pelajaran.nama')
            ->get()
            ->map(function ($r) {
                $r->persentase = $r->total > 0 ? round(($r->hadir / $r->total) * 100, 2) : 0;
                return $r;
            });

        return view('laporan.absensianaksaya', compact('rekap'));
    }

    /**
     * 6) Export laporan ke CSV / PDF
     */
    public function export(Request $request)
    {
        $guruId = Auth::user()->guru->id ?? null;
        if (!$guruId) {
            return back()->with('error', 'Guru tidak ditemukan.');
        }

        $data = DB::table('guru_mapel_kelas')
            ->join('kelas', 'guru_mapel_kelas.kelas_id', '=', 'kelas.id')
            ->join('mata_pelajaran', 'guru_mapel_kelas.mata_pelajaran_id', '=', 'mata_pelajaran.id')
            ->where('guru_mapel_kelas.guru_id', $guruId)
            ->select('kelas.nama as kelas', 'mata_pelajaran.nama as mapel')
            ->get();

        $filename = 'laporan_absensi_' . date('Ymd_His') . '.csv';
        $handle = fopen($filename, 'w+');
        fputcsv($handle, ['Kelas', 'Mata Pelajaran']);

        foreach ($data as $row) {
            fputcsv($handle, [$row->kelas, $row->mapel]);
        }

        fclose($handle);

        return response()->download($filename)->deleteFileAfterSend(true);
    }
}
