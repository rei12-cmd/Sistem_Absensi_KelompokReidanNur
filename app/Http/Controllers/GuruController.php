<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class GuruController extends Controller
{
    public function __construct()
    {
        // Jika ingin melindungi rute dengan authentication, uncomment baris di bawah:
        // $this->middleware('auth');
    }

    /**
     * Menampilkan daftar semua guru.
     * Mengambil semua data (->get()) agar konsisten dengan permintaan.
     */
    public function index(): View
    {
        // Ambil semua guru beserta relasi user (tanpa paginate)
        $gurus = Guru::with('user')->get();

        // Jika view sebelumnya mengharapkan paginator (links), ganti view agar menggunakan collection ($loop->iteration)
        return view('guru.index', compact('gurus'));
    }

    /**
     * Tampilkan form tambah guru
     */
    public function create(): View
    {
        // Ambil daftar user untuk dropdown (akun yang terkait dengan guru)
        // Gunakan kolom 'username' karena tabel users tidak selalu punya kolom 'name'
        $users = User::orderBy('username', 'asc')->get();
        return view('guru.create', compact('users'));
    }

    /**
     * Simpan guru baru
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'nip'     => 'nullable|string|max:50|unique:guru,nip',
            'nama'    => 'required|string|max:255',
            'telepon' => 'nullable|string|max:20',
        ]);

        try {
            Guru::create($validated);
            return redirect()->route('guru.index')->with('success', 'Data guru berhasil ditambahkan.');
        } catch (\Throwable $e) {
            Log::error('Gagal menyimpan guru: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data guru.');
        }
    }

    /**
     * Tampilkan form edit
     */
    public function edit(Guru $guru): View
    {
        // Ambil daftar user juga dengan get()
        $users = User::orderBy('username', 'asc')->get();
        return view('guru.edit', compact('guru', 'users'));
    }

    /**
     * Update data guru
     */
    public function update(Request $request, Guru $guru): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'nip'     => 'nullable|string|max:50|unique:guru,nip,' . $guru->id,
            'nama'    => 'required|string|max:255',
            'telepon' => 'nullable|string|max:20',
        ]);

        try {
            $guru->update($validated);
            return redirect()->route('guru.index')->with('success', 'Data guru berhasil diupdate.');
        } catch (\Throwable $e) {
            Log::error('Gagal memperbarui guru (id='.$guru->id.'): ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui data guru.');
        }
    }

    /**
     * Hapus data guru
     */
    public function destroy(Guru $guru): RedirectResponse
    {
        try {
            $guru->delete();
            return redirect()->route('guru.index')->with('success', 'Data guru berhasil dihapus.');
        } catch (\Throwable $e) {
            Log::error('Gagal menghapus guru (id='.$guru->id.'): ' . $e->getMessage());
            return redirect()->route('guru.index')->with('error', 'Terjadi kesalahan saat menghapus data guru.');
        }
    }
}
