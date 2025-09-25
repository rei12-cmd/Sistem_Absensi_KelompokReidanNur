<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanAbsensiController extends Controller
{
    public function index(): View
    {
        return view('laporan.index');
    }

    public function absensisaya(): View
    {
        return view('laporan.absensisaya');
    }

    public function absensianaksaya(): View
    {
        return view('laporan.absensianaksaya');
    }
}
