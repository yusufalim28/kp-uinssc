<?php

namespace App\Livewire\Admin\MasterData;

use App\Models\Kelas;
use App\Models\ProgramStudi;
use Livewire\Component;
use Livewire\WithPagination;

class KelasIndex extends Component
{
    use WithPagination;

    public ?int $editId = null;

    public string $nama_kelas = '';

    public ?int $program_studi_id = null;

    public ?int $semester = null;

    public string $status = 'aktif';

    public bool $showModal = false;

    public string $search = '';

    public ?int $confirmDeleteId = null;

    protected function rules(): array
    {
        return [
            'nama_kelas' => 'required|string|max:50',
            'program_studi_id' => 'required|exists:program_studi,id',
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
        $this->reset(['editId', 'nama_kelas', 'program_studi_id', 'semester']);
        $this->status = 'aktif';
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $kelas = Kelas::findOrFail($id);

        $this->editId = $kelas->id;
        $this->nama_kelas = $kelas->nama_kelas;
        $this->program_studi_id = $kelas->program_studi_id;
        $this->semester = $kelas->semester;
        $this->status = $kelas->status;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        Kelas::updateOrCreate(['id' => $this->editId], $validated);

        $this->showModal = false;
        $this->reset(['editId', 'nama_kelas', 'program_studi_id', 'semester']);
        session()->flash('success', $this->editId ? 'Kelas berhasil diperbarui.' : 'Kelas berhasil ditambahkan.');
    }

    public function confirmDelete(int $id): void
    {
        $this->confirmDeleteId = $id;
    }

    public function delete(): void
    {
        $kelas = Kelas::find($this->confirmDeleteId);

        if ($kelas) {
            if ($kelas->jadwalPraktikum()->exists()) {
                session()->flash('error', 'Kelas tidak bisa dihapus karena masih dipakai di Jadwal Praktikum.');
            } else {
                $kelas->delete();
                session()->flash('success', 'Kelas berhasil dihapus.');
            }
        }

        $this->confirmDeleteId = null;
    }

    public function render()
    {
        $kelas = Kelas::query()
            ->with('programStudi')
            ->when($this->search, fn ($q) => $q->where('nama_kelas', 'like', "%{$this->search}%"))
            ->orderBy('nama_kelas')
            ->paginate(10);

        return view('livewire.admin.master-data.kelas-index', [
            'kelas' => $kelas,
            'programStudi' => ProgramStudi::orderBy('nama_program_studi')->get(),
        ])->layout('components.layouts.admin', ['title' => 'Master Data — Kelas']);
    }
}
