<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\JuriController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\HasilController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Juri\JuriController as RoleJuriController;
use App\Http\Controllers\Peserta\PesertaController as RolePesertaController;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Profile routes for all authenticated users
    Route::get('profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('profile/password', [AuthController::class, 'updatePassword'])->name('password.update');

    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('users', [AdminController::class, 'users'])->name('users');
        Route::get('users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::patch('users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggle');
        Route::delete('users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
        Route::get('settings', [AdminController::class, 'settings'])->name('settings');
        Route::get('analytics', [AdminController::class, 'analytics'])->name('analytics');
        Route::get('export', [AdminController::class, 'export'])->name('export');
    });

    // Juri Routes
    Route::middleware('role:juri')->prefix('juri')->name('juri.')->group(function () {
        Route::get('dashboard', [RoleJuriController::class, 'dashboard'])->name('dashboard');
        Route::get('pesertas', [RoleJuriController::class, 'pesertas'])->name('pesertas');
        Route::get('pesertas/{peserta}/evaluate', [RoleJuriController::class, 'evaluate'])->name('evaluate');
        Route::post('pesertas/{peserta}/evaluate', [RoleJuriController::class, 'saveEvaluation'])->name('save_evaluation');
        Route::get('history', [RoleJuriController::class, 'history'])->name('history');
        Route::get('statistics', [RoleJuriController::class, 'statistics'])->name('statistics');
        Route::get('profile', [RoleJuriController::class, 'profile'])->name('profile');
    });

    // Peserta Routes
    Route::middleware('role:peserta')->prefix('peserta')->name('peserta.')->group(function () {
        Route::get('dashboard', [RolePesertaController::class, 'dashboard'])->name('dashboard');
        Route::get('results', [RolePesertaController::class, 'results'])->name('results');
        Route::get('profile', [RolePesertaController::class, 'profile'])->name('profile');
        Route::put('profile', [RolePesertaController::class, 'updateProfile'])->name('profile.update');
        Route::get('competition', [RolePesertaController::class, 'competition'])->name('competition');
        Route::get('ranking', [RolePesertaController::class, 'ranking'])->name('ranking');
    });

    // Legacy Routes for Admin - Keep existing functionality
    Route::middleware('role:admin')->group(function () {
        // Routes untuk Peserta (CRUD)
        Route::resource('peserta', PesertaController::class);

        // Routes untuk Juri (CRUD)
        Route::resource('juri', JuriController::class);

        // Routes untuk Penilaian
        Route::get('penilaian', [PenilaianController::class, 'index'])->name('penilaian.index');
        Route::get('penilaian/create', [PenilaianController::class, 'create'])->name('penilaian.create');
        Route::post('penilaian/store', [PenilaianController::class, 'store'])->name('penilaian.store');
        Route::get('penilaian/{peserta_id}/{juri_id}', [PenilaianController::class, 'edit'])->name('penilaian.edit');
        Route::put('penilaian/{peserta_id}/{juri_id}', [PenilaianController::class, 'update'])->name('penilaian.update');

        // Routes untuk Hasil dan Perhitungan
        Route::get('hasil', [HasilController::class, 'index'])->name('hasil.index');
        Route::post('hasil/hitung-smart', [HasilController::class, 'hitungSMART'])->name('hasil.hitung_smart');
        Route::post('hasil/hitung-borda', [HasilController::class, 'hitungBorda'])->name('hasil.hitung_borda');
        Route::post('hasil/hitung-gabungan', [HasilController::class, 'hitungGabungan'])->name('hasil.hitung_gabungan');
        Route::get('hasil/matriks-smart', [HasilController::class, 'matriksSMART'])->name('hasil.matriks_smart');
        Route::get('hasil/matriks-borda', [HasilController::class, 'matriksBorda'])->name('hasil.matriks_borda');
    });

    // Redirect based on role for root path
    Route::get('/', function () {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isJuri()) {
            return redirect()->route('juri.dashboard');
        } elseif ($user->isPeserta()) {
            return redirect()->route('peserta.dashboard');
        }

        return redirect()->route('login');
    })->name('dashboard');
});
