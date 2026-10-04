<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

class ActivityLogIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $logs = Activity::query()
            ->with('causer')
            ->when($this->search, fn ($q) => $q->where('description', 'like', "%{$this->search}%")
                ->orWhere('subject_type', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(20);

        return view('livewire.admin.activity-log-index', [
            'logs' => $logs,
        ])->layout('components.layouts.admin', ['title' => 'Riwayat Aktivitas']);
    }
}
