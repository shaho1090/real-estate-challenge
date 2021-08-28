<?php

use App\Http\Controllers\Customer\MyHomeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api'])->prefix('/customer')->group(function () {
    Route::post('/homes/create', [MyHomeController::class, 'store'])->name('customer-home-create');
    Route::get('/homes', [MyHomeController::class, 'index'])->name('customer-home-index');
});
