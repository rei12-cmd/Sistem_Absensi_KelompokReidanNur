<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Jurusan;
use Illuminate\Support\Facades\Log;

class JurusanController extends Controller
{
    /**
     * Menampilkan daftar semua jurusan.
     */
    public function index(): View
    {
        // Ambil semua data jurusan (tanpa orderBy)
        $jurusan = Jurusan::get();

        return view('jurusan.index', compact('jurusan'));
    }

    /**
     * Menampilkan form untuk membuat jurusan baru.
     */
    public function create(): View
    {
        return view('jurusan.create');
    }

    /**
     * Menyimpan data jurusan baru yang dikirim dari form.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama' => 'required|unique:jurusan,nama|max:255',
        ]);

        try {
            Jurusan::create([
                'nama' => $request->nama,
            ]);

            return redirect()
                ->route('jurusan.index')
                ->with('success', 'Data Jurusan berhasil ditambahkan!');
        } catch (\Throwable $e) {
            Log::error('Gagal menyimpan jurusan: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data jurusan.');
        }
    }

    /**
     * Menampilkan form untuk mengedit jurusan tertentu.
     */
    public function edit(Jurusan $jurusan): View
    {
        return view('jurusan.edit', compact('jurusan'));
    }

    /**
     * Memperbarui data jurusan di database.
     */
    public function update(Request $request, Jurusan $jurusan): RedirectResponse
    {
        $request->validate([
            'nama' => 'required|unique:jurusan,nama,' . $jurusan->id . '|max:255',
        ]);

        try {
            $jurusan->update([
                'nama' => $request->nama,
            ]);

            return redirect()
                ->route('jurusan.index')
                ->with('success', 'Data Jurusan berhasil diperbarui!');
        } catch (\Throwable $e) {
            Log::error('Gagal memperbarui jurusan (id=' . $jurusan->id . '): ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data jurusan.');
        }
    }

    /**
     * Menghapus data jurusan dari database.
     */
    public function destroy(Jurusan $jurusan): RedirectResponse
    {
        try {
            $jurusan->delete();
            return redirect()
                ->route('jurusan.index')
                ->with('success', 'Data Jurusan berhasil dihapus!');
        } catch (\Throwable $e) {
            Log::error('Gagal menghapus jurusan (id=' . $jurusan->id . '): ' . $e->getMessage());
            return redirect()
                ->route('jurusan.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data jurusan.');
        }
    }
}
