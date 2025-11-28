<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\JuriController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\HasilController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    // Registration temporarily disabled - use demo accounts instead
    // Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    // Route::post('register', [RegisterController::class, 'register']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Route utana
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

  // Routes untuk Peserta
    Route::resource('peserta', PesertaController::class);

    // Routes untuk Juri
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
