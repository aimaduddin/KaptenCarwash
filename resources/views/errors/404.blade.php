<x-layouts.app title="Page Not Found - Kapten Carwash">
<div class="min-h-screen flex items-center justify-center p-6 pb-safe">
    <div class="text-center">
        <h1 class="text-6xl font-bold text-slate-500 mb-4">404</h1>
        <h2 class="text-2xl font-semibold text-slate-50 mb-6">Page Not Found</h2>
        <p class="text-slate-300 mb-8 max-w-md mx-auto">
            The page you're looking for doesn't exist or has been moved.
        </p>
        <a href="{{ url('/') }}" class="inline-block px-8 py-3 bg-cyan-500 text-white font-semibold rounded-lg hover:bg-cyan-600 transition-colors">
            ← Back to Home
        </a>
    </div>
</x-layouts.app>
