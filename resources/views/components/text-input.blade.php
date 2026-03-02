@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full p-3 bg-slate-900 border border-slate-800 focus:border-cyan-500 focus:ring-cyan-500 rounded-lg shadow-sm text-slate-50 text-base placeholder-slate-500 transition-colors']) }}>
