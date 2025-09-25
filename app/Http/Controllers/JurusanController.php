<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Jurusan;

class JurusanController extends Controller
{
    public function index(): View
    {
        $jurusan = Jurusan::All();
        return view('jurusan.index', compact('jurusan'));
    }

    public function create(): View
    {
        return view('jurusan.create');
    }
}
