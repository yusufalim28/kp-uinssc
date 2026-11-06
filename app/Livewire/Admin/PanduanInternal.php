<?php

namespace App\Livewire\Admin;

use App\Models\Panduan;
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
        $panduan = Panduan::where('kategori', 'internal')
            ->where('is_active', true)
            ->orderBy('urutan')
            ->get();

        return view('livewire.admin.panduan-internal', [
            'panduan' => $panduan,
        ])->layout('components.layouts.admin', ['title' => 'Panduan & SOP Pengelola']);
    }
}
