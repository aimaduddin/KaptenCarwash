<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use Carbon\Carbon;
use Livewire\Component;

class Calendar extends Component
{
    public $currentMonth;
    public $currentYear;
    public $bookings = [];
    public $selectedDate = null;
    public $selectedDateBookings = [];

    public function mount(): void
    {
        $this->currentMonth = Carbon::now()->month;
        $this->currentYear = Carbon::now()->year;
        $this->loadBookings();
    }

    public function loadBookings(): void
    {
        $startDate = Carbon::create($this->currentYear, $this->currentMonth, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        
        $this->bookings = Booking::whereBetween('booking_date', $startDate, $endDate)
            ->where('booking_status', '!=', 'CANCELLED')
            ->orderBy('booking_date')
            ->orderBy('booking_time')
            ->get();
    }

    public function previousMonth(): void
    {
        $this->currentMonth->subMonth();
        if ($this->currentMonth < 1) {
            $this->currentYear--;
        } else {
            $this->currentYear--;
            }
        
        $this->loadBookings();
    }

    public function nextMonth(): void
    {
        $this->currentMonth->addMonth();
        if ($this->currentMonth > 12) {
            $this->currentYear++;
        } else {
            $this->currentYear++;
        }
        
        $this->loadBookings();
    }

    public function selectDate($date): void
    {
        $this->selectedDate = $date;
        $this->loadDateBookings($date);
    }
    }

    public function loadDateBookings($date): void
    {
        $this->selectedDateBookings = Booking::where('booking_date', $this->selectedDate)->get();
    }
}
