<?php

use App\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Route;

Route::post('/appointment/create', [AppointmentController::class, 'store'])
    ->middleware(['api'])
    ->name('create-appointment');

Route::get('/appointments', [AppointmentController::class, 'index'])
    ->middleware(['api'])
    ->name('appointments');

//Route::middleware(['api'])->prefix('auth')->group(function () {
//    Route::post('logout', [AuthController::class, 'logout']);
//    Route::post('refresh', [AuthController::class, 'refresh']);
//    Route::post('me', [AuthController::class, 'me']);
//});
