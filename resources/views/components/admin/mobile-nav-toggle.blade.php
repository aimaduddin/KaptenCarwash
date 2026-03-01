<div class="fixed top-4 left-4 z-10 md:hidden">
    <div wire:click="$dispatch('switchTab', 'activeTab')"
         class="fixed top-4 left-4 z-10">
        <button type="button"
                class="{{ $activeTab === 'confirmed' ? 'text-cyan-500 border-b-2 border-cyan-500' : 'text-slate-400 border-slate-700' }} px-6 py-2 rounded-lg transition-colors">
            Confirmed
        </button>
        <button type="button"
                class="{{ $activeTab === 'in_progress' ? 'text-cyan-500 border-b-2 border-cyan-500' : 'text-slate-400 border-slate-700' }} px-6 py-2 rounded-lg transition-colors">
            In Progress
        </button>
        <button type="button"
                class="{{ $activeTab === 'completed' ? 'text-cyan-500 border-b-2 border-cyan-500' : 'text-slate-400 border-slate-700' } px-6 py-2 rounded-lg transition-colors">
            Completed
        </button>
    </div>
</div>
