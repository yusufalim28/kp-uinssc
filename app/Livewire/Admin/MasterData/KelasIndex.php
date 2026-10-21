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
        $user = auth()->user();
        $kelas = Kelas::with('programStudi')->findOrFail($id);

        if ($user && $user->hasRole('operator') && $user->fakultas_id) {
            abort_if($kelas->programStudi && $kelas->programStudi->fakultas_id !== $user->fakultas_id, 403, 'Operator hanya dapat mengedit Kelas di bawah fakultas sendiri.');
        }

        $this->editId = $kelas->id;
        $this->nama_kelas = $kelas->nama_kelas;
        $this->program_studi_id = $kelas->program_studi_id;
        $this->semester = $kelas->semester;
        $this->status = $kelas->status;
        $this->showModal = true;
    }

    public function save(): void
    {
        $user = auth()->user();
        $validated = $this->validate();

        if ($user && $user->hasRole('operator') && $user->fakultas_id) {
            $prodi = ProgramStudi::findOrFail($this->program_studi_id);
            abort_if($prodi->fakultas_id !== $user->fakultas_id, 403, 'Operator hanya dapat mengelola Kelas di bawah fakultas sendiri.');

            if ($this->editId) {
                $kelas = Kelas::with('programStudi')->findOrFail($this->editId);
                abort_if($kelas->programStudi && $kelas->programStudi->fakultas_id !== $user->fakultas_id, 403, 'Operator hanya dapat mengedit Kelas di bawah fakultas sendiri.');
            }
        }

        Kelas::updateOrCreate(['id' => $this->editId], $validated);

        $this->showModal = false;
        $this->reset(['editId', 'nama_kelas', 'program_studi_id', 'semester']);
        session()->flash('success', $this->editId ? 'Kelas berhasil diperbarui.' : 'Kelas berhasil ditambahkan.');
    }

    public function confirmDelete(int $id): void
    {
        $user = auth()->user();
        if ($user && $user->hasRole('operator') && $user->fakultas_id) {
            $kelas = Kelas::with('programStudi')->findOrFail($id);
            abort_if($kelas->programStudi && $kelas->programStudi->fakultas_id !== $user->fakultas_id, 403, 'Operator hanya dapat menghapus Kelas di bawah fakultas sendiri.');
        }

        $this->confirmDeleteId = $id;
    }

    public function delete(): void
    {
        $user = auth()->user();
        $kelas = Kelas::with('programStudi')->find($this->confirmDeleteId);

        if ($kelas) {
            if ($user && $user->hasRole('operator') && $user->fakultas_id) {
                abort_if($kelas->programStudi && $kelas->programStudi->fakultas_id !== $user->fakultas_id, 403, 'Operator hanya dapat menghapus Kelas di bawah fakultas sendiri.');
            }

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
        $user = auth()->user();

        $query = Kelas::query()->with('programStudi');

        if ($user && $user->hasRole('operator') && $user->fakultas_id) {
            $query->whereHas('programStudi', fn ($q) => $q->where('fakultas_id', $user->fakultas_id));
        }

        $kelas = $query
            ->when($this->search, fn ($q) => $q->where('nama_kelas', 'like', "%{$this->search}%"))
            ->orderBy('nama_kelas')
            ->paginate(10);

        $programStudi = ($user && $user->hasRole('operator') && $user->fakultas_id)
            ? ProgramStudi::where('fakultas_id', $user->fakultas_id)->orderBy('nama_program_studi')->get()
            : ProgramStudi::orderBy('nama_program_studi')->get();

        return view('livewire.admin.master-data.kelas-index', [
            'kelas' => $kelas,
            'programStudi' => $programStudi,
        ])->layout('components.layouts.admin', ['title' => 'Master Data — Kelas']);
    }
}
