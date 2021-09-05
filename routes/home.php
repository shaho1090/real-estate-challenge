<?php

use App\Http\Controllers\Landlord\MyHomeController;
use App\Models\Home;
use Illuminate\Support\Facades\Route;

Route::middleware(['api'])->prefix('/landlord')->group(function () {
    Route::post('/homes/create', [MyHomeController::class, 'store'])
        ->name('landlord-home-create')
        ->middleware('can:create,' . Home::class);

    Route::get('/homes', [MyHomeController::class, 'index'])
        ->name('landlord-home-index')
        ->middleware('can:viewAny,' . Home::class);

    Route::get('/homes/{home}', [MyHomeController::class, 'show'])
        ->name('landlord-home-show')
        ->middleware('can:view,home');
});
