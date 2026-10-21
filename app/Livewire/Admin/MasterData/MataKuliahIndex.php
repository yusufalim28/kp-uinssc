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
        $user = auth()->user();
        $mk = MataKuliah::with('programStudi')->findOrFail($id);

        if ($user && $user->hasRole('operator') && $user->fakultas_id) {
            abort_if($mk->programStudi && $mk->programStudi->fakultas_id !== $user->fakultas_id, 403, 'Operator hanya dapat mengedit Mata Kuliah di bawah fakultas sendiri.');
        }

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
        $user = auth()->user();
        $validated = $this->validate();

        if ($user && $user->hasRole('operator') && $user->fakultas_id) {
            $prodi = ProgramStudi::findOrFail($this->program_studi_id);
            abort_if($prodi->fakultas_id !== $user->fakultas_id, 403, 'Operator hanya dapat mengelola Mata Kuliah di bawah fakultas sendiri.');

            if ($this->editId) {
                $mk = MataKuliah::with('programStudi')->findOrFail($this->editId);
                abort_if($mk->programStudi && $mk->programStudi->fakultas_id !== $user->fakultas_id, 403, 'Operator hanya dapat mengedit Mata Kuliah di bawah fakultas sendiri.');
            }
        }

        MataKuliah::updateOrCreate(['id' => $this->editId], $validated);

        $this->showModal = false;
        $this->reset(['editId', 'kode_mk', 'nama_mk', 'program_studi_id', 'sks', 'semester']);
        session()->flash('success', $this->editId ? 'Mata Kuliah berhasil diperbarui.' : 'Mata Kuliah berhasil ditambahkan.');
    }

    public function confirmDelete(int $id): void
    {
        $user = auth()->user();
        if ($user && $user->hasRole('operator') && $user->fakultas_id) {
            $mk = MataKuliah::with('programStudi')->findOrFail($id);
            abort_if($mk->programStudi && $mk->programStudi->fakultas_id !== $user->fakultas_id, 403, 'Operator hanya dapat menghapus Mata Kuliah di bawah fakultas sendiri.');
        }

        $this->confirmDeleteId = $id;
    }

    public function delete(): void
    {
        $user = auth()->user();
        $mk = MataKuliah::with('programStudi')->find($this->confirmDeleteId);

        if ($mk) {
            if ($user && $user->hasRole('operator') && $user->fakultas_id) {
                abort_if($mk->programStudi && $mk->programStudi->fakultas_id !== $user->fakultas_id, 403, 'Operator hanya dapat menghapus Mata Kuliah di bawah fakultas sendiri.');
            }

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
        $user = auth()->user();

        $query = MataKuliah::query()->with('programStudi');

        if ($user && $user->hasRole('operator') && $user->fakultas_id) {
            $query->whereHas('programStudi', fn ($q) => $q->where('fakultas_id', $user->fakultas_id));
        }

        $mataKuliah = $query
            ->when($this->search, fn ($q) => $q->where('nama_mk', 'like', "%{$this->search}%")
                ->orWhere('kode_mk', 'like', "%{$this->search}%"))
            ->orderBy('nama_mk')
            ->paginate(10);

        $programStudi = ($user && $user->hasRole('operator') && $user->fakultas_id)
            ? ProgramStudi::where('fakultas_id', $user->fakultas_id)->orderBy('nama_program_studi')->get()
            : ProgramStudi::orderBy('nama_program_studi')->get();

        return view('livewire.admin.master-data.mata-kuliah-index', [
            'mataKuliah' => $mataKuliah,
            'programStudi' => $programStudi,
        ])->layout('components.layouts.admin', ['title' => 'Master Data — Mata Kuliah']);
    }
}
