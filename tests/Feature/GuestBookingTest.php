<?php

namespace Tests\Feature;

use App\Livewire\BookingWizard;
use App\Models\Booking;
use App\Models\CarType;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class GuestBookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_confirm_booking_and_redirect_to_dummy_payment(): void
    {
        $carType = CarType::create([
            'name' => 'Sedan',
            'price_multiplier' => 1.00,
        ]);

        $service = Service::create([
            'name' => 'Basic Wash',
            'base_price' => 3000,
            'duration_minutes' => 30,
            'is_active' => true,
        ]);

        $component = Livewire::test(BookingWizard::class)
            ->set('selectedCarTypeId', $carType->id)
            ->set('selectedServiceIds', [$service->id])
            ->set('selectedDate', '2030-01-01')
            ->set('timeSlots', ['09:00'])
            ->set('selectedTime', '09:00')
            ->set('customerName', 'Guest Customer')
            ->set('customerPhone', '0123456789')
            ->set('carPlate', 'ABC1234')
            ->set('customerNotes', 'No notes')
            ->set('priceBreakdown', [
                'totalPrice' => 3300,
                'bookingFee' => 300,
            ])
            ->call('confirmBooking');

        $booking = Booking::first();

        $this->assertNotNull($booking);
        $this->assertNull($booking->user_id);
        $this->assertSame('Guest Customer', $booking->customer_name);
        $this->assertSame('0123456789', $booking->customer_phone);

        $component->assertRedirect(route('payment.dummy', $booking->id));
    }

    public function test_confirm_booking_revalidates_time_slots_when_component_slots_are_stale(): void
    {
        config()->set('kapten.business_hour_start', '09:00');
        config()->set('kapten.business_hour_end', '18:00');
        config()->set('kapten.slot_duration_minutes', 30);

        $carType = CarType::create([
            'name' => 'Sedan',
            'price_multiplier' => 1.00,
        ]);

        $service = Service::create([
            'name' => 'Basic Wash',
            'base_price' => 3000,
            'duration_minutes' => 30,
            'is_active' => true,
        ]);

        $component = Livewire::test(BookingWizard::class)
            ->set('selectedCarTypeId', $carType->id)
            ->set('selectedServiceIds', [$service->id])
            ->set('selectedDate', '2030-01-01')
            ->set('timeSlots', ['23:30'])
            ->set('selectedTime', '09:00')
            ->set('customerName', 'Guest Customer')
            ->set('customerPhone', '0123456789')
            ->set('priceBreakdown', [
                'totalPrice' => 3300,
                'bookingFee' => 300,
            ])
            ->call('confirmBooking');

        $booking = Booking::first();

        $this->assertNotNull($booking);
        $component->assertRedirect(route('payment.dummy', $booking->id));
    }
}
