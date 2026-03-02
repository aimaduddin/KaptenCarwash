<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-slate-800 border border-slate-700 rounded-lg font-semibold text-slate-50 tracking-wide hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 focus:ring-offset-slate-950 disabled:opacity-50 transition-all ease-in-out duration-200']) }}>
    {{ $slot }}
</button>
