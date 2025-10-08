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
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Seluruh rute aplikasi. Root "/" di-redirect secara aman:
| - jika user terautentikasi -> ke dashboard (route named 'dashboard')
| - jika belum login -> ke halaman login
|
| Route 'dashboard' didefinisikan di dalam middleware auth supaya hanya
| user yang sudah login yang dapat mengaksesnya.
|
*/

// Root: arahkan user tergantung status login
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// --------------------------------------------------------------------------
// Public / Auth routes (login/logout)
// --------------------------------------------------------------------------
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('loginStore');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --------------------------------------------------------------------------
// Atur Mengajar (manajemen relasi guru-mapel-kelas)
// --------------------------------------------------------------------------
Route::get('/atur-mengajar', [JadwalController::class, 'aturIndex'])->name('atur-mengajar.index');
Route::post('/atur-mengajar/store', [JadwalController::class, 'aturStore'])->name('atur-mengajar.store');
Route::delete('/atur-mengajar/{id}', [JadwalController::class, 'aturDestroy'])->name('atur-mengajar.destroy');

// --------------------------------------------------------------------------
// Protected routes (auth)
// --------------------------------------------------------------------------
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ----------------------------------------------------------------------
    // ADMIN ONLY
    // ----------------------------------------------------------------------
    Route::group(['middleware' => ['role:admin']], function () {

        Route::resource('jurusan', JurusanController::class);
        Route::resource('kelas', KelasController::class)->parameters(['kelas' => 'kelas']);
        Route::resource('guru', GuruController::class);
        Route::resource('mapel', MataPelajaranController::class);

        // Siswa explicit route + resource
        Route::get('siswa', [SiswaController::class, 'index'])->name('siswa.index');
        Route::get('siswa/create', [SiswaController::class, 'create'])->name('siswa.create');
        Route::post('siswa', [SiswaController::class, 'store'])->name('siswa.store');
        Route::get('siswa/{siswa}/edit', [SiswaController::class, 'edit'])->name('siswa.edit');
        Route::put('siswa/{siswa}', [SiswaController::class, 'update'])->name('siswa.update');
        Route::delete('siswa/{siswa}', [SiswaController::class, 'destroy'])->name('siswa.destroy');
        Route::resource('siswa', SiswaController::class);

        // Wali + Jadwal
        Route::resource('wali', WaliController::class);
        Route::resource('jadwal', JadwalController::class);

        Route::get('/guru-mapel-kelas', function () {
            return redirect()->route('jadwal.index');
        })->name('guru-mapel-kelas.index');
    });

    // ----------------------------------------------------------------------
    // ADMIN & GURU
    // ----------------------------------------------------------------------
    Route::group(['middleware' => ['role:admin|guru']], function () {
        // Lama
        Route::get('/laporanabsensi', [LaporanAbsensiController::class, 'index'])->name('laporanabsensi');

        // 🔰 Laporan Absensi Baru (Lengkap)
        Route::get('/laporan', [LaporanAbsensiController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/kelas/{kelas}/mapel/{mapel}', [LaporanAbsensiController::class, 'kelasDetail'])
            ->name('laporan.kelas.detail');
        Route::get('/laporan/siswa/{siswa}/mapel/{mapel}', [LaporanAbsensiController::class, 'siswaRekap'])
            ->name('laporan.siswa.rekap');

        // Export data laporan
        Route::get('/laporanabsensi/export', [LaporanAbsensiController::class, 'export'])
            ->name('laporanabsensi.export');
    });

    // ----------------------------------------------------------------------
    // GURU ONLY
    // ----------------------------------------------------------------------
    Route::group(['middleware' => ['role:guru']], function () {
        Route::get('/jadwalsaya', [JadwalController::class, 'jadwalsaya'])->name('jadwalsaya');
        Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
        Route::get('/absensi/{id}', [AbsensiController::class, 'show'])->name('absensi.show');
        Route::post('/absensi/store', [AbsensiController::class, 'store'])->name('absensi.store');
    });

    // ----------------------------------------------------------------------
    // SISWA ONLY
    // ----------------------------------------------------------------------
    Route::group(['middleware' => ['role:siswa']], function () {
        Route::get('/absensisaya', [LaporanAbsensiController::class, 'absensisaya'])->name('absensisaya');
        Route::get('/laporanabsensi/export/siswa', [LaporanAbsensiController::class, 'export'])
            ->name('laporanabsensi.export.siswa');
    });

    // ----------------------------------------------------------------------
    // WALI ONLY
    // ----------------------------------------------------------------------
    Route::group(['middleware' => ['role:wali']], function () {
        Route::get('/absensianaksaya', [LaporanAbsensiController::class, 'absensianaksaya'])->name('absensianaksaya');
        Route::get('/laporanabsensi/export/wali', [LaporanAbsensiController::class, 'export'])
            ->name('laporanabsensi.export.wali');
    });
});

// ==========================================================================
// Tambahan Route fallback & testing (opsional untuk memastikan route hidup)
// ==========================================================================
Route::get('/cek-laporan', function () {
    return redirect()->route('laporan.index');
});
