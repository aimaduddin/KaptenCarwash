<?php

namespace App\Livewire\Admin;

use App\Models\UnavailableDate;
use Livewire\Component;

class UnavailableDatesManager extends Component
{
    public $dates = [];

    public $newDate = '';

    public $newReason = '';

    public $adding = false;

    public $deletingId = null;

    public $error = null;

    public function mount(): void
    {
        $this->dates = UnavailableDate::orderBy('date')->get()->toArray();
    }

    public function addDate(): void
    {
        if (! $this->newDate) {
            $this->error = 'Please select a date';

            return;
        }

        $this->adding = true;
        $this->error = null;

        try {
            $existing = UnavailableDate::where('date', $this->newDate)->first();
            if ($existing) {
                $this->error = 'This date is already blocked';
                $this->adding = false;

                return;
            }

            $date = UnavailableDate::create([
                'date' => $this->newDate,
                'reason' => $this->newReason ?: null,
            ]);

            $this->dates[] = $date->toArray();
            usort($this->dates, fn ($a, $b) => $a['date'] <=> $b['date']);

            $this->newDate = '';
            $this->newReason = '';
        } catch (\Exception $e) {
            $this->error = 'Failed to add date';
        } finally {
            $this->adding = false;
        }
    }

    public function deleteDate($dateId): void
    {
        $this->deletingId = $dateId;
        $this->error = null;

        try {
            $date = UnavailableDate::find($dateId);
            if ($date) {
                $date->delete();
                $this->dates = array_filter($this->dates, fn ($d) => $d['id'] !== $dateId);
            }
        } catch (\Exception $e) {
            $this->error = 'Failed to delete date';
        } finally {
            $this->deletingId = null;
        }
    }

    public function render()
    {
        return view('livewire.admin.unavailable-dates-manager');
    }
}
