<?php

namespace App\Livewire\Admin\MasterData;

use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use Livewire\Component;
use Livewire\WithPagination;

class MataKuliahIndex extends Component
{
    use WithPagination;

    public ?int $editId = null;

    public string $kode_mk = '';

    public string $nama_mk = '';

    public ?int $program_studi_id = null;

    public ?int $sks = null;

    public ?int $semester = null;

    public string $status = 'aktif';

    public bool $showModal = false;

    public string $search = '';

    public ?int $confirmDeleteId = null;

    protected function rules(): array
    {
        return [
            'kode_mk' => 'required|string|max:20|unique:mata_kuliah,kode_mk,'.$this->editId,
            'nama_mk' => 'required|string|max:150',
            'program_studi_id' => 'required|exists:program_studi,id',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:8',
            'status' => 'required|in:aktif,nonaktif',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->reset(['editId', 'kode_mk', 'nama_mk', 'program_studi_id', 'sks', 'semester']);
        $this->status = 'aktif';
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $mk = MataKuliah::findOrFail($id);

        $this->editId = $mk->id;
        $this->kode_mk = $mk->kode_mk;
        $this->nama_mk = $mk->nama_mk;
        $this->program_studi_id = $mk->program_studi_id;
        $this->sks = $mk->sks;
        $this->semester = $mk->semester;
        $this->status = $mk->status;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        MataKuliah::updateOrCreate(['id' => $this->editId], $validated);

        $this->showModal = false;
        $this->reset(['editId', 'kode_mk', 'nama_mk', 'program_studi_id', 'sks', 'semester']);
        session()->flash('success', $this->editId ? 'Mata Kuliah berhasil diperbarui.' : 'Mata Kuliah berhasil ditambahkan.');
    }

    public function confirmDelete(int $id): void
    {
        $this->confirmDeleteId = $id;
    }

    public function delete(): void
    {
        $mk = MataKuliah::find($this->confirmDeleteId);

        if ($mk) {
            if ($mk->jadwalPraktikum()->exists()) {
                session()->flash('error', 'Mata Kuliah tidak bisa dihapus karena masih dipakai di Jadwal Praktikum.');
            } else {
                $mk->delete();
                session()->flash('success', 'Mata Kuliah berhasil dihapus.');
            }
        }

        $this->confirmDeleteId = null;
    }

    public function render()
    {
        $mataKuliah = MataKuliah::query()
            ->with('programStudi')
            ->when($this->search, fn ($q) => $q->where('nama_mk', 'like', "%{$this->search}%")
                ->orWhere('kode_mk', 'like', "%{$this->search}%"))
            ->orderBy('nama_mk')
            ->paginate(10);

        return view('livewire.admin.master-data.mata-kuliah-index', [
            'mataKuliah' => $mataKuliah,
            'programStudi' => ProgramStudi::orderBy('nama_program_studi')->get(),
        ])->layout('components.layouts.admin', ['title' => 'Master Data — Mata Kuliah']);
    }
}
