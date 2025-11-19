<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MataPelajaranController extends Controller
{
    /**
     * Tampilkan daftar mata pelajaran.
     * Menggunakan pola seperti $jadwals = Jadwal::with([...])->get();
     */
    public function index(): View
    {
        $mapels = MataPelajaran::latest()->get();

        return view('mapel.index', compact('mapels'));
        // Jika model MataPelajaran memiliki relasi (misalnya ke GuruMapel atau Kelas), tambahkan di sini.
        // Misal: 'guruMapelKelas.guru', 'guruMapelKelas.kelas'

    }

    /**
     * Tampilkan form create.
     */
    public function create(): View
    {
        return view('mapel.create');
    }

    /**
     * Simpan data baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:mata_pelajaran,nama',
        ]);

        MataPelajaran::create($validated);

        return redirect()->route('mapel.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit.
     */
    public function edit(MataPelajaran $mapel): View
    {
        return view('mapel.edit', compact('mapel'));
    }

    /**
     * Update data.
     */
    public function update(Request $request, MataPelajaran $mapel): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => "required|string|max:255|unique:mata_pelajaran,nama,{$mapel->id}",
        ]);

        $mapel->update($validated);

        return redirect()->route('mapel.index')
            ->with('success', 'Mata pelajaran berhasil diupdate.');
    }

    /**
     * Hapus data.
     */
    public function destroy(MataPelajaran $mapel): RedirectResponse
    {
        $mapel->delete();

        return redirect()->route('mapel.index')
            ->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
