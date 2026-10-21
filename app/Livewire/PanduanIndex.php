<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Panduan Penggunaan & Tata Tertib')]
class PanduanIndex extends Component
{
    public int $openSection = 1;

    public function toggleSection(int $section): void
    {
        $this->openSection = ($this->openSection === $section) ? 0 : $section;
    }

    public function render()
    {
        $layout = auth()->check() ? 'components.layouts.admin' : 'components.layouts.guest';

        return view('livewire.panduan-index')
            ->layout($layout, ['title' => 'Panduan Penggunaan & Tata Tertib']);
    }
}
