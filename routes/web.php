<?php

use App\Http\Controllers\Admin;
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

// ===================== Mahasiswa Routes =====================
Route::prefix('mahasiswa')
    ->name('mahasiswa.')
    ->middleware(['auth', 'role:mahasiswa'])
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
            ->name('pengaduan.store');
        Route::get('/pengaduan/{pengaduan}', [Mahasiswa\PengaduanController::class, 'show'])
            ->name('pengaduan.show');

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
        Route::delete('/users/{user}', [Admin\UserController::class, 'destroy'])
            ->name('users.destroy');
    });
