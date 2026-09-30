<?php

namespace App\Livewire\Admin\MasterData;

use App\Models\RuangPraktikum;
use Livewire\Component;
use Livewire\WithPagination;

class RuangPraktikumIndex extends Component
{
    use WithPagination;

    public ?int $editId = null;

    public string $kode_ruang = '';

    public string $nama_ruang = '';

    public string $lokasi = '';

    public ?int $kapasitas = null;

    public string $status = 'aktif';

    public bool $showModal = false;

    public string $search = '';

    public ?int $confirmDeleteId = null;

    protected function rules(): array
    {
        return [
            'kode_ruang' => 'required|string|max:20|unique:ruang_praktikum,kode_ruang,'.$this->editId,
            'nama_ruang' => 'required|string|max:100',
            'lokasi' => 'required|string|max:150',
            'kapasitas' => 'required|integer|min:1|max:200',
            'status' => 'required|in:aktif,nonaktif',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->reset(['editId', 'kode_ruang', 'nama_ruang', 'lokasi', 'kapasitas']);
        $this->status = 'aktif';
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $ruang = RuangPraktikum::findOrFail($id);

        $this->editId = $ruang->id;
        $this->kode_ruang = $ruang->kode_ruang;
        $this->nama_ruang = $ruang->nama_ruang;
        $this->lokasi = $ruang->lokasi;
        $this->kapasitas = $ruang->kapasitas;
        $this->status = $ruang->status;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        RuangPraktikum::updateOrCreate(['id' => $this->editId], $validated);

        $this->showModal = false;
        $this->reset(['editId', 'kode_ruang', 'nama_ruang', 'lokasi', 'kapasitas']);
        session()->flash('success', $this->editId ? 'Ruang Praktikum berhasil diperbarui.' : 'Ruang Praktikum berhasil ditambahkan.');
    }

    public function confirmDelete(int $id): void
    {
        $this->confirmDeleteId = $id;
    }

    public function delete(): void
    {
        $ruang = RuangPraktikum::find($this->confirmDeleteId);

        if ($ruang) {
            if ($ruang->jadwalPraktikum()->exists()) {
                session()->flash('error', 'Ruang Praktikum tidak bisa dihapus karena masih dipakai di Jadwal Praktikum.');
            } else {
                $ruang->delete();
                session()->flash('success', 'Ruang Praktikum berhasil dihapus.');
            }
        }

        $this->confirmDeleteId = null;
    }

    public function render()
    {
        $ruang = RuangPraktikum::query()
            ->when($this->search, fn ($q) => $q->where('nama_ruang', 'like', "%{$this->search}%")
                ->orWhere('kode_ruang', 'like', "%{$this->search}%"))
            ->orderBy('nama_ruang')
            ->paginate(10);

        return view('livewire.admin.master-data.ruang-praktikum-index', [
            'ruang' => $ruang,
        ])->layout('components.layouts.admin', ['title' => 'Master Data — Ruang Praktikum']);
    }
}
