<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse; // Untuk store, update, destroy
use App\Models\Kelas;
use App\Models\Jurusan; // Tambahkan ini untuk relasi ke jurusan

class KelasController extends Controller
{
    /**
     * Menampilkan daftar semua kelas.
     */
    public function index(): View
    {
        $kelas = Kelas::with('jurusan')->get(); // Ambil kelas beserta jurusan
        return view('kelas.index', compact('kelas'));
    }

    /**
     * Menampilkan form untuk membuat kelas baru.
     */
    public function create(): View
    {
        $jurusans = Jurusan::all(); // Ambil semua jurusan untuk dropdown
        return view('kelas.create', compact('jurusans'));
    }

    /**
     * Menyimpan data kelas baru yang dikirim dari form.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama' => 'required|unique:kelas,nama|max:255', // pakai tabel kelas
            // PERBAIKAN: Mengubah jurusans menjadi jurusan (sesuai nama tabel di migrasi)
            'jurusan_id' => 'required|exists:jurusan,id', // pastikan jurusan valid
        ]);

        Kelas::create([
            'nama' => $request->nama,
            'jurusan_id' => $request->jurusan_id,
        ]);

        return redirect()->route('kelas.index')->with('success', 'Data Kelas berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail satu kelas berdasarkan ID.
     */
    public function show(Kelas $kelas): View
    {
        return view('kelas.show', compact('kelas'));
    }

    /**
     * Menampilkan form untuk mengedit kelas tertentu.
     */
    public function edit(Kelas $kelas): View
    {
        $jurusans = Jurusan::all(); // biar bisa ubah jurusan juga
        return view('kelas.edit', compact('kelas', 'jurusans'));
    }

    /**
     * Memperbarui data kelas di database.
     */
    public function update(Request $request, Kelas $kelas): RedirectResponse
    {
        $request->validate([
            'nama' => 'required|unique:kelas,nama,' . $kelas->id . '|max:255',
            // PERBAIKAN: Mengubah jurusans menjadi jurusan (sesuai nama tabel di migrasi)
            'jurusan_id' => 'required|exists:jurusan,id',
        ]);

        $kelas->update([
            'nama' => $request->nama,
            'jurusan_id' => $request->jurusan_id,
        ]);

        return redirect()->route('kelas.index')->with('success', 'Data Kelas berhasil diperbarui!');
    }

    /**
     * Menghapus data kelas dari database.
     */
    public function destroy(Kelas $kelas): RedirectResponse
    {
        $kelas->delete();
        return redirect()->route('kelas.index')->with('success', 'Data Kelas berhasil dihapus!');
    }
}
