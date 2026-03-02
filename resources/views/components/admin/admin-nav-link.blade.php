@props(['href', 'label', 'icon' => null, 'isMobile' => false])

@php
$currentPath = request()->path();
$isActive = $href === 'admin' 
    ? $currentPath === 'admin' 
    : str_starts_with($currentPath, $href);
@endphp

@if($isMobile)
    <a href="{{ $href }}"
       class="flex flex-col items-center justify-center w-full h-full gap-1 text-[10px] font-medium transition-colors {{ $isActive ? 'text-cyan-400' : 'text-slate-500 hover:text-slate-300' }}">
        <div class="p-1 rounded-full transition-all duration-300 {{ $isActive ? 'bg-cyan-500/10 scale-110' : '' }}">
            @if($icon)
                {!! $icon !!}
            @endif
        </div>
        <span>{{ $label }}</span>
    </a>
@else
    <a href="{{ $href }}"
       class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors {{ $isActive ? 'bg-slate-800 text-cyan-400 font-semibold' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }}">
        @if($icon)
            {!! $icon !!}
        @endif
        {{ $label }}
    </a>
@endif
