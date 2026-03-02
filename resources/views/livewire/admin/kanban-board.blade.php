<div class="p-4 md:p-6 pb-24 max-w-7xl mx-auto">
    <h1 class="text-xl md:text-2xl font-bold text-slate-50 mb-4 md:mb-6">Today</h1>
    
    <!-- Mobile Tab Switcher -->
    <div class="md:hidden flex gap-2 mb-6 overflow-x-auto pb-2 scrollbar-hide">
        <button type="button"
                wire:click="switchTab('confirmed')"
                class="{{ $activeTab === 'confirmed' ? 'text-cyan-500 border-b-2 border-cyan-500' : 'text-slate-400 border-transparent hover:text-slate-300' }} px-4 py-2 text-sm font-medium whitespace-nowrap transition-colors">
            Confirmed
        </button>
        <button type="button"
                wire:click="switchTab('in_progress')"
                class="{{ $activeTab === 'in_progress' ? 'text-cyan-500 border-b-2 border-cyan-500' : 'text-slate-400 border-transparent hover:text-slate-300' }} px-4 py-2 text-sm font-medium whitespace-nowrap transition-colors">
            In Progress
        </button>
        <button type="button"
                wire:click="switchTab('completed')"
                class="{{ $activeTab === 'completed' ? 'text-cyan-500 border-b-2 border-cyan-500' : 'text-slate-400 border-transparent hover:text-slate-300' }} px-4 py-2 text-sm font-medium whitespace-nowrap transition-colors">
            Completed
        </button>
    </div>
    
    <!-- Kanban Board -->
    <div class="flex flex-col md:flex-row gap-6 min-h-[600px] overflow-x-auto">
        <!-- Confirmed Column -->
        <div class="flex-1 flex-col min-w-full md:min-w-[300px] {{ $activeTab === 'confirmed' ? 'flex' : 'hidden md:flex' }}">
            <div class="font-semibold text-slate-400 mb-4 px-2 flex items-center justify-between">
                <span>Confirmed</span>
                <span class="text-xs bg-slate-800 text-slate-300 px-2 py-1 rounded-full">{{ count($columns['confirmed'] ?? []) }}</span>
            </div>
            
            <div wire:poll.30s class="flex flex-col gap-3">
                @forelse ($columns['confirmed'] ?? [] as $booking)
                    <div class="bg-slate-900 rounded-xl p-4 border border-slate-800 hover:border-cyan-500/30 shadow-sm transition-colors cursor-grab active:cursor-grabbing">
                        <div class="flex justify-between items-start mb-3">
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-cyan-500/10 text-cyan-500 border border-cyan-500/20">
                                {{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }}
                            </span>
                            <button wire:click="updateBookingStatus({{ $booking->id }}, 'IN_PROGRESS')"
                                    class="px-2.5 py-1 text-xs font-semibold text-amber-500 bg-amber-500/10 border border-amber-500/20 rounded hover:bg-amber-500/20 transition-colors">
                                Start →
                            </button>
                        </div>
                        <div class="mb-3">
                            <div class="text-sm font-bold text-slate-100">{{ $booking->customer_name ?? $booking->user?->name ?? 'Unknown' }}</div>
                            <div class="text-xs text-slate-400">{{ $booking->carType->name }}</div>
                        </div>
                        <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-800/50">
                            <div class="text-xs font-medium text-slate-500">
                                {{ $booking->services->count() }} services
                            </div>
                            <a href="{{ route('admin.bookings') }}?search={{ $booking->id }}"
                               class="text-xs font-semibold text-cyan-500 hover:text-cyan-400 transition-colors">
                                Details
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-slate-500 py-10 bg-slate-900/50 rounded-xl border border-slate-800/50 border-dashed">
                        <p class="text-sm">No confirmed bookings.</p>
                    </div>
                @endforelse
            </div>
        </div>
            
        <!-- In Progress Column -->
        <div class="flex-1 flex-col min-w-full md:min-w-[300px] {{ $activeTab === 'in_progress' ? 'flex' : 'hidden md:flex' }}">
            <div class="font-semibold text-slate-400 mb-4 px-2 flex items-center justify-between">
                <span>In Progress</span>
                <span class="text-xs bg-slate-800 text-slate-300 px-2 py-1 rounded-full">{{ count($columns['in_progress'] ?? []) }}</span>
            </div>
            
            <div wire:poll.30s class="flex flex-col gap-3">
                @forelse ($columns['in_progress'] ?? [] as $booking)
                    <div class="bg-slate-900 rounded-xl p-4 border border-amber-500/30 shadow-sm transition-colors cursor-grab active:cursor-grabbing relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1 h-full bg-amber-500"></div>
                        <div class="flex justify-between items-start mb-3 pl-2">
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-amber-500/10 text-amber-500">
                                {{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }}
                            </span>
                            <button wire:click="updateBookingStatus({{ $booking->id }}, 'COMPLETED')"
                                class="px-2.5 py-1 text-xs font-semibold text-emerald-500 bg-emerald-500/10 border border-emerald-500/20 rounded hover:bg-emerald-500/20 transition-colors">
                                Complete ✓
                            </button>
                        </div>
                        <div class="mb-3 pl-2">
                            <div class="text-sm font-bold text-slate-100">{{ $booking->customer_name ?? $booking->user?->name ?? 'Unknown' }}</div>
                            <div class="text-xs text-slate-400">{{ $booking->carType->name }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-slate-500 py-10 bg-slate-900/50 rounded-xl border border-slate-800/50 border-dashed">
                        <p class="text-sm">No active tasks.</p>
                    </div>
                @endforelse
            </div>
        </div>
        
        <!-- Completed Column -->
        <div class="flex-1 flex-col min-w-full md:min-w-[300px] {{ $activeTab === 'completed' ? 'flex' : 'hidden md:flex' }}">
            <div class="font-semibold text-slate-400 mb-4 px-2 flex items-center justify-between">
                <span>Completed</span>
                <span class="text-xs bg-slate-800 text-slate-300 px-2 py-1 rounded-full">{{ count($columns['completed'] ?? []) }}</span>
            </div>
            
            <div wire:poll.30s class="flex flex-col gap-3">
                @forelse ($columns['completed'] ?? [] as $booking)
                    <div class="bg-slate-900 rounded-xl p-4 border border-emerald-500/20 opacity-80 shadow-sm transition-colors">
                        <div class="flex justify-between items-start mb-3">
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-slate-800 text-slate-400">
                                {{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }}
                            </span>
                            <span class="text-[10px] font-bold tracking-wider text-emerald-500 uppercase">Paid</span>
                        </div>
                        <div class="mb-3">
                            <div class="text-sm font-bold text-slate-300 line-through decoration-slate-600">{{ $booking->customer_name ?? $booking->user?->name ?? 'Unknown' }}</div>
                            <div class="text-xs text-slate-500">{{ $booking->carType->name }}</div>
                        </div>
                        <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-800/50">
                            <a href="{{ route('admin.bookings') }}?search={{ $booking->id }}"
                               class="text-xs font-semibold text-slate-400 hover:text-cyan-500 transition-colors">
                                Details
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-slate-500 py-10 bg-slate-900/50 rounded-xl border border-slate-800/50 border-dashed">
                        <p class="text-sm">No completed bookings today.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

