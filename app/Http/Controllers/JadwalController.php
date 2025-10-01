<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Guru;
use App\Models\GuruMapelKelas;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class JadwalController extends Controller
{
    /**
     * Menampilkan semua jadwal (untuk admin)
     */
    public function index(): View
    {
        $jadwals = Jadwal::with([
            'guruMapelKelas.guru',
            'guruMapelKelas.mataPelajaran',
            'guruMapelKelas.kelas',
        ])
        ->orderBy('hari')
        ->orderBy('jam_mulai')
        ->paginate(15);

        return view('jadwal.index', compact('jadwals'));
    }

    /**
     * Menampilkan jadwal milik guru yang sedang login
     */
    public function jadwalsaya(): View
    {
        $user = Auth::user();
        $guru = $user ? Guru::where('user_id', $user->id)->first() : null;

        if (! $guru) {
            $jadwalsaya = collect([]);
            return view('jadwal.jadwalsaya', compact('jadwalsaya', 'guru'));
        }

        $jadwalsaya = Jadwal::with([
            'guruMapelKelas.guru',
            'guruMapelKelas.mataPelajaran',
            'guruMapelKelas.kelas',
        ])
        ->whereHas('guruMapelKelas', function ($q) use ($guru) {
            $q->where('guru_id', $guru->id);
        })
        ->orderBy('hari')
        ->orderBy('jam_mulai')
        ->get();

        return view('jadwal.jadwalsaya', compact('jadwalsaya', 'guru'));
    }

    /**
     * Form tambah jadwal
     */
    public function create(): View
    {
        // Ambil relasi dengan Eloquent
        $relations = GuruMapelKelas::with(['guru', 'mataPelajaran', 'kelas'])->get();

        // Log ke file laravel.log
        Log::info('RELATIONS COUNT: ' . $relations->count());
        Log::info('RELATIONS DUMP: ' . json_encode($relations->toArray()));

        // Jika kosong, fallback join manual
        if ($relations->isEmpty()) {
            $rows = DB::table('guru_mapel_kelas as gmk')
                ->join('guru as g', 'gmk.guru_id', '=', 'g.id')
                ->join('mata_pelajaran as mp', 'gmk.mata_pelajaran_id', '=', 'mp.id')
                ->join('kelas as k', 'gmk.kelas_id', '=', 'k.id')
                ->select(
                    'gmk.id as id',
                    'g.nama as guru_nama',
                    'mp.nama as mapel_nama',
                    'k.nama_kelas as kelas_nama'
                )
                ->get();

            $relations = $rows->map(function ($r) {
                return (object) [
                    'id' => $r->id,
                    'guru' => (object) ['nama' => $r->guru_nama],
                    'mataPelajaran' => (object) ['nama' => $r->mapel_nama],
                    'kelas' => (object) ['nama_kelas' => $r->kelas_nama],
                ];
            });

            Log::info('FALLBACK RELATIONS COUNT: ' . $relations->count());
            Log::info('FALLBACK RELATIONS DUMP: ' . json_encode($relations->toArray()));
        }

        return view('jadwal.create', compact('relations'));
    }

    /**
     * Simpan jadwal baru
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'guru_mapel_kelas_id' => 'required|exists:guru_mapel_kelas,id',
            'hari' => ['required', 'string'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'ruang' => ['nullable', 'string', 'max:50'],
        ]);

        Jadwal::create($validated);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    /**
     * Form edit jadwal
     */
    public function edit(Jadwal $jadwal): View
    {
        $relations = GuruMapelKelas::with(['guru', 'mataPelajaran', 'kelas'])->get();
        return view('jadwal.edit', compact('jadwal', 'relations'));
    }

    /**
     * Update jadwal
     */
    public function update(Request $request, Jadwal $jadwal): RedirectResponse
    {
        $validated = $request->validate([
            'guru_mapel_kelas_id' => 'required|exists:guru_mapel_kelas,id',
            'hari' => ['required', 'string'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'ruang' => ['nullable', 'string', 'max:50'],
        ]);

        $jadwal->update($validated);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    /**
     * Hapus jadwal
     */
    public function destroy(Jadwal $jadwal): RedirectResponse
    {
        $jadwal->delete();
        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }
}
