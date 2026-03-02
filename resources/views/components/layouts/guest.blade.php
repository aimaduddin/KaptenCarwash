<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#020617">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

        <title>{{ config('app.name', 'Kapten Carwash') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-['Outfit'] antialiased bg-slate-950 text-slate-50 min-h-screen selection:bg-cyan-500/30">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div>
                <a href="/" wire:navigate class="flex items-center gap-2">
                    <svg class="w-10 h-10 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8-4M4 7l8 4M20 21H4M4 21h2"/>
                    </svg>
                    <span class="font-bold text-2xl tracking-tight text-white">Kapten Carwash</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-8 px-6 py-8 bg-slate-900 shadow-xl overflow-hidden sm:rounded-2xl border border-slate-800">
                {{ $slot }}
            </div>
        </div>
        @livewireScripts
    </body>
</html>
