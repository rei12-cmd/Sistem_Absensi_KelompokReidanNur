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

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('loginStore');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function() {
    Route::get('/', function() {
        return view('dashboard');
    })->name('dashboard');

    Route::group(['middleware' => ['role:admin']], function () {
        // Route resource untuk jurusan
        Route::resource('jurusan', JurusanController::class);

        // Route resource untuk kelas
        Route::resource('kelas', KelasController::class);

        // Alias lama biar tidak error di view lama
        Route::get('/kelas', [KelasController::class, 'index'])->name('kelas');

        // Alias lama untuk controller lain
        Route::get('/guru', [GuruController::class, 'index'])->name('guru');
        Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa');
        Route::get('/wali', [WaliController::class, 'index'])->name('wali');
        Route::get('/mapel', [MataPelajaranController::class, 'index'])->name('mapel');
        Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal');
    });

    Route::group(['middleware' => ['role:admin|guru']], function () {
        Route::get('/laporanabsensi', [LaporanAbsensiController::class, 'index'])->name('laporanabsensi');
    });

    Route::group(['middleware' => ['role:guru']], function () {
        Route::get('/jadwalsaya', [JadwalController::class, 'jadwalsaya'])->name('jadwalsaya');
        Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi');
    });

    Route::group(['middleware' => ['role:siswa']], function () {
        Route::get('/absensisaya', [LaporanAbsensiController::class, 'absensisaya'])->name('absensisaya');
    });

    Route::group(['middleware' => ['role:wali']], function () {
        Route::get('/absensianaksaya', [LaporanAbsensiController::class, 'absensianaksaya'])->name('absensianaksaya');
    });
});
