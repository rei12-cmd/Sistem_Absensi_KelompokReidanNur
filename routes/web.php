<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\WaliController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\LaporanAbsensiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public / Auth routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('loginStore');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Protected routes (auth)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function() {

    Route::get('/', function() {
        return view('dashboard');
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Admin only routes
    |--------------------------------------------------------------------------
    | Letakkan resource routes yang hanya boleh diakses admin di sini.
    */
    Route::group(['middleware' => ['role:admin']], function () {

        // Resource untuk jurusan
        Route::resource('jurusan', JurusanController::class);

        // Resource untuk kelas
        Route::resource('kelas', KelasController::class)->parameters(['kelas' => 'kelas']);

        // Resource untuk guru (membuat route names: guru.index, guru.create, dsb.)
        Route::resource('guru', GuruController::class);
        Route::resource('jadwal', JadwalController::class);

        // Resource untuk mapel (ini sudah membuat route 'mapel.index', 'mapel.create', dsb.)
        Route::resource('mapel', MataPelajaranController::class);



        // Simple GET routes untuk siswa/wali (jika memang hanya menampilkan index sederhana)
        Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa');
        Route::get('/wali', [WaliController::class, 'index'])->name('wali');
       


    });

    /*
    |--------------------------------------------------------------------------
    | Admin or Guru routes
    |--------------------------------------------------------------------------
    */
    Route::group(['middleware' => ['role:admin|guru']], function () {
        Route::get('/laporanabsensi', [LaporanAbsensiController::class, 'index'])->name('laporanabsensi');
    });

    /*
    |--------------------------------------------------------------------------
    | Guru only routes
    |--------------------------------------------------------------------------
    */
    Route::group(['middleware' => ['role:guru']], function () {
        Route::get('/jadwalsaya', [JadwalController::class, 'jadwalsaya'])->name('jadwalsaya');
        Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi');
    });

    /*
    |--------------------------------------------------------------------------
    | Siswa only routes
    |--------------------------------------------------------------------------
    */
    Route::group(['middleware' => ['role:siswa']], function () {
        Route::get('/absensisaya', [LaporanAbsensiController::class, 'absensisaya'])->name('absensisaya');
    });

    /*
    |--------------------------------------------------------------------------
    | Wali only routes
    |--------------------------------------------------------------------------
    */
    Route::group(['middleware' => ['role:wali']], function () {
        Route::get('/absensianaksaya', [LaporanAbsensiController::class, 'absensianaksaya'])->name('absensianaksaya');
    });
});
