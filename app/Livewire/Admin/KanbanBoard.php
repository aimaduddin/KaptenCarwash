<?php

namespace App\Livewire\Admin;

use App\Enums\BookingStatus;
use App\Models\Booking;
use Carbon\Carbon;
use Livewire\Component;

class KanbanBoard extends Component
{
    public $todayDate;
    public $activeTab = 'confirmed';
    public $columns = [
        'confirmed' => [],
        'inProgress' => [],
        'completed' => [],
    ];

    public function mount(): void
    {
        $this->todayDate = Carbon::today()->format('Y-m-d');
        $this->loadBookings();
    }

    public function loadBookings(): void
    {
        $bookings = Booking::with(['user', 'carType'])
            ->where('booking_date', $this->todayDate)
            ->where('booking_status', '!=', BookingStatus::CANCELLED)
            ->orderBy('booking_time')
            ->get();

        $this->columns = [
            'confirmed' => $bookings->filter(fn ($b) => $b->booking_status->value === BookingStatus::CONFIRMED)->values(),
            'inProgress' => $bookings->filter(fn ($b) => $b->booking_status->value === BookingStatus::IN_PROGRESS)->values(),
            'completed' => $bookings->filter(fn ($b) => $b->booking_status->value === BookingStatus::COMPLETED)->values(),
        ];
    }

    public function switchTab($tab): void
    {
        $this->activeTab = $tab;
    }

    public function updateBookingStatus($bookingId, $newStatus): void
    {
        $booking = Booking::find($bookingId);
        $booking->booking_status = BookingStatus::from($newStatus);
        $booking->save();
        
        $this->loadBookings();
    }
}
