<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\QrCodeController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('qrcodes')->group(function () {
    Route::post('/preview', [QrCodeController::class, 'preview']);
    Route::post('/download', [QrCodeController::class, 'saveAndDownload']);
});
