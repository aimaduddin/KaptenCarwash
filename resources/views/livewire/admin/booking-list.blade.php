<div class="p-6">
    <h1 class="text-2xl font-bold text-slate-50 mb-6">Bookings</h1>
    
    <!-- Filters -->
    <div class="bg-slate-900 rounded-xl p-6 border border-slate-800 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label class="text-sm font-medium text-slate-300">Status</label>
                <select wire:model.live="statusFilter"
                        class="w-full p-3 bg-slate-900 border border-slate-800 rounded-lg text-slate-50 focus:ring-2 focus:ring-cyan-500 focus:ring-offset-1">
                    @foreach ($filters.status as $value => $label => $value)
                        <option value="{{ $value }}" {{ $statusFilter === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1">
                <label class="text-sm font-medium text-slate-300">Date</label>
                <input type="date" wire:model.live="dateFilter"
                       class="w-full p-3 bg-slate-900 border border-slate-800 rounded-lg text-slate-50 focus:ring-2 focus:ring-cyan-500 focus:ring-offset-1">
                @if ($dateFilter)
                    <button wire:click="$set('dateFilter', null)"
                            class="absolute right-2 top-2 text-slate-400 hover:text-slate-200">
                        ✕
                    </button>
                @endif
            </div>
        </div>
        <button wire:click="applyFilters"
                class="w-full md:w-auto py-3 bg-cyan-500 text-white font-semibold rounded-lg hover:bg-cyan-600 transition-colors">
            Apply Filters
        </button>
    </div>
    
    <!-- Bookings Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-800">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500">Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500">Car Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500">Services</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bookings->count() === 0)
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-400">
                            <p class="text-lg">No bookings found.</p>
                        </td>
                    </tr>
                @else
                    @foreach ($bookings as $booking)
                        <tr class="hover:bg-slate-800 transition-colors">
                            <td class="px-6 py-3 text-xs font-mono text-slate-300">{{ $booking->booking_time }}</td>
                            <td class="px-6 py-3 text-sm font-medium text-slate-50">{{ $booking->user->name }}</td>
                            <td class="px-6 py-3 text-xs text-slate-500">{{ $booking->carType->name }}</td>
                            <td class="px-6 py-3 text-sm text-slate-400">
                                <ul class="text-xs space-y-1">
                                    @foreach ($booking->services as $service)
                                        <li>{{ $service->name }} (RM {{ number_format($service->effective_price / 100, 2) }})</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="px-6 py-3 text-right font-semibold text-cyan-500">RM {{ number_format($booking->total_price / 100, 2) }}</td>
                            <td class="px-6 py-3">
                                <div>
                                    <span class="text-xs text-green-500">{{ $booking->payment_status->value }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-3">
                                <div>
                                    <button wire:click="updateBookingStatus({{ $booking->id }}, 'confirmed')"
                                        class="text-xs text-cyan-500 hover:text-cyan-400">
                                            → Confirm
                                        </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    
    <!-- Mobile Cards (hidden on desktop) -->
    <div class="md:hidden space-y-4">
        @foreach ($bookings as $booking)
            <div class="bg-slate-900 rounded-xl p-4 border border-slate-800 shadow-sm">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex-1">
                        <div>
                            <span class="text-sm text-slate-400">{{ $booking->booking_date->format('M j, Y') }}</span>
                            <div class="text-xs text-slate-500">{{ $booking->booking_time }}</span>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-slate-50">{{ $booking->user->name }}</div>
                            <div class="text-xs text-slate-500">{{ $booking->carType->name }}</div>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <div>
                            <div>
                                <span class="text-sm text-slate-400">Total</span>
                                <span class="text-lg font-semibold text-cyan-500">RM {{ number_format($booking->total_price / 100, 2) }}</span>
                            </div>
                            <a href="{{ route('admin.bookings.show', $booking->id) }}" class="text-cyan-500 hover:text-cyan-400">
                                View
                            </a>
                        </div>
                        <div>
                            <div>
                                <span class="text-xs text-green-500">{{ $booking->payment_status->value }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <!-- Pagination -->
    {{ $bookings->links() }}
</div>
</div>
