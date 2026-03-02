<div class="p-4 md:p-6 pb-24 max-w-7xl mx-auto">
    <h1 class="text-xl md:text-2xl font-bold text-slate-50 mb-4 md:mb-6">Dashboard</h1>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-6 mb-6 md:mb-8">
        <div class="bg-slate-900 rounded-xl p-4 md:p-6 border border-slate-800 hover:border-cyan-500/30 transition-colors">
            <div class="text-xs md:text-sm text-slate-400 mb-1 md:mb-2 font-medium">Total Bookings</div>
            <div class="text-2xl md:text-3xl font-bold text-cyan-500">{{ $totalBookings }}</div>
        </div>
        
        <div class="bg-slate-900 rounded-xl p-4 md:p-6 border border-slate-800 hover:border-cyan-500/30 transition-colors">
            <div class="text-xs md:text-sm text-slate-400 mb-1 md:mb-2 font-medium">Today's Bookings</div>
            <div class="text-2xl md:text-3xl font-bold text-cyan-500">{{ $todayBookings }}</div>
        </div>
        
        <div class="bg-slate-900 rounded-xl p-4 md:p-6 border border-slate-800 hover:border-cyan-500/30 transition-colors">
            <div class="text-xs md:text-sm text-slate-400 mb-1 md:mb-2 font-medium">Confirmed</div>
            <div class="text-2xl md:text-3xl font-bold text-emerald-500">{{ $confirmedBookings }}</div>
        </div>
        
        <div class="bg-slate-900 rounded-xl p-4 md:p-6 border border-slate-800 hover:border-cyan-500/30 transition-colors">
            <div class="text-xs md:text-sm text-slate-400 mb-1 md:mb-2 font-medium">In Progress</div>
            <div class="text-2xl md:text-3xl font-bold text-amber-500">{{ $inProgressBookings }}</div>
        </div>
        
        <div class="bg-slate-900 rounded-xl p-4 md:p-6 border border-slate-800 hover:border-cyan-500/30 transition-colors col-span-2 md:col-span-1">
            <div class="text-xs md:text-sm text-slate-400 mb-1 md:mb-2 font-medium">Today's Revenue</div>
            <div class="text-2xl md:text-3xl font-bold text-cyan-500">RM {{ number_format($todayRevenue / 100, 2) }}</div>
        </div>
        
        <div class="bg-slate-900 rounded-xl p-4 md:p-6 border border-slate-800 hover:border-cyan-500/30 transition-colors col-span-2 md:col-span-1 flex justify-between md:block">
            <div>
                <div class="text-xs md:text-sm text-slate-400 mb-1 md:mb-2 font-medium">Services</div>
                <div class="text-xl md:text-3xl font-bold text-slate-300">{{ $servicesCount }}</div>
            </div>
            <div class="md:mt-4 text-right md:text-left">
                <div class="text-xs md:text-sm text-slate-400 mb-1 md:mb-2 font-medium">Car Types</div>
                <div class="text-xl md:text-3xl font-bold text-slate-300">{{ $carTypesCount }}</div>
            </div>
        </div>
    </div>
    
    <!-- Today's Schedule -->
    <div class="flex items-center justify-between mb-4 md:mb-6">
        <h2 class="text-lg md:text-xl font-bold text-slate-50">Today's Schedule</h2>
    </div>
    
    <div class="bg-slate-900 rounded-xl p-4 md:p-6 border border-slate-800">
        @forelse ($todaySchedule as $booking)
            @if($loop->first)
                <ul class="space-y-3 md:space-y-4">
            @endif
            <li class="flex flex-col sm:flex-row sm:items-center gap-3 md:gap-4 bg-slate-950 rounded-xl p-4 border border-slate-800/50 hover:border-slate-700 transition-colors">
                <div class="flex items-center gap-4 sm:w-48 shrink-0">
                    <div class="w-12 h-12 rounded-full bg-cyan-500/10 text-cyan-500 flex items-center justify-center font-bold border border-cyan-500/20">
                        {{ \Carbon\Carbon::parse($booking->booking_time)->format('H:i') }}
                    </div>
                    <div>
                        <div class="text-sm font-medium text-slate-50">{{ $booking->customer_name ?? $booking->user?->name ?? 'Unknown' }}</div>
                        <div class="text-xs text-slate-400">{{ $booking->carType->name }}</div>
                    </div>
                </div>
                
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap gap-1">
                        @foreach($booking->services->take(2) as $service)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-800 text-slate-300">
                                {{ $service->name }}
                            </span>
                        @endforeach
                        @if($booking->services->count() > 2)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-800 text-slate-400">
                                +{{ $booking->services->count() - 2 }}
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="flex items-center justify-between sm:justify-end gap-4 shrink-0 mt-2 sm:mt-0 pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-800/50">
                    @switch($booking->booking_status->value)
                        @case('pending')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-500/10 text-yellow-500 border border-yellow-500/20">
                                {{ ucfirst($booking->booking_status->value) }}
                            </span>
                            @break
                        @case('confirmed')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
                                {{ ucfirst($booking->booking_status->value) }}
                            </span>
                            @break
                        @case('in_progress')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-cyan-500/10 text-cyan-500 border border-cyan-500/20">
                                {{ ucfirst($booking->booking_status->value) }}
                            </span>
                            @break
                        @case('completed')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-800 text-slate-300 border border-slate-700">
                                {{ ucfirst($booking->booking_status->value) }}
                            </span>
                            @break
                        @case('cancelled')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-500/10 text-red-500 border border-red-500/20">
                                {{ ucfirst($booking->booking_status->value) }}
                            </span>
                            @break
                    @endswitch
                    <a href="{{ route('admin.bookings') }}?search={{ $booking->id }}" class="p-2 text-slate-400 hover:text-cyan-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </li>
            @if($loop->last)
                </ul>
            @endif
        @empty
            <div class="text-center text-slate-400 py-12 flex flex-col items-center">
                <svg class="w-16 h-16 text-slate-800 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-base md:text-lg font-medium text-slate-300">No bookings scheduled for today.</p>
                <p class="text-sm mt-1 text-slate-500">Take a break or check upcoming days.</p>
            </div>
        @endforelse
    </div>
</div>
