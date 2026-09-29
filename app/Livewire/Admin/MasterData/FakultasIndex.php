<?php

namespace App\Livewire\Admin\MasterData;

use App\Models\Fakultas;
use Livewire\Component;
use Livewire\WithPagination;

class FakultasIndex extends Component
{
    use WithPagination;

    // State form
    public ?int $editId = null;
    public string $nama_fakultas = '';
    public string $kode_fakultas = '';
    public string $status = 'aktif';

    public bool $showModal = false;
    public string $search = '';

    // State konfirmasi hapus
    public ?int $confirmDeleteId = null;

    protected function rules(): array
    {
        return [
            'nama_fakultas' => 'required|string|max:150',
            'kode_fakultas' => 'required|string|max:20|unique:fakultas,kode_fakultas,' . $this->editId,
            'status'        => 'required|in:aktif,nonaktif',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->reset(['editId', 'nama_fakultas', 'kode_fakultas']);
        $this->status = 'aktif';
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $fakultas = Fakultas::findOrFail($id);

        $this->editId        = $fakultas->id;
        $this->nama_fakultas = $fakultas->nama_fakultas;
        $this->kode_fakultas = $fakultas->kode_fakultas;
        $this->status        = $fakultas->status;
        $this->showModal     = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        Fakultas::updateOrCreate(
            ['id' => $this->editId],
            $validated
        );

        $this->showModal = false;
        $this->reset(['editId', 'nama_fakultas', 'kode_fakultas']);
        session()->flash('success', $this->editId ? 'Fakultas berhasil diperbarui.' : 'Fakultas berhasil ditambahkan.');
    }

    public function confirmDelete(int $id): void
    {
        $this->confirmDeleteId = $id;
    }

    public function delete(): void
    {
        $fakultas = Fakultas::find($this->confirmDeleteId);

        if ($fakultas) {
            // Cegah hapus fakultas yang masih punya program studi anak
            if ($fakultas->programStudi()->exists()) {
                session()->flash('error', 'Fakultas tidak bisa dihapus karena masih memiliki Program Studi.');
            } else {
                $fakultas->delete();
                session()->flash('success', 'Fakultas berhasil dihapus.');
            }
        }

        $this->confirmDeleteId = null;
    }

    public function render()
    {
        $fakultas = Fakultas::query()
            ->when($this->search, fn ($q) => $q->where('nama_fakultas', 'like', "%{$this->search}%")
                ->orWhere('kode_fakultas', 'like', "%{$this->search}%"))
            ->orderBy('nama_fakultas')
            ->paginate(10);

        return view('livewire.admin.master-data.fakultas-index', [
            'fakultas' => $fakultas,
        ])->layout('components.layouts.admin', ['title' => 'Master Data — Fakultas']);
    }
}
