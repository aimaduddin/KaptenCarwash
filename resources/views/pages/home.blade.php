<x-layouts.app title="Kapten Carwash">
<div class="min-h-screen flex flex-col">
    <main class="flex-1">
        <section class="py-20 px-6">
            <div class="mx-auto max-w-4xl">
                <h1 class="text-4xl font-bold text-cyan-500 mb-6">
                    Professional Car Wash & Detailing
                </h1>
                <p class="text-lg text-slate-300 mb-8 max-w-2xl">
                    Book your car wash appointment in minutes. Choose your car type, select services, and pick a convenient time slot.
                </p>
                <a href="{{ url('/book') }}" 
                   class="inline-flex items-center px-8 py-4 bg-cyan-500 text-slate-950 font-semibold rounded-lg hover:bg-cyan-600 transition-colors">
                    <span>Book Appointment</span>
                </a>
            </div>
        </section>
    </main>
    
    <footer class="bg-slate-900 border-t border-slate-800 py-6">
        <div class="mx-auto max-w-4xl px-6">
            <p class="text-center text-slate-400 text-sm">
                &copy; 2026 Kapten Carwash. All rights reserved.
            </p>
        </div>
    </footer>
</div>
</x-layouts.app>
