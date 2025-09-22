<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function index(): View
    {
      return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
      $request->validate([
        'login'     => 'required',
        'password'  => 'required',
      ]);

      $login = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email': 'username';

      if (Auth::attempt([$login => $request->input('login'), 'password' => $request->input('password')])) {
        $request->session()->regenerate();

        return redirect()->intended('dashboard')->with('success', 'Selamat datang kembali');
      }

      return back()->withErrors([
        'error' => 'Login gagal',
      ])->onlyInput('error');
    }
}
