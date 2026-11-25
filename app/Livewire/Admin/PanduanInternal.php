<?php

namespace App\Livewire\Admin;

use App\Models\Panduan;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Buku Pedoman & Alur Kerja Sistem')]
class PanduanInternal extends Component
{
    public string $userRoleKey = 'operator';

    public string $userRoleLabel = 'Alur Operator Fakultas';

    public string $activeTab = 'operator';

    public int $openSection = 1;

    public function mount(): void
    {
        $user = auth()->user();
        if ($user) {
            if ($user->hasSuperAdminView()) {
                $this->userRoleKey = 'super-admin';
                $this->userRoleLabel = 'Alur Super Admin';
            } elseif ($user->hasRole('ketua-lab')) {
                $this->userRoleKey = 'ketua-lab';
                $this->userRoleLabel = 'Alur Ketua Laboratorium';
            } elseif ($user->hasRole('dosen')) {
                $this->userRoleKey = 'dosen';
                $this->userRoleLabel = 'Alur Dosen / Tutor';
            } else {
                $this->userRoleKey = 'operator';
                $this->userRoleLabel = 'Alur Operator Fakultas';
            }
        }

        $this->activeTab = $this->userRoleKey;
    }

    public function setTab(string $tab): void
    {
        if ($tab === 'fitur' || $tab === $this->userRoleKey) {
            $this->activeTab = $tab;
        } else {
            $this->activeTab = $this->userRoleKey;
        }

        $this->openSection = 1;
    }

    public function toggleSection(int $section): void
    {
        $this->openSection = ($this->openSection === $section) ? 0 : $section;
    }

    public function render()
    {
        $panduan = Panduan::where('is_active', true)
            ->where('kategori', $this->activeTab)
            ->orderBy('urutan')
            ->get();

        if ($panduan->isEmpty() && $this->activeTab === 'operator') {
            $panduan = Panduan::where('is_active', true)
                ->where('kategori', 'internal')
                ->orderBy('urutan')
                ->get();
        }

        return view('livewire.admin.panduan-internal', [
            'panduan' => $panduan,
            'userRoleKey' => $this->userRoleKey,
            'userRoleLabel' => $this->userRoleLabel,
        ])->layout('components.layouts.admin', ['title' => 'Buku Pedoman & Alur Kerja']);
    }
}
