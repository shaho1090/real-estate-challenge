<?php

use App\Models\Appointment;
use Illuminate\Support\Facades\Route;

//------------- admin's routes for appointment -------------------------------------
Route::middleware(['api'])->prefix('/admin')->group(function () {

    Route::post('/appointments/create', [\App\Http\Controllers\Admin\AppointmentController::class, 'store'])
        ->name('create-appointment')
        ->middleware('can:create,' . Appointment::class);

    Route::get('/appointments', [\App\Http\Controllers\Admin\AppointmentController::class, 'index'])
        ->name('all-appointments');
});

//--------------------employee's routes for appointment ----------------------------
Route::middleware(['api'])->prefix('/employee')->group(function () {
    Route::get('/my-appointments', [\App\Http\Controllers\Employee\MyAppointmentController::class, 'index'])
        ->name('employee-my-appointments');
});


//-------------------customer's routes for appointment
Route::middleware(['api'])->prefix('/customer')->group(function () {
    Route::get('/my-appointments', [\App\Http\Controllers\Employee\MyAppointmentController::class, 'index'])
        ->name('customer-my-appointments');
});

