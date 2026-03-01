<div class="md:hidden flex gap-2 mb-6">
    <button type="button"
                wire:click="$dispatch('switchTab', 'confirmed')"
                class="{{ $activeTab === 'confirmed' ? 'text-cyan-500 border-b-2 border-cyan-500' : 'text-slate-400 border-slate-700' }} px-6 py-2 rounded-lg transition-colors">
            Confirmed
        </button>
    <button type="button"
                wire:click="$dispatch('switchTab', 'in_progress')"
                class="{{ $activeTab === 'in_progress' ? 'text-cyan-500 border-b-2 border-cyan-500' : 'text-slate-400 border-slate-700' }} px-6 py-2 rounded-lg transition-colors">
            In Progress
        </button>
    <button type="button"
                wire:click="$dispatch('switchTab', 'completed')"
                class="{{ $activeTab === 'completed' ? 'text-cyan-500 border-b-2 border-cyan-500' : 'text-slate-400 border-slate-700' } px-6 py-2 rounded-lg transition-colors">
            Completed
        </button>
</div>
