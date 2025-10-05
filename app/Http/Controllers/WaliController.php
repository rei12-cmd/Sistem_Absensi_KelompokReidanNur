<?php

namespace App\Http\Controllers;

use App\Models\Wali;
use App\Models\User;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class WaliController extends Controller
{
    /**
     * Tampilkan daftar wali (GET, pakai get())
     */
    public function index(): View
    {
        // Ambil semua wali beserta relasi user dan siswa (pakai get())
        $walis = Wali::with(['user', 'siswa'])
            ->orderBy('id', 'asc') // urut berdasarkan id agar konsisten
            ->get();

        return view('wali.index', compact('walis'));
    }

    /**
     * Form create wali (kirim users dan daftar siswa)
     */
    public function create(): View
    {
        $siswas = Siswa::orderBy('id', 'asc')->get();

        // Ambil users robust (coba beberapa kolom jika name tidak ada)
        $users = collect();
        $candidates = ['name', 'username', 'email'];
        foreach ($candidates as $col) {
            try {
                $users = User::orderBy($col, 'asc')->get();
                break;
            } catch (QueryException $e) {
                Log::warning("users orderBy failed for column '{$col}' (wali create): " . $e->getMessage());
                continue;
            } catch (\Throwable $t) {
                Log::error("Error fetching users in wali.create: " . $t->getMessage());
                continue;
            }
        }
        if ($users->isEmpty()) {
            try { $users = User::orderBy('id','asc')->get(); } catch (\Throwable $t) { $users = collect(); }
        }

        return view('wali.create', compact('users', 'siswas'));
    }

    /**
     * Simpan wali baru + relasi siswa
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'nama' => 'required|string|max:255',
            'telepon' => 'nullable|string|max:50',
            'alamat' => 'nullable|string|max:1000',
            'siswa_ids' => 'nullable|array',
            'siswa_ids.*' => 'exists:siswa,id',
        ]);

        try {
            $wali = Wali::create([
                'user_id' => $validated['user_id'],
                'nama' => $validated['nama'],
                'telepon' => $validated['telepon'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
            ]);

            if (!empty($validated['siswa_ids'])) {
                $wali->siswa()->sync($validated['siswa_ids']);
            }

            return redirect()->route('wali.index')->with('success', 'Wali berhasil ditambahkan.');
        } catch (\Throwable $e) {
            Log::error('Gagal menyimpan wali: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan wali.');
        }
    }

    /**
     * Form edit wali + relasi siswa
     */
    public function edit(Wali $wali): View
    {
        $siswas = Siswa::orderBy('id', 'asc')->get();

        // Ambil users robust
        $users = collect();
        $candidates = ['name', 'username', 'email'];
        foreach ($candidates as $col) {
            try {
                $users = User::orderBy($col, 'asc')->get();
                break;
            } catch (QueryException $e) {
                Log::warning("users orderBy failed for column '{$col}' (wali edit): " . $e->getMessage());
                continue;
            } catch (\Throwable $t) {
                Log::error("Error fetching users in wali.edit: " . $t->getMessage());
                continue;
            }
        }
        if ($users->isEmpty()) {
            try { $users = User::orderBy('id','asc')->get(); } catch (\Throwable $t) { $users = collect(); }
        }

        // ambil id siswa yang sudah terkait agar bisa preselect
        $selectedSiswas = $wali->siswa()->pluck('siswa.id')->toArray();

        return view('wali.edit', compact('wali', 'users', 'siswas', 'selectedSiswas'));
    }

    /**
     * Update wali & relasi siswa
     */
    public function update(Request $request, Wali $wali): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'nama' => 'required|string|max:255',
            'telepon' => 'nullable|string|max:50',
            'alamat' => 'nullable|string|max:1000',
            'siswa_ids' => 'nullable|array',
            'siswa_ids.*' => 'exists:siswa,id',
        ]);

        try {
            $wali->update([
                'user_id' => $validated['user_id'],
                'nama' => $validated['nama'],
                'telepon' => $validated['telepon'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
            ]);

            $wali->siswa()->sync($validated['siswa_ids'] ?? []);

            return redirect()->route('wali.index')->with('success', 'Wali berhasil diperbarui.');
        } catch (\Throwable $e) {
            Log::error('Gagal memperbarui wali (id='.$wali->id.'): ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui wali.');
        }
    }

    /**
     * Hapus wali (detach relasi terlebih dahulu)
     */
    public function destroy(Wali $wali): RedirectResponse
    {
        try {
            $wali->siswa()->detach();
            $wali->delete();
            return redirect()->route('wali.index')->with('success', 'Wali berhasil dihapus.');
        } catch (\Throwable $e) {
            Log::error('Gagal menghapus wali (id='.$wali->id.'): ' . $e->getMessage());
            return redirect()->route('wali.index')->with('error', 'Terjadi kesalahan saat menghapus wali.');
        }
    }
}
