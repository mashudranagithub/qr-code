<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Api\QrCodeController;
use App\Http\Controllers\QrTrackingController;

Route::get('/', function () {
    return view('welcome');
});

// Tracking Route
Route::get('/t/{unique_code}', [QrTrackingController::class, 'track'])->name('qr.track');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/analytics/{qrCode}', [DashboardController::class, 'showAnalytics'])->name('dashboard.analytics');
    Route::delete('/dashboard/qrcodes/{qrCode}', [DashboardController::class, 'destroy'])->name('dashboard.qrcodes.destroy');
});

// Web API equivalent for stateful AJAX requests
Route::prefix('app-api/qrcodes')->group(function () {
    Route::post('/preview', [QrCodeController::class, 'preview']);
    Route::post('/download', [QrCodeController::class, 'saveAndDownload'])->middleware('auth');
});
