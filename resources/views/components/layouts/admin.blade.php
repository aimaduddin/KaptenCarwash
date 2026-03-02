<!DOCTYPE html>
<html lang="en-MY" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin - Kapten Carwash')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@100..900&family=Geist+Mono:wght@100..900&display=swap" rel="stylesheet">
    
    <meta name="theme-color" content="#020617">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @livewireScripts
</head>
<body class="bg-slate-950 text-slate-50 font-sans">
    <div class="flex h-screen overflow-hidden">
        <nav class="hidden md:flex flex-col w-64 bg-slate-900 border-r border-slate-800">
            <div class="p-4">
                <h1 class="text-xl font-bold text-cyan-500">Kapten Admin</h1>
            </div>
            <div class="flex-1 px-4 py-6 space-y-2">
                <a href="{{ url('/admin') }}" class="flex items-center px-4 py-2 text-sm rounded-lg {{ request()->is('admin') ? 'bg-cyan-500/10 text-cyan-500' : 'text-slate-300 hover:bg-slate-800' }}">
                    Dashboard
                </a>
                <a href="{{ url('/admin/today') }}" class="flex items-center px-4 py-2 text-sm rounded-lg {{ request()->is('admin/today*') ? 'bg-cyan-500/10 text-cyan-500' : 'text-slate-300 hover:bg-slate-800' }}">
                    Today
                </a>
                <a href="{{ url('/admin/bookings') }}" class="flex items-center px-4 py-2 text-sm rounded-lg {{ request()->is('admin/bookings*') ? 'bg-cyan-500/10 text-cyan-500' : 'text-slate-300 hover:bg-slate-800' }}">
                    Bookings
                </a>
                <a href="{{ url('/admin/calendar') }}" class="flex items-center px-4 py-2 text-sm rounded-lg {{ request()->is('admin/calendar*') ? 'bg-cyan-500/10 text-cyan-500' : 'text-slate-300 hover:bg-slate-800' }}">
                    Calendar
                </a>
                <a href="{{ url('/admin/settings') }}" class="flex items-center px-4 py-2 text-sm rounded-lg {{ request()->is('admin/settings*') ? 'bg-cyan-500/10 text-cyan-500' : 'text-slate-300 hover:bg-slate-800' }}">
                    Settings
                </a>
            </div>
        </nav>
        
        <main class="flex-1 overflow-auto">
            {{ $slot }}
        </main>
    </div>
    
    <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-slate-900 border-t border-slate-800 safe-area-pb">
        <div class="flex justify-around py-2">
            <a href="{{ url('/admin') }}" class="flex flex-col items-center px-4 py-2 {{ request()->is('admin') ? 'text-cyan-500' : 'text-slate-400' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
            </a>
            <a href="{{ url('/admin/today') }}" class="flex flex-col items-center px-4 py-2 {{ request()->is('admin/today*') ? 'text-cyan-500' : 'text-slate-400' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </a>
            <a href="{{ url('/admin/bookings') }}" class="flex flex-col items-center px-4 py-2 {{ request()->is('admin/bookings*') ? 'text-cyan-500' : 'text-slate-400' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012 2h2a2 2 0 012-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </a>
            <a href="{{ url('/admin/settings') }}" class="flex flex-col items-center px-4 py-2 {{ request()->is('admin/settings*') ? 'text-cyan-500' : 'text-slate-400' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </a>
        </div>
    </nav>
    
    <style>
        .safe-area-pb {
            padding-bottom: env(safe-area-inset-bottom, 0);
        }
    </style>
</body>
</html>
