<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
     *
     * Catatan:
     * - Mendukung dua alur:
     *   1) Mengaitkan guru dengan user yang sudah ada -> kirim user_id
     *   2) Membuat user baru lalu membuat guru -> kirim username,email,password,password_confirmation (user_id boleh kosong)
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi bersyarat:
        // - Jika user_id tidak dikirim, maka username & email & password harus ada (membuat user baru)
        // - Jika user_id dikirim, username/email/password boleh tidak ada
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'username' => 'required_without:user_id|string|max:50|unique:users,username',
            'email' => 'required_without:user_id|email|max:100|unique:users,email',
            'password' => 'required_without:user_id|string|confirmed',
            'nip'     => 'nullable|string|max:50|unique:guru,nip',
            'nama'    => 'required|string|max:255',
            'telepon' => 'nullable|string|max:20',
        ]);

        DB::beginTransaction();
        try {
            // Tentukan user_id akhir yang akan di-relasikan ke table guru
            if (!empty($validated['user_id'])) {
                // Menggunakan user yang sudah ada
                $userId = $validated['user_id'];
            } else {
                // Buat user baru
                $user = User::create([
                    'username' => $validated['username'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    // Jika ada kolom role atau is_active, tambahkan di sini, contoh:
                    // 'role' => 'guru',
                ]);
                $userId = $user->id;
            }

            // Simpan data guru (sesuaikan nama kolom tabel guru jika berbeda)
            $guru = Guru::create([
                'user_id' => $userId,
                'nip' => $validated['nip'] ?? null,
                'nama' => $validated['nama'],
                'telepon' => $validated['telepon'] ?? null,
            ]);

            DB::commit();
            return redirect()->route('guru.index')->with('success', 'Data guru berhasil ditambahkan.');
        } catch (\Throwable $e) {
            DB::rollBack();
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
        // Pastikan relasi user dimuat jika view menggunakannya
        $guru->load('user');
        return view('guru.edit', compact('guru', 'users'));
    }

    /**
     * Update data guru
     *
     * - Mendukung update field guru.
     * - Jika saat update disertakan data user (username/email/password), controller akan mencoba
     *   memperbarui user terkait. Jika ingin mengganti relasi ke user lain, kirim user_id.
     */
    public function update(Request $request, Guru $guru): RedirectResponse
    {
        // Kita perlu validasi yang fleksibel:
        // - Jika user_id diberikan dan bukan user milik guru sekarang, gunakan exists
        // - Jika ingin memperbarui data user terkait: username/email harus unik kecuali milik user yang sama
        $rules = [
            'user_id' => 'nullable|exists:users,id',
            'nip'     => 'nullable|string|max:50|unique:guru,nip,' . $guru->id,
            'nama'    => 'required|string|max:255',
            'telepon' => 'nullable|string|max:20',
            // Untuk update user: hanya valid jika dikirim
            'username' => 'nullable|string|max:50|unique:users,username',
            'email' => 'nullable|email|max:100|unique:users,email',
            // password optional - bila diisi harus confirmed
            'password' => 'nullable|string|min:8|confirmed',
        ];

        // Agar rule unique untuk username/email mengizinkan username/email milik user terkait,
        // kita akan handling sedikit setelah validasi awal.
        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
            // 1) Jika request mengandung user_id (mengikat guru ke user lain), lakukan perubahan relasi
            if (!empty($validated['user_id'])) {
                $guru->user_id = $validated['user_id'];
            }

            // 2) Jika request mengandung update data user (username/email/password), update user terkait
            // Pastikan ada relasi user saat ini
            $currentUser = $guru->user; // bisa null jika belum ada

            // Jika username/email dikirim dan user ada, periksa uniqueness yang mengabaikan id user terkait
            if (!empty($validated['username']) && $currentUser) {
                // jika username sama dengan user lain, validasi di atas sudah mencegah; kita lakukan pengecualian:
                $exists = User::where('username', $validated['username'])
                    ->where('id', '!=', $currentUser->id)
                    ->exists();
                if ($exists) {
                    return redirect()->back()->withInput()->with('error', 'Username sudah dipakai oleh user lain.');
                }
                $currentUser->username = $validated['username'];
            }

            if (!empty($validated['email']) && $currentUser) {
                $exists = User::where('email', $validated['email'])
                    ->where('id', '!=', $currentUser->id)
                    ->exists();
                if ($exists) {
                    return redirect()->back()->withInput()->with('error', 'Email sudah dipakai oleh user lain.');
                }
                $currentUser->email = $validated['email'];
            }

            if (!empty($validated['password']) && $currentUser) {
                $currentUser->password = Hash::make($validated['password']);
            }

            // Jika ada perubahan pada user, simpan
            if (!empty($currentUser) && $currentUser->isDirty()) {
                $currentUser->save();
            }

            // 3) Update field guru
            $guru->nip = $validated['nip'] ?? $guru->nip;
            $guru->nama = $validated['nama'];
            $guru->telepon = $validated['telepon'] ?? $guru->telepon;

            $guru->save();

            DB::commit();
            return redirect()->route('guru.index')->with('success', 'Data guru berhasil diupdate.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui guru (id=' . $guru->id . '): ' . $e->getMessage());
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
            Log::error('Gagal menghapus guru (id=' . $guru->id . '): ' . $e->getMessage());
            return redirect()->route('guru.index')->with('error', 'Terjadi kesalahan saat menghapus data guru.');
        }
    }
}
