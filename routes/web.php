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
    */
    Route::group(['middleware' => ['role:admin']], function () {

        // Resource untuk jurusan
        Route::resource('jurusan', JurusanController::class);

        // Resource untuk kelas
        Route::resource('kelas', KelasController::class);

        // Resource untuk guru
        Route::resource('guru', GuruController::class);

        // Resource untuk jadwal (pakai default names Laravel)
        Route::resource('jadwal', JadwalController::class);

        // Redirect dari route lama (guru-mapel-kelas.index) ke jadwal.index
        Route::get('/guru-mapel-kelas', function () {
            return redirect()->route('jadwal.index');
        })->name('guru-mapel-kelas.index');

        // Resource untuk mapel
        Route::resource('mapel', MataPelajaranController::class);

        // Siswa & wali (index saja)
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
