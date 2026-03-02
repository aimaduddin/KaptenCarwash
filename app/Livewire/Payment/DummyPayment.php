<?php

namespace App\Livewire\Payment;

use App\Models\Booking;
use Livewire\Component;

class DummyPayment extends Component
{
    public $booking;

    public $paymentStatus = null;

    public function mount($id): void
    {
        $this->booking = Booking::findOrFail($id);
    }

    public function simulateSuccess(): void
    {
        $this->paymentStatus = 'success';

        // Update booking status
        $this->booking->update([
            'payment_status' => 'PAID',
            'booking_status' => 'CONFIRMED',
        ]);

        // In real implementation, this would trigger notification
        // \App\Notifications\BookingConfirmedNotification::send($this->booking);

        session()->flash('success', 'Payment successful! Your booking is confirmed.');

        $this->redirect(route('book.success', ['id' => $this->booking->id]), navigate: true);
    }

    public function simulateFailure(): void
    {
        $this->paymentStatus = 'failed';

        $this->booking->update([
            'payment_status' => 'FAILED',
            'booking_status' => 'CANCELLED',
        ]);

        session()->flash('error', 'Payment failed. Please try again.');

        $this->redirect(route('book'), navigate: true);
    }

    public function simulateCancel(): void
    {
        $this->paymentStatus = 'cancelled';

        // Booking remains as PENDING_PAYMENT, user can try again
        session()->flash('info', 'Payment cancelled. You can try booking again.');

        $this->redirect(route('book'), navigate: true);
    }

    public function resetPayment(): void
    {
        $this->paymentStatus = null;
    }
}
