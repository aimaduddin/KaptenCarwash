<?php

use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', fn () => view('pages.home'))->name('home');

// Admin routes (protected)
Route::middleware(['auth'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('admin.dashboard');
    Route::view('profile', 'profile')->name('admin.profile');
});

require __DIR__.'/auth.php';
