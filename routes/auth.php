<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Customer\CustomerRegisterController;
use App\Http\Controllers\Landlord\LandlordRegisterController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('guest')
    ->name('login');

Route::post('/landlord-register', [LandlordRegisterController::class, 'register'])
    ->name('landlord-register');

Route::post('/customer-register', [CustomerRegisterController::class, 'register'])
    ->name('customer-register');

//Route::middleware(['api'])->prefix('auth')->group(function () {
//    Route::post('logout', [AuthController::class, 'logout']);
//    Route::post('refresh', [AuthController::class, 'refresh']);
//    Route::post('me', [AuthController::class, 'me']);
//});

//Route::middleware(['api'])->prefix('auth')->group(function () {
//    Route::post('logout', [AuthController::class, 'logout']);
//    Route::post('refresh', [AuthController::class, 'refresh']);
//    Route::post('me', [AuthController::class, 'me']);
//});
