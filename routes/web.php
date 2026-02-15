<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Include role-specific routes (Teacher, Admin, Accountant)
require __DIR__ . '/teacher.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/accountant.php';

// Authenticated Routes
Route::middleware('auth')->group(function () {

    // THIS IS THE FIX: 
    // Loads resources/views/profile.blade.php and names it 'profile'
    Route::view('profile', 'profile')->name('profile');

    // Centralized Dashboard Redirector
    Route::get('/dashboard', function () {
        $user = Auth::user();

        return match ($user->role) {
            'admin'      => redirect()->route('admin.dashboard'),
            'teacher'    => redirect()->route('teacher.dashboard'),
            'accountant' => redirect()->route('accountant.dashboard'),
            default      => redirect('/'),
        };
    })->name('dashboard');
});

require __DIR__ . '/auth.php';
