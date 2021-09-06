<?php

use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Landlord\MyHomeController;
use App\Models\Home;
use Illuminate\Support\Facades\Route;

Route::middleware(['api'])->prefix('/landlord')->group(function () {
    Route::post('/homes/create', [MyHomeController::class, 'store'])
        ->name('landlord-home-create')
        ->middleware('can:create,' . Home::class);

    Route::get('/homes', [MyHomeController::class, 'index'])
        ->name('landlord-home-index')
        ->middleware('can:viewItsOwnHomes,' . Home::class);

    Route::get('/homes/{home}', [MyHomeController::class, 'show'])
        ->name('landlord-home-show')
        ->middleware('can:view,home');

    Route::patch('/homes/{home}', [MyHomeController::class, 'update'])
        ->name('landlord-home-update')
        ->middleware('can:update,home');
});

Route::middleware(['api'])->prefix('/admin')->group(function () {
    Route::get('/homes', [HomeController::class, 'index'])
        ->name('homes-index')
        ->middleware('can:viewAllHomes,' . Home::class);

    Route::get('/homes/{home}', [HomeController::class, 'show'])
        ->name('home-show')
        ->middleware('can:viewAHomeAsAdmin,home');
});