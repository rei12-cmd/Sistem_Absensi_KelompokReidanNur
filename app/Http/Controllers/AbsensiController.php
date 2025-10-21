<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $guru = Guru::where('user_id', $user->id)->first();

        if (!$guru) {
            return redirect()->back()->with('error', 'Data guru tidak ditemukan untuk user ini.');
        }

        $jadwals = Jadwal::with([
            'guruMapelKelas.mataPelajaran',
            'guruMapelKelas.kelas',
        ])
            ->whereHas('guruMapelKelas', function ($q) use ($guru) {
                $q->where('guru_id', $guru->id);
            })
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();

        return view('absensi.index', compact('jadwals'));
    }

    public function show($id)
    {
        $jadwal = Jadwal::with([
            'guruMapelKelas.mataPelajaran',
            'guruMapelKelas.kelas',
            'guruMapelKelas.guru',
        ])->findOrFail($id);

        $kelasId = $jadwal->guruMapelKelas->kelas->id;
        $siswas = Siswa::where('kelas_id', $kelasId)
            ->orderBy('nama', 'asc')
            ->paginate(10);

        $now = Carbon::now();
        $hariSekarang = strtolower($now->translatedFormat('l')); 
        $hariJadwal = strtolower($jadwal->hari);

        $jamMulai = Carbon::parse($jadwal->jam_mulai);
        $jamSelesai = Carbon::parse($jadwal->jam_selesai);

        $bisaAbsensi = ($hariSekarang == $hariJadwal) && $now->between($jamMulai, $jamSelesai);

        return view('absensi.form', compact('jadwal', 'siswas', 'bisaAbsensi'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwal,id',
            'status' => 'required|array',
        ]);

        $jadwalId = $request->jadwal_id;
        $tanggal = now()->toDateString(); 

        DB::beginTransaction();
        try {
            foreach ($request->status as $siswaId => $status) {
                if (!$status) continue;

                Absensi::updateOrCreate(
                    [
                        'jadwal_id' => $jadwalId,
                        'siswa_id' => $siswaId,
                        'tanggal' => $tanggal,
                    ],
                    [
                        'status' => $status,
                        'keterangan' => null,
                    ]
                );
            }
            DB::commit();
            return redirect()->route('absensi.index')->with('success', 'Absensi berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
