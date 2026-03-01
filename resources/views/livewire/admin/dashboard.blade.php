<div class="p-6">
    <h1 class="text-2xl font-bold text-slate-50 mb-6">Dashboard</h1>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-slate-900 rounded-xl p-6 border border-slate-800">
            <div class="text-sm text-slate-400 mb-2">Total Bookings</div>
            <div class="text-3xl font-bold text-cyan-500">{{ $totalBookings }}</div>
        </div>
        
        <div class="bg-slate-900 rounded-xl p-6 border border-slate-800">
            <div class="text-sm text-slate-400 mb-2">Today's Bookings</div>
            <div class="text-3xl font-bold text-cyan-500">{{ $todayBookings }}</div>
        </div>
        
        <div class="bg-slate-900 rounded-xl p-6 border border-slate-800">
            <div class="text-sm text-slate-400 mb-2">Confirmed</div>
            <div class="text-3xl font-bold text-green-500">{{ $confirmedBookings }}</div>
        </div>
        
        <div class="bg-slate-900 rounded-xl p-6 border border-slate-800">
            <div class="text-sm text-slate-400 mb-2">In Progress</div>
            <div class="text-3xl font-bold text-yellow-500">{{ $inProgressBookings }}</div>
        </div>
        
        <div class="bg-slate-900 rounded-xl p-6 border border-slate-800">
            <div class="text-sm text-slate-400 mb-2">Today's Revenue</div>
            <div class="text-3xl font-bold text-cyan-500">RM {{ number_format($todayRevenue / 100, 2) }}</div>
        </div>
        
        <div class="bg-slate-900 rounded-xl p-6 border border-slate-800">
            <div class="text-sm text-slate-400 mb-2">Total Services</div>
            <div class="text-3xl font-bold text-cyan-500">{{ $servicesCount }}</div>
            <div class="text-sm text-slate-400 mb-2">Car Types</div>
            <div class="text-3xl font-bold text-cyan-500">{{ $carTypesCount }}</div>
        </div>
    </div>
    
    <!-- Today's Schedule -->
    <h2 class="text-xl font-bold text-slate-50 mb-6">Today's Schedule</h2>
    <div class="bg-slate-900 rounded-xl p-6 border border-slate-800">
        @forelse ($todaySchedule->isNotEmpty())
            <ul class="space-y-3">
                @foreach ($todaySchedule as $booking)
                    <li class="flex items-start gap-4 bg-slate-950 rounded-lg p-4">
                        <div>
                            <span class="text-lg font-semibold text-slate-50">{{ $booking->booking_time }}</span>
                            <div class="flex-1">
                                <div>
                                    <div class="text-sm text-slate-400">{{ $booking->user->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $booking->carType->name }}</div>
                                </div>
                            </div>
                            <div class="flex-1">
                                <span class="text-sm font-medium text-cyan-500">{{ $booking->booking_status->value }}</span>
                            </div>
                        </div>
                    </li>
                @empty
                    <div class="text-center text-slate-400 py-8">
                        <p class="text-lg">No bookings scheduled for today.</p>
                    </div>
                @endforelse
    </div>
</div>
