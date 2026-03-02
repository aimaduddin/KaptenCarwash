<div class="p-4 md:p-6 pb-24 max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6">
        <div class="flex items-center w-full sm:w-auto justify-between sm:justify-start">
            <button wire:click="previousMonth" class="p-2 text-slate-400 hover:text-slate-200 bg-slate-900 rounded-lg border border-slate-800 hover:border-slate-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <span class="text-base md:text-lg font-semibold text-slate-200 mx-4">
                {{ \Carbon\Carbon::create()->month($currentMonth)->format('F') }} {{ $currentYear }}
            </span>
            <button wire:click="nextMonth" class="p-2 text-slate-400 hover:text-slate-200 bg-slate-900 rounded-lg border border-slate-800 hover:border-slate-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
        <div class="w-full sm:w-auto bg-cyan-500/10 border border-cyan-500/30 px-4 py-2.5 rounded-xl text-cyan-400 font-semibold text-sm text-center">
            {{ $selectedDate ? \Carbon\Carbon::parse($selectedDate)->format('F j, Y') : 'Select a date' }}
        </div>
    </div>
    
    <!-- Calendar Grid -->
    <div class="grid grid-cols-7 gap-1 md:gap-2 mb-2 text-center text-slate-400 text-xs md:text-sm font-medium">
        <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
    </div>
    
    <div class="grid grid-cols-7 gap-1 md:gap-2">
        @php
            $startOfMonth = \Carbon\Carbon::createFromDate($currentYear, $currentMonth, 1);
            $endOfMonth = $startOfMonth->copy()->endOfMonth();
            $startDayOfWeek = $startOfMonth->dayOfWeek; // 0 (Sun) to 6 (Sat)
            $totalDays = $endOfMonth->day;
            
            // Generate empty slots for padding
            $days = array_fill(0, $startDayOfWeek, null);
            for ($i = 1; $i <= $totalDays; $i++) {
                $days[] = $startOfMonth->copy()->addDays($i - 1);
            }
        @endphp

        @foreach ($days as $day)
            @if ($day)
                @php
                    $dateStr = $day->format('Y-m-d');
                    $count = $bookingsByDate[$dateStr] ?? 0;
                    $isSelected = $selectedDate === $dateStr;
                @endphp
                <div class="border {{ $isSelected ? 'border-cyan-500 bg-cyan-500/5 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'border-slate-800 bg-slate-900/50 hover:bg-slate-800' }} rounded-xl p-1 md:p-2 transition-all cursor-pointer min-h-[60px] md:min-h-[90px] flex flex-col items-center md:items-stretch"
                     wire:click="selectDate('{{ $dateStr }}')">
                    <div class="text-xs md:text-sm font-semibold md:text-right w-full {{ $isSelected ? 'text-cyan-400' : 'text-slate-300' }}">{{ $day->day }}</div>
                    @if ($count > 0)
                        <div class="mt-1 md:mt-2 flex justify-center md:justify-end w-full">
                            <span class="inline-flex items-center justify-center text-[11px] md:text-sm font-bold text-cyan-400 bg-cyan-500/20 rounded md:rounded-lg px-2 py-0.5 md:px-2 md:py-1.5 border border-cyan-500/30 text-center shadow-[0_0_10px_rgba(6,182,212,0.3)] leading-none md:leading-normal">
                                {{ $count }} <span class="hidden md:inline ml-1">booking{{ $count > 1 ? 's' : '' }}</span>
                            </span>
                        </div>
                    @endif
                </div>
            @else
                <div class="border border-slate-800/30 bg-slate-950/30 rounded-xl p-2 opacity-30 min-h-[60px] md:min-h-[90px]"></div>
            @endif
        @endforeach
    </div>
    
    @if ($selectedDate && $selectedDateBookings->isNotEmpty())
        <div class="mt-8 bg-slate-900 rounded-2xl p-4 md:p-6 shadow-xl border border-slate-800 animate-in fade-in slide-in-from-bottom-4 duration-300">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg md:text-xl font-bold text-slate-50">
                    {{ \Carbon\Carbon::parse($selectedDate)->format('l, F j') }}
                </h2>
                <button wire:click="$set('selectedDate', null)" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-800 text-slate-400 hover:text-cyan-400 hover:bg-slate-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <div class="space-y-3">
                @foreach ($selectedDateBookings as $booking)
                    <div class="bg-slate-950 rounded-xl p-4 border border-slate-800 hover:border-slate-700 transition-colors relative overflow-hidden group">
                        <div class="absolute left-0 top-0 bottom-0 w-1
                            {{ $booking->booking_status->value === 'CONFIRMED' ? 'bg-blue-500' : '' }}
                            {{ $booking->booking_status->value === 'IN_PROGRESS' ? 'bg-amber-500' : '' }}
                            {{ $booking->booking_status->value === 'COMPLETED' ? 'bg-emerald-500' : '' }}
                            {{ $booking->booking_status->value === 'CANCELLED' ? 'bg-red-500' : '' }}
                            {{ $booking->booking_status->value === 'PENDING_PAYMENT' ? 'bg-slate-500' : '' }}">
                        </div>
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3 pl-2">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="text-base font-bold text-slate-100">
                                        {{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }}
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                                        {{ $booking->booking_status->value === 'CONFIRMED' ? 'bg-blue-500/10 text-blue-400' : '' }}
                                        {{ $booking->booking_status->value === 'IN_PROGRESS' ? 'bg-amber-500/10 text-amber-400' : '' }}
                                        {{ $booking->booking_status->value === 'COMPLETED' ? 'bg-emerald-500/10 text-emerald-400' : '' }}
                                        {{ $booking->booking_status->value === 'CANCELLED' ? 'bg-red-500/10 text-red-400' : '' }}
                                        {{ $booking->booking_status->value === 'PENDING_PAYMENT' ? 'bg-slate-500/10 text-slate-400' : '' }}">
                                        {{ $booking->booking_status->value }}
                                    </span>
                                </div>
                                <div class="text-sm font-semibold text-slate-300">{{ $booking->customer_name ?? $booking->user?->name ?? 'Unknown User' }}</div>
                                <div class="text-xs text-slate-500">{{ $booking->carType->name ?? 'Unknown Car' }}</div>
                            </div>
                            <div class="sm:text-right flex sm:flex-col items-center sm:items-end justify-between border-t sm:border-t-0 border-slate-800 pt-2 sm:pt-0">
                                <a href="{{ route('admin.bookings') }}?search={{ $booking->id }}" class="text-xs font-semibold text-cyan-500 hover:text-cyan-400 transition-colors">
                                    View Details &rarr;
                                </a>
                                <div class="text-[10px] font-bold uppercase tracking-wider {{ $booking->payment_status->value === 'PAID' ? 'text-emerald-500' : 'text-amber-500' }}">
                                    {{ $booking->payment_status->value }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @elseif ($selectedDate)
        <div class="mt-8 bg-slate-900 rounded-2xl p-8 text-center border border-slate-800 border-dashed animate-in fade-in duration-300">
            <svg class="w-12 h-12 text-slate-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <div class="text-slate-300 font-medium mb-1">No bookings</div>
            <div class="text-slate-500 text-sm">No appointments scheduled for {{ \Carbon\Carbon::parse($selectedDate)->format('F j') }}</div>
        </div>
    @endif
</div>
