# Phase 2: Customer-Facing Features Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Implement customer-facing booking wizard, payment flow, and pages with 100% UI parity to Next.js version.

**Architecture:** Livewire 4.x components for reactive UI without JavaScript. BookingWizard handles all 5 steps internally. Payment flow uses mock provider pattern.

**Tech Stack:** Laravel 12, Livewire 4.x, Tailwind CSS v4, Alpine.js, PHPUnit

---

## Task 1: Create Landing Page

**Goal:** Port the landing page from Next.js to Laravel Blade.

**Files:**
- Create: `resources/views/pages/home.blade.php`

**Step 1: Create landing page blade file**

**File:** `resources/views/pages/home.blade.php`

```blade
<x-layouts.app title="Kapten Carwash">
<div class="min-h-screen flex flex-col">
    <main class="flex-1">
        <section class="py-20 px-6">
            <div class="mx-auto max-w-4xl">
                <h1 class="text-4xl font-bold text-cyan-500 mb-6">
                    Professional Car Wash & Detailing
                </h1>
                <p class="text-lg text-slate-300 mb-8 max-w-2xl">
                    Book your car wash appointment in minutes. Choose your car type, select services, and pick a convenient time slot.
                </p>
                <a href="{{ url('/book') }}" 
                   class="inline-flex items-center px-8 py-4 bg-cyan-500 text-slate-950 font-semibold rounded-lg hover:bg-cyan-600 transition-colors">
                    <span>Book Appointment</span>
                </a>
            </div>
        </section>
    </main>
    
    <footer class="bg-slate-900 border-t border-slate-800 py-6">
        <div class="mx-auto max-w-4xl px-6">
            <p class="text-center text-slate-400 text-sm">
                &copy; 2026 Kapten Carwash. All rights reserved.
            </p>
        </div>
    </footer>
</div>
</x-layouts.app>
```

**Step 2: Add route for landing page**

**File:** `routes/web.php`

Add to routes:
```php
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('pages.home'))->name('home');
```

**Step 3: Create public page views directory**

Run: `mkdir -p resources/views/pages`

Expected: Directory created.

**Step 4: Commit**

```bash
git add routes/web.php resources/views/pages/home.blade.php
git commit -m "feat: add landing page"
```

---

## Task 2: Create Booking Wizard (Livewire Component)

**Goal:** Create the main booking wizard component that handles all 5 steps.

**Files:**
- Create: `app/Livewire/BookingWizard.php`
- Create: `resources/views/livewire/booking-wizard.blade.php`

**Step 1: Create BookingWizard Livewire component**

**File:** `app/Livewire/BookingWizard.php`

```php
<?php

namespace App\Livewire;

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
        $this->js('alert(\'Booking creation will be implemented in the next phase\')');
    }

    public function goToStep($step): void
    {
        $this->currentStep = $step;
    }
}
```

**Step 2: Create BookingWizard blade view**

**File:** `resources/views/livewire/booking-wizard.blade.php`

```blade
<div class="min-h-screen bg-slate-950 pb-safe">
    @if ($currentStep === 1)
        <div class="p-6">
            <h2 class="text-2xl font-bold text-slate-50 mb-6">Select Car Type</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @foreach ($carTypes as $carType)
                    <button wire:click="selectCarType({{ $carType->id }})"
                            class="{{ $selectedCarTypeId === $carType->id ? 'border-cyan-500 bg-cyan-500/10' : 'border-slate-700 bg-slate-900' }} p-6 rounded-xl border-2 transition-all hover:border-cyan-500">
                        <h3 class="text-xl font-semibold text-slate-50 mb-2">{{ $carType->name }}</h3>
                        <p class="text-slate-400">Price multiplier: {{ number_format($carType->price_multiplier, 2) }}x</p>
                    </button>
                @endforeach
            </div>
        </div>
    @elseif ($currentStep === 2)
        <div class="p-6">
            <h2 class="text-2xl font-bold text-slate-50 mb-6">Select Services</h2>
            <div class="space-y-3">
                @foreach ($services as $service)
                    <button wire:click="toggleService({{ $service->id }})"
                            class="{{ in_array($service->id, $selectedServiceIds) ? 'border-cyan-500 bg-cyan-500/10' : 'border-slate-700 bg-slate-900' }} w-full p-4 rounded-xl border-2 transition-all hover:border-cyan-500 text-left">
                        <h3 class="text-lg font-semibold text-slate-50">{{ $service->name }}</h3>
                        <p class="text-slate-400">RM {{ number_format($service->base_price / 100, 2) }} • {{ $service->duration_minutes }} min</p>
                    </button>
                @endforeach
            </div>
            @if ($priceBreakdown)
                <div class="mt-6 p-4 bg-slate-900 rounded-xl border border-slate-800">
                    <h3 class="text-lg font-semibold text-slate-50 mb-3">Total: RM {{ number_format($priceBreakdown['totalPrice'] / 100, 2) }}</h3>
                    <p class="text-sm text-slate-400">Includes RM {{ number_format($priceBreakdown['bookingFee'] / 100, 2) }} booking fee</p>
                </div>
        </div>
    @elseif ($currentStep === 3)
        <div class="p-6">
            <h2 class="text-2xl font-bold text-slate-50 mb-6">Select Date & Time</h2>
            <div>
                <input type="date" wire:model.live="selectedDate" 
                       class="w-full p-4 bg-slate-900 border border-slate-800 rounded-lg text-slate-50"
                       min="{{ today()->format('Y-m-d') }}">
            </div>
            @if ($selectedDate && $timeSlots)
                <div class="mt-4 grid grid-cols-3 md:grid-cols-6 gap-2">
                    @foreach ($timeSlots as $slot)
                        <button wire:click="selectTime('{{ $slot }}')"
                                class="{{ $selectedTime === $slot ? 'bg-cyan-500 text-white' : 'bg-slate-800 text-slate-50' }} p-3 rounded-lg font-medium hover:bg-slate-700 transition-colors">
                            {{ $slot }}
                        </button>
                    @endforeach
                </div>
            @if ($errors.time)
                <p class="mt-2 text-red-500 text-sm">{{ $errors.time }}</p>
        </div>
    @elseif ($currentStep === 4)
        <div class="p-6">
            <h2 class="text-2xl font-bold text-slate-50 mb-6">Your Details</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Name</label>
                    <input type="text" wire:model="customerName"
                           class="w-full p-3 bg-slate-900 border border-slate-800 rounded-lg text-slate-50">
                    @if ($errors.name)
                        <p class="mt-1 text-red-500 text-sm">{{ $errors.name }}</p>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Phone</label>
                    <input type="tel" wire:model="customerPhone"
                           class="w-full p-3 bg-slate-900 border border-slate-800 rounded-lg text-slate-50">
                    @if ($errors.phone)
                        <p class="mt-1 text-red-500 text-sm">{{ $errors.phone }}</p>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Car Plate</label>
                    <input type="text" wire:model="carPlate"
                           class="w-full p-3 bg-slate-900 border border-slate-800 rounded-lg text-slate-50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Notes (optional)</label>
                    <textarea wire:model="customerNotes"
                              class="w-full p-3 bg-slate-900 border border-slate-800 rounded-lg text-slate-50 h-24"></textarea>
                </div>
            </div>
        </div>
    @elseif ($currentStep === 5)
        <div class="p-6">
            <h2 class="text-2xl font-bold text-slate-50 mb-6">Review & Confirm</h2>
            <div class="bg-slate-900 rounded-xl p-6 border border-slate-800">
                @if ($priceBreakdown)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-slate-50">Car Type</h3>
                        <p>{{ $carTypes->find($selectedCarTypeId)->name }}</p>
                    </div>
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-slate-50">Services</h3>
                        <ul class="space-y-1 text-slate-300">
                            @foreach ($services->whereIn('id', $selectedServiceIds) as $service)
                                <li>• {{ $service->name }} - RM {{ number_format($service->base_price / 100, 2) }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-slate-50">Date & Time</h3>
                        <p>{{ $selectedDate }} @ {{ $selectedTime }}</p>
                    </div>
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-slate-50">Your Details</h3>
                        <p><strong>Name:</strong> {{ $customerName }}</p>
                        <p><strong>Phone:</strong> {{ $customerPhone }}</p>
                        <p><strong>Car Plate:</strong> {{ $carPlate or 'N/A' }}</p>
                        @if ($customerNotes)
                            <p><strong>Notes:</strong> {{ $customerNotes }}</p>
                        @endif
                    </div>
                    <div class="border-t border-slate-800 pt-6">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-lg font-semibold text-slate-50">Total</span>
                            <span class="text-2xl font-bold text-cyan-500">RM {{ number_format($priceBreakdown['totalPrice'] / 100, 2) }}</span>
                        </div>
                        <p class="text-sm text-slate-400">Includes RM {{ number_format($priceBreakdown['bookingFee'] / 100, 2) }} booking fee</p>
                    </div>
                    <div class="flex gap-4 mt-6">
                        <button wire:click="goToStep(4)" class="flex-1 px-6 py-3 bg-slate-800 text-slate-50 rounded-lg hover:bg-slate-700">
                            Back
                        </button>
                        <button wire:click="confirmBooking" class="flex-1 px-6 py-3 bg-cyan-500 text-white font-semibold rounded-lg hover:bg-cyan-600">
                            Confirm & Pay RM {{ number_format($priceBreakdown['totalPrice'] / 100, 2) }}
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endif
    
    @if ($currentStep > 1)
        <div class="fixed top-4 left-4 z-10">
            <button wire:click="previousStep" 
                    class="flex items-center px-4 py-2 bg-slate-800 text-slate-50 rounded-lg hover:bg-slate-700">
                ← Back
            </button>
        </div>
    @endif
</div>
```

**Step 3: Create book page wrapper**

**File:** `resources/views/pages/book.blade.php`

```blade
<x-layouts.app title="Book Appointment">
    <livewire:booking-wizard />
</x-layouts.app>
```

**Step 4: Add route for booking page**

**File:** `routes/web.php`

Add to routes:
```php
Route::get('/book', BookingWizard::class)->name('book');
```

**Step 5: Commit**

```bash
git add app/Livewire/BookingWizard.php resources/views/livewire/booking-wizard.blade.php resources/views/pages/book.blade.php routes/web.php
git commit -m "feat: add booking wizard Livewire component (5 steps)"
```

---

## Task 3: Create Booking Success Page

**Goal:** Create the booking confirmation page after successful payment.

**Files:**
- Create: `resources/views/pages/book-success.blade.php`
- Add: `app/Http/Controllers/BookingController.php` (for data fetching)

**Step 1: Create BookingController**

**File:** `app/Http/Controllers/BookingController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function show($id): View
    {
        $booking = Booking::with(['user', 'carType', 'services'])->findOrFail($id);
        
        return view('pages.book-success', compact('booking'));
    }
}
```

**Step 2: Create book-success blade view**

**File:** `resources/views/pages/book-success.blade.php`

```blade
<x-layouts.app title="Booking Confirmed - Kapten Carwash">
<div class="min-h-screen flex items-center justify-center p-6 pb-safe">
    <div class="max-w-lg w-full">
        <div class="bg-slate-900 rounded-2xl p-8 border border-cyan-500">
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-cyan-500 rounded-full mb-4">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L6 17l-4 4H5l-4 4V7l4 4-1 1-0-1z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-slate-50 mb-2">Booking Confirmed!</h1>
                <p class="text-slate-300 mb-6">Thank you {{ $booking->user->name }}. Your car wash appointment has been successfully booked.</p>
            </div>
            
            <div class="border-t border-slate-800 pt-6">
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-slate-50 mb-2">Booking Reference</h3>
                    <p class="text-2xl font-mono text-cyan-500">{{ 'KW-' . str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</p>
                </div>
                
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-slate-50 mb-2">Car Type</h3>
                    <p class="text-slate-300">{{ $booking->carType->name }}</p>
                </div>
                
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-slate-50 mb-2">Services</h3>
                    <ul class="space-y-1 text-slate-300">
                        @foreach ($booking->services as $service)
                            <li>• {{ $service->name }}</li>
                        @endforeach
                    </ul>
                </div>
                
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-slate-50 mb-2">Date & Time</h3>
                    <p class="text-slate-300">{{ $booking->booking_date->format('F j, Y') }} @ {{ $booking->booking_time->format('g:i A') }}</p>
                </div>
                
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-slate-50 mb-2">Your Details</h3>
                    <p><strong>Name:</strong> {{ $booking->user->name }}</p>
                    <p><strong>Phone:</strong> {{ $booking->user->phone }}</p>
                    <p><strong>Car Plate:</strong> {{ $booking->car_plate ?? 'N/A' }}</p>
                </div>
                
                <div class="border-t border-slate-800 pt-6">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-semibold text-slate-50">Total Paid</span>
                        <span class="text-2xl font-bold text-cyan-500">RM {{ number_format($booking->total_price / 100, 2) }}</span>
                    </div>
                    <p class="text-sm text-slate-400 mt-2">Includes RM {{ number_format($booking->booking_fee / 100, 2) }} booking fee</p>
                </div>
            </div>
            
            <div class="mt-6 text-center">
                <a href="{{ url('/') }}" class="text-cyan-500 hover:text-cyan-400 font-medium">
                    ← Back to Home
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
```

**Step 3: Add booking controller routes**

**File:** `routes/web.php`

Add to routes:
```php
Route::get('/book/success/{id}', [BookingController::class, 'show'])->name('book.success');
```

**Step 4: Commit**

```bash
git add app/Http/Controllers/BookingController.php resources/views/pages/book-success.blade.php routes/web.php
git commit -m "feat: add booking success page with controller"
```

---

## Task 4: Create Dummy Payment Page (Livewire)

**Goal:** Create the dummy payment page for testing payment flow.

**Files:**
- Create: `app/Livewire/Payment/DummyPayment.php`
- Create: `resources/views/pages/pay/dummy.blade.php`

**Step 1: Create DummyPayment Livewire component**

**File:** `app/Livewire/Payment/DummyPayment.php`

```php
<?php

namespace App\Livewire\Payment;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

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
        
        return redirect()->route('book.success', ['id' => $this->booking->id]);
    }

    public function simulateFailure(): void
    {
        $this->paymentStatus = 'failed';
        
        $this->booking->update([
            'payment_status' => 'FAILED',
            'booking_status' => 'CANCELLED',
        ]);

        session()->flash('error', 'Payment failed. Please try again.');
        
        return redirect()->route('book');
    }

    public function simulateCancel(): void
    {
        $this->paymentStatus = 'cancelled';
        
        // Booking remains as PENDING_PAYMENT, user can try again
        session()->flash('info', 'Payment cancelled. You can try booking again.');
        
        return redirect()->route('book');
    }

    public function reset(): void
    {
        $this->paymentStatus = null;
    }
}
```

**Step 2: Create dummy payment blade view**

**File:** `resources/views/livewire/payment/dummy-payment.blade.php`

```blade
<div class="min-h-screen bg-slate-950 pb-safe flex items-center justify-center p-6">
    <div class="max-w-md w-full">
        <div class="bg-slate-900 rounded-2xl p-8 border border-slate-800">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-slate-50 mb-2">Dummy Payment</h1>
                <p class="text-slate-300">This is a test payment gateway for development purposes.</p>
            </div>
            
            <div class="mb-6">
                <div class="text-sm text-slate-400 mb-2">
                    <span class="text-slate-50">Booking ID:</span> {{ $booking->id }}
                </div>
                <div class="text-sm text-slate-400 mb-4">
                    <span class="text-slate-50">Amount:</span> RM {{ number_format($booking->total_price / 100, 2) }}
                </div>
            </div>
            
            <div class="space-y-3">
                <button wire:click="simulateSuccess"
                        class="w-full py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors">
                    ✓ Simulate Success
                </button>
                <button wire:click="simulateFailure"
                        class="w-full py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors">
                    ✗ Simulate Failure
                </button>
                <button wire:click="simulateCancel"
                        class="w-full py-3 bg-slate-700 text-slate-50 font-medium rounded-lg hover:bg-slate-600 transition-colors">
                    ✗ Simulate Cancel
                </button>
                <button wire:click="reset"
                        class="w-full py-3 bg-slate-800 text-slate-400 rounded-lg hover:bg-slate-700 transition-colors">
                    Reset
                </button>
            </div>
            
            @if ($paymentStatus === 'success')
                <div class="mt-4 p-4 bg-green-900/20 border border-green-700 rounded-lg text-center">
                    <p class="text-green-400">Payment successful! Redirecting...</p>
                </div>
            @elseif ($paymentStatus === 'failed')
                <div class="mt-4 p-4 bg-red-900/20 border border-red-700 rounded-lg text-center">
                    <p class="text-red-400">Payment failed</p>
                </div>
            @elseif ($paymentStatus === 'cancelled')
                <div class="mt-4 p-4 bg-slate-800 border border-slate-700 rounded-lg text-center">
                    <p class="text-slate-400">Payment cancelled</p>
                </div>
            @endif
        </div>
    </div>
</div>
```

**Step 3: Create payment page wrapper**

**File:** `resources/views/pages/pay/dummy.blade.php`

```blade
<x-layouts.app title="Payment - Kapten Carwash">
    <livewire:payment.dummy-payment />
</x-layouts.app>
```

**Step 4: Add payment routes**

**File:** `routes/web.php`

Add to routes:
```php
Route::get('/pay/dummy/{id}', [Livewire\Payment\DummyPayment::class])->name('payment.dummy');
```

**Step 5: Commit**

```bash
git add app/Livewire/Payment/DummyPayment.php resources/views/livewire/payment/dummy-payment.blade.php resources/views/pages/pay/ routes/web.php
git commit -m "feat: add dummy payment page Livewire component"
```

---

## Task 5: Create 404 and Offline Pages

**Goal:** Create error pages for better UX.

**Files:**
- Create: `resources/views/errors/404.blade.php`
- Create: `resources/views/pages/offline.blade.php`

**Step 1: Create 404 error page**

**File:** `resources/views/errors/404.blade.php`

```blade
<x-layouts.app title="Page Not Found - Kapten Carwash">
<div class="min-h-screen flex items-center justify-center p-6 pb-safe">
    <div class="text-center">
        <h1 class="text-6xl font-bold text-slate-500 mb-4">404</h1>
        <h2 class="text-2xl font-semibold text-slate-50 mb-6">Page Not Found</h2>
        <p class="text-slate-300 mb-8 max-w-md mx-auto">
            The page you're looking for doesn't exist or has been moved.
        </p>
        <a href="{{ url('/') }}" class="inline-block px-8 py-3 bg-cyan-500 text-white font-semibold rounded-lg hover:bg-cyan-600 transition-colors">
            ← Back to Home
        </a>
    </div>
</x-layouts.app>
```

**Step 2: Create offline PWA page**

**File:** `resources/views/pages/offline.blade.php`

```blade
<x-layouts.app title="Offline - Kapten Carwash">
<div class="min-h-screen flex items-center justify-center p-6 pb-safe">
    <div class="text-center max-w-lg w-full">
        <div class="mb-6">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-slate-800 rounded-full mb-4">
                <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172l-6.828 6.828a2 2 0 012 2 012 2 012-2.012l-1.414 1.414-1.414a2 2 0 001 2 012 2.012l-4.414 1.414-1.414a2 2 012-2.012l-1.414 1.414-1.414a2 2 012 2.012z"></path>
                </svg>
            </div>
        </div>
        <h1 class="text-3xl font-bold text-slate-50 mb-4">You're Offline</h1>
        <p class="text-slate-300 mb-8">
            It looks like you're not connected to the internet. Please check your connection and try again.
        </p>
        <button onclick="window.location.reload()" 
                class="inline-block px-8 py-3 bg-cyan-500 text-white font-semibold rounded-lg hover:bg-cyan-600 transition-colors">
            Retry
        </button>
    </div>
</x-layouts.app>
```

**Step 3: Commit**

```bash
git add resources/views/errors/404.blade.php resources/views/pages/offline.blade.php
git commit -m "feat: add 404 and offline PWA pages"
```

---

## Milestone: Phase 2 Complete

Phase 2 customer-facing features are now complete:
- ✅ Landing page with Book Appointment CTA
- ✅ Booking wizard with all 5 steps (car type → services → schedule → customer info → summary)
- ✅ Booking success page with full booking details
- ✅ Dummy payment page with success/failure/cancel simulation
- ✅ 404 and offline PWA pages
- ✅ All pages use Tailwind CSS v4 with identical styling to Next.js version

**Ready for Phase 3: Admin Panel**
