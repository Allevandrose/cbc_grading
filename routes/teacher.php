<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Teacher\DashboardController;
use App\Http\Controllers\Teacher\MarksController;
use App\Http\Controllers\Teacher\ReportsController;

Route::prefix('teacher')->name('teacher.')->middleware(['auth', 'role:teacher'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Marks Management
    Route::resource('marks', MarksController::class);
    Route::post('/marks/bulk-store', [MarksController::class, 'bulkStore'])->name('marks.bulk-store');

    // Reports
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/{student}', [ReportsController::class, 'show'])->name('reports.show');
    Route::get('/reports/{student}/pdf', [ReportsController::class, 'generatePdf'])->name('reports.pdf');
});
