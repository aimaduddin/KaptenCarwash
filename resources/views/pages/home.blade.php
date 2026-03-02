<!DOCTYPE html>
<html lang="en-MY" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>Kapten Carwash | Premium Detail</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <meta name="theme-color" content="#020617">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-['Outfit'] bg-slate-950 text-slate-50 min-h-screen selection:bg-cyan-500/30 pb-safe">
    
    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 border-b border-slate-800/50 bg-slate-950/80 backdrop-blur-md">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-2 shrink-0">
                <svg class="w-6 h-6 text-cyan-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <span class="font-bold text-lg sm:text-xl tracking-tight whitespace-nowrap">Kapten Carwash</span>
            </div>
            <div class="flex items-center gap-4 shrink-0">
                <a href="#services" class="hidden sm:block text-sm font-medium text-slate-300 hover:text-white transition-colors">
                    Services
                </a>
                <a href="/book" 
                   class="bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-semibold px-5 py-2 rounded-full text-sm transition-all whitespace-nowrap">
                    Book Now
                </a>
            </div>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <section class="relative pt-24 pb-16 md:pt-40 md:pb-24 px-4 overflow-hidden">
        <!-- Abstract Background Elements -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-cyan-500/10 blur-[100px] rounded-full pointer-events-none"></div>
        <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-blue-600/10 blur-[100px] rounded-full pointer-events-none"></div>
        
        <div class="relative mx-auto max-w-5xl text-center space-y-6 z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full border border-cyan-500/30 bg-cyan-500/10 text-cyan-400 text-xs font-medium tracking-wide">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
                Premium Auto Detailing
            </div>
            <h1 class="text-4xl sm:text-5xl md:text-7xl font-bold tracking-tight text-transparent bg-clip-text bg-gradient-to-br from-white to-slate-400 leading-tight">
                Meticulous Detail.<br /> Unmatched Shine.
            </h1>
            <p class="text-base sm:text-lg md:text-xl text-slate-400 max-w-2xl mx-auto leading-relaxed px-2">
                Professional car care tailored for your vehicle. Book your appointment online in seconds and let our experts restore your car's showroom finish.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-6">
                <a href="/book" 
                   class="flex items-center justify-center gap-2 w-full sm:w-auto px-8 py-4 bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold rounded-full transition-all active:scale-95 text-lg">
                    Book Your Wash
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>
    
    <!-- Services Section (Bento Grid Style) -->
    <section id="services" class="py-16 px-4">
        <div class="mx-auto max-w-6xl">
            <div class="text-center mb-10">
                <h2 class="text-2xl sm:text-3xl font-bold text-white mb-3">Our Services</h2>
                <p class="text-slate-400 max-w-2xl mx-auto text-sm sm:text-base px-4">
                    Choose from our comprehensive range of car washing and detailing services.
                </p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 hover:border-cyan-500/50 transition-all cursor-pointer flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-full bg-slate-800/50 flex items-center justify-center text-cyan-400 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">Exterior Wash</h3>
                    <p class="text-slate-400 text-sm">Complete exterior cleaning with premium products</p>
                </div>
                
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 hover:border-cyan-500/50 transition-all cursor-pointer flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-full bg-slate-800/50 flex items-center justify-center text-cyan-400 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">Interior Detailing</h3>
                    <p class="text-slate-400 text-sm">Deep cleaning of interior surfaces and upholstery</p>
                </div>
                
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 hover:border-cyan-500/50 transition-all cursor-pointer flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-full bg-slate-800/50 flex items-center justify-center text-cyan-400 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">Polish & Wax</h3>
                    <p class="text-slate-400 text-sm">High-quality waxing for lasting protection and shine</p>
                </div>
                
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 hover:border-cyan-500/50 transition-all cursor-pointer flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-full bg-slate-800/50 flex items-center justify-center text-cyan-400 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">Full Detail</h3>
                    <p class="text-slate-400 text-sm">Complete inside and outside detailing service</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="bg-slate-900 border-t border-slate-800 py-8 mt-8">
        <div class="mx-auto max-w-7xl px-6 flex flex-col items-center justify-center gap-4">
            <div class="flex items-center gap-2 text-cyan-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <span class="font-bold text-xl tracking-tight text-white">Kapten Carwash</span>
            </div>
            <p class="text-center text-slate-500 text-sm">
                &copy; {{ date('Y') }} Kapten Carwash. All rights reserved.
            </p>
            <a href="/login" class="flex items-center gap-1.5 text-slate-400 hover:text-cyan-400 text-sm transition-colors mt-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Admin Login
            </a>
        </div>
    </footer>
    
    @livewireScripts
</body>
</html>
