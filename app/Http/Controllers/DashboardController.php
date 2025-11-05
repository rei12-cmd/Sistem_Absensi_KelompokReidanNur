<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Wali;
use App\Models\Kelas;
use App\Models\Jurusan;
use App\Models\MataPelajaran;
use App\Models\Jadwal;
use App\Models\Absensi;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            $data = [
                'totalSiswa' => Siswa::count(),
                'totalGuru' => Guru::count(),
                'totalWali' => Wali::count(),
                'totalKelas' => Kelas::count(),
                'totalJurusan' => Jurusan::count(),
                'totalMapel' => MataPelajaran::count(),
                'totalRole' => Role::count(),
            ];

            return view('dashboard.admin', compact('data'));
        }

        if ($user->hasRole('guru')) {
            $guru = Guru::where('user_id', $user->id)->first();
            $jadwal = Jadwal::with(['guruMapelKelas.kelas', 'guruMapelKelas.mataPelajaran'])
                ->whereHas('guruMapelKelas', fn($q) => $q->where('guru_id', $guru->id))
                ->get();

            return view('dashboard.guru', compact('guru', 'jadwal'));
        }

        if ($user->hasRole('siswa')) {
            $siswa = Siswa::with(['kelas', 'jurusan'])->where('user_id', $user->id)->first();
            $absensi = Absensi::where('siswa_id', $siswa->id)->latest()->take(5)->get();

            return view('dashboard.siswa', compact('siswa', 'absensi'));
        }

        if ($user->hasRole('wali')) {
            $wali = Wali::where('user_id', $user->id)->first();
            $siswaList = $wali->siswa()->with(['kelas', 'jurusan'])->get();

            return view('dashboard.wali', compact('wali', 'siswaList'));
        }

        abort(403);
    }
}
