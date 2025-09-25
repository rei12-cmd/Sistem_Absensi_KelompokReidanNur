<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class JadwalController extends Controller
{
    public function index(): View
    {
        return view('jadwal.index');
    }

    public function jadwalsaya(): View
    {
        return view('jadwal.jadwalsaya');
    }
}
