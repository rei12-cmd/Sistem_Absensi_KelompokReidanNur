<?php

namespace App\Http\Controllers;

use App\Models\Wali;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class WaliController extends Controller
{
    public function index()
    {
        $walis = Wali::with('user')->latest()->get();
        return view('wali.index', compact('walis'));
    }

    public function create()
    {

        $siswas = Siswa::get();
        return view('wali.create', compact('siswas'));
    }

    public function store(Request $request)
    {
        dd($request->all());
    }
}
