<?php

namespace App\Livewire\Public;

use App\Models\Fakultas;
use App\Models\RuangPraktikum;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layouts.guest')]
#[Title('Struktur Penanggung Jawab Laboratorium — SI Jadwal Praktikum')]
class StrukturLab extends Component
{
    #[Url(as: 'fakultas')]
    public ?int $fakultasId = null;

    #[Url(as: 'ruang')]
    public ?int $ruangPraktikumId = null;

    public string $search = '';

    public function updatedFakultasId(): void
    {
        $this->ruangPraktikumId = null;
    }

    public function resetFilters(): void
    {
        $this->reset(['fakultasId', 'ruangPraktikumId', 'search']);
    }

    public function render(): View
    {
        $fakultasList = Fakultas::where('status', 'aktif')
            ->orderBy('nama_fakultas')
            ->get();

        $ruangOptionsQuery = RuangPraktikum::where('status', 'aktif')
            ->orderBy('nama_ruang');

        if ($this->fakultasId) {
            $ruangOptionsQuery->where('fakultas_id', $this->fakultasId);
        }

        $ruangOptions = $ruangOptionsQuery->get();

        $search = trim($this->search);

        $ruangGroupsQuery = RuangPraktikum::query()
            ->where('status', 'aktif')
            ->with([
                'fakultas',
                'penanggungJawab' => function ($query) use ($search) {
                    $query->where('status', 'aktif')
                        ->when($search !== '', function ($q) use ($search) {
                            $q->where(function ($sub) use ($search) {
                                $sub->where('nama', 'like', "%{$search}%")
                                    ->orWhere('jabatan', 'like', "%{$search}%")
                                    ->orWhere('nip_nidn', 'like', "%{$search}%");
                            });
                        })
                        ->orderByRaw("CASE tingkat WHEN 'pimpinan' THEN 1 ELSE 2 END")
                        ->orderBy('urutan')
                        ->orderBy('nama');
                },
            ])
            ->whereHas('penanggungJawab', function ($query) use ($search) {
                $query->where('status', 'aktif')
                    ->when($search !== '', function ($q) use ($search) {
                        $q->where(function ($sub) use ($search) {
                            $sub->where('nama', 'like', "%{$search}%")
                                ->orWhere('jabatan', 'like', "%{$search}%")
                                ->orWhere('nip_nidn', 'like', "%{$search}%");
                        });
                    });
            });

        if ($this->fakultasId) {
            $ruangGroupsQuery->where('fakultas_id', $this->fakultasId);
        }

        if ($this->ruangPraktikumId) {
            $ruangGroupsQuery->where('id', $this->ruangPraktikumId);
        }

        $ruangGroups = $ruangGroupsQuery->orderBy('nama_ruang')->get();

        return view('livewire.public.struktur-lab', [
            'fakultasList' => $fakultasList,
            'ruangOptions' => $ruangOptions,
            'ruangGroups' => $ruangGroups,
        ]);
    }
}
