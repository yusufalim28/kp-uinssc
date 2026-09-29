<?php

namespace App\Livewire\Public;

use App\Models\JadwalPraktikum;
use App\Models\MataKuliah;
use App\Models\Dosen;
use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        return view('livewire.public.home', [
            'totalJadwalAktif' => JadwalPraktikum::where('status', 'aktif')->count(),
            'totalMataKuliah'  => MataKuliah::count(),
            'totalDosen'       => Dosen::count(),
            'jadwalTerbaru'    => JadwalPraktikum::with(['mataKuliah', 'dosen'])
                ->where('status', 'aktif')
                ->latest()
                ->limit(5)
                ->get(),
        ])->layout('components.layouts.guest', ['title' => 'Beranda']);
    }
}
