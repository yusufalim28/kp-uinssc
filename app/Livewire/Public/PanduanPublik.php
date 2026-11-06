<?php

namespace App\Livewire\Public;

use App\Models\Panduan;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Tata Tertib & Informasi Praktikum')]
class PanduanPublik extends Component
{
    public int $openSection = 1;

    public function toggleSection(int $section): void
    {
        $this->openSection = ($this->openSection === $section) ? 0 : $section;
    }

    public function render()
    {
        $panduan = Panduan::where('kategori', 'publik')
            ->where('is_active', true)
            ->orderBy('urutan')
            ->get();

        return view('livewire.public.panduan-publik', [
            'panduan' => $panduan,
        ])->layout('components.layouts.guest', ['title' => 'Tata Tertib & Informasi Praktikum']);
    }
}
