<x-layouts.app title="Booking Confirmed - Kapten Carwash">
<div class="min-h-screen flex items-center justify-center p-6 pb-safe">
    <div class="max-w-lg w-full">
        <div class="bg-slate-900 rounded-2xl p-8 border border-cyan-500 shadow-xl shadow-cyan-500/10">
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-cyan-500/10 rounded-full mb-4 border-4 border-cyan-500">
                    <svg class="w-10 h-10 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-slate-50 mb-2">Booking Confirmed!</h1>
                <p class="text-slate-300 mb-6">Thank you {{ $booking->customer_name ?? $booking->user?->name ?? 'Customer' }}. Your car wash appointment has been successfully booked.</p>
            </div>
            
            <div class="border-t border-slate-800 pt-6">
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-slate-50 mb-2">Booking Reference</h3>
                    <p class="text-2xl font-mono text-cyan-500">{{ 'KW-' . str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</p>
                </div>
                
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-slate-50 mb-2">Car Type</h3>
                    <p class="text-slate-300">{{ $booking->carType->name }}</p>
                </div>
                
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-slate-50 mb-2">Services</h3>
                    <ul class="space-y-1 text-slate-300">
                        @foreach ($booking->services as $service)
                            <li>• {{ $service->name }}</li>
                        @endforeach
                    </ul>
                </div>
                
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-slate-50 mb-2">Date & Time</h3>
                    <p class="text-slate-300">{{ $booking->booking_date->format('F j, Y') }} @ {{ $booking->booking_time }}</p>
                </div>
                
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-slate-50 mb-2">Your Details</h3>
                    <p><strong>Name:</strong> {{ $booking->customer_name ?? $booking->user?->name ?? 'N/A' }}</p>
                    <p><strong>Phone:</strong> {{ $booking->customer_phone ?? $booking->user?->phone ?? 'N/A' }}</p>
                    <p><strong>Car Plate:</strong> {{ $booking->car_plate ?? 'N/A' }}</p>
                </div>
                
                <div class="border-t border-slate-800 pt-6">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-semibold text-slate-50">Total Paid</span>
                        <span class="text-2xl font-bold text-cyan-500">RM {{ number_format($booking->total_price / 100, 2) }}</span>
                    </div>
                    <p class="text-sm text-slate-400 mt-2">Includes RM {{ number_format($booking->booking_fee / 100, 2) }} booking fee</p>
                </div>
            </div>
            
            <div class="mt-6 text-center space-y-4">
                <a href="{{ route('receipt.download', $booking->id) }}"
                   class="inline-flex items-center justify-center gap-2 w-full py-3 bg-cyan-600 text-white font-semibold rounded-lg hover:bg-cyan-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Download Receipt
                </a>
                <a href="{{ url('/') }}" class="inline-block text-cyan-500 hover:text-cyan-400 font-medium">
                    ← Back to Home
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
