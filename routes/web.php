<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Api\QrCodeController;
use App\Http\Controllers\QrTrackingController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

// Tracking & Contact Routes
Route::get('/t/{unique_code}', [QrTrackingController::class, 'track'])->name('qr.track');
Route::get('/vcf/{unique_code}', [QrTrackingController::class, 'downloadVcf'])->name('qr.vcf');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Password Reset
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Email Verification
    Route::get('/email/verify', [AuthController::class, 'verifyNotice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->middleware('signed')->name('verification.verify');
    Route::post('/email/verification-notification', [AuthController::class, 'verifyResend'])->middleware('throttle:6,1')->name('verification.send');
    // Protected UI Routes
    Route::middleware('verified')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/analytics/{qrCode}', [DashboardController::class, 'showAnalytics'])->name('dashboard.analytics');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    });

    // Protected API Actions
    Route::middleware('verified')->prefix('app-api')->group(function () {
        Route::delete('/qrcodes/{qrCode}', [DashboardController::class, 'destroy'])->name('dashboard.qrcodes.destroy');
        Route::post('/qrcodes/{qrCode}/send-email', [QrCodeController::class, 'sendToEmail'])->name('dashboard.qrcodes.send-email');
        Route::post('/qrcodes/download', [QrCodeController::class, 'saveAndDownload'])->name('qrcodes.download');
    });
});

// Public Preview API (Unprotected for Landing Page)
Route::post('/app-api/qrcodes/preview', [QrCodeController::class, 'preview'])->name('qrcodes.preview');
