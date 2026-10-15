<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use Livewire\WithPagination;

class NotifikasiSaya extends Component
{
    use WithPagination;

    public function markAsRead(string $id): void
    {
        $notifikasi = auth()->user()->notifications()->findOrFail($id);
        $notifikasi->markAsRead();
    }

    public function markAllAsRead(): void
    {
        auth()->user()->unreadNotifications->markAsRead();
    }

    public function render()
    {
        $notifikasi = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(10);

        return view('livewire.dosen.notifikasi-saya', [
            'notifikasi' => $notifikasi,
            'jumlahBelumDibaca' => auth()->user()->unreadNotifications()->count(),
        ])->layout('components.layouts.admin', ['title' => 'Notifikasi Saya']);
    }
}
