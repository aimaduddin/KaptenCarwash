<div class="p-6">
    <h1 class="text-2xl font-bold text-slate-50 mb-6">Service Pricing</h1>
    
    <div class="bg-slate-900 rounded-xl p-6 border border-slate-800 overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-800">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500">Service</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500">Base Price (RM)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500">Duration</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($services as $service)
                    <tr>
                        <td class="px-6 py-3">
                            <span class="text-sm font-medium text-slate-50">{{ $service->name }}</span>
                        </td>
                        <td class="px-6 py-3 text-mono text-slate-300">RM {{ number_format($service->base_price / 100, 2) }}</td>
                        <td class="px-6 py-3 text-sm text-slate-400">{{ $service->duration_minutes }} min</td>
                        <td class="px-6 py-3">
                            @if ($service->is_active)
                                <span class="text-xs text-green-500">Active</span>
                            @else
                                <span class="text-xs text-slate-400">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-3">
                            <button wire:click="startEditing({{ $service->id }})"
                                    class="text-cyan-500 hover:text-cyan-400">
                                Edit
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    @if ($editingServiceId)
        <div class="fixed inset-0 top-1/2 right-0 left-1/2 w-full h-3/4 bg-slate-950/95 backdrop-blur-sm z-50 flex items-center justify-center">
            <div class="bg-slate-900 rounded-2xl p-8 max-w-md shadow-2xl">
                <h2 class="text-xl font-bold text-slate-50 mb-4">
                    Edit: {{ $services->find($editingServiceId)->name }}
                </h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Price (RM)</label>
                        <input type="number" wire:model.live="newPrice" step="0.01" min="100"
                               class="w-full p-3 bg-slate-900 border border-slate-800 rounded-lg text-slate-50 focus:ring-2 focus:ring-cyan-500 focus:ring-offset-1">
                    </div>
                    
                    <label class="block text-sm font-medium text-slate-300 mb-2">Active Status</label>
                        <div class="flex items-center gap-2">
                            <button wire:click="toggleActive({{ $service->id }})"
                                    class="{{ $newActive ? 'bg-green-600 text-white' : 'bg-slate-700 text-slate-50' }} px-4 py-2 rounded-lg transition-colors">
                                @if ($newActive)
                                    ✓ Active
                                @else
                                    ✗ Inactive
                                @endif
                            </button>
                        </div>
                    </div>
                    
                    <div class="flex gap-3">
                        <button wire:click="savePrice"
                                class="px-6 py-3 bg-cyan-500 text-white font-semibold rounded-lg hover:bg-cyan-600">
                                Save
                        </button>
                        <button wire:click="cancelEditing"
                                class="px-6 py-3 bg-slate-700 text-slate-50 font-medium rounded-lg hover:bg-slate-600">
                                Cancel
                        </button>
                    </div>
            </div>
        </div>
    @endif
</div>
</div>
