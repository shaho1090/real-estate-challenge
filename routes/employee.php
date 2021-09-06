<?php

use App\Http\Controllers\Admin\EmployeeController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::middleware(['api'])->prefix('/admin')->group(function () {
    Route::get('/employee', [EmployeeController::class, 'index'])
        ->name('employee-index')
        ->middleware('can:viewAny,' . User::class);

//    Route::get('/homes', [MyHomeController::class, 'index'])
//        ->name('landlord-home-index')
//        ->middleware('can:viewItsOwnHomes,' . Home::class);
//
//    Route::get('/homes/{home}', [MyHomeController::class, 'show'])
//        ->name('landlord-home-show')
//        ->middleware('can:view,home');
//
//    Route::patch('/homes/{home}', [MyHomeController::class, 'update'])
//        ->name('landlord-home-update')
//        ->middleware('can:update,home');
});

