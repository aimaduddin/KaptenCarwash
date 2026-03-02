<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#020617">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

        <title>@yield('title', config('app.name', 'Kapten Carwash'))</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-['Outfit'] antialiased bg-slate-950 text-slate-50 min-h-screen selection:bg-cyan-500/30 pb-safe">
        <div class="min-h-screen flex flex-col">
            <!-- Navigation -->
            @auth
                @include('livewire.layout.navigation')
            @endauth

            <!-- Page Content -->
            <main class="flex-grow pb-24 sm:pb-8">
                @yield('content')
                {{ $slot ?? '' }}
            </main>
        </div>
        @livewireScripts
    </body>
</html>
