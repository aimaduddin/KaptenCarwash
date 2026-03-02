<div class="space-y-6">
    <!-- Add new date -->
    <div class="bg-slate-950/50 border border-slate-800 rounded-2xl p-5 sm:p-6">
        <h3 class="text-sm font-bold text-slate-200 mb-4 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-cyan-500"></span>
            Block a Date
        </h3>
        <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-end">
            <div class="w-full sm:w-auto">
                <label class="block text-[10px] font-black text-slate-400 mb-1.5 uppercase tracking-wider">Date</label>
                <input
                    type="date"
                    wire:model="newDate"
                    class="w-full sm:w-auto text-sm bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-white outline-none focus:ring-2 focus:ring-cyan-500/50 transition-all [color-scheme:dark]"
                >
            </div>
            <div class="w-full sm:flex-1">
                <label class="block text-[10px] font-black text-slate-400 mb-1.5 uppercase tracking-wider">
                    Reason (optional)
                </label>
                <input
                    type="text"
                    wire:model="newReason"
                    placeholder="e.g. Public holiday"
                    class="w-full text-sm bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-white placeholder:text-slate-600 outline-none focus:ring-2 focus:ring-cyan-500/50 transition-all"
                    wire:keydown.enter="addDate"
                >
            </div>
            <button
                wire:click="addDate"
                wire:loading.disabled="adding"
                class="w-full sm:w-auto text-sm bg-cyan-500 text-slate-950 px-5 py-2.5 rounded-xl font-bold hover:bg-cyan-400 disabled:opacity-50 transition-all active:scale-95 whitespace-nowrap"
            >
                @if($adding)
                    Adding...
                @else
                    Block Date
                @endif
            </button>
        </div>
        @if($error)
            <div class="mt-4 p-3 bg-red-500/10 border border-red-500/20 rounded-xl text-sm font-medium text-red-400">
                {{ $error }}
            </div>
        @endif
    </div>

    <!-- List -->
    <div class="bg-slate-950/50 border border-slate-800 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            @if(empty($dates))
                <p class="text-sm text-slate-500 p-6 text-center font-medium">No unavailable dates configured.</p>
            @else
                <table class="w-full text-sm min-w-[420px] sm:min-w-[500px]">
                    <thead>
                        <tr class="bg-slate-900 border-b border-slate-800 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            <th class="py-4 pr-2 pl-3 sm:pl-5 min-w-[110px] sm:min-w-[120px]">Date</th>
                            <th class="px-2 sm:px-3 py-4 min-w-[140px] sm:min-w-[160px]">Reason</th>
                            <th class="px-2 sm:px-3 py-4 min-w-[84px] sm:min-w-[100px]">Added</th>
                            <th class="py-4 pl-2 sm:pl-3 pr-3 sm:pr-5 text-right sm:text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/50">
                        @foreach($dates as $entry)
                            <tr class="hover:bg-slate-800/30 transition-colors">
                                <td class="py-4 pr-2 pl-3 sm:pl-5 text-sm font-bold text-cyan-400 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($entry['date'])->format('F j, Y') }}
                                </td>
                                <td class="px-2 sm:px-3 py-4 text-slate-300 font-medium">
                                    {{ $entry['reason'] ?? '-' }}
                                </td>
                                <td class="px-2 sm:px-3 py-4 text-xs text-slate-500 font-medium whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($entry['created_at'])->format('d/m/Y') }}
                                </td>
                                <td class="py-4 pl-2 sm:pl-3 pr-3 sm:pr-5 text-right sm:text-left">
                                    <button
                                        wire:click="deleteDate({{ $entry['id'] }})"
                                        wire:loading.disabled="deletingId === {{ $entry['id'] }}"
                                        class="text-xs font-bold text-red-400 bg-red-500/10 hover:bg-red-500/20 px-3 py-1.5 rounded-lg disabled:opacity-50 transition-colors whitespace-nowrap"
                                    >
                                        @if($deletingId === $entry['id'])
                                            Removing...
                                        @else
                                            Remove
                                        @endif
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
