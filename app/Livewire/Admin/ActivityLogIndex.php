<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

class ActivityLogIndex extends Component
{
    use WithPagination;

    public string $filterAksi = '';

    public bool $showCleanModal = false;

    public int $cleanDays = 30;

    public function updatingFilterAksi(): void
    {
        $this->resetPage();
    }

    public function openCleanModal(): void
    {
        $this->showCleanModal = true;
    }

    public function cleanLogs(): void
    {
        $query = Activity::query();

        if ($this->cleanDays > 0) {
            $cutoff = now()->subDays($this->cleanDays);
            $count = $query->where('created_at', '<', $cutoff)->delete();
            session()->flash('success', "Berhasil membersihkan {$count} catatan aktivitas yang lebih lama dari {$this->cleanDays} hari.");
        } else {
            // Bersihkan semua riwayat log
            $count = $query->count();
            Activity::truncate();
            session()->flash('success', "Berhasil mengosongkan seluruh riwayat log aktivitas ({$count} data dihapus).");
        }

        $this->showCleanModal = false;
        $this->resetPage();
    }

    public function render()
    {
        $logs = Activity::query()
            ->with('causer')
            ->when($this->filterAksi, fn ($q) => $q->where('description', $this->filterAksi))
            ->latest()
            ->paginate(20);

        return view('livewire.admin.activity-log-index', [
            'logs' => $logs,
            'totalLogs' => Activity::count(),
        ])->layout('components.layouts.admin', ['title' => 'Riwayat Aktivitas']);
    }
}
