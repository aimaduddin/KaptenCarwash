<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmed extends Notification implements ShouldQueue
{
    use Queueable;

    public $booking;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Booking Confirmed - Kapten Carwash')
            ->greeting('Hello '.($this->booking->customer_name ?? $this->booking->user?->name ?? 'Customer').',')
            ->line('Your booking for a car wash has been confirmed.')
            ->line('Date: '.$this->booking->booking_date->format('l, F j, Y'))
            ->line('Time: '.$this->booking->booking_time->format('H:i'))
            ->line('Car Type: '.$this->booking->carType->name)
            ->line('Total Price: RM '.number_format($this->booking->total_price / 100, 2))
            ->action('View Booking', url('/book/success/'.$this->booking->id))
            ->line('Thank you for choosing Kapten Carwash!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'amount' => $this->booking->total_price,
            'date' => $this->booking->booking_date,
        ];
    }
}
