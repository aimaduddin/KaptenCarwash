<?php

namespace App\Livewire\Admin;

use App\Enums\BookingStatus;
use App\Models\Booking;
use Livewire\Component;
use Livewire\WithPagination;

class BookingList extends Component
{
    use WithPagination;

    public string $statusFilter = 'all';

    public ?string $dateFilter = null;

    protected $queryString = [
        'statusFilter' => ['except' => 'all'],
        'dateFilter' => ['except' => ''],
    ];

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatedDateFilter(): void
    {
        $this->resetPage();
    }

    public function updateBookingStatus(int $bookingId, string $newStatus): void
    {
        $booking = Booking::find($bookingId);
        $booking->booking_status = BookingStatus::from($newStatus);
        $booking->save();
    }

    public function render()
    {
        $query = Booking::with(['user', 'carType', 'services']);

        if ($this->statusFilter !== 'all') {
            $query->where('booking_status', BookingStatus::from($this->statusFilter));
        }

        if ($this->dateFilter) {
            $query->whereDate('booking_date', $this->dateFilter);
        }

        $bookings = $query->orderBy('booking_date', 'desc')->orderBy('booking_time', 'desc')->paginate(20);

        return view('livewire.admin.booking-list', compact('bookings'));
    }
}
