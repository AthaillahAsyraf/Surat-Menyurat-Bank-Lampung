<?php
// routes/web.php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 🔐 GUEST ROUTES: Reset Password (JANGAN dalam auth)
Route::middleware('guest')->group(function () {
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

// Profile routes (hanya perlu auth, tidak perlu password.changed)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// Protected routes dengan middleware password.changed
Route::middleware(['auth', 'password.changed'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Surat routes
    Route::get('/surat', [SuratController::class, 'index'])->name('surat.index');
    Route::get('/surat/keluar', [SuratController::class, 'suratKeluar'])->name('surat.keluar');
    Route::get('/surat/create', [SuratController::class, 'create'])->name('surat.create');
    Route::post('/surat', [SuratController::class, 'store'])->name('surat.store');
    Route::get('/surat/{id}', [SuratController::class, 'show'])->name('surat.show');
    Route::get('/surat/{id}/download', [SuratController::class, 'download'])->name('surat.download');
    
    // Reply routes
    Route::get('/surat/{id}/reply', [SuratController::class, 'reply'])->name('surat.reply');
    Route::post('/surat/{id}/reply', [SuratController::class, 'storeReply'])->name('surat.storeReply');
    Route::get('/surat/{id}/thread', [SuratController::class, 'showThread'])->name('surat.thread');

    Route::delete('/surat/{id}', [SuratController::class, 'destroy'])->name('surat.destroy');
});
