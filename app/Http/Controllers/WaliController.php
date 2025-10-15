<?php
namespace App\Http\Controllers;

use App\Models\Wali;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class WaliController extends Controller
{
    public function index()
    {
        $walis = Wali::with('user', 'siswa')->latest()->get();
        return view('wali.index', compact('walis'));
    }

    public function create()
    {
        $siswas = Siswa::all();
        return view('wali.create', compact('siswas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
            'siswas' => 'array|nullable',
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'wali',
        ]);

        $wali = Wali::create([
            'user_id' => $user->id,
            'nama' => $request->nama,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
        ]);

        if ($request->has('siswas')) {
            $wali->siswa()->sync($request->siswas);
        }

        return redirect()->route('wali.index')->with('success', 'Data wali berhasil ditambahkan.');
    }

    public function edit(Wali $wali)
    {
        $siswas = Siswa::all();
        return view('wali.edit', compact('wali', 'siswas'));
    }

    public function update(Request $request, Wali $wali)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
            'siswas' => 'array|nullable',
        ]);

        $wali->update([
            'nama' => $request->nama,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
        ]);

        if ($request->has('siswas')) {
            $wali->siswa()->sync($request->siswas);
        }

        return redirect()->route('wali.index')->with('success', 'Data wali berhasil diperbarui.');
    }

    public function destroy(Wali $wali)
    {
        $wali->user()->delete();
        $wali->delete();
        return redirect()->route('wali.index')->with('success', 'Data wali berhasil dihapus.');
    }
}
