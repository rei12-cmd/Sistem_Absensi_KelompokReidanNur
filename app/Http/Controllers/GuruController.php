<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class GuruController extends Controller
{
    public function index()
    {
        $gurus = Guru::with('user')->latest()->get();
        return view('guru.index', compact('gurus'));
    }

    public function create()
    {
        return view('guru.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'nip' => 'required|string|max:50|unique:guru,nip',
            'telepon' => 'nullable|string|max:20',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'username' => $validated['nip'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole('guru');

        Guru::create([
            'user_id' => $user->id,
            'nama' => $validated['nama'],
            'nip' => $validated['nip'],
            'telepon' => $validated['telepon'] ?? null,
        ]);

        return redirect()->route('guru.index')->with('success', 'Data guru berhasil ditambahkan!');
    }

    public function edit(Guru $guru)
    {
        return view('guru.edit', compact('guru'));
    }

    public function update(Request $request, Guru $guru)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'nip' => 'required|string|max:50|unique:guru,nip,' . $guru->id,
            'telepon' => 'nullable|string|max:20',
            'email' => 'required|email|unique:users,email,' . $guru->user_id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        $guru->user->update([
            'email' => $validated['email'],
            'password' => $validated['password']
                ? Hash::make($validated['password'])
                : $guru->user->password,
        ]);

        $guru->update([
            'nama' => $validated['nama'],
            'nip' => $validated['nip'],
            'telepon' => $validated['telepon'] ?? null,
        ]);

        return redirect()->route('guru.index')->with('success', 'Data guru berhasil diperbarui!');
    }

    public function destroy(Guru $guru)
    {
        $guru->user->delete();
        $guru->delete();
        return redirect()->route('guru.index')->with('success', 'Data guru berhasil dihapus!');
    }
}
