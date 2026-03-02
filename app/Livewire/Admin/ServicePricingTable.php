<?php

namespace App\Livewire\Admin;

use App\Models\Service;
use Livewire\Component;

class ServicePricingTable extends Component
{
    public $services;

    public $editingServiceId = null;

    public $newPrice = '';

    public $newActive = false;

    public $newName = '';

    public $newDuration = '';

    public function mount(): void
    {
        $this->services = Service::orderBy('name')->get();
    }

    public function startEditing($serviceId): void
    {
        $this->editingServiceId = $serviceId;
        $service = Service::find($serviceId);
        $this->newName = $service->name;
        $this->newPrice = $service->base_price / 100;
        $this->newDuration = $service->duration_minutes;
        $this->newActive = $service->is_active;
    }

    public function cancelEditing(): void
    {
        $this->editingServiceId = null;
        $this->newPrice = '';
        $this->newActive = false;
        $this->newName = '';
        $this->newDuration = '';
    }

    public function toggleActive(): void
    {
        $this->newActive = ! $this->newActive;
    }

    public function savePrice(): void
    {
        if (! $this->editingServiceId) {
            return;
        }

        if (! is_numeric($this->newPrice) || $this->newPrice < 10) {
            session()->flash('error', 'Price must be at least RM 10');

            return;
        }

        if (empty($this->newName)) {
            session()->flash('error', 'Service name is required');

            return;
        }

        if (! is_numeric($this->newDuration) || $this->newDuration < 5) {
            session()->flash('error', 'Duration must be at least 5 minutes');

            return;
        }

        $service = Service::find($this->editingServiceId);
        $service->name = $this->newName;
        $service->base_price = (int) ($this->newPrice * 100);
        $service->duration_minutes = (int) $this->newDuration;
        $service->is_active = $this->newActive;
        $service->save();

        $this->services = Service::orderBy('name')->get();
        $this->cancelEditing();
    }
}
