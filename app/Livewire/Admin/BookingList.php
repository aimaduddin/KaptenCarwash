<?php

namespace App\Livewire\Admin;

use App\Enums\BookingStatus;
use App\Models\Booking;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class BookingList extends Component
{
    use WithPagination;

    public $statusFilter = 'all';
    public $dateFilter = null;
    public $bookings;
        'status' => ['all', 'confirmed', 'in_progress', 'completed', 'cancelled'],
    'sort' => ['booking_time', 'date'],
    'per_page' => 20,
    'sort_direction' => 'asc',
    'page' => 1,
    ];

    public function mount(): void
    {
        $this->applyFilters();
    }

    public function updatedStatusFilter(): void
    {
        $this->statusFilter = request()->get('status', 'all');
        $this->resetPage();
        $this->applyFilters();
    }

    public function updatedDateFilter($date): void
    {
        $ $this->dateFilter = $date;
        $this->resetPage();
        $this->applyFilters();
    }

    public function applyFilters(): void
    {
        $query = Booking::with(['user', 'carType', 'services']);

        if ($this->statusFilter !== 'all') {
            $query->where('booking_status', BookingStatus::from($this->statusFilter));
        }

        if ($this->dateFilter) {
            $query->whereDate('booking_date', $this->dateFilter);
        }

        $this->bookings = $query->orderBy('booking_time', 'desc')->orderBy('booking_date', 'desc')->paginate(20);
    }

    public function updateBookingStatus($bookingId, $newStatus): void
    {
        $booking = Booking::find($bookingId);
        $booking->booking_status = BookingStatus::from($newStatus);
        $booking->save();
    }

    public function render()
    {
        return view('livewire.admin.booking-list', [
            'bookings' => $this->bookings,
            'statusFilter' => $this->statusFilter,
            'dateFilter' => $this->dateFilter,
        'queryString' => $this->queryString,
        'filters' => [
                'status' => $this->statusFilter,
                'date' => $this->dateFilter,
            ],
        ]);
    }
}
