<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\BookingWizard;

// Public routes
Route::get('/', fn () => view('pages.home'))->name('home');

Route::get('/book', BookingWizard::class)->name('book');

// Admin routes (protected)
Route::middleware(['auth'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('admin.dashboard');
    Route::view('profile', 'profile')->name('admin.profile');
});

require __DIR__.'/auth.php';
