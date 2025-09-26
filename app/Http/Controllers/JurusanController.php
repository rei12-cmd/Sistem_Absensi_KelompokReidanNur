<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse; // Tambahkan ini untuk method store, update, destroy
use App\Models\Jurusan;

class JurusanController extends Controller
{
    /**
     * Menampilkan daftar semua jurusan.
     */
    public function index(): View
    {
        // Variabel $jurusan sekarang tersedia di view 'jurusan.index'
        $jurusan = Jurusan::all();

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
        // Validasi data
        $request->validate([
            'nama' => 'required|unique:jurusan,nama|max:255', // Pastikan 'jurusan' sesuai nama tabel
        ]);

        // Simpan data ke database
        Jurusan::create([
            'nama' => $request->nama,
        ]);

        // Redirect kembali dengan pesan sukses
        return redirect()->route('jurusan.index')->with('success', 'Data Jurusan berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail satu jurusan berdasarkan ID.
     */
    public function show(Jurusan $jurusan): View
    {
        // Parameter di-resolve otomatis (Route Model Binding)
        return view('jurusan.show', compact('jurusan'));
    }

    /**
     * Menampilkan form untuk mengedit jurusan tertentu.
     */
    public function edit(Jurusan $jurusan): View
    {
        // Parameter di-resolve otomatis (Route Model Binding)
        return view('jurusan.edit', compact('jurusan'));
    }

    /**
     * Memperbarui data jurusan di database.
     */
    public function update(Request $request, Jurusan $jurusan): RedirectResponse
    {
        // Validasi data
        $request->validate([
            // 'unique' diabaikan untuk nama yang sedang diedit
            'nama' => 'required|unique:jurusan,nama,' . $jurusan->id . '|max:255',
        ]);

        // Perbarui data
        $jurusan->update([
            'nama' => $request->nama,
        ]);

        // Redirect kembali dengan pesan sukses
        return redirect()->route('jurusan.index')->with('success', 'Data Jurusan berhasil diperbarui!');
    }

    /**
     * Menghapus data jurusan dari database.
     */
    public function destroy(Jurusan $jurusan): RedirectResponse
    {
        $jurusan->delete();

        return redirect()->route('jurusan.index')->with('success', 'Data Jurusan berhasil dihapus!');
    }
}
