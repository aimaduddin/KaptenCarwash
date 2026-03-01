<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\BookingWizard;
use App\Http\Controllers\BookingController;

// Public routes
Route::get('/', fn () => view('pages.home'))->name('home');
Route::get('/book', BookingWizard::class)->name('book');
Route::get('/book/success/{id}', [BookingController::class, 'show'])->name('book.success');
Route::get('/pay/dummy/{id}', 'App\\Livewire\\Payment\\DummyPayment::class')->name('payment.dummy');

// Admin routes (protected)
Route::middleware(['auth'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('admin.dashboard');
    Route::view('profile', 'profile')->name('admin.profile');
});

require __DIR__.'/auth.php';
