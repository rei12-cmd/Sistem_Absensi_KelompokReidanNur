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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
     *
     * Mendukung:
     * - Mengaitkan ke user existing (kirim user_id)
     * - Membuat user baru bila user_id kosong (kirim username,email,password,password_confirmation)
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            // user_id bisa dikosongkan jika ingin membuat user baru
            'user_id' => 'nullable|exists:users,id',
            'kelas_id' => 'required|exists:kelas,id',
            'jurusan_id' => 'required|exists:jurusan,id',
            'nis' => 'required|string|max:50|unique:siswa,nis',
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string|max:1000',

            // fields untuk membuat user baru jika user_id kosong
            'username' => 'required_without:user_id|string|max:50|unique:users,username',
            'email' => 'required_without:user_id|email|max:100|unique:users,email',
            // password tidak lagi dipaksa min:8, hanya harus confirmed ketika dikirim
            'password' => 'required_without:user_id|string|confirmed',
        ]);

        DB::beginTransaction();
        try {
            // jika user_id dikirim, gunakan user tersebut
            if (!empty($validated['user_id'])) {
                $userId = $validated['user_id'];
            } else {
                // buat user baru
                $user = User::create([
                    'username' => $validated['username'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    // tambahkan field lain jika model User di-app Anda membutuhkan (role, is_active, dll.)
                ]);
                $userId = $user->id;
            }

            // Simpan siswa
            Siswa::create([
                'user_id' => $userId,
                'kelas_id' => $validated['kelas_id'],
                'jurusan_id' => $validated['jurusan_id'],
                'nis' => $validated['nis'],
                'nama' => $validated['nama'],
                'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
            ]);

            DB::commit();
            return redirect()->route('siswa.index')->with('success', 'Siswa berhasil ditambahkan.');
        } catch (\Throwable $e) {
            DB::rollBack();
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
     *
     * - Mendukung:
     *   - Mengaitkan ulang ke user lain dengan mengirim user_id
     *   - Memperbarui user terkait (username, email, password) jika dikirim
     *   - Jika tidak ada user terkait tapi username/email dikirim, buat user baru dan kaitkan
     */
    public function update(Request $request, Siswa $siswa): RedirectResponse
    {
        // dasar validasi untuk siswa
        $rules = [
            'user_id' => 'nullable|exists:users,id',
            'kelas_id' => 'required|exists:kelas,id',
            'jurusan_id' => 'required|exists:jurusan,id',
            'nis' => 'required|string|max:50|unique:siswa,nis,' . $siswa->id,
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string|max:1000',

            // fields optional untuk update/creation user
            'username' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            // password optional, bila dikirim harus confirmed (tanpa batasan minimal)
            'password' => 'nullable|string|confirmed',
        ];

        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
            // 1) Tangani relasi user: jika user_id diberikan -> kaitkan ke user itu
            if (!empty($validated['user_id'])) {
                $siswa->user_id = $validated['user_id'];
            }

            // 2) Tangani pembuatan / update user terkait
            $currentUser = $siswa->user; // bisa null

            // Jika ada username/email dikirim, periksa uniqueness dan apply:
            if (!empty($validated['username'])) {
                // jika currentUser ada, pastikan username unik kecuali milik currentUser
                if ($currentUser) {
                    $exists = User::where('username', $validated['username'])
                        ->where('id', '!=', $currentUser->id)
                        ->exists();
                    if ($exists) {
                        return redirect()->back()->withInput()->with('error', 'Username sudah dipakai oleh user lain.');
                    }
                    $currentUser->username = $validated['username'];
                } else {
                    // tidak ada user saat ini -> kita bisa buat user baru nanti (jika email & password juga ada)
                }
            }

            if (!empty($validated['email'])) {
                if ($currentUser) {
                    $exists = User::where('email', $validated['email'])
                        ->where('id', '!=', $currentUser->id)
                        ->exists();
                    if ($exists) {
                        return redirect()->back()->withInput()->with('error', 'Email sudah dipakai oleh user lain.');
                    }
                    $currentUser->email = $validated['email'];
                } else {
                    // no current user, creation handled below if enough data
                }
            }

            if (!empty($validated['password']) && $currentUser) {
                $currentUser->password = Hash::make($validated['password']);
            }

            // Jika currentUser ada dan ada perubahan, simpan
            if (!empty($currentUser) && $currentUser->isDirty()) {
                $currentUser->save();
            }

            // Jika tidak ada currentUser dan user_id tidak dikirim, tapi ada data username+email (minimal)
            if (empty($currentUser) && empty($validated['user_id']) && (!empty($validated['username']) || !empty($validated['email']))) {
                // pastikan minimal username & email & password tersedia agar aman membuat user
                // kita izinkan pembuatan user jika username & email tersedia; password boleh kosong (sesuaikan kebutuhan)
                $usernameToCreate = $validated['username'] ?? null;
                $emailToCreate = $validated['email'] ?? null;

                if ($usernameToCreate && $emailToCreate) {
                    // cek unique sebelum pembuatan
                    $existsUsername = User::where('username', $usernameToCreate)->exists();
                    $existsEmail = User::where('email', $emailToCreate)->exists();
                    if ($existsUsername || $existsEmail) {
                        DB::rollBack();
                        return redirect()->back()->withInput()->with('error', 'Tidak dapat membuat user baru: username/email sudah terpakai.');
                    }

                    $userPayload = [
                        'username' => $usernameToCreate,
                        'email' => $emailToCreate,
                        // jika password dikirim, hash; jika tidak, set password random atau kosong sesuai kebijakan
                        'password' => !empty($validated['password']) ? Hash::make($validated['password']) : Hash::make(str()->random(12)),
                    ];

                    $newUser = User::create($userPayload);
                    $siswa->user_id = $newUser->id;
                }
            }

            // 3) Update field siswa
            $siswa->kelas_id = $validated['kelas_id'];
            $siswa->jurusan_id = $validated['jurusan_id'];
            $siswa->nis = $validated['nis'];
            $siswa->nama = $validated['nama'];
            $siswa->tanggal_lahir = $validated['tanggal_lahir'] ?? null;
            $siswa->alamat = $validated['alamat'] ?? null;

            $siswa->save();

            DB::commit();
            return redirect()->route('siswa.index')->with('success', 'Siswa berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
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
