<?php

namespace App\Livewire\Public;

use App\Models\JadwalPraktikum;
use Livewire\Component;

class KalenderJadwal extends Component
{
    public function render()
    {
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', "Jum'at", 'Sabtu'];

        $jadwalPerHari = JadwalPraktikum::with(['mataKuliah', 'dosen', 'ruangPraktikum'])
            ->where('status', 'aktif')
            ->orderBy('jam_mulai')
            ->get()
            ->groupBy('hari');

        return view('livewire.public.kalender-jadwal', [
            'hariList'       => $hariList,
            'jadwalPerHari'  => $jadwalPerHari,
        ])->layout('components.layouts.guest', ['title' => 'Kalender Jadwal']);
    }
}
