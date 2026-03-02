@php
$colorMap = [
    'PENDING_PAYMENT' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
    'CONFIRMED' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
    'IN_PROGRESS' => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
    'COMPLETED' => 'bg-green-500/10 text-green-400 border-green-500/20',
    'CANCELLED' => 'bg-red-500/10 text-red-400 border-red-500/20',
];

$labelMap = [
    'PENDING_PAYMENT' => 'Pending',
    'CONFIRMED' => 'Confirmed',
    'IN_PROGRESS' => 'In Progress',
    'COMPLETED' => 'Completed',
    'CANCELLED' => 'Cancelled',
];

$colorClass = $colorMap[$status] ?? 'bg-slate-800 text-slate-300 border-slate-700';
$label = $labelMap[$status] ?? $status;
@endphp

<span class="inline-flex items-center rounded-md px-2 py-0.5 text-[10px] font-bold uppercase tracking-widest border {{ $colorClass }}">
    {{ $label }}
</span>

@php
/**
 * Dot color for calendar-style indicators - export for use in other components
 */
$dotColorMap = [
    'PENDING_PAYMENT' => 'bg-amber-400',
    'CONFIRMED' => 'bg-blue-400',
    'IN_PROGRESS' => 'bg-purple-400',
    'COMPLETED' => 'bg-green-400',
    'CANCELLED' => 'bg-red-400',
];
@endphp
