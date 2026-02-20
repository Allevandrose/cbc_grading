<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Teacher\DashboardController;
use App\Http\Controllers\Teacher\MarksController;
use App\Http\Controllers\Teacher\ReportsController;
use App\Http\Controllers\Teacher\StudentController; // ADD THIS

Route::prefix('teacher')->name('teacher.')->middleware(['auth', 'role:teacher'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Marks Management
    Route::get('/marks/select', [MarksController::class, 'select'])->name('marks.select');
    Route::get('/marks/create', [MarksController::class, 'create'])->name('marks.create');
    Route::post('/marks/bulk-store', [MarksController::class, 'bulkStore'])->name('marks.bulk-store');
    Route::resource('marks', MarksController::class)->except(['create']);

    // Reports
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/{student}', [ReportsController::class, 'show'])->name('reports.show');
    Route::get('/reports/{student}/pdf', [ReportsController::class, 'generatePdf'])->name('reports.pdf');

    // Student Management - ADD THIS SECTION
    Route::resource('students', StudentController::class);
    Route::post('/students/{student}/toggle-active', [StudentController::class, 'toggleActive'])->name('students.toggle-active');
    Route::post('/students/{student}/graduated', [StudentController::class, 'markGraduated'])->name('students.graduated');
});
