<div class="p-6">
    <h1 class="text-2xl font-bold text-slate-50 mb-6">Today</h1>
    
    <!-- Mobile Tab Switcher -->
    <div class="md:hidden flex gap-2 mb-6">
        <button type="button"
                wire:click="switchTab('confirmed')"
                class="{{ $activeTab === 'confirmed' ? 'text-cyan-500 border-b-2 border-cyan-500' : 'text-slate-400 border-slate-700' }} px-6 py-2 rounded-lg transition-colors">
            Confirmed
        </button>
        <button type="button"
                wire:click="switchTab('in_progress')"
                class="{{ $activeTab === 'in_progress' ? 'text-cyan-500 border-b-2 border-cyan-500' : 'text-slate-400 border-slate-700' }} px-6 py-2 rounded-lg transition-colors">
            In Progress
        </button>
        <button type="button"
                wire:click="switchTab('completed')"
                class="{{ $activeTab === 'completed' ? 'text-cyan-500 border-b-2 border-cyan-500' : 'text-slate-400 border-slate-700' }} px-6 py-2 rounded-lg transition-colors">
            Completed
        </button>
    </div>
    
    <!-- Kanban Board -->
    <div class="flex gap-6 min-h-[600px]">
        <!-- Confirmed Column -->
        <div class="flex-1 flex-col min-w-[300px]">
            <div class="font-semibold text-slate-400 mb-4 px-4">
                Confirmed
                <span class="text-sm text-slate-500">({{ $columns.confirmed|count }})</span>
            </div>
            
            <div wire:poll.30s class="flex-1 gap-3 overflow-y-auto">
                @foreach ($columns.confirmed as $booking)
                    <div class="bg-slate-950 rounded-lg p-4 border border-slate-800 shadow-sm">
                        <div class="flex justify-between items-start mb-3">
                            <span class="text-sm text-slate-400">{{ $booking->booking_time }}</span>
                            <div class="flex-1">
                                <div>
                                    <div class="text-sm text-slate-400">{{ $booking->user->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $booking->carType->name }}</div>
                                </div>
                            </div>
                            <button wire:click="updateBookingStatus({{ $booking->id }}, 'in_progress')"
                                    class="px-3 py-1 text-xs text-slate-400 bg-yellow-500 rounded hover:bg-yellow-600">
                                → In Progress
                            </button>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.bookings.show', $booking->id) }}"
                               class="text-cyan-500 hover:text-cyan-400">
                                View Details
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-slate-400 py-8">
                        <p>No confirmed bookings today.</p>
                    </div>
                @endforeach
            </div>
            
            <!-- In Progress Column -->
            <div class="flex-1 flex-col min-w-[300px]">
                <div class="font-semibold text-slate-400 mb-4 px-4">
                    In Progress
                    <span class="text-sm text-slate-500">({{ $columns.inProgress|count }})</span>
                </div>
                
                <div wire:poll.30s class="flex-1 gap-3 overflow-y-auto">
                    @foreach ($columns.inProgress as $booking)
                        <div class="bg-slate-950 rounded-lg p-4 border border-slate-800 shadow-sm">
                            <div class="flex justify-between items-start mb-3">
                                <span class="text-sm text-slate-400">{{ $booking->booking_time }}</span>
                                <div class="flex-1">
                                    <div>
                                        <div class="text-sm text-slate-400">{{ $booking->user->name }}</div>
                                        <div class="text-xs text-slate-500">{{ $booking->carType->name }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button wire:click="updateBookingStatus({{ $booking->id }}, 'completed')"
                                    class="px-3 py-1 text-xs text-slate-400 bg-green-500 rounded hover:bg-green-600">
                                    → Completed
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-slate-400 py-8">
                        <p>No bookings in progress.</p>
                    </div>
                @endforeach
            </div>
            
            <!-- Completed Column -->
            <div class="flex-1 flex-col min-w-[300px]">
                <div class="font-semibold text-slate-400 mb-4 px-4">
                    Completed
                    <span class="text-sm text-slate-500">({{ $columns.completed|count }})</span>
                </div>
                
                <div wire:poll.30s class="flex-1 gap-3 overflow-y-auto">
                    @foreach ($columns.completed as $booking)
                        <div class="bg-slate-950 rounded-lg p-4 border border-slate-800 shadow-sm">
                            <div class="flex justify-between items-start mb-3">
                                <span class="text-sm text-slate-400">{{ $booking->booking_time }}</span>
                                <div class="flex-1">
                                    <div>
                                        <div class="text-sm text-slate-400">{{ $booking->user->name }}</div>
                                        <div class="text-xs text-slate-500">{{ $booking->carType->name }}</div>
                                        <div class="text-xs text-green-500">PAID</div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('admin.bookings.show', $booking->id) }}"
                                   class="text-cyan-500 hover:text-cyan-400">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-slate-400 py-8">
                        <p class="text-lg">No completed bookings today.</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
