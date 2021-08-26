<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::post('/homes/create', [HomeController::class, 'store'])
    ->middleware(['api'])
    ->name('create-home');

Route::get('/homes', [HomeController::class, 'index'])
    ->middleware(['api'])
    ->name('homes');

//Route::middleware(['api'])->prefix('auth')->group(function () {
//    Route::post('logout', [AuthController::class, 'logout']);
//    Route::post('refresh', [AuthController::class, 'refresh']);
//    Route::post('me', [AuthController::class, 'me']);
//});
