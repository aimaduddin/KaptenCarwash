<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use Carbon\Carbon;
use Livewire\Component;

class Calendar extends Component
{
    public int $currentMonth;

    public int $currentYear;

    public $bookings = [];

    public ?string $selectedDate = null;

    public $selectedDateBookings = [];

    public array $bookingsByDate = [];

    public function mount(): void
    {
        $this->currentMonth = Carbon::now()->month;
        $this->currentYear = Carbon::now()->year;
        $this->loadBookings();
        $this->selectDate(Carbon::now()->format('Y-m-d'));
    }

    public function loadBookings(): void
    {
        $startDate = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $this->bookings = Booking::whereBetween('booking_date', [$startDate, $endDate])
            ->where('booking_status', '!=', 'CANCELLED')
            ->orderBy('booking_date')
            ->orderBy('booking_time')
            ->get();

        $this->bookingsByDate = $this->bookings->groupBy(function ($booking) {
            return $booking->booking_date->format('Y-m-d');
        })->map->count()->toArray();
    }

    public function previousMonth(): void
    {
        if ($this->currentMonth === 1) {
            $this->currentMonth = 12;
            $this->currentYear--;
        } else {
            $this->currentMonth--;
        }

        $this->loadBookings();
    }

    public function nextMonth(): void
    {
        if ($this->currentMonth === 12) {
            $this->currentMonth = 1;
            $this->currentYear++;
        } else {
            $this->currentMonth++;
        }

        $this->loadBookings();
    }

    public function selectDate(string $date): void
    {
        $this->selectedDate = $date;
        $this->selectedDateBookings = Booking::with(['user', 'carType'])
            ->where('booking_date', $date)
            ->get();
    }

    public function render()
    {
        if (empty($this->bookings)) {
            $this->loadBookings();
        }

        return view('livewire.admin.calendar');
    }
}
