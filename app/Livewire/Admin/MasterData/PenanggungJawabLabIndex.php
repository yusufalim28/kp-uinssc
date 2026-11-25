<?php

namespace App\Livewire\Admin\MasterData;

use App\Models\Fakultas;
use App\Models\PenanggungJawabLab;
use App\Models\RuangPraktikum;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class PenanggungJawabLabIndex extends Component
{
    use WithFileUploads, WithPagination;

    public ?int $editId = null;

    public ?int $ruang_praktikum_id = null;

    public string $nama = '';

    public string $nip_nidn = '';

    public string $jabatan = '';

    public string $tingkat = 'anggota';

    public int $urutan = 1;

    public $foto = null;

    public ?string $existingFoto = null;

    public bool $hapusFoto = false;

    public string $email = '';

    public string $no_telepon = '';

    public string $link_instagram = '';

    public string $link_linkedin = '';

    public string $status = 'aktif';

    public bool $showModal = false;

    public ?int $confirmDeleteId = null;

    public string $search = '';

    public ?int $filterFakultas = null;

    public ?int $filterRuang = null;

    public string $filterTingkat = '';

    public string $viewMode = 'grid';

    protected function rules(): array
    {
        return [
            'ruang_praktikum_id' => 'required|exists:ruang_praktikum,id',
            'nama' => 'required|string|max:150',
            'nip_nidn' => 'nullable|string|max:50',
            'jabatan' => 'required|string|max:100',
            'tingkat' => 'required|in:pimpinan,anggota',
            'urutan' => 'required|integer|min:1|max:999',
            'foto' => 'nullable|file|extensions:jpg,jpeg,png,heic,heif,webp|max:20480',
            'email' => ['nullable', 'string', 'max:150', 'email:rfc', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'no_telepon' => ['nullable', 'string', 'max:20', 'regex:/^(?:\+62|62|0)8[1-9][0-9]{7,11}$/'],
            'link_instagram' => 'nullable|url|max:255',
            'link_linkedin' => 'nullable|url|max:255',
            'status' => 'required|in:aktif,nonaktif',
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'email.email' => 'Format alamat email tidak valid (contoh: nama@gmail.com atau nama@uinssc.ac.id).',
            'email.regex' => 'Alamat email harus memiliki domain yang valid (contoh: nama@gmail.com atau nama@uinssc.ac.id).',
            'no_telepon.regex' => 'Nomor WhatsApp harus format Indonesia yang valid, diawali 08 atau +628 (10–14 digit angka).',
            'link_instagram.url' => 'Tautan Instagram harus berupa URL valid (diawali https://).',
            'link_linkedin.url' => 'Tautan LinkedIn harus berupa URL valid (diawali https://).',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterFakultas(): void
    {
        $this->filterRuang = null;
        $this->resetPage();
    }

    public function updatingFilterRuang(): void
    {
        $this->resetPage();
    }

    public function updatingFilterTingkat(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'filterFakultas', 'filterRuang', 'filterTingkat']);
        $this->resetPage();
    }

    protected function authorizeRuangAccess(RuangPraktikum $ruang): void
    {
        $user = auth()->user();

        if (! $user || $user->hasRole('super-admin')) {
            return;
        }

        if ($user->hasRole('ketua-lab')) {
            if ($user->ruang_praktikum_id) {
                abort_if((int) $ruang->id !== (int) $user->ruang_praktikum_id, 403, 'Ketua Lab hanya berwenang mengelola penanggung jawab pada laboratorium miliknya.');
            } elseif ($user->fakultas_id) {
                abort_if((int) $ruang->fakultas_id !== (int) $user->fakultas_id, 403, 'Ketua Lab hanya berwenang mengelola penanggung jawab pada fakultas miliknya.');
            }

            return;
        }

        if ($user->hasRole('operator') && $user->fakultas_id) {
            abort_if((int) $ruang->fakultas_id !== (int) $user->fakultas_id, 403, 'Operator hanya berwenang mengelola penanggung jawab laboratorium di fakultasnya sendiri.');
        }
    }

    public function updatedFoto(): void
    {
        if ($this->foto) {
            $this->hapusFoto = false;
        }
    }

    public function removeFoto(): void
    {
        if ($this->foto) {
            $this->foto = null;

            return;
        }

        if ($this->existingFoto) {
            $this->existingFoto = null;
            $this->hapusFoto = true;
        }
    }

    public function openCreate(): void
    {
        $this->reset([
            'editId', 'ruang_praktikum_id', 'nama', 'nip_nidn', 'jabatan',
            'tingkat', 'urutan', 'foto', 'existingFoto', 'hapusFoto', 'email',
            'no_telepon', 'link_instagram', 'link_linkedin', 'status',
        ]);

        $user = auth()->user();
        if ($user && $user->hasRole('ketua-lab') && $user->ruang_praktikum_id) {
            $this->ruang_praktikum_id = $user->ruang_praktikum_id;
        }

        $this->tingkat = 'anggota';
        $this->urutan = 1;
        $this->status = 'aktif';
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $pj = PenanggungJawabLab::with('ruangPraktikum')->findOrFail($id);

        if ($pj->ruangPraktikum) {
            $this->authorizeRuangAccess($pj->ruangPraktikum);
        }

        $this->editId = $pj->id;
        $this->ruang_praktikum_id = $pj->ruang_praktikum_id;
        $this->nama = $pj->nama;
        $this->nip_nidn = $pj->nip_nidn ?? '';
        $this->jabatan = $pj->jabatan;
        $this->tingkat = $pj->tingkat ?? 'anggota';
        $this->urutan = (int) ($pj->urutan ?? 1);
        $this->foto = null;
        $this->existingFoto = $pj->foto;
        $this->hapusFoto = false;
        $this->email = $pj->email ?? '';
        $this->no_telepon = $pj->no_telepon ?? '';
        $this->link_instagram = $pj->link_instagram ?? '';
        $this->link_linkedin = $pj->link_linkedin ?? '';
        $this->status = $pj->status ?? 'aktif';
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->email = strtolower(trim($this->email));
        $this->no_telepon = preg_replace('/[\s\-]+/', '', trim($this->no_telepon)) ?? '';

        $validated = $this->validate();

        $ruang = RuangPraktikum::findOrFail($validated['ruang_praktikum_id']);
        $this->authorizeRuangAccess($ruang);

        $data = [
            'fakultas_id' => $ruang->fakultas_id,
            'ruang_praktikum_id' => $ruang->id,
            'nama' => $validated['nama'],
            'nip_nidn' => $validated['nip_nidn'] ?: null,
            'jabatan' => $validated['jabatan'],
            'tingkat' => $validated['tingkat'],
            'urutan' => $validated['urutan'],
            'email' => $validated['email'] ?: null,
            'no_telepon' => $validated['no_telepon'] ?: null,
            'link_instagram' => $validated['link_instagram'] ?: null,
            'link_linkedin' => $validated['link_linkedin'] ?: null,
            'status' => $validated['status'],
        ];

        if ($this->editId) {
            $pj = PenanggungJawabLab::with('ruangPraktikum')->findOrFail($this->editId);
            if ($pj->ruangPraktikum) {
                $this->authorizeRuangAccess($pj->ruangPraktikum);
            }

            if ($this->foto) {
                if ($pj->foto && Storage::disk('public')->exists($pj->foto)) {
                    Storage::disk('public')->delete($pj->foto);
                }
                $data['foto'] = $this->foto->store('penanggung-jawab-lab', 'public');
            } elseif ($this->hapusFoto) {
                if ($pj->foto && Storage::disk('public')->exists($pj->foto)) {
                    Storage::disk('public')->delete($pj->foto);
                }
                $data['foto'] = null;
            }

            $pj->update($data);
            $message = 'Data penanggung jawab laboratorium berhasil diperbarui.';
        } else {
            if ($this->foto) {
                $data['foto'] = $this->foto->store('penanggung-jawab-lab', 'public');
            }
            PenanggungJawabLab::create($data);
            $message = 'Penanggung jawab laboratorium berhasil ditambahkan.';
        }

        $this->showModal = false;
        $this->reset([
            'editId', 'ruang_praktikum_id', 'nama', 'nip_nidn', 'jabatan',
            'tingkat', 'urutan', 'foto', 'existingFoto', 'hapusFoto', 'email',
            'no_telepon', 'link_instagram', 'link_linkedin', 'status',
        ]);
        session()->flash('success', $message);
    }

    public function confirmDelete(int $id): void
    {
        $pj = PenanggungJawabLab::with('ruangPraktikum')->findOrFail($id);
        if ($pj->ruangPraktikum) {
            $this->authorizeRuangAccess($pj->ruangPraktikum);
        }
        $this->confirmDeleteId = $id;
    }

    public function delete(): void
    {
        $pj = PenanggungJawabLab::with('ruangPraktikum')->find($this->confirmDeleteId);

        if ($pj) {
            if ($pj->ruangPraktikum) {
                $this->authorizeRuangAccess($pj->ruangPraktikum);
            }
            $pj->delete();
            session()->flash('success', 'Data penanggung jawab laboratorium berhasil dihapus.');
        }

        $this->confirmDeleteId = null;
    }

    public function render(): View
    {
        $user = auth()->user();

        $query = PenanggungJawabLab::query()
            ->with(['ruangPraktikum.fakultas', 'fakultas']);

        $ruangQuery = RuangPraktikum::query()->with('fakultas')->orderBy('nama_ruang');

        if ($user && $user->hasRole('ketua-lab')) {
            if ($user->ruang_praktikum_id) {
                $query->where('ruang_praktikum_id', $user->ruang_praktikum_id);
                $ruangQuery->where('id', $user->ruang_praktikum_id);
            } elseif ($user->fakultas_id) {
                $query->where('fakultas_id', $user->fakultas_id);
                $ruangQuery->where('fakultas_id', $user->fakultas_id);
            }
        } elseif ($user && $user->hasRole('operator') && $user->fakultas_id) {
            $query->where(function ($q) use ($user) {
                $q->where('fakultas_id', $user->fakultas_id)
                    ->orWhereHas('ruangPraktikum', fn ($r) => $r->where('fakultas_id', $user->fakultas_id));
            });
            $ruangQuery->where('fakultas_id', $user->fakultas_id);
        } elseif ($user && $user->hasSuperAdminView() && $this->filterFakultas) {
            $fakId = (int) $this->filterFakultas;
            $query->where(function ($q) use ($fakId) {
                $q->where('fakultas_id', $fakId)
                    ->orWhereHas('ruangPraktikum', fn ($r) => $r->where('fakultas_id', $fakId));
            });
            $ruangQuery->where('fakultas_id', $fakId);
        }

        if ($this->search !== '') {
            $search = trim($this->search);
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('jabatan', 'like', "%{$search}%")
                    ->orWhere('nip_nidn', 'like', "%{$search}%")
                    ->orWhereHas('ruangPraktikum', fn ($r) => $r->where('nama_ruang', 'like', "%{$search}%"));
            });
        }

        if ($this->filterRuang) {
            $query->where('ruang_praktikum_id', $this->filterRuang);
        }

        if ($this->filterTingkat !== '') {
            $query->where('tingkat', $this->filterTingkat);
        }

        $penanggungJawab = $query
            ->orderBy('ruang_praktikum_id')
            ->orderByRaw("CASE tingkat WHEN 'pimpinan' THEN 1 ELSE 2 END")
            ->orderBy('urutan')
            ->orderBy('nama')
            ->paginate(12);

        return view('livewire.admin.master-data.penanggung-jawab-lab-index', [
            'penanggungJawab' => $penanggungJawab,
            'ruangList' => $ruangQuery->get(),
            'fakultasList' => Fakultas::orderBy('nama_fakultas')->get(),
            'isSuperAdmin' => $user && $user->hasSuperAdminView(),
            'isKetuaLabLocked' => $user && $user->hasRole('ketua-lab') && ! is_null($user->ruang_praktikum_id),
        ])->layout('components.layouts.admin', ['title' => 'Penanggung Jawab Laboratorium']);
    }
}
