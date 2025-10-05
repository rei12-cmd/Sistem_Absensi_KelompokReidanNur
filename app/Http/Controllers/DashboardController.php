<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Database\QueryException;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard.
     * Mengirimkan data statistik absensi dan data chart.
     *
     * NOTE:
     * - Sesuaikan nama tabel/kolom jika di database kamu beda.
     * - File ini aman: jika query gagal (tabel belum ada), view tetap akan menerima default values.
     */
    public function index(): View
    {
        $today = Carbon::today()->toDateString();

        // Default values (jika query gagal)
        $totalSiswa = 0;
        $hadirToday = 0;
        $izinSakitToday = 0;
        $totalAbsensi = 0;
        $pengumumans = collect();
        $chartLabels = collect();
        $chartValues = collect();

        try {
            // Hitung total siswa
            $totalSiswa = DB::table('siswa')->count();

            // Kehadiran hari ini (sesuaikan kolom 'tanggal' & nilai 'Hadir' jika berbeda)
            $hadirToday = DB::table('absensi')
                ->whereDate('tanggal', $today)   // kalau pakai created_at -> whereDate('created_at', $today)
                ->where('status', 'Hadir')
                ->count();

            // Izin / Sakit hari ini
            $izinSakitToday = DB::table('absensi')
                ->whereDate('tanggal', $today)
                ->whereIn('status', ['Izin', 'Sakit'])
                ->count();

            // Total record absensi
            $totalAbsensi = DB::table('absensi')->count();

            // Pengumuman terbaru (ambil 3)
            $pengumumans = DB::table('pengumuman')
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();

            // Data chart: jumlah siswa per jurusan
            $jurusanData = DB::table('jurusan')
                ->leftJoin('siswa', 'jurusan.id', '=', 'siswa.jurusan_id')
                ->select('jurusan.nama as nama', DB::raw('COUNT(siswa.id) as total'))
                ->groupBy('jurusan.nama')
                ->get();

            $chartLabels = $jurusanData->pluck('nama');
            $chartValues = $jurusanData->pluck('total');

        } catch (QueryException $e) {
            // Log error agar bisa diperiksa, namun aplikasi tetap tidak crash
            Log::error('Dashboard query error: ' . $e->getMessage());
        }

        return view('dashboard', [
            'totalSiswa' => $totalSiswa,
            'hadirToday' => $hadirToday,
            'izinSakitToday' => $izinSakitToday,
            'totalAbsensi' => $totalAbsensi,
            'pengumumans' => $pengumumans,
            'chartLabels' => $chartLabels,
            'chartValues' => $chartValues,
        ]);
    }
}
