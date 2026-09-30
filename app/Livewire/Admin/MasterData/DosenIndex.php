<?php

namespace App\Livewire\Admin\MasterData;

use App\Models\Dosen;
use Livewire\Component;
use Livewire\WithPagination;

class DosenIndex extends Component
{
    use WithPagination;

    public ?int $editId = null;

    public string $kode_dosen = '';

    public string $nama = '';

    public string $email = '';

    public string $status = 'aktif';

    public bool $showModal = false;

    public string $search = '';

    public ?int $confirmDeleteId = null;

    protected function rules(): array
    {
        return [
            'kode_dosen' => 'required|string|max:20|unique:dosen,kode_dosen,'.$this->editId,
            'nama' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:dosen,email,'.$this->editId,
            'status' => 'required|in:aktif,nonaktif',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->reset(['editId', 'kode_dosen', 'nama', 'email']);
        $this->status = 'aktif';
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $dosen = Dosen::findOrFail($id);

        $this->editId = $dosen->id;
        $this->kode_dosen = $dosen->kode_dosen;
        $this->nama = $dosen->nama;
        $this->email = $dosen->email;
        $this->status = $dosen->status;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        Dosen::updateOrCreate(['id' => $this->editId], $validated);

        $this->showModal = false;
        $this->reset(['editId', 'kode_dosen', 'nama', 'email']);
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
                ->orWhere('kode_dosen', 'like', "%{$this->search}%"))
            ->orderBy('nama')
            ->paginate(10);

        return view('livewire.admin.master-data.dosen-index', [
            'dosen' => $dosen,
        ])->layout('components.layouts.admin', ['title' => 'Master Data — Dosen']);
    }
}
