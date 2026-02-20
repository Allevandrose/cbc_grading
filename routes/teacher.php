<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Teacher\DashboardController;
use App\Http\Controllers\Teacher\MarksController;
use App\Http\Controllers\Teacher\ReportsController;
use App\Http\Controllers\Teacher\StudentController;
use App\Http\Controllers\Teacher\SetupController;

/*
|--------------------------------------------------------------------------
| Teacher Routes (PathCore-Analytics)
|--------------------------------------------------------------------------
*/

Route::prefix('teacher')->name('teacher.')->middleware(['auth', 'role:teacher'])->group(function () {

    // --- ONBOARDING SECTION ---
    Route::get('/setup', [SetupController::class, 'create'])->name('setup');
    Route::post('/setup', [SetupController::class, 'store'])->name('setup.store');

    // --- PROTECTED DASHBOARD & FEATURES ---
    Route::middleware(['teacher.setup'])->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // --- SETTINGS / PROFILE MANAGEMENT ---
        Route::controller(SetupController::class)->prefix('settings')->name('settings.')->group(function () {
            Route::get('/', 'settingsIndex')->name('index');    // teacher.settings.index
            Route::post('/', 'settingsUpdate')->name('update'); // teacher.settings.update
        });

        // --- MARKS MANAGEMENT ---
        Route::controller(MarksController::class)->prefix('marks')->name('marks.')->group(function () {
            Route::get('/select', 'select')->name('select');
            Route::get('/create', 'create')->name('create');
            Route::post('/bulk-store', 'bulkStore')->name('bulk-store');
        });
        Route::resource('marks', MarksController::class)->except(['create']);

        // --- STUDENT MANAGEMENT ---
        Route::resource('students', StudentController::class);
        Route::controller(StudentController::class)->prefix('students')->name('students.')->group(function () {
            Route::post('/{student}/toggle-active', 'toggleActive')->name('toggle-active');
            Route::post('/{student}/graduated', 'markGraduated')->name('graduated');
        });

        // --- REPORTS ---
        Route::controller(ReportsController::class)->prefix('reports')->name('reports.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{student}', 'show')->name('show');
            Route::get('/{student}/pdf', 'generatePdf')->name('pdf');
        });
    });
});
