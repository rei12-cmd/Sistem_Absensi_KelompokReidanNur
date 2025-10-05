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
// (Tetap didefinisikan publik; jika butuh proteksi pindahkan ke group auth)
// --------------------------------------------------------------------------
Route::get('/atur-mengajar', [JadwalController::class, 'aturIndex'])->name('atur-mengajar.index');
Route::post('/atur-mengajar/store', [JadwalController::class, 'aturStore'])->name('atur-mengajar.store');
Route::delete('/atur-mengajar/{id}', [JadwalController::class, 'aturDestroy'])->name('atur-mengajar.destroy');


// --------------------------------------------------------------------------
// Protected routes (auth)
// --------------------------------------------------------------------------
Route::middleware(['auth'])->group(function () {

    // Dashboard hanya untuk user yang sudah login
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ----------------------------------------------------------------------
    // Admin only routes
    // ----------------------------------------------------------------------
    Route::group(['middleware' => ['role:admin']], function () {

        // Resource untuk jurusan
        Route::resource('jurusan', JurusanController::class);

        // Resource untuk kelas
        Route::resource('kelas', KelasController::class)->parameters(['kelas' => 'kelas']);

        // Resource untuk guru
        Route::resource('guru', GuruController::class);

        // Resource untuk mata pelajaran
        Route::resource('mapel', MataPelajaranController::class);

        /**
         * EXPLICIT Siswa routes (added to ensure siswa.create / siswa.store etc exist)
         * kept here BEFORE the Route::resource below — safe to keep both.
         */
        Route::get('siswa', [SiswaController::class, 'index'])->name('siswa.index');
        Route::get('siswa/create', [SiswaController::class, 'create'])->name('siswa.create');
        Route::post('siswa', [SiswaController::class, 'store'])->name('siswa.store');
        Route::get('siswa/{siswa}/edit', [SiswaController::class, 'edit'])->name('siswa.edit');
        Route::put('siswa/{siswa}', [SiswaController::class, 'update'])->name('siswa.update');
        Route::delete('siswa/{siswa}', [SiswaController::class, 'destroy'])->name('siswa.destroy');

        // Resource untuk siswa (CRUD lengkap) - tetap dipertahankan
        Route::resource('siswa', SiswaController::class);

        // ------------------------------------------------------------------
        // Wali routes
        // ------------------------------------------------------------------
        Route::resource('wali', WaliController::class);

        // ------------------------------------------------------------------
        // Resource untuk jadwal
        // ------------------------------------------------------------------
        Route::resource('jadwal', JadwalController::class);

        // Redirect dari route lama (guru-mapel-kelas.index) ke jadwal.index
        Route::get('/guru-mapel-kelas', function () {
            return redirect()->route('jadwal.index');
        })->name('guru-mapel-kelas.index');
    });

    // ----------------------------------------------------------------------
    // Admin or Guru routes
    // ----------------------------------------------------------------------
    Route::group(['middleware' => ['role:admin|guru']], function () {
        // Laporan absensi untuk admin & guru
        Route::get('/laporanabsensi', [LaporanAbsensiController::class, 'index'])->name('laporanabsensi');
        // Optional: export (CSV) untuk admin/guru
        Route::get('/laporanabsensi/export', [LaporanAbsensiController::class, 'export'])->name('laporanabsensi.export');
    });

    // ----------------------------------------------------------------------
    // Guru only routes
    // ----------------------------------------------------------------------
    Route::group(['middleware' => ['role:guru']], function () {
        Route::get('/jadwalsaya', [JadwalController::class, 'jadwalsaya'])->name('jadwalsaya');
        Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi');
    });

    // ----------------------------------------------------------------------
    // Siswa only routes
    // ----------------------------------------------------------------------
    Route::group(['middleware' => ['role:siswa']], function () {
        // Absensi siswa (halaman khusus siswa)
        Route::get('/absensisaya', [LaporanAbsensiController::class, 'absensisaya'])->name('absensisaya');

        // Memberikan akses export bagi siswa (jika dibutuhkan)
        Route::get('/laporanabsensi/export', [LaporanAbsensiController::class, 'export'])
            ->middleware('role:siswa')
            ->name('laporanabsensi.export.siswa');
    });

    // ----------------------------------------------------------------------
    // Wali only routes
    // ----------------------------------------------------------------------
    Route::group(['middleware' => ['role:wali']], function () {
        // Halaman absensi anak oleh wali
        Route::get('/absensianaksaya', [LaporanAbsensiController::class, 'absensianaksaya'])->name('absensianaksaya');

        // Jika ingin ijinkan export bagi wali
        Route::get('/laporanabsensi/export', [LaporanAbsensiController::class, 'export'])
            ->middleware('role:wali')
            ->name('laporanabsensi.export.wali');
    });

});
