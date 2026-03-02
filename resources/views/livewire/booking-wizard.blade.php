<div class="min-h-screen bg-slate-950 flex flex-col">
    <!-- Progress Indicator -->
    <div class="fixed top-16 left-0 right-0 z-40 bg-slate-950/95 backdrop-blur-md border-b border-slate-800/50">
        <div class="flex items-center w-full px-4 py-3 max-w-lg mx-auto">
            @for ($i = 1; $i <= 4; $i++)
                <div class="flex items-center justify-center w-8 h-8 rounded-full text-sm font-semibold transition-all shrink-0
                    {{ $i < $currentStep ? 'bg-cyan-500 text-slate-950' : ($i === $currentStep ? 'bg-cyan-500/20 text-cyan-400 border-2 border-cyan-500' : 'bg-slate-800 text-slate-500') }}">
                    @if ($i < $currentStep)
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                    @else
                        {{ $i }}
                    @endif
                </div>
                @if ($i < 4)
                    <div class="flex-1 h-0.5 mx-2 rounded
                        {{ $i < $currentStep ? 'bg-cyan-500' : 'bg-slate-800' }}">
                    </div>
                @endif
            @endfor
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 pt-24 pb-24">
        @if ($currentStep === 1)
            <div wire:key="booking-step-1" class="p-4 md:p-6 max-w-lg mx-auto">
                <h2 class="text-2xl md:text-3xl font-bold text-slate-50 mb-2">Select Car Type</h2>
                <p class="text-slate-400 mb-6">Choose your vehicle type for accurate pricing</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4">
                    @foreach ($carTypes as $carType)
                        <button wire:click="selectCarType({{ $carType->id }})"
                                class="{{ $selectedCarTypeId === $carType->id ? 'border-cyan-500 bg-cyan-500/10' : 'border-slate-700 bg-slate-900' }} p-5 md:p-6 rounded-2xl border-2 transition-all hover:border-cyan-500/50 text-left group cursor-pointer">
                            <h3 class="text-lg md:text-xl font-semibold text-slate-50 mb-2 group-hover:text-cyan-400 transition-colors">{{ $carType->name }}</h3>
                            <p class="text-sm md:text-base text-slate-400">Price multiplier: {{ number_format($carType->price_multiplier, 2) }}x</p>
                        </button>
                    @endforeach
                </div>
            </div>
        @elseif ($currentStep === 2)
            <div wire:key="booking-step-2" class="p-4 md:p-6 max-w-lg mx-auto">
                <h2 class="text-2xl md:text-3xl font-bold text-slate-50 mb-2">Select Services</h2>
                <p class="text-slate-400 mb-6">Choose the services you need</p>
                <div class="space-y-3">
                    @foreach ($services as $service)
                        <button wire:click="toggleService({{ $service->id }})"
                                class="{{ in_array($service->id, $selectedServiceIds) ? 'border-cyan-500 bg-cyan-500/10' : 'border-slate-700 bg-slate-900' }} w-full p-4 md:p-5 rounded-2xl border-2 transition-all hover:border-cyan-500/50 text-left group cursor-pointer">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <h3 class="text-base md:text-lg font-semibold text-slate-50 mb-1 group-hover:text-cyan-400 transition-colors">{{ $service->name }}</h3>
                                    <p class="text-sm text-slate-400">{{ $service->duration_minutes }} min</p>
                                </div>
                                <div class="flex-shrink-0 text-right">
                                    <p class="text-lg md:text-xl font-bold text-cyan-500">RM {{ number_format($service->base_price / 100, 2) }}</p>
                                </div>
                            </div>
                        </button>
                    @endforeach
                </div>
                @if ($priceBreakdown)
                    <div class="mt-6 p-4 md:p-5 bg-slate-900 rounded-2xl border border-slate-800">
                        <div class="flex justify-between items-center">
                            <span class="text-base md:text-lg font-semibold text-slate-50">Total</span>
                            <span class="text-2xl md:text-3xl font-bold text-cyan-500">RM {{ number_format($priceBreakdown['totalPrice'] / 100, 2) }}</span>
                        </div>
                        <p class="text-sm text-slate-400 mt-2">Includes RM {{ number_format($priceBreakdown['bookingFee'] / 100, 2) }} booking fee</p>
                    </div>
                @endif
            </div>
        @elseif ($currentStep === 3)
            <div wire:key="booking-step-3" class="p-4 md:p-6 max-w-lg mx-auto">
                <h2 class="text-2xl md:text-3xl font-bold text-slate-50 mb-2">Select Date & Time</h2>
                <p class="text-slate-400 mb-6">Choose your preferred appointment time</p>
                
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Date</label>
                        <input type="date" wire:key="selected-date-input" wire:model.live="selectedDate"
                               class="w-full p-4 bg-slate-900 border border-slate-800 rounded-xl text-slate-50 text-base md:text-lg focus:border-cyan-500 focus:ring-cyan-500 transition-colors"
                               min="{{ today()->format('Y-m-d') }}">
                    </div>
                    
                    @if ($selectedDate && $timeSlots)
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Available Times</label>
                            <div class="grid grid-cols-3 sm:grid-cols-4 gap-2">
                                @foreach ($timeSlots as $slot)
                                    <button wire:click="selectTime('{{ $slot }}')"
                                            class="{{ $selectedTime === $slot ? 'bg-cyan-500 text-slate-950' : 'bg-slate-800 text-slate-50' }} p-3 md:p-4 rounded-xl font-semibold hover:bg-slate-700 transition-colors">
                                        {{ $slot }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                
                @if ($errors->has('time'))
                    <p class="mt-3 text-red-500 text-sm">{{ $errors->first('time') }}</p>
                @endif
            </div>
        @elseif ($currentStep === 4)
            <div wire:key="booking-step-4" class="p-4 md:p-6 max-w-lg mx-auto">
                <h2 class="text-2xl md:text-3xl font-bold text-slate-50 mb-2">Your Details</h2>
                <p class="text-slate-400 mb-6">Fill in your contact information</p>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Name</label>
                        <input type="text" wire:model.live="customerName"
                               class="w-full p-4 bg-slate-900 border border-slate-800 rounded-xl text-slate-50 text-base focus:border-cyan-500 focus:ring-cyan-500 transition-colors"
                               placeholder="Your full name">
                        @if ($errors->has('customerName'))
                            <p class="mt-1 text-red-500 text-sm">{{ $errors->first('customerName') }}</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Phone</label>
                        <input type="tel" wire:model.live="customerPhone"
                               class="w-full p-4 bg-slate-900 border border-slate-800 rounded-xl text-slate-50 text-base focus:border-cyan-500 focus:ring-cyan-500 transition-colors"
                               placeholder="e.g., +60 12 345 6789">
                        @if ($errors->has('customerPhone'))
                            <p class="mt-1 text-red-500 text-sm">{{ $errors->first('customerPhone') }}</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Car Plate</label>
                        <input type="text" wire:model.live="carPlate"
                               class="w-full p-4 bg-slate-900 border border-slate-800 rounded-xl text-slate-50 text-base focus:border-cyan-500 focus:ring-cyan-500 transition-colors"
                               placeholder="e.g., ABC 1234">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Notes (optional)</label>
                        <textarea wire:model.live="customerNotes"
                                  class="w-full p-4 bg-slate-900 border border-slate-800 rounded-xl text-slate-50 h-24 text-base focus:border-cyan-500 focus:ring-cyan-500 transition-colors resize-none"
                                  placeholder="Any special requests..."></textarea>
                    </div>
                </div>
            </div>
        @elseif ($currentStep === 5)
            <div wire:key="booking-step-5" class="p-4 md:p-6 max-w-lg mx-auto">
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-cyan-500/10 border-2 border-cyan-500 mb-4">
                        <svg class="w-8 h-8 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 7 17l-5-5"/>
                            <path d="m22 10-7.5 7.5L13 16"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl md:text-3xl font-bold text-slate-50 mb-2">Review & Confirm</h2>
                    <p class="text-slate-400">Please review your booking details</p>
                </div>
                
                @if ($priceBreakdown)
                    <div class="bg-slate-900 rounded-2xl p-4 md:p-6 border border-slate-800 space-y-4">
                        <div>
                            <h3 class="text-sm font-medium text-slate-400 mb-1">Car Type</h3>
                            <p class="text-base md:text-lg font-semibold text-slate-50">{{ collect($carTypes)->firstWhere('id', $selectedCarTypeId)?->name }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-slate-400 mb-2">Services</h3>
                            <ul class="space-y-2">
                                @foreach (collect($services)->whereIn('id', $selectedServiceIds) as $service)
                                    <li class="flex justify-between items-center">
                                        <span class="text-slate-300">{{ $service->name }}</span>
                                        <span class="font-semibold text-slate-50">RM {{ number_format($service->base_price / 100, 2) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-slate-400 mb-1">Date & Time</h3>
                            <p class="text-base md:text-lg font-semibold text-slate-50">{{ $selectedDate }} @ {{ $selectedTime }}</p>
                        </div>
                        <div class="pt-4 border-t border-slate-800">
                            <h3 class="text-sm font-medium text-slate-400 mb-2">Your Details</h3>
                            <div class="space-y-1">
                                <p class="text-slate-300"><span class="text-slate-500">Name:</span> {{ $customerName }}</p>
                                <p class="text-slate-300"><span class="text-slate-500">Phone:</span> {{ $customerPhone }}</p>
                                <p class="text-slate-300"><span class="text-slate-500">Car Plate:</span> {{ $carPlate ?: 'N/A' }}</p>
                                @if ($customerNotes)
                                    <p class="text-slate-300"><span class="text-slate-500">Notes:</span> {{ $customerNotes }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="pt-4 border-t border-slate-800">
                            <div class="flex justify-between items-center">
                                <span class="text-base md:text-lg font-semibold text-slate-50">Total</span>
                                <span class="text-2xl md:text-3xl font-bold text-cyan-500">RM {{ number_format($priceBreakdown['totalPrice'] / 100, 2) }}</span>
                            </div>
                            <p class="text-sm text-slate-400 mt-2">Includes RM {{ number_format($priceBreakdown['bookingFee'] / 100, 2) }} booking fee</p>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Bottom Action Bar -->
    <div class="fixed bottom-0 left-0 right-0 z-40 bg-slate-950/95 backdrop-blur-md border-t border-slate-800/50 pb-safe">
        <div class="flex items-center justify-between px-4 py-4 max-w-lg mx-auto gap-4">
            @if ($currentStep > 1)
                <button wire:click="previousStep" 
                        class="flex items-center justify-center gap-2 px-6 py-3 bg-slate-800 text-slate-50 rounded-xl hover:bg-slate-700 transition-colors font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    <span>Back</span>
                </button>
            @else
                <div></div>
            @endif

            <button
                wire:click="{{ $currentStep < 4 ? 'validateAndProceed' : ($currentStep === 4 ? 'goToStep(5)' : 'confirmBooking') }}"
                @if ($currentStep === 1 && !$selectedCarTypeId) disabled @endif
                @if ($currentStep === 2 && count($selectedServiceIds) === 0) disabled @endif
                @if ($currentStep === 3 && (!$selectedDate || !$selectedTime)) disabled @endif
                @if ($currentStep === 4 && (!$customerName || !$customerPhone)) disabled @endif
                class="{{ ($currentStep === 1 && !$selectedCarTypeId) || ($currentStep === 2 && count($selectedServiceIds) === 0) || ($currentStep === 3 && (!$selectedDate || !$selectedTime)) || ($currentStep === 4 && (!$customerName || !$customerPhone)) ? 'opacity-50 cursor-not-allowed' : 'opacity-100' }} flex-1 flex items-center justify-center gap-2 px-6 py-3 bg-cyan-500 text-slate-950 rounded-xl hover:bg-cyan-400 transition-all font-semibold disabled:opacity-50 disabled:cursor-not-allowed">
                <span>{{ $currentStep === 5 ? 'Confirm & Pay' : 'Continue' }}</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
    </div>
</div>
