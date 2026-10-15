<?php

namespace App\Livewire\Admin\MasterData;

use App\Models\Dosen;
use Livewire\Component;
use Livewire\WithPagination;

class DosenIndex extends Component
{
    use WithPagination;

    public ?int $editId = null;

    public string $nidn = '';

    public string $kode_dosen = '';

    public string $nama = '';

    public string $email = '';

    public string $status = 'aktif';

    public bool $showModal = false;

    public string $search = '';

    public ?int $confirmDeleteId = null;

    public function updatedKodeDosen(string $value): void
    {
        $this->nidn = $value;
    }

    public function updatedNidn(string $value): void
    {
        $this->kode_dosen = $value;
    }

    protected function rules(): array
    {
        return [
            'nidn' => 'required|string|max:30|unique:dosen,nidn,'.$this->editId,
            'kode_dosen' => 'nullable|string|max:30',
            'nama' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:dosen,email,'.$this->editId,
            'status' => 'required|in:aktif,nonaktif,cuti,keluar',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->reset(['editId', 'nidn', 'kode_dosen', 'nama', 'email']);
        $this->status = 'aktif';
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $dosen = Dosen::findOrFail($id);

        $this->editId = $dosen->id;
        $this->nidn = $dosen->nidn;
        $this->kode_dosen = $dosen->nidn;
        $this->nama = $dosen->nama;
        $this->email = $dosen->email;
        $this->status = $dosen->status;
        $this->showModal = true;
    }

    public function save(): void
    {
        if (empty($this->nidn) && ! empty($this->kode_dosen)) {
            $this->nidn = $this->kode_dosen;
        }
        if (empty($this->kode_dosen) && ! empty($this->nidn)) {
            $this->kode_dosen = $this->nidn;
        }

        $validated = $this->validate();
        $validated['nidn'] = $this->nidn;
        unset($validated['kode_dosen']);

        Dosen::updateOrCreate(['id' => $this->editId], $validated);

        $this->showModal = false;
        $this->reset(['editId', 'nidn', 'kode_dosen', 'nama', 'email']);
        session()->flash('success', $this->editId ? 'Dosen berhasil diperbarui.' : 'Dosen berhasil ditambahkan.');
    }

    public function confirmDelete(int $id): void
    {
        $this->confirmDeleteId = $id;
    }

    public function delete(): void
    {
        $dosen = Dosen::find($this->confirmDeleteId);

        if ($dosen) {
            if ($dosen->jadwalPraktikum()->exists()) {
                session()->flash('error', 'Dosen tidak bisa dihapus karena masih memiliki Jadwal Praktikum.');
            } else {
                $dosen->delete();
                session()->flash('success', 'Dosen berhasil dihapus.');
            }
        }

        $this->confirmDeleteId = null;
    }

    public function render()
    {
        $dosen = Dosen::query()
            ->when($this->search, fn ($q) => $q->where('nama', 'like', "%{$this->search}%")
                ->orWhere('nidn', 'like', "%{$this->search}%"))
            ->orderBy('nama')
            ->paginate(10);

        return view('livewire.admin.master-data.dosen-index', [
            'dosen' => $dosen,
        ])->layout('components.layouts.admin', ['title' => 'Master Data — Dosen']);
    }
}
