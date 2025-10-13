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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
            'nama' => 'required|string|max:255',
            'telepon' => 'nullable|string|max:50',
            'alamat' => 'nullable|string|max:1000',
            'siswa_ids' => 'nullable|array',
            'siswa_ids.*' => 'exists:siswa,id',

            // fields untuk membuat user baru jika user_id kosong
            'username' => 'required_without:user_id|string|max:50|unique:users,username',
            'email' => 'required_without:user_id|email|max:100|unique:users,email',
            // password tidak lagi dipaksa min:8, hanya harus confirmed bila dikirim
            'password' => 'required_without:user_id|string|confirmed',
        ]);

        DB::beginTransaction();
        try {
            // tentukan user_id akhir
            if (!empty($validated['user_id'])) {
                $userId = $validated['user_id'];
            } else {
                $user = User::create([
                    'username' => $validated['username'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                ]);
                $userId = $user->id;
            }

            $wali = Wali::create([
                'user_id' => $userId,
                'nama' => $validated['nama'],
                'telepon' => $validated['telepon'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
            ]);

            if (!empty($validated['siswa_ids'])) {
                $wali->siswa()->sync($validated['siswa_ids']);
            }

            DB::commit();
            return redirect()->route('wali.index')->with('success', 'Wali berhasil ditambahkan.');
        } catch (\Throwable $e) {
            DB::rollBack();
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
        // menggunakan pluck('id') agar robust terhadap nama tabel/alias
        $selectedSiswas = $wali->siswa()->pluck('id')->toArray();

        return view('wali.edit', compact('wali', 'users', 'siswas', 'selectedSiswas'));
    }

    /**
     * Update wali & relasi siswa
     *
     * - Mendukung update/relasi user seperti di store:
     *   - user_id untuk mengaitkan ulang
     *   - username/email/password untuk memperbarui user terkait
     *   - jika tidak ada user terkait dan data username+email dikirim, buat user baru
     */
    public function update(Request $request, Wali $wali): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'nama' => 'required|string|max:255',
            'telepon' => 'nullable|string|max:50',
            'alamat' => 'nullable|string|max:1000',
            'siswa_ids' => 'nullable|array',
            'siswa_ids.*' => 'exists:siswa,id',

            // optional fields to update/create user
            'username' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            // password optional, bila dikirim harus confirmed (tanpa batasan minimal)
            'password' => 'nullable|string|confirmed',
        ]);

        DB::beginTransaction();
        try {
            // 1) jika user_id diberikan, kaitkan ulang ke user tersebut
            if (!empty($validated['user_id'])) {
                $wali->user_id = $validated['user_id'];
            }

            // 2) update/create user terkait bila perlu
            $currentUser = $wali->user; // bisa null

            if (!empty($validated['username'])) {
                if ($currentUser) {
                    $exists = User::where('username', $validated['username'])
                        ->where('id', '!=', $currentUser->id)
                        ->exists();
                    if ($exists) {
                        return redirect()->back()->withInput()->with('error', 'Username sudah dipakai oleh user lain.');
                    }
                    $currentUser->username = $validated['username'];
                } else {
                    // akan dibuat nanti jika cukup data
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
                    // akan dibuat nanti jika cukup data
                }
            }

            if (!empty($validated['password']) && $currentUser) {
                $currentUser->password = Hash::make($validated['password']);
            }

            if (!empty($currentUser) && $currentUser->isDirty()) {
                $currentUser->save();
            }

            // Jika tidak ada currentUser dan user_id kosong, tapi ada data username+email, buat user baru
            if (empty($currentUser) && empty($validated['user_id']) && (!empty($validated['username']) || !empty($validated['email']))) {
                $usernameToCreate = $validated['username'] ?? null;
                $emailToCreate = $validated['email'] ?? null;

                if ($usernameToCreate && $emailToCreate) {
                    $existsUsername = User::where('username', $usernameToCreate)->exists();
                    $existsEmail = User::where('email', $emailToCreate)->exists();
                    if ($existsUsername || $existsEmail) {
                        DB::rollBack();
                        return redirect()->back()->withInput()->with('error', 'Tidak dapat membuat user baru: username/email sudah terpakai.');
                    }

                    $userPayload = [
                        'username' => $usernameToCreate,
                        'email' => $emailToCreate,
                        // jika password dikirim, hash; jika tidak, buat password random
                        'password' => !empty($validated['password']) ? Hash::make($validated['password']) : Hash::make(str()->random(12)),
                    ];

                    $newUser = User::create($userPayload);
                    $wali->user_id = $newUser->id;
                }
            }

            // 3) update field wali & relasi siswa
            $wali->nama = $validated['nama'];
            $wali->telepon = $validated['telepon'] ?? null;
            $wali->alamat = $validated['alamat'] ?? null;
            $wali->save();

            $wali->siswa()->sync($validated['siswa_ids'] ?? []);

            DB::commit();
            return redirect()->route('wali.index')->with('success', 'Wali berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
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
