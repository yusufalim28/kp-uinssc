<?php

namespace App\Livewire\Public;

use App\Models\Dosen;
use App\Models\JadwalPraktikum;
use App\Models\MataKuliah;
use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        $hariMap = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];
        $hariIni = $hariMap[now()->format('l')] ?? now()->format('l');

        return view('livewire.public.home', [
            'totalJadwalAktif' => JadwalPraktikum::where('status', 'aktif')->count(),
            'totalMataKuliah' => MataKuliah::count(),
            'totalDosen' => Dosen::count(),
            'hariIni' => $hariIni,
            'jadwalHariIni' => JadwalPraktikum::with(['mataKuliah', 'dosen', 'ruangPraktikum', 'kelas'])
                ->where('status', 'aktif')
                ->where('hari', $hariIni)
                ->orderBy('jam_mulai')
                ->get(),
        ])->layout('components.layouts.guest', ['title' => 'Beranda']);
    }
}
