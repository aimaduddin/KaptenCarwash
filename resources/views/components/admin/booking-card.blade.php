@props(['booking', 'borderColor'])

@php
use App\Enums\BookingStatus;

$allowedTransitions = [
    BookingStatus::CONFIRMED->value,
    BookingStatus::IN_PROGRESS->value,
    BookingStatus::COMPLETED->value,
    BookingStatus::CANCELLED->value,
];

$statusConfig = [
    BookingStatus::CONFIRMED->value => ['label' => 'Confirmed', 'color' => 'text-blue-400 bg-blue-500/10'],
    BookingStatus::IN_PROGRESS->value => ['label' => 'In Progress', 'color' => 'text-purple-400 bg-purple-500/10'],
    BookingStatus::COMPLETED->value => ['label' => 'Completed', 'color' => 'text-green-400 bg-green-500/10'],
    BookingStatus::CANCELLED->value => ['label' => 'Cancelled', 'color' => 'text-red-400 bg-red-500/10'],
];

$currentConfig = $statusConfig[$booking->booking_status->value] ?? ['label' => $booking->booking_status->value, 'color' => 'text-slate-400 bg-slate-700'];
$services = $booking->services->map(fn($s) => $s->name)->join(', ');
@endphp

<div class="relative bg-slate-800 rounded-2xl shadow-lg border-l-8 {{ $borderColor }} p-5 hover:bg-slate-800/80 transition-all cursor-pointer min-h-[140px]">
    <div class="flex justify-between items-start mb-3">
        <div class="text-3xl font-black text-white tracking-tight">
            {{ \Carbon\Carbon::parse($booking->booking_time)->format('g:i A') }}
        </div>
        
        <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider border border-slate-700 {{ $currentConfig['color'] }}">
            {{ $currentConfig['label'] }}
        </div>
    </div>

    <div class="space-y-2 mt-4">
        <div class="flex items-center gap-2 text-slate-300">
            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <span class="text-lg font-bold text-slate-100">{{ $booking->customer_name ?? $booking->user?->name ?? 'Unknown' }}</span>
        </div>

        <div class="flex items-center gap-2 text-slate-300">
            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            <span class="text-sm font-medium">{{ $booking->carType->name }}</span>
        </div>

        <div class="flex items-start gap-2 text-slate-400">
            <svg class="w-4 h-4 text-slate-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <span class="text-sm leading-snug">{{ $services }}</span>
        </div>
    </div>

    <div class="mt-4 pt-4 border-t border-slate-700/50 flex justify-between items-center">
        <span class="text-xs font-mono text-slate-500 bg-slate-900/50 px-2 py-1 rounded">
            #{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}
        </span>
    </div>
</div>
