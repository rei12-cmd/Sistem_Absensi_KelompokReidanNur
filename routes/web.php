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
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Guru\LaporanController as GuruLaporanController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', fn() => Auth::check() ? redirect()->route('dashboard') : redirect()->route('login'));

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('loginStore');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth:web'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('admin')->middleware('role:admin')->group(function () {

        Route::resource('jurusan', JurusanController::class);
        Route::resource('kelas', KelasController::class)->parameters(['kelas' => 'kelas']);
        Route::resource('guru', GuruController::class);
        Route::resource('mapel', MataPelajaranController::class);
        Route::resource('siswa', SiswaController::class);
        Route::resource('wali', WaliController::class);
        Route::resource('jadwal', JadwalController::class);

        Route::get('atur-mengajar', [JadwalController::class, 'aturIndex'])->name('atur-mengajar.index');
        Route::post('atur-mengajar/store', [JadwalController::class, 'aturStore'])->name('atur-mengajar.store');
        Route::delete('atur-mengajar/{id}', [JadwalController::class, 'aturDestroy'])->name('atur-mengajar.destroy');

        Route::get('laporan', [AdminLaporanController::class, 'index'])->name('laporan.index');
        Route::get('laporan/siswa/{id}', [AdminLaporanController::class, 'siswa'])->name('laporan.siswa');

        Route::get('guru-mapel-kelas', fn() => redirect()->route('jadwal.index'))->name('guru-mapel-kelas.index');
    });

    Route::prefix('guru')->middleware('role:guru')->group(function () {
        Route::get('jadwalsaya', [JadwalController::class, 'jadwalsaya'])->name('jadwalsaya');

        Route::prefix('absensi')->group(function () {
            Route::get('/', [AbsensiController::class, 'index'])->name('absensi.index');
            Route::get('{id}', [AbsensiController::class, 'show'])->name('absensi.show');
            Route::post('store', [AbsensiController::class, 'store'])->name('absensi.store');
        });

        Route::prefix('laporan')->group(function () {
            Route::get('/', [GuruLaporanController::class, 'index'])->name('guru.laporan.index');
            Route::get('kelas/{id}', [GuruLaporanController::class, 'kelas'])->name('guru.laporan.kelas');
            Route::get('siswa/{id}', [GuruLaporanController::class, 'siswa'])->name('guru.laporan.siswa');
        });
    });

    Route::prefix('siswa')->middleware('role:siswa')->group(function () {
 
    });

    Route::prefix('wali')->middleware('role:wali')->group(function () {

    });

});
