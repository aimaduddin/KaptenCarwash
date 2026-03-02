<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div>
    <!-- Top Navigation -->
    <nav class="bg-slate-950 border-b border-slate-800/50 sticky top-0 z-50 backdrop-blur-md bg-opacity-80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-center sm:justify-between h-16">
                <div class="flex w-full sm:w-auto justify-center sm:justify-start">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('admin.dashboard') }}" wire:navigate class="flex items-center gap-2">
                            <svg class="w-8 h-8 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8-4M4 7l8 4M20 21H4M4 21h2"/>
                            </svg>
                            <span class="font-bold text-xl tracking-tight text-white block">Kapten Carwash</span>
                        </a>
                    </div>

                    <!-- Navigation Links (Desktop) -->
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('admin.dashboard') ? 'border-cyan-500 text-slate-50' : 'border-transparent text-slate-400 hover:text-slate-300 hover:border-slate-700' }}" wire:navigate>
                            {{ __('Dashboard') }}
                        </a>
                        <a href="{{ route('admin.today') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('admin.today') ? 'border-cyan-500 text-slate-50' : 'border-transparent text-slate-400 hover:text-slate-300 hover:border-slate-700' }}" wire:navigate>
                            {{ __('Today') }}
                        </a>
                        <a href="{{ route('admin.bookings') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('admin.bookings') ? 'border-cyan-500 text-slate-50' : 'border-transparent text-slate-400 hover:text-slate-300 hover:border-slate-700' }}" wire:navigate>
                            {{ __('Bookings') }}
                        </a>
                        <a href="{{ route('admin.calendar') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('admin.calendar') ? 'border-cyan-500 text-slate-50' : 'border-transparent text-slate-400 hover:text-slate-300 hover:border-slate-700' }}" wire:navigate>
                            {{ __('Calendar') }}
                        </a>
                    </div>
                </div>

                <!-- Settings Dropdown (Desktop) -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-slate-800 text-sm leading-4 font-medium rounded-md text-slate-300 bg-slate-900 hover:text-slate-100 hover:border-slate-700 focus:outline-none transition ease-in-out duration-150">
                                <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('admin.settings')" wire:navigate class="text-slate-300 hover:bg-slate-800 hover:text-white">
                                {{ __('Settings') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <button wire:click="logout" class="w-full text-start">
                                <x-dropdown-link class="text-slate-300 hover:bg-slate-800 hover:text-white">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </button>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Bottom Navigation -->
    <div class="sm:hidden fixed bottom-0 left-0 right-0 z-[100] bg-slate-950/90 backdrop-blur-xl border-t border-slate-800/80 pb-[env(safe-area-inset-bottom)]">
        <div class="flex items-center justify-around h-[68px] px-2">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" wire:navigate class="flex flex-col items-center justify-center w-full h-full space-y-1 group relative">
                @if(request()->routeIs('admin.dashboard'))
                    <div class="absolute top-0 w-12 h-1 bg-cyan-500 rounded-b-full"></div>
                @endif
                <svg class="w-6 h-6 {{ request()->routeIs('admin.dashboard') ? 'text-cyan-400' : 'text-slate-400 group-hover:text-slate-200' }} transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                <span class="text-[10px] font-medium {{ request()->routeIs('admin.dashboard') ? 'text-cyan-400' : 'text-slate-400 group-hover:text-slate-200' }} transition-colors">Home</span>
            </a>

            <!-- Today -->
            <a href="{{ route('admin.today') }}" wire:navigate class="flex flex-col items-center justify-center w-full h-full space-y-1 group relative">
                @if(request()->routeIs('admin.today'))
                    <div class="absolute top-0 w-12 h-1 bg-cyan-500 rounded-b-full"></div>
                @endif
                <svg class="w-6 h-6 {{ request()->routeIs('admin.today') ? 'text-cyan-400' : 'text-slate-400 group-hover:text-slate-200' }} transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span class="text-[10px] font-medium {{ request()->routeIs('admin.today') ? 'text-cyan-400' : 'text-slate-400 group-hover:text-slate-200' }} transition-colors">Today</span>
            </a>

            <!-- Bookings -->
            <a href="{{ route('admin.bookings') }}" wire:navigate class="flex flex-col items-center justify-center w-full h-full space-y-1 group relative">
                @if(request()->routeIs('admin.bookings'))
                    <div class="absolute top-0 w-12 h-1 bg-cyan-500 rounded-b-full"></div>
                @endif
                <svg class="w-6 h-6 {{ request()->routeIs('admin.bookings') ? 'text-cyan-400' : 'text-slate-400 group-hover:text-slate-200' }} transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                </svg>
                <span class="text-[10px] font-medium {{ request()->routeIs('admin.bookings') ? 'text-cyan-400' : 'text-slate-400 group-hover:text-slate-200' }} transition-colors">Bookings</span>
            </a>

            <!-- Calendar -->
            <a href="{{ route('admin.calendar') }}" wire:navigate class="flex flex-col items-center justify-center w-full h-full space-y-1 group relative">
                @if(request()->routeIs('admin.calendar'))
                    <div class="absolute top-0 w-12 h-1 bg-cyan-500 rounded-b-full"></div>
                @endif
                <svg class="w-6 h-6 {{ request()->routeIs('admin.calendar') ? 'text-cyan-400' : 'text-slate-400 group-hover:text-slate-200' }} transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                </svg>
                <span class="text-[10px] font-medium {{ request()->routeIs('admin.calendar') ? 'text-cyan-400' : 'text-slate-400 group-hover:text-slate-200' }} transition-colors">Calendar</span>
            </a>

            <!-- Profile -->
            <a href="{{ route('admin.settings') }}" wire:navigate class="flex flex-col items-center justify-center w-full h-full space-y-1 group relative">
                @if(request()->routeIs('admin.settings'))
                    <div class="absolute top-0 w-12 h-1 bg-cyan-500 rounded-b-full"></div>
                @endif
                <svg class="w-6 h-6 {{ request()->routeIs('admin.settings') ? 'text-cyan-400' : 'text-slate-400 group-hover:text-slate-200' }} transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
                <span class="text-[10px] font-medium {{ request()->routeIs('admin.settings') ? 'text-cyan-400' : 'text-slate-400 group-hover:text-slate-200' }} transition-colors">Settings</span>
            </a>
        </div>
    </div>
</div>
