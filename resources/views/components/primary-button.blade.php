<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-cyan-500 border border-transparent rounded-lg font-semibold text-slate-950 tracking-wide hover:bg-cyan-400 focus:bg-cyan-400 active:scale-95 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 focus:ring-offset-slate-950 transition-all ease-in-out duration-200']) }}>
    {{ $slot }}
</button>
