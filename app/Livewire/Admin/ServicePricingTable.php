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

    public function mount(): void
    {
        $this->services = Service::orderBy('name')->get();
    }

    public function startEditing($serviceId): void
    {
        $this->editingServiceId = $serviceId;
        $service = Service::find($serviceId);
        $this->newPrice = $service->base_price;
        $this->newActive = $service->is_active;
    }

    public function cancelEditing(): void
    {
        $this->editingServiceId = null;
        $this->newPrice = '';
        $this->newActive = false;
    }

    public function savePrice(): void
    {
        $this->validate();

        $service = Service::find($this->editingServiceId);
        $service->base_price = (int) ($this->newPrice * 100);
        $service->is_active = $this->newActive;
        $service->save();

        $this->cancelEditing();
    }

    public function toggleActive($serviceId): void
    {
        $service = Service::find($serviceId);
        $service->is_active = !$service->is_active;
        $service->save();

        $this->services = Service::orderBy('name')->get();
    }

    protected function validate(): void
    {
        if (!$this->editingServiceId) {
            return;
        }

        if (!is_numeric($this->newPrice) || $this->newPrice < 100) {
            session()->flash('error', 'Price must be at least RM 100');
            return;
        }
    }
}
