<?php

use App\Http\Controllers\Landlord\MyHomeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api'])->prefix('/landlord')->group(function () {
    Route::post('/homes/create', [MyHomeController::class, 'store'])->name('landlord-home-create');
    Route::get('/homes', [MyHomeController::class, 'index'])->name('landlord-home-index');
});
