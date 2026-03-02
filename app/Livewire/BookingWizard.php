<?php

namespace App\Livewire;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Models\CarType;
use App\Models\Service;
use App\Services\AvailabilityService;
use App\Services\PricingService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BookingWizard extends Component
{
    public $currentStep = 1;

    // Step 1: Car Type
    public $carTypes;

    public $selectedCarTypeId = null;

    // Step 2: Services
    public $services;

    public $selectedServiceIds = [];

    public $priceBreakdown = [];

    // Step 3: Schedule
    public $selectedDate = null;

    public $timeSlots = [];

    public $selectedTime = null;

    // Step 4: Customer Form
    public $customerName = '';

    public $customerPhone = '';

    public $carPlate = '';

    public $customerNotes = '';

    public function mount(): void
    {
        $this->carTypes = CarType::all();
        $this->services = Service::where('is_active', true)->get();
    }

    public function selectCarType($carTypeId): void
    {
        $this->selectedCarTypeId = $carTypeId;
        $this->selectedServiceIds = [];
        $this->currentStep = 2;
    }

    public function toggleService($serviceId): void
    {
        if (in_array($serviceId, $this->selectedServiceIds)) {
            $this->selectedServiceIds = array_values(array_filter($this->selectedServiceIds, fn ($id) => $id !== $serviceId));
        } else {
            $this->selectedServiceIds[] = $serviceId;
        }

        $this->calculatePrice();
    }

    public function calculatePrice(): void
    {
        if (! $this->selectedCarTypeId || empty($this->selectedServiceIds)) {
            $this->priceBreakdown = [];

            return;
        }

        $carType = $this->carTypes->find($this->selectedCarTypeId);
        $services = $this->services->whereIn('id', $this->selectedServiceIds);

        $this->priceBreakdown = PricingService::calculateBookingPrice($carType, $services, 10);
    }

    public function updatedSelectedDate(): void
    {
        if (! $this->isValidBookingDate($this->selectedDate)) {
            $this->timeSlots = [];
            $this->selectedTime = null;

            return;
        }

        $config = [
            'business_hour_start' => config('kapten.business_hour_start'),
            'business_hour_end' => config('kapten.business_hour_end'),
            'slot_duration_minutes' => (int) config('kapten.slot_duration_minutes'),
            'buffer_minutes' => (int) config('kapten.buffer_minutes'),
        ];

        $this->timeSlots = AvailabilityService::generateTimeSlots($this->selectedDate, $config);
    }

    public function selectTime($time): void
    {
        $this->selectedTime = $time;
    }

    public function validateAndProceed(): void
    {
        // Step 1 validation
        if ($this->currentStep === 1 && ! $this->selectedCarTypeId) {
            $this->addError('car_type', 'Please select a car type');

            return;
        }

        // Step 2 validation
        if ($this->currentStep === 2 && empty($this->selectedServiceIds)) {
            $this->addError('services', 'Please select at least one service');

            return;
        }

        // Step 3 validation
        if ($this->currentStep === 3) {
            if (! $this->isValidBookingDate($this->selectedDate)) {
                $this->addError('date', 'Please select a date');

                return;
            }

            if (empty($this->timeSlots)) {
                $config = [
                    'business_hour_start' => config('kapten.business_hour_start'),
                    'business_hour_end' => config('kapten.business_hour_end'),
                    'slot_duration_minutes' => (int) config('kapten.slot_duration_minutes'),
                    'buffer_minutes' => (int) config('kapten.buffer_minutes'),
                ];

                $this->timeSlots = AvailabilityService::generateTimeSlots($this->selectedDate, $config);
            }

            if (! $this->selectedTime || ! in_array($this->selectedTime, $this->timeSlots, true)) {
                $this->addError('time', 'Please select a time');

                return;
            }
        }

        // Step 4 validation
        if ($this->currentStep === 4) {
            if (! $this->customerName) {
                $this->addError('customerName', 'Name is required');

                return;
            }
            if (! $this->customerPhone) {
                $this->addError('customerPhone', 'Phone is required');

                return;
            }
        }

        $this->currentStep++;
    }

    public function previousStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function nextStep(): void
    {
        if ($this->currentStep === 2) {
            $this->calculatePrice();
        }

        $this->currentStep++;
    }

    public function confirmBooking(): void
    {
        if (! $this->selectedCarTypeId) {
            $this->currentStep = 1;
            $this->addError('car_type', 'Please select a car type');

            return;
        }

        if (empty($this->selectedServiceIds)) {
            $this->currentStep = 2;
            $this->addError('services', 'Please select at least one service');

            return;
        }

        if (! $this->isValidBookingDate($this->selectedDate)) {
            $this->currentStep = 3;
            $this->addError('date', 'Please select a date');

            return;
        }

        $config = [
            'business_hour_start' => config('kapten.business_hour_start'),
            'business_hour_end' => config('kapten.business_hour_end'),
            'slot_duration_minutes' => (int) config('kapten.slot_duration_minutes'),
            'buffer_minutes' => (int) config('kapten.buffer_minutes'),
        ];

        $this->timeSlots = AvailabilityService::generateTimeSlots($this->selectedDate, $config);

        if (! $this->selectedTime || ! in_array($this->selectedTime, $this->timeSlots, true)) {
            $this->currentStep = 3;
            $this->addError('time', 'Please select a valid time');

            return;
        }

        if (! $this->customerName) {
            $this->currentStep = 4;
            $this->addError('customerName', 'Name is required');

            return;
        }

        if (! $this->customerPhone) {
            $this->currentStep = 4;
            $this->addError('customerPhone', 'Phone is required');

            return;
        }

        if (empty($this->priceBreakdown)) {
            $this->addError('price', 'Price calculation failed. Please try again.');

            return;
        }

        $carType = $this->carTypes->find($this->selectedCarTypeId);
        $services = $this->services->whereIn('id', $this->selectedServiceIds);

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'customer_name' => $this->customerName,
            'customer_phone' => $this->customerPhone,
            'car_type_id' => $this->selectedCarTypeId,
            'booking_date' => $this->selectedDate,
            'booking_time' => $this->selectedTime,
            'car_plate' => $this->carPlate,
            'customer_notes' => $this->customerNotes,
            'total_price' => $this->priceBreakdown['totalPrice'],
            'booking_fee' => $this->priceBreakdown['bookingFee'],
            'payment_status' => PaymentStatus::UNPAID,
            'booking_status' => BookingStatus::PENDING_PAYMENT,
        ]);

        foreach ($services as $service) {
            $booking->services()->attach($service->id, [
                'effective_price' => $service->base_price * $carType->price_multiplier,
            ]);
        }

        if (! Auth::check()) {
            $guestBookings = session('guest_bookings', []);
            $guestBookings[] = $booking->id;
            session(['guest_bookings' => $guestBookings]);
        }

        $this->redirect(route('payment.dummy', $booking->id), navigate: true);
    }

    public function goToStep($step): void
    {
        $this->currentStep = $step;
    }

    private function isValidBookingDate($date): bool
    {
        if (! is_string($date) || ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return false;
        }

        $parsedDate = \DateTime::createFromFormat('Y-m-d', $date);

        return $parsedDate !== false && $parsedDate->format('Y-m-d') === $date;
    }
}
