<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Jurusan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class SiswaController extends Controller
{
    /**
     * Tampilkan daftar semua siswa
     */
    public function index(): View
    {
        $siswas = Siswa::with(['kelas', 'jurusan', 'user'])
            ->orderBy('id', 'asc') // urut berdasarkan id agar item baru muncul di akhir
            ->get();

        return view('siswa.index', compact('siswas'));
    }

    /**
     * Tampilkan form tambah siswa baru
     */
    public function create(): View
    {
        // Ambil daftar kelas & jurusan (pakai get())
        $kelas = Kelas::orderBy('id', 'asc')->get();
        $jurusan = Jurusan::orderBy('id', 'asc')->get();

        // Ambil daftar users — beberapa project memakai kolom name/username/email.
        // Coba beberapa opsi agar tidak error ketika kolom tidak ada.
        $users = collect();

        // Daftar kandidat kolom yang mungkin ada
        $candidates = ['name', 'username', 'email'];

        foreach ($candidates as $col) {
            try {
                // coba orderBy kolom yang tersedia
                $users = User::orderBy($col, 'asc')->get();
                // jika query berhasil, pakai hasilnya dan hentikan loop
                break;
            } catch (QueryException $e) {
                // kolom tidak ada — catat dan lanjutkan ke kolom berikutnya
                Log::warning("users orderBy failed for column '{$col}': " . $e->getMessage());
                continue;
            } catch (\Throwable $t) {
                Log::error("Unexpected error when fetching users ordered by {$col}: " . $t->getMessage());
                continue;
            }
        }

        // Jika semua candidate gagal, fallback ke orderBy id
        if ($users->isEmpty()) {
            try {
                $users = User::orderBy('id', 'asc')->get();
            } catch (\Throwable $t) {
                Log::error('Failed to fetch users fallback by id: ' . $t->getMessage());
                $users = collect();
            }
        }

        return view('siswa.create', compact('kelas', 'jurusan', 'users'));
    }

    /**
     * Simpan siswa baru ke database
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'kelas_id' => 'required|exists:kelas,id',
            'jurusan_id' => 'required|exists:jurusan,id',
            'nis' => 'required|string|max:50|unique:siswa,nis',
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string|max:1000',
        ]);

        try {
            Siswa::create($validated);
            return redirect()->route('siswa.index')->with('success', 'Siswa berhasil ditambahkan.');
        } catch (\Throwable $e) {
            Log::error('Gagal menyimpan siswa: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan siswa.');
        }
    }

    /**
     * Form edit siswa
     */
    public function edit(Siswa $siswa): View
    {
        $kelas = Kelas::orderBy('id', 'asc')->get();
        $jurusan = Jurusan::orderBy('id', 'asc')->get();

        // Ambil users sama robust seperti di create()
        $users = collect();
        $candidates = ['name', 'username', 'email'];
        foreach ($candidates as $col) {
            try {
                $users = User::orderBy($col, 'asc')->get();
                break;
            } catch (QueryException $e) {
                Log::warning("users orderBy failed for column '{$col}' (edit): " . $e->getMessage());
                continue;
            } catch (\Throwable $t) {
                Log::error("Unexpected error when fetching users ordered by {$col} (edit): " . $t->getMessage());
                continue;
            }
        }
        if ($users->isEmpty()) {
            try {
                $users = User::orderBy('id', 'asc')->get();
            } catch (\Throwable $t) {
                Log::error('Failed to fetch users fallback by id (edit): ' . $t->getMessage());
                $users = collect();
            }
        }

        return view('siswa.edit', compact('siswa', 'kelas', 'jurusan', 'users'));
    }

    /**
     * Update siswa
     */
    public function update(Request $request, Siswa $siswa): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'kelas_id' => 'required|exists:kelas,id',
            'jurusan_id' => 'required|exists:jurusan,id',
            'nis' => 'required|string|max:50|unique:siswa,nis,' . $siswa->id,
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string|max:1000',
        ]);

        try {
            $siswa->update($validated);
            return redirect()->route('siswa.index')->with('success', 'Siswa berhasil diperbarui.');
        } catch (\Throwable $e) {
            Log::error('Gagal memperbarui siswa (id='.$siswa->id.'): ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui siswa.');
        }
    }

    /**
     * Hapus siswa
     */
    public function destroy(Siswa $siswa): RedirectResponse
    {
        try {
            $siswa->delete();
            return redirect()->route('siswa.index')->with('success', 'Siswa berhasil dihapus.');
        } catch (\Throwable $e) {
            Log::error('Gagal menghapus siswa (id='.$siswa->id.'): ' . $e->getMessage());
            return redirect()->route('siswa.index')->with('error', 'Terjadi kesalahan saat menghapus siswa.');
        }
    }
}
