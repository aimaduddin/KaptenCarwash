<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CarType;
use App\Models\Service;
use App\Services\PricingService;
use App\Services\AvailabilityService;
use Illuminate\Support\Collection;

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
    
    // Validation errors
    public $errors = [];

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
        if (!$this->selectedCarTypeId || empty($this->selectedServiceIds)) {
            $this->priceBreakdown = [];
            return;
        }

        $carType = $this->carTypes->find($this->selectedCarTypeId);
        $services = $this->services->whereIn('id', $this->selectedServiceIds);

        $this->priceBreakdown = PricingService::calculateBookingPrice($carType, $services, 10);
    }

    public function updatedSelectedDate(): void
    {
        if (!$this->selectedDate) {
            $this->timeSlots = [];
            return;
        }

        $config = [
            'business_hour_start' => config('kapten.business_hour_start'),
            'business_hour_end' => config('kapten.business_hour_end'),
            'slot_duration_minutes' => config('kapten.slot_duration_minutes'),
            'buffer_minutes' => config('kapten.buffer_minutes'),
        ];

        $this->timeSlots = AvailabilityService::generateTimeSlots($this->selectedDate, $config);
    }

    public function selectTime($time): void
    {
        $this->selectedTime = $time;
        $this->validateAndProceed();
    }

    public function validateAndProceed(): void
    {
        $this->errors = [];

        // Step 1 validation
        if ($this->currentStep === 1 && !$this->selectedCarTypeId) {
            $this->errors['car_type'] = 'Please select a car type';
            return;
        }

        // Step 2 validation
        if ($this->currentStep === 2 && empty($this->selectedServiceIds)) {
            $this->errors['services'] = 'Please select at least one service';
            return;
        }

        // Step 3 validation
        if ($this->currentStep === 3) {
            if (!$this->selectedDate) {
                $this->errors['date'] = 'Please select a date';
                return;
            }
            if (!$this->selectedTime) {
                $this->errors['time'] = 'Please select a time';
                return;
            }
        }

        // Step 4 validation
        if ($this->currentStep === 4) {
            if (!$this->customerName) {
                $this->errors['name'] = 'Name is required';
                return;
            }
            if (!$this->customerPhone) {
                $this->errors['phone'] = 'Phone is required';
                return;
            }
        }

        $this->currentStep = 5;
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
        // This will be implemented in later task when BookingService exists
        $this->dispatch('alert', message: 'Booking creation will be implemented in the next phase');
    }

    public function goToStep($step): void
    {
        $this->currentStep = $step;
    }
}
