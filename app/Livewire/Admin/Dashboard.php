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
    public function render()
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
            ->where('booking_status', [BookingStatus::CONFIRMED, BookingStatus::IN_PROGRESS])
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

        return view('livewire.admin.dashboard', [
            'totalBookings' => $totalBookings,
            'todayBookings' => $todayBookings,
            'confirmedBookings' => $confirmedBookings,
            'inProgressBookings' => $inProgressBookings,
            'todayRevenue' => $todayRevenue,
            'todaySchedule' => $todaySchedule,
            'upcomingBookings' => $upcomingBookings,
            'carTypesCount' => $carTypesCount,
            'servicesCount' => $servicesCount,
        ]);
    }
}
