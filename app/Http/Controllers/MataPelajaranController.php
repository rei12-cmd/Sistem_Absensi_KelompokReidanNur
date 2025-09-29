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
     * (Preserved: method index tetap ada, sekarang mengirim data ke view)
     */
    public function index(): View
    {
        // Ambil semua mapel, urut berdasarkan nama agar tampil lebih rapi
        $mapels = MataPelajaran::orderBy('nama')->get();

        return view('mapel.index', compact('mapels'));
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

        // Pastikan model MataPelajaran memiliki properti $fillable = ['nama']
        MataPelajaran::create($validated);

        return redirect()->route('mapel.index')->with('success', 'Mata pelajaran berhasil ditambahkan.');
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
            // unique: abaikan record saat ini dengan id
            'nama' => "required|string|max:255|unique:mata_pelajaran,nama,{$mapel->id}",
        ]);

        $mapel->update($validated);

        return redirect()->route('mapel.index')->with('success', 'Mata pelajaran berhasil diupdate.');
    }

    /**
     * Hapus data.
     */
    public function destroy(MataPelajaran $mapel): RedirectResponse
    {
        $mapel->delete();

        return redirect()->route('mapel.index')->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
