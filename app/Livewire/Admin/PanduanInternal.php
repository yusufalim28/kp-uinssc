<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Panduan & SOP Pengelola Praktikum')]
class PanduanInternal extends Component
{
    public int $openSection = 1;

    public function toggleSection(int $section): void
    {
        $this->openSection = ($this->openSection === $section) ? 0 : $section;
    }

    public function render()
    {
        return view('livewire.admin.panduan-internal')
            ->layout('components.layouts.admin', ['title' => 'Panduan & SOP Pengelola']);
    }
}
