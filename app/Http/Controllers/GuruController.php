<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class GuruController extends Controller
{
    public function __construct()
    {
        // Jika ingin melindungi rute dengan authentication, uncomment baris di bawah:
        // $this->middleware('auth');
    }

    /**
     * Method index yang kamu kirimkan sebelumnya — saya pertahankan signature-nya.
     * Saya tambahkan pengambilan data supaya view dapat menampilkan daftar guru.
     */
    public function index(): View
    {
        // Ambil data guru beserta relasi user, paginasi agar tidak membebani view
        $gurus = Guru::with('user')->latest()->paginate(10);

        // Kembalikan view 'guru.index' (sama seperti yang ada di kode aslinya)
        return view('guru.index', compact('gurus'));
    }

    /**
     * Tampilkan form tambah guru
     */
    public function create(): View
    {
        // Ambil daftar user untuk dropdown (akun yang terkait dengan guru)
        // Gunakan kolom 'username' karena tabel users tidak punya kolom 'name'
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

        Guru::create($validated);

        return redirect()->route('guru.index')->with('success', 'Data guru berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit
     */
    public function edit(Guru $guru): View
    {
        // Sama, gunakan 'username' supaya tidak error
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

        $guru->update($validated);

        return redirect()->route('guru.index')->with('success', 'Data guru berhasil diupdate.');
    }

    /**
     * Hapus data guru
     */
    public function destroy(Guru $guru): RedirectResponse
    {
        $guru->delete();
        return redirect()->route('guru.index')->with('success', 'Data guru berhasil dihapus.');
    }
}
