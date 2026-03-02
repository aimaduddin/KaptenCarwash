<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-red-500/10 border border-red-500/20 rounded-lg font-semibold text-red-500 tracking-wide hover:bg-red-500/20 active:scale-95 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-slate-950 transition-all ease-in-out duration-200']) }}>
    {{ $slot }}
</button>
