<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Accountant\DashboardController;
use App\Http\Controllers\Accountant\FeeController;
use App\Http\Controllers\Accountant\ClearanceController;

Route::prefix('accountant')->name('accountant.')->middleware(['auth', 'role:accountant'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Fee Management
    Route::resource('fees', FeeController::class);
    Route::get('/fees/student/{student}', [FeeController::class, 'studentFees'])->name('fees.student');

    // Clearance
    Route::get('/clearance', [ClearanceController::class, 'index'])->name('clearance.index');
    Route::post('/clearance/{student}/toggle', [ClearanceController::class, 'toggle'])->name('clearance.toggle');
});
