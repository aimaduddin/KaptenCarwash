<div class="min-h-screen bg-slate-950 pb-safe flex items-center justify-center p-6">
    <div class="max-w-md w-full">
        <div class="bg-slate-900 rounded-2xl p-8 border border-slate-800">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-slate-50 mb-2">Dummy Payment</h1>
                <p class="text-slate-300">This is a test payment gateway for development purposes.</p>
            </div>
            
            <div class="mb-6">
                <div class="text-sm text-slate-400 mb-2">
                    <span class="text-slate-50">Booking ID:</span> {{ $booking->id }}
                </div>
                <div class="text-sm text-slate-400 mb-4">
                    <span class="text-slate-50">Amount:</span> RM {{ number_format($booking->total_price / 100, 2) }}
                </div>
            </div>
            
            <div class="space-y-3">
                <button wire:click="simulateSuccess"
                        class="w-full py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors">
                    ✓ Simulate Success
                </button>
                <button wire:click="simulateFailure"
                        class="w-full py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors">
                    ✗ Simulate Failure
                </button>
                <button wire:click="simulateCancel"
                        class="w-full py-3 bg-slate-700 text-slate-50 font-medium rounded-lg hover:bg-slate-600 transition-colors">
                    ✗ Simulate Cancel
                </button>
                <button wire:click="reset"
                        class="w-full py-3 bg-slate-800 text-slate-400 rounded-lg hover:bg-slate-700 transition-colors">
                    Reset
                </button>
            </div>
            
            @if ($paymentStatus === 'success')
                <div class="mt-4 p-4 bg-green-900/20 border border-green-700 rounded-lg text-center">
                    <p class="text-green-400">Payment successful! Redirecting...</p>
                </div>
            @elseif ($paymentStatus === 'failed')
                <div class="mt-4 p-4 bg-red-900/20 border border-red-700 rounded-lg text-center">
                    <p class="text-red-400">Payment failed</p>
                </div>
            @elseif ($paymentStatus === 'cancelled')
                <div class="mt-4 p-4 bg-slate-800 border border-slate-700 rounded-lg text-center">
                    <p class="text-slate-400">Payment cancelled</p>
                </div>
            @endif
        </div>
    </div>
</div>
