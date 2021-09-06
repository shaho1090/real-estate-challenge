<?php

use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Landlord\MyHomeController;
use App\Models\Home;
use Illuminate\Support\Facades\Route;

/**
 * landlord routes
 */
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

/***
 * admin routes
 */
Route::middleware(['api'])->prefix('/admin')->group(function () {
    Route::get('/homes', [App\Http\Controllers\Admin\HomeController::class, 'index'])
        ->name('homes-index')
        ->middleware('can:viewAllHomes,' . Home::class);

    Route::get('/homes/{home}', [App\Http\Controllers\Admin\HomeController::class, 'show'])
        ->name('home-show')
        ->middleware('can:viewAHomeAsAdmin,home');
});

/**
 * route for normal user
 */
Route::middleware(['api'])->prefix('/public')->group(function () {
    Route::get('/homes', [App\Http\Controllers\HomeController::class, 'index'])
        ->name('homes-public-index');
});
