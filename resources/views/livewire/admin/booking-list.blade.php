<div class="p-4 md:p-6 pb-24 max-w-7xl mx-auto">
    <h1 class="text-xl md:text-2xl font-bold text-slate-50 mb-4 md:mb-6">Bookings</h1>
    
    <!-- Filters -->
    <div class="bg-slate-900 rounded-xl p-4 md:p-6 border border-slate-800 mb-4 md:mb-6">
        <div class="flex flex-col sm:flex-row gap-3 md:gap-4">
            <div class="flex-1">
                <label class="text-xs md:text-sm font-medium text-slate-300">Status</label>
                <select wire:model.live="statusFilter"
                        class="w-full p-2.5 md:p-3 bg-slate-950 border border-slate-800 rounded-lg text-slate-50 focus:ring-2 focus:ring-cyan-500 focus:ring-offset-1 focus:ring-offset-slate-900 mt-1 text-sm md:text-base">
                    <option value="all">All Statuses</option>
                    @foreach (\App\Enums\BookingStatus::cases() as $status)
                        <option value="{{ $status->value }}">{{ $status->value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 relative">
                <label class="text-xs md:text-sm font-medium text-slate-300">Date</label>
                <input type="date" wire:model.live="dateFilter"
                       class="w-full p-2.5 md:p-3 bg-slate-950 border border-slate-800 rounded-lg text-slate-50 focus:ring-2 focus:ring-cyan-500 focus:ring-offset-1 focus:ring-offset-slate-900 mt-1 color-scheme-dark text-sm md:text-base">
                @if ($dateFilter)
                    <button wire:click="$set('dateFilter', null)"
                            class="absolute right-3 top-9 md:top-10 text-slate-400 hover:text-slate-200">
                        ✕
                    </button>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Bookings Table (Desktop) -->
    <div class="hidden md:block overflow-x-auto bg-slate-900 rounded-xl border border-slate-800">
        <table class="min-w-full divide-y divide-slate-800">
            <thead class="bg-slate-800/50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Date & Time</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Services</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-slate-400 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-slate-400 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/50">
                @forelse ($bookings as $booking)
                    <tr class="hover:bg-slate-800/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-slate-200">{{ \Carbon\Carbon::parse($booking->booking_date)->format('M j, Y') }}</div>
                            <div class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($booking->booking_time)->format('g:i A') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-slate-200">{{ $booking->customer_name ?? $booking->user?->name ?? 'Unknown' }}</div>
                            <div class="text-xs text-slate-400">{{ $booking->carType->name ?? 'Unknown' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @foreach ($booking->services as $service)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-800 text-slate-300 border border-slate-700">
                                        {{ $service->name }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="text-sm font-semibold text-cyan-400">RM {{ number_format($booking->total_price / 100, 2) }}</div>
                            <div class="text-xs {{ $booking->payment_status->value === 'PAID' ? 'text-green-500' : 'text-amber-500' }}">
                                {{ $booking->payment_status->value }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold
                                {{ $booking->booking_status->value === 'CONFIRMED' ? 'bg-blue-500/10 text-blue-400 border border-blue-500/20' : '' }}
                                {{ $booking->booking_status->value === 'IN_PROGRESS' ? 'bg-purple-500/10 text-purple-400 border border-purple-500/20' : '' }}
                                {{ $booking->booking_status->value === 'COMPLETED' ? 'bg-green-500/10 text-green-400 border border-green-500/20' : '' }}
                                {{ $booking->booking_status->value === 'CANCELLED' ? 'bg-red-500/10 text-red-400 border border-red-500/20' : '' }}
                                {{ $booking->booking_status->value === 'PENDING_PAYMENT' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : '' }}">
                                {{ $booking->booking_status->value }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-slate-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <h3 class="text-lg font-medium text-slate-300 mb-1">No bookings found</h3>
                            <p class="text-slate-500 text-sm">Try changing your filters or checking a different date.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Mobile Cards (hidden on desktop) -->
    <div class="md:hidden space-y-4">
        @forelse ($bookings as $booking)
            <div class="bg-slate-900 rounded-xl p-5 border border-slate-800 shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <div class="text-sm font-semibold text-slate-200">{{ \Carbon\Carbon::parse($booking->booking_date)->format('M j, Y') }}</div>
                        <div class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($booking->booking_time)->format('g:i A') }}</div>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold
                        {{ $booking->booking_status->value === 'CONFIRMED' ? 'bg-blue-500/10 text-blue-400' : 'bg-slate-800 text-slate-400' }}">
                        {{ $booking->booking_status->value }}
                    </span>
                </div>
                
                <div class="mb-4">
                    <div class="text-sm font-medium text-slate-100">{{ $booking->customer_name ?? $booking->user?->name ?? 'Unknown' }}</div>
                    <div class="text-xs text-slate-400">{{ $booking->carType->name ?? 'Unknown' }}</div>
                </div>
                
                <div class="flex flex-wrap gap-1 mb-4">
                    @foreach ($booking->services as $service)
                        <span class="inline-flex px-1.5 py-0.5 rounded text-[10px] font-medium bg-slate-800 text-slate-300">
                            {{ $service->name }}
                        </span>
                    @endforeach
                </div>
                
                <div class="pt-4 border-t border-slate-800 flex justify-between items-center">
                    <div class="text-xs {{ $booking->payment_status->value === 'PAID' ? 'text-green-500' : 'text-amber-500' }}">
                        {{ $booking->payment_status->value }}
                    </div>
                    <div class="text-base font-bold text-cyan-400">
                        RM {{ number_format($booking->total_price / 100, 2) }}
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-slate-900 rounded-xl p-8 text-center border border-slate-800">
                <p class="text-slate-400">No bookings found for the selected filters.</p>
            </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    <div class="mt-6">
        {{ $bookings->links() }}
    </div>
</div>
