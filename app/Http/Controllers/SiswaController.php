<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SiswaController extends Controller
{
    public function index()
    {
        $siswas = Siswa::with('user')->latest()->get();
        return view('siswa.index', compact('siswas'));
    }

    public function create()
    {
        $kelass = Kelas::get();
        return view('siswa.create', compact('kelass'));
    }

    public function store(Request $request)
    {
        $jurusan = Kelas::find($request->input('kelas_id'));

        $validated = $request->validate([
            'nis' => 'required|string|max:100',
            'nama' => 'required|string',
            'username' => 'required|string',
            'email' => 'required',
            'kelas_id' => 'required',
            'password' => 'required|confirmed',
            'tanggal_lahir' => 'required',
            'alamat' => 'required',
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole('siswa');

        Siswa::create([
            'user_id' => $user->id,
            'nama' => $validated['nama'],
            'kelas_id' => $validated['kelas_id'],
            'jurusan_id' => $jurusan->jurusan_id,
            'nis' => $validated['nis'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'alamat' => $validated['alamat'],
        ]);

        return redirect()->route('siswa.index')->with('success', 'Data guru berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $siswa = Siswa::with('user')->findOrFail($id);
        $kelass = Kelas::all();

        return view('siswa.edit', compact('siswa', 'kelass'));
    }

    public function update(Request $request, $id)
    {
        $siswa = Siswa::with('user')->findOrFail($id);
        $jurusan = Kelas::find($request->input('kelas_id'));

        $validated = $request->validate([
            'nis' => 'required|string|max:100',
            'nama' => 'required|string',
            'username' => 'required|string',
            'email' => 'required|email',
            'kelas_id' => 'required',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'password' => 'nullable|confirmed|min:6',
        ]);

        $siswa->user->update([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => $validated['password']
                ? Hash::make($validated['password'])
                : $siswa->user->password,
        ]);

        $siswa->update([
            'nis' => $validated['nis'],
            'nama' => $validated['nama'],
            'kelas_id' => $validated['kelas_id'],
            'jurusan_id' => $jurusan->jurusan_id,
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'alamat' => $validated['alamat'],
        ]);

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui!');
    }


    public function destroy(Siswa $siswa)
    {
        $siswa->user->delete();
        $siswa->delete();
        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil dihapus!');
    }
}
