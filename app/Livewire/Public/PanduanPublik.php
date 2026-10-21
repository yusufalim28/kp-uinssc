<?php

namespace App\Livewire\Public;

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
        return view('livewire.public.panduan-publik')
            ->layout('components.layouts.guest', ['title' => 'Tata Tertib & Informasi Praktikum']);
    }
}
