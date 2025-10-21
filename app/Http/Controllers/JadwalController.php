<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Guru;
use App\Models\GuruMapelKelas;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
        ])->get();

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
        // Ambil relasi dengan Eloquent (pakai get)
        $relations = GuruMapelKelas::with(['guru', 'mataPelajaran', 'kelas'])
            ->orderBy('id', 'asc')
            ->get();

        Log::info('RELATIONS COUNT: ' . $relations->count());

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
                    'k.nama as kelas_nama'
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

    public function edit(Jadwal $jadwal)
    {
        $jadwal->jam_mulai = str_replace('.', ':', substr($jadwal->jam_mulai, 0, 5));
        $jadwal->jam_selesai = str_replace('.', ':', substr($jadwal->jam_selesai, 0, 5));

        $relations = GuruMapelKelas::with(['guru','mataPelajaran','kelas'])->get();

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

    /**
     * Menampilkan halaman daftar relasi Guru-Mapel-Kelas (Atur Mengajar)
     * Semua memakai get() dan ordering aman (cek kolom tersedia)
     */
    public function aturIndex(): View
    {
        // Ambil relasi (urutkan berdasarkan id agar stabil)
        $relations = GuruMapelKelas::with(['guru', 'mataPelajaran', 'kelas'])
            ->orderBy('id', 'asc')
            ->get();

        // ===== Guru: tentukan kolom untuk ordering secara aman =====
        if (Schema::hasTable('guru')) {
            if (Schema::hasColumn('guru', 'nama')) {
                $guruOrder = 'nama';
            } elseif (Schema::hasColumn('guru', 'nama_guru')) {
                $guruOrder = 'nama_guru';
            } else {
                $guruOrder = 'id';
            }
        } else {
            $guruOrder = 'id';
        }
        $gurus = Guru::orderBy($guruOrder)->get();

        // ===== Kelas: tentukan kolom untuk ordering secara aman =====
        if (Schema::hasTable('kelas')) {
            if (Schema::hasColumn('kelas', 'nama')) {
                $kelasOrder = 'nama';
            } elseif (Schema::hasColumn('kelas', 'nama_kelas')) {
                $kelasOrder = 'nama_kelas';
            } elseif (Schema::hasColumn('kelas', 'kode')) {
                $kelasOrder = 'kode';
            } else {
                $kelasOrder = 'id';
            }
        } else {
            $kelasOrder = 'id';
        }
        $kelas = Kelas::orderBy($kelasOrder)->get();

        // ===== MataPelajaran: tentukan kolom untuk ordering secara aman =====
        if (Schema::hasTable('mata_pelajaran')) {
            if (Schema::hasColumn('mata_pelajaran', 'nama')) {
                $mapelOrder = 'nama';
            } elseif (Schema::hasColumn('mata_pelajaran', 'nama_mapel')) {
                $mapelOrder = 'nama_mapel';
            } else {
                $mapelOrder = 'id';
            }
        } else {
            $mapelOrder = 'id';
        }
        $mapels = MataPelajaran::orderBy($mapelOrder)->get();

        return view('jadwal.atur_mengajar', compact('relations', 'gurus', 'kelas', 'mapels'));
    }

    /**
     * Simpan relasi baru (Atur Mengajar)
     */
    public function aturStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'guru_id' => 'required|exists:guru,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        try {
            $exists = GuruMapelKelas::where('guru_id', $validated['guru_id'])
                ->where('mata_pelajaran_id', $validated['mata_pelajaran_id'])
                ->where('kelas_id', $validated['kelas_id'])
                ->exists();

            if ($exists) {
                return redirect()->route('atur-mengajar.index')->with('error', 'Relasi sudah ada.');
            }

            GuruMapelKelas::create([
                'guru_id' => $validated['guru_id'],
                'mata_pelajaran_id' => $validated['mata_pelajaran_id'],
                'kelas_id' => $validated['kelas_id'],
            ]);

            return redirect()->route('atur-mengajar.index')->with('success', 'Relasi berhasil ditambahkan.');
        } catch (\Throwable $e) {
            Log::error('Error saat menyimpan relasi: ' . $e->getMessage());
            return redirect()->route('atur-mengajar.index')->with('error', 'Terjadi kesalahan saat menyimpan relasi.');
        }
    }

    /**
     * Hapus relasi Guru-Mapel-Kelas (Atur Mengajar)
     */
    public function aturDestroy($id): RedirectResponse
    {
        try {
            $rel = GuruMapelKelas::find($id);
            if ($rel) {
                $rel->delete();
                return redirect()->route('atur-mengajar.index')->with('success', 'Relasi berhasil dihapus.');
            }
            return redirect()->route('atur-mengajar.index')->with('error', 'Relasi tidak ditemukan.');
        } catch (\Throwable $e) {
            Log::error('Error saat menghapus relasi: ' . $e->getMessage());
            return redirect()->route('atur-mengajar.index')->with('error', 'Terjadi kesalahan saat menghapus relasi.');
        }
    }
}
