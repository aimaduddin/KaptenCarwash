<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <button wire:click="previousMonth" class="text-slate-400 hover:text-slate-200 disabled:opacity-50 cursor-not-allowed">
                ←
            </button>
            <div class="text-lg font-semibold text-slate-500">
                {{ $currentMonth->format('F Y') }} {{ $currentYear }}
            </div>
            <button wire:click="nextMonth" class="text-slate-400 hover:text-slate-200 disabled:opacity-50 cursor-not-allowed">
                →
            </button>
        </div>
        <div class="bg-cyan-500 px-4 py-2 rounded-lg text-white font-medium">
            {{ $selectedDate ? $selectedDate->format('F j, Y') : 'Select a date' }}
        </div>
    </div>
    
    <!-- Calendar Grid -->
    @if ($selectedDate && $selectedDateBookings->isNotEmpty())
        <div class="mt-8">
            <div class="mb-4">
                <h2 class="text-xl font-semibold text-slate-50">
                    {{ $selectedDate->format('l, F j, Y') }}
                </h2>
                
                <div class="grid grid-cols-1 gap-4">
                    @for ($i = 0; $i < 7; $i++)
                        <div class="border border-slate-800 rounded-lg p-4 hover:border-cyan-500 cursor-pointer"
                             wire:click="selectDate('{{ Carbon::parse($selectedDate)->addDays($i)->format('Y-m-d') }}')">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-slate-50 mb-1">{{ $selectedDate->format('D') }}</div>
                                @if ($selectedDateBookings[$i]->count() > 0)
                                    <div class="mt-2 text-cyan-500">{{ $selectedDateBookings[$i]|count }} bookings</div>
                                @else
                                    <div class="text-slate-400">No bookings</div>
                                @endif
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        @else
            <div class="text-center text-slate-400 py-16">
                <p>Select a date to view bookings.</p>
            </div>
        @endif
    </div>
    
    @if ($selectedDate && $selectedDateBookings->isNotEmpty())
        <div class="fixed inset-0 top-20 left-1/2 w-full h-3/4 bg-slate-950/95 backdrop-blur-sm flex items-center justify-center">
            <div class="bg-slate-900 rounded-2xl p-4 max-w-md shadow-2xl">
                <h2 class="text-lg font-semibold text-slate-50 mb-4">
                    {{ $selectedDate->format('l, F j, Y') }}
                    <button wire:click="$set('selectedDate', null)" class="absolute top-2 right-2 text-cyan-500 hover:text-cyan-400">
                        ✕
                    </button>
                </h2>
                
                <div class="space-y-4">
                    @foreach ($selectedDateBookings as $booking)
                        <div class="bg-slate-800 rounded-lg p-4">
                            <div class="flex justify-between items-center mb-2">
                                <div class="text-sm text-slate-400">{{ $booking->booking_time }}</div>
                                <div>
                                    <div>{{ $booking->user->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $booking->carType->name }}</div>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="text-cyan-500 hover:text-cyan-400">
                                    View Details
                                </a>
                                <div class="text-xs text-green-500">{{ $booking->payment_status->value }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
