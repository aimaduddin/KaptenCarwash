<x-layouts.app title="Booking Confirmed - Kapten Carwash">
<div class="min-h-screen flex items-center justify-center p-6 pb-safe">
    <div class="max-w-lg w-full">
        <div class="bg-slate-900 rounded-2xl p-8 border border-cyan-500">
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-cyan-500 rounded-full mb-4">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L6 17l-4 4H5l-4 4V7l4 4-1 1-0-1z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-slate-50 mb-2">Booking Confirmed!</h1>
                <p class="text-slate-300 mb-6">Thank you {{ $booking->user->name }}. Your car wash appointment has been successfully booked.</p>
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
                    <p><strong>Name:</strong> {{ $booking->user->name }}</p>
                    <p><strong>Phone:</strong> {{ $booking->user->phone }}</p>
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
            
            <div class="mt-6 text-center">
                <a href="{{ url('/') }}" class="text-cyan-500 hover:text-cyan-400 font-medium">
                    ← Back to Home
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
