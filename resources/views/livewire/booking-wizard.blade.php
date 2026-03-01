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
