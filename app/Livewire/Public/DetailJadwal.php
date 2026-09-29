<?php

namespace App\Livewire\Public;

use App\Models\JadwalPraktikum;
use Livewire\Component;

class DetailJadwal extends Component
{
    public JadwalPraktikum $jadwal;

    /**
     * Livewire full-page component mendukung route-model binding
     * langsung lewat parameter mount(), sama seperti di Controller.
     */
    public function mount(JadwalPraktikum $jadwal): void
    {
        $this->jadwal = $jadwal->load(['mataKuliah', 'dosen', 'kelas', 'ruangPraktikum', 'tahunAkademik']);
    }

    public function render()
    {
        return view('livewire.public.detail-jadwal')
            ->layout('components.layouts.guest', ['title' => 'Detail Jadwal']);
    }
}
