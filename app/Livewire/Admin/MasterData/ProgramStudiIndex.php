<?php

namespace App\Livewire\Admin\MasterData;

use App\Models\Fakultas;
use App\Models\ProgramStudi;
use Livewire\Component;
use Livewire\WithPagination;

class ProgramStudiIndex extends Component
{
    use WithPagination;

    public ?int $editId = null;

    public string $nama_program_studi = '';

    public string $kode_program_studi = '';

    public ?int $fakultas_id = null;      // <-- beda dari Fakultas: ada relasi induk

    public string $status = 'aktif';

    public bool $showModal = false;

    public string $search = '';

    public ?int $confirmDeleteId = null;

    protected function rules(): array
    {
        return [
            'nama_program_studi' => 'required|string|max:150',
            'kode_program_studi' => 'required|string|max:20|unique:program_studi,kode_program_studi,'.$this->editId,
            'fakultas_id' => 'required|exists:fakultas,id',   // <-- validasi relasi induk
            'status' => 'required|in:aktif,nonaktif',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $user = auth()->user();
        $this->reset(['editId', 'nama_program_studi', 'kode_program_studi', 'fakultas_id']);
        if ($user && $user->hasRole('operator') && $user->fakultas_id) {
            $this->fakultas_id = $user->fakultas_id;
        }
        $this->status = 'aktif';
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $user = auth()->user();
        $prodi = ProgramStudi::findOrFail($id);

        if ($user && $user->hasRole('operator') && $user->fakultas_id) {
            abort_if($prodi->fakultas_id !== $user->fakultas_id, 403, 'Operator hanya dapat mengedit Program Studi di bawah fakultas sendiri.');
        }

        $this->editId = $prodi->id;
        $this->nama_program_studi = $prodi->nama_program_studi;
        $this->kode_program_studi = $prodi->kode_program_studi;
        $this->fakultas_id = $prodi->fakultas_id;
        $this->status = $prodi->status;
        $this->showModal = true;
    }

    public function save(): void
    {
        $user = auth()->user();
        if ($user && $user->hasRole('operator') && $user->fakultas_id) {
            $this->fakultas_id = $user->fakultas_id;
            if ($this->editId) {
                $prodi = ProgramStudi::findOrFail($this->editId);
                abort_if($prodi->fakultas_id !== $user->fakultas_id, 403, 'Operator hanya dapat mengedit Program Studi di bawah fakultas sendiri.');
            }
        }

        $validated = $this->validate();

        ProgramStudi::updateOrCreate(['id' => $this->editId], $validated);

        $this->showModal = false;
        $this->reset(['editId', 'nama_program_studi', 'kode_program_studi', 'fakultas_id']);
        session()->flash('success', $this->editId ? 'Program Studi berhasil diperbarui.' : 'Program Studi berhasil ditambahkan.');
    }

    public function confirmDelete(int $id): void
    {
        $user = auth()->user();
        if ($user && $user->hasRole('operator') && $user->fakultas_id) {
            $prodi = ProgramStudi::findOrFail($id);
            abort_if($prodi->fakultas_id !== $user->fakultas_id, 403, 'Operator hanya dapat menghapus Program Studi di bawah fakultas sendiri.');
        }

        $this->confirmDeleteId = $id;
    }

    public function delete(): void
    {
        $user = auth()->user();
        $prodi = ProgramStudi::find($this->confirmDeleteId);

        if ($prodi) {
            if ($user && $user->hasRole('operator') && $user->fakultas_id) {
                abort_if($prodi->fakultas_id !== $user->fakultas_id, 403, 'Operator hanya dapat menghapus Program Studi di bawah fakultas sendiri.');
            }

            // Cegah hapus prodi yang masih punya kelas atau mata kuliah anak
            if ($prodi->kelas()->exists() || $prodi->mataKuliah()->exists()) {
                session()->flash('error', 'Program Studi tidak bisa dihapus karena masih memiliki Kelas atau Mata Kuliah.');
            } else {
                $prodi->delete();
                session()->flash('success', 'Program Studi berhasil dihapus.');
            }
        }

        $this->confirmDeleteId = null;
    }

    public function render()
    {
        $user = auth()->user();

        $query = ProgramStudi::query()->with('fakultas');

        if ($user && $user->hasRole('operator') && $user->fakultas_id) {
            $query->where('fakultas_id', $user->fakultas_id);
        }

        $prodi = $query
            ->when($this->search, fn ($q) => $q->where('nama_program_studi', 'like', "%{$this->search}%")
                ->orWhere('kode_program_studi', 'like', "%{$this->search}%"))
            ->orderBy('nama_program_studi')
            ->paginate(10);

        $fakultas = ($user && $user->hasRole('operator') && $user->fakultas_id)
            ? Fakultas::where('id', $user->fakultas_id)->get()
            : Fakultas::orderBy('nama_fakultas')->get();

        return view('livewire.admin.master-data.program-studi-index', [
            'prodi' => $prodi,
            'fakultas' => $fakultas,
        ])->layout('components.layouts.admin', ['title' => 'Master Data — Program Studi']);
    }
}
