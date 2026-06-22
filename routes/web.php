<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\BuktiController;
use App\Http\Controllers\Mahasiswa;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — SILPM
|--------------------------------------------------------------------------
*/

// Halaman landing — redirect berdasarkan auth status
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('mahasiswa.dashboard');
    }
    return redirect()->route('login');
});

// ===================== Auth Routes (dari Breeze) =====================
require __DIR__.'/auth.php';

// ===================== Berkas Bukti (terotentikasi, disk privat) =====================
// Dipakai mahasiswa pemilik & admin — tidak diberi prefix role karena keduanya butuh akses.
Route::middleware('auth')->prefix('bukti')->name('bukti.')->group(function () {
    Route::get('/pengaduan/{pengaduan}', [BuktiController::class, 'pengaduan'])->name('pengaduan');
    Route::get('/riwayat/{statusHistory}', [BuktiController::class, 'statusHistory'])->name('riwayat');
});

// ===================== Mahasiswa Routes =====================
Route::prefix('mahasiswa')
    ->name('mahasiswa.')
    ->middleware(['auth', 'role:mahasiswa']) // 'verified' dihilangkan agar user lama yang login bisa langsung ke dashboard
    ->group(function () {

        // Dashboard mahasiswa
        Route::get('/dashboard', [Mahasiswa\DashboardController::class, 'index'])
            ->name('dashboard');

        // Manajemen pengaduan mahasiswa
        Route::get('/pengaduan', [Mahasiswa\PengaduanController::class, 'index'])
            ->name('pengaduan.index');
        Route::get('/pengaduan/buat', [Mahasiswa\PengaduanController::class, 'create'])
            ->name('pengaduan.create');
        Route::post('/pengaduan', [Mahasiswa\PengaduanController::class, 'store'])
            ->middleware('throttle:pengaduan-submit')
            ->name('pengaduan.store');
        Route::get('/pengaduan/{pengaduan}', [Mahasiswa\PengaduanController::class, 'show'])
            ->name('pengaduan.show');
        Route::get('/pengaduan/{pengaduan}/edit', [Mahasiswa\PengaduanController::class, 'edit'])
            ->name('pengaduan.edit');
        Route::put('/pengaduan/{pengaduan}', [Mahasiswa\PengaduanController::class, 'update'])
            ->name('pengaduan.update');
        Route::patch('/pengaduan/{pengaduan}/konfirmasi-selesai', [Mahasiswa\PengaduanController::class, 'konfirmasiSelesai'])
            ->name('pengaduan.konfirmasi-selesai');
        Route::patch('/pengaduan/{pengaduan}/tolak-konfirmasi', [Mahasiswa\PengaduanController::class, 'tolakKonfirmasi'])
            ->name('pengaduan.tolak-konfirmasi');
        Route::patch('/pengaduan/{pengaduan}/balas-informasi', [Mahasiswa\PengaduanController::class, 'balasInformasi'])
            ->name('pengaduan.balas-informasi');

        // Profil mahasiswa
        Route::get('/profil', [ProfileController::class, 'edit'])->name('profil.edit');
        Route::patch('/profil', [ProfileController::class, 'update'])->name('profil.update');
        Route::delete('/profil', [ProfileController::class, 'destroy'])->name('profil.destroy');
    });

// ===================== Admin Routes =====================
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        // Dashboard admin
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])
            ->name('dashboard');

        // Statistik & rekap pengaduan
        Route::get('/statistik', [Admin\StatistikController::class, 'index'])
            ->name('statistik');

        // Manajemen pengaduan (admin)
        // Route ekspor didaftarkan sebelum {pengaduan} agar "export" tidak ditangkap sebagai ID.
        Route::get('/pengaduan/export', [Admin\PengaduanController::class, 'export'])
            ->name('pengaduan.export');
        Route::get('/pengaduan', [Admin\PengaduanController::class, 'index'])
            ->name('pengaduan.index');
        Route::get('/pengaduan/{pengaduan}', [Admin\PengaduanController::class, 'show'])
            ->name('pengaduan.show');
        Route::patch('/pengaduan/{pengaduan}/status', [Admin\PengaduanController::class, 'updateStatus'])
            ->name('pengaduan.update-status');

        // Kelola pengguna (mahasiswa)
        Route::get('/users', [Admin\UserController::class, 'index'])
            ->name('users.index');
        Route::get('/users/{user}', [Admin\UserController::class, 'show'])
            ->name('users.show');
        Route::patch('/users/{user}/toggle-active', [Admin\UserController::class, 'toggleActive'])
            ->name('users.toggle-active');

        // Kelola kategori pengaduan
        Route::get('/kategori', [Admin\KategoriPengaduanController::class, 'index'])
            ->name('kategori.index');
        Route::get('/kategori/buat', [Admin\KategoriPengaduanController::class, 'create'])
            ->name('kategori.create');
        Route::post('/kategori', [Admin\KategoriPengaduanController::class, 'store'])
            ->name('kategori.store');
        Route::get('/kategori/{kategori}/edit', [Admin\KategoriPengaduanController::class, 'edit'])
            ->name('kategori.edit');
        Route::put('/kategori/{kategori}', [Admin\KategoriPengaduanController::class, 'update'])
            ->name('kategori.update');
        Route::patch('/kategori/{kategori}/toggle-active', [Admin\KategoriPengaduanController::class, 'toggleActive'])
            ->name('kategori.toggle-active');
        Route::delete('/kategori/{kategori}', [Admin\KategoriPengaduanController::class, 'destroy'])
            ->name('kategori.destroy');
    });
