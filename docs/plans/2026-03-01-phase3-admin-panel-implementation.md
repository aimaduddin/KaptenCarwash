# Phase 3: Admin Panel Implementation

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Implement all admin panel pages with 100% UI parity to Next.js version.

**Architecture:** Livewire 4.x components for reactive UI without JavaScript. KanbanBoard uses `wire:poll.30s` for 30-second auto-refresh. BookingList uses filters and pagination. Calendar is a monthly calendar view. Settings manages pricing and blocked dates.

**Tech Stack:** Laravel 12, Livewire 4.x, Tailwind CSS v4, Alpine.js, PHPUnit

---

## Task 1: Dashboard Livewire Component

**Goal:** Create main admin dashboard with stats by status, today's revenue, today's schedule, upcoming 7 days.

**Files:**
- Create: `app/Livewire/Admin/Dashboard.php`
- Create: `resources/views/livewire/admin/dashboard.blade.php`

**Step 1: Create Dashboard Livewire component**

**File:** `app/Livewire/Admin/Dashboard.php`

```php
<?php

namespace App\Livewire\Admin;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Models\CarType;
use App\Models\Service;
use Carbon\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    public function render(): View
    {
        // Stats
        $totalBookings = Booking::count();
        $todayBookings = Booking::whereDate('today')->count();
        $confirmedBookings = Booking::where('booking_status', BookingStatus::CONFIRMED)->count();
        $inProgressBookings = Booking::where('booking_status', BookingStatus::IN_PROGRESS)->count();
        $todayRevenue = Booking::where('booking_date', '>=', 'today')
            ->where('payment_status', PaymentStatus::PAID)
            ->get()
            ->sum('total_price');

        // Today's schedule
        $todaySchedule = Booking::with(['user', 'carType'])
            ->where('booking_date', 'today')
            ->whereIn('booking_status', [BookingStatus::CONFIRMED, BookingStatus::IN_PROGRESS])
            ->orderBy('booking_time')
            ->get();

        // Upcoming 7 days
        $upcomingBookings = Booking::with(['user', 'carType'])
            ->whereBetween('booking_date', Carbon::tomorrow(), Carbon::today()->addDays(7))
            ->where('booking_status', '!=', BookingStatus::CANCELLED)
            ->orderBy('booking_date')
            ->orderBy('booking_time')
            ->limit(10)
            ->get();

        $carTypesCount = CarType::count();
        $servicesCount = Service::where('is_active', true)->count();
    }
}
```

**Step 2: Create Dashboard blade view**

**File:** `resources/views/livewire/admin/dashboard.blade.php`

```blade
<div class="p-6">
    <h1 class="text-2xl font-bold text-slate-50 mb-6">Dashboard</h1>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-slate-900 rounded-xl p-6 border border-slate-800">
            <div class="text-sm text-slate-400 mb-2">Total Bookings</div>
            <div class="text-3xl font-bold text-cyan-500 mb-6">{{ $totalBookings }}</div>
        </div>
        
        <div class="bg-slate-900 rounded-xl p-6 border-slate-800">
            <div class="text-sm text-slate-400 mb-2">Today's Bookings</div>
            <div class="text-3xl font-bold text-slate-500 mb-2">{{ $todayBookings }}</div>
        </div>
        
        <div class="bg-slate-900 rounded-xl p-6 border-slate-800">
            <div class="text-sm text-slate-400 mb-2">Confirmed</div>
            <div class="text-3xl font-bold text-green-500 mb-2">{{ $confirmedBookings }}</div>
        </div>
        
        <div class="bg-slate-900 rounded-xl p-6 border-slate-800">
            <div class="text-sm text-slate-400 mb-2">In Progress</div>
            <div class="text-3xl font-bold text-yellow-500 mb-2">{{ $inProgressBookings }}</div>
        </div>
        
        <div class="bg-slate-900 rounded-xl p-6 border-slate-800">
            <div class="text-sm text-slate-400 mb-2">Today's Revenue</div>
            <div class="text-3xl font-bold text-cyan-500 mb-2">RM {{ number_format($todayRevenue / 100, 2) }}</div>
        </div>
        
        <div class="bg-slate-900 rounded-xl p-6 border-slate-800">
            <div class="text-sm text-slate-400 mb-2">Total Services</div>
            <div class="text-3xl font-bold text-slate-500 mb-2">{{ $servicesCount }}</div>
        </div>
        
        <div class="bg-slate-900 rounded-xl p-6 border-slate-800">
            <div class="text-sm text-slate-400 mb-2">Car Types</div>
            <div class="text-3xl font-bold text-slate-500 mb-2">{{ $carTypesCount }}</div>
        </div>
    </div>
    
    <!-- Today's Schedule -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-50 mb-6">Today's Schedule</h2>
        
        @forelse ($todaySchedule->isNotEmpty())
            <ul class="space-y-3">
                @foreach ($todaySchedule as $booking)
                    <li class="flex items-start gap-4 bg-slate-950 rounded-lg p-4 shadow-sm">
                        <div class="flex flex-1">
                            <span class="text-lg font-semibold text-slate-50 mb-2">{{ $booking->booking_time }}</span>
                            <div>
                                <div>
                                    <div class="text-sm text-slate-400">{{ $booking->user->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $booking->carType->name }}</div>
                                </div>
                            </div>
                        </div>
                    </li>
                @empty
                    <div class="text-center text-slate-400 py-16">
                        <p class="text-lg">No bookings scheduled for today.</p>
                    </div>
                @endforeach
            </div>
    </div>
    
    <!-- Upcoming 7 days -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-500 mb-6">Upcoming 7 Days</h2>
        
        <div class="bg-slate-900 rounded-xl p-6 border-slate-800">
            <div class="text-sm text-slate-400 mb-2">({{ $upcomingBookings|count }}) upcoming bookings</div>
        </div>
    </div>
</div>
