<x-layouts.app title="Offline - Kapten Carwash">
<div class="min-h-screen flex items-center justify-center p-6 pb-safe">
    <div class="text-center max-w-lg w-full">
        <div class="mb-6">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-slate-800 rounded-full mb-4">
                <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172l-6.828 6.828a2 2 0 012 2 012 2.012l-1.414 1.414a2 0 001 2 012 2.012l-4.414 1.414-1.414a2 2 012 2.012z"></path>
                </svg>
            </div>
        </div>
        <h1 class="text-3xl font-bold text-slate-50 mb-4">You're Offline</h1>
        <p class="text-slate-300 mb-8">
            It looks like you're not connected to the internet. Please check your connection and try again.
        </p>
        <button onclick="window.location.reload()" 
                class="inline-block px-8 py-3 bg-cyan-500 text-white font-semibold rounded-lg hover:bg-cyan-600 transition-colors">
            Retry
        </button>
    </div>
</x-layouts.app>
