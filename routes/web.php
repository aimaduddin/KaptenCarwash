<?php

use App\Http\Controllers\BookingController;
use App\Livewire\Admin\BookingList;
use App\Livewire\Admin\Calendar;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\KanbanBoard;
use App\Livewire\Payment\DummyPayment;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', fn () => view('pages.home'))->name('home');
Route::get('/book', fn () => view('pages.book'))->name('book');
Route::get('/book/success/{id}', [BookingController::class, 'show'])->name('book.success');
Route::get('/receipt/download/{id}', [BookingController::class, 'downloadReceipt'])->name('receipt.download');
Route::get('/pay/dummy/{id}', DummyPayment::class)->name('payment.dummy');

// Admin routes (protected with auth)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/today', KanbanBoard::class)->name('today');
    Route::get('/bookings', BookingList::class)->name('bookings');
    Route::get('/calendar', Calendar::class)->name('calendar');
    Route::get('/settings', \App\Livewire\Admin\Settings::class)->name('settings');
});

// Breeze auth routes
require __DIR__.'/auth.php';
