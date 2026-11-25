<?php

namespace App\Livewire\Admin\MasterData;

use App\Models\Fakultas;
use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class MataKuliahIndex extends Component
{
    use WithFileUploads, WithPagination;

    public ?int $editId = null;

    public string $kode_mk = '';

    public string $nama_mk = '';

    public string $cakupan_mk = 'prodi'; // 'prodi', 'bersama_fakultas', 'umum_universitas'

    public ?int $fakultas_id = null;

    public ?int $program_studi_id = null;

    public ?int $filterFakultas = null;

    public string $filterCakupan = '';

    public ?int $sks = null;

    public ?int $semester = null;

    public string $status = 'aktif';

    // Poin 4: Modul Ajar Praktikum
    public $upload_modul;

    public string $file_modul = '';

    public string $tautan_modul = '';

    public string $deskripsi_modul = '';

    public bool $showModal = false;

    public string $search = '';

    public string $viewMode = 'grid';

    public ?int $confirmDeleteId = null;

    protected function rules(): array
    {
        return [
            'kode_mk' => 'required|string|max:20|unique:mata_kuliah,kode_mk,'.$this->editId,
            'nama_mk' => 'required|string|max:150',
            'cakupan_mk' => 'required|in:prodi,bersama_fakultas,umum_universitas',
            'fakultas_id' => 'required_if:cakupan_mk,prodi,bersama_fakultas|nullable|exists:fakultas,id',
            'program_studi_id' => 'required_if:cakupan_mk,prodi|nullable|exists:program_studi,id',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:8',
            'status' => 'required|in:aktif,nonaktif',
            'upload_modul' => 'nullable|file|mimes:pdf,doc,docx,zip|max:20480',
            'tautan_modul' => 'nullable|url|max:500',
            'deskripsi_modul' => 'nullable|string|max:1000',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterFakultas(): void
    {
        $this->resetPage();
    }

    public function updatingFilterCakupan(): void
    {
        $this->resetPage();
    }

    public function updatedProgramStudiId($value): void
    {
        if ($value && $this->cakupan_mk === 'prodi') {
            $prodi = ProgramStudi::find($value);
            if ($prodi) {
                $this->fakultas_id = $prodi->fakultas_id;
            }
        }
    }

    public function updatedCakupanMk($value): void
    {
        if ($value === 'bersama_fakultas') {
            $this->program_studi_id = null;
        } elseif ($value === 'umum_universitas') {
            $this->program_studi_id = null;
            $this->fakultas_id = null;
        }
    }

    public function updatedFakultasId($value): void
    {
        if ($this->cakupan_mk === 'prodi' && $this->program_studi_id) {
            $prodi = ProgramStudi::find($this->program_studi_id);
            if ($prodi && $prodi->fakultas_id != $value) {
                $this->program_studi_id = null;
            }
        }
    }

    public function openCreate(): void
    {
        $user = auth()->user();
        $this->reset(['editId', 'kode_mk', 'nama_mk', 'program_studi_id', 'sks', 'semester', 'upload_modul', 'file_modul', 'tautan_modul', 'deskripsi_modul']);
        $this->cakupan_mk = 'prodi';
        $this->fakultas_id = ($user && $user->fakultas_id) ? $user->fakultas_id : null;
        $this->status = 'aktif';
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $user = auth()->user();
        $mk = MataKuliah::with(['programStudi', 'fakultas'])->findOrFail($id);
        $effectiveFakultasId = $mk->fakultas_id ?? $mk->programStudi?->fakultas_id;

        if ($user && $user->hasRole(['operator', 'ketua-lab']) && $user->fakultas_id) {
            abort_if($effectiveFakultasId !== $user->fakultas_id, 403, 'Operator hanya dapat mengedit Mata Kuliah di bawah fakultas sendiri.');
        }

        $this->editId = $mk->id;
        $this->kode_mk = $mk->kode_mk;
        $this->nama_mk = $mk->nama_mk;
        $this->cakupan_mk = $mk->cakupan_mk ?? 'prodi';
        $this->fakultas_id = $effectiveFakultasId;
        $this->program_studi_id = $mk->program_studi_id;
        $this->sks = $mk->sks;
        $this->semester = $mk->semester;
        $this->status = $mk->status;
        $this->file_modul = $mk->file_modul ?? '';
        $this->tautan_modul = $mk->tautan_modul ?? '';
        $this->deskripsi_modul = $mk->deskripsi_modul ?? '';
        $this->upload_modul = null;
        $this->showModal = true;
    }

    public function hapusFileModul(): void
    {
        if ($this->editId && $this->file_modul) {
            Storage::disk('public')->delete($this->file_modul);
            MataKuliah::where('id', $this->editId)->update(['file_modul' => null]);
        }
        $this->file_modul = '';
        $this->upload_modul = null;
    }

    public function save(): void
    {
        $user = auth()->user();

        // Operator fakultas otomatis mengunci fakultas_id ke miliknya
        if ($user && $user->hasRole(['operator', 'ketua-lab']) && $user->fakultas_id) {
            $this->fakultas_id = $user->fakultas_id;
        }

        // Sinkronisasi cakupan & prodi/fakultas
        if ($this->cakupan_mk === 'prodi' && $this->program_studi_id) {
            $prodi = ProgramStudi::find($this->program_studi_id);
            if ($prodi) {
                $this->fakultas_id = $prodi->fakultas_id;
            }
        } elseif ($this->cakupan_mk === 'bersama_fakultas') {
            $this->program_studi_id = null;
        } elseif ($this->cakupan_mk === 'umum_universitas') {
            $this->program_studi_id = null;
            $this->fakultas_id = null;
        }

        $validated = $this->validate();

        if ($user && $user->hasRole(['operator', 'ketua-lab']) && $user->fakultas_id) {
            abort_if($validated['fakultas_id'] !== $user->fakultas_id, 403, 'Operator hanya dapat mengelola Mata Kuliah di bawah fakultas sendiri.');

            if ($this->editId) {
                $mk = MataKuliah::with('programStudi')->findOrFail($this->editId);
                $oldFakultasId = $mk->fakultas_id ?? $mk->programStudi?->fakultas_id;
                abort_if($oldFakultasId !== $user->fakultas_id, 403, 'Operator hanya dapat mengedit Mata Kuliah di bawah fakultas sendiri.');
            }
        }

        if ($this->upload_modul) {
            if ($this->file_modul) {
                Storage::disk('public')->delete($this->file_modul);
            }
            $path = $this->upload_modul->store('modul-praktikum', 'public');
            $validated['file_modul'] = $path;
        } else {
            $validated['file_modul'] = $this->file_modul ?: null;
        }

        $validated['tautan_modul'] = $this->tautan_modul ?: null;
        $validated['deskripsi_modul'] = $this->deskripsi_modul ?: null;
        unset($validated['upload_modul']);

        MataKuliah::updateOrCreate(['id' => $this->editId], $validated);

        $this->showModal = false;
        $this->reset(['editId', 'kode_mk', 'nama_mk', 'cakupan_mk', 'fakultas_id', 'program_studi_id', 'sks', 'semester', 'upload_modul', 'file_modul', 'tautan_modul', 'deskripsi_modul']);
        session()->flash('success', $this->editId ? 'Mata Kuliah & Modul Ajar berhasil diperbarui.' : 'Mata Kuliah & Modul Ajar berhasil ditambahkan.');
    }

    public function confirmDelete(int $id): void
    {
        $user = auth()->user();
        $mk = MataKuliah::with('programStudi')->findOrFail($id);
        $fakId = $mk->fakultas_id ?? $mk->programStudi?->fakultas_id;

        if ($user && $user->hasRole(['operator', 'ketua-lab']) && $user->fakultas_id) {
            abort_if($fakId !== $user->fakultas_id, 403, 'Operator hanya dapat menghapus Mata Kuliah di bawah fakultas sendiri.');
        }

        $this->confirmDeleteId = $id;
    }

    public function delete(): void
    {
        $user = auth()->user();
        $mk = MataKuliah::with('programStudi')->find($this->confirmDeleteId);

        if ($mk) {
            $fakId = $mk->fakultas_id ?? $mk->programStudi?->fakultas_id;
            if ($user && $user->hasRole(['operator', 'ketua-lab']) && $user->fakultas_id) {
                abort_if($fakId !== $user->fakultas_id, 403, 'Operator hanya dapat menghapus Mata Kuliah di bawah fakultas sendiri.');
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

        $query = MataKuliah::query()
            ->with(['programStudi.fakultas', 'fakultas'])
            ->withCount('jadwalPraktikum');

        if ($user && $user->hasRole(['operator', 'ketua-lab']) && $user->fakultas_id) {
            $query->where(function ($q) use ($user) {
                $q->where('fakultas_id', $user->fakultas_id)
                    ->orWhereHas('programStudi', fn ($q2) => $q2->where('fakultas_id', $user->fakultas_id));
            });
        }

        // Poin 6: Filter Fakultas untuk Super Admin
        if ($user && $user->hasSuperAdminView() && ! empty($this->filterFakultas)) {
            $fakId = (int) $this->filterFakultas;
            $query->where(function ($q) use ($fakId) {
                $q->where('fakultas_id', $fakId)
                    ->orWhereHas('programStudi', fn ($q2) => $q2->where('fakultas_id', $fakId));
            });
        }

        // Filter Cakupan Mata Kuliah
        if (! empty($this->filterCakupan)) {
            $query->where('cakupan_mk', $this->filterCakupan);
        }

        $mataKuliah = $query
            ->when($this->search, fn ($q) => $q->where(function ($sq) {
                $sq->where('nama_mk', 'like', "%{$this->search}%")
                    ->orWhere('kode_mk', 'like', "%{$this->search}%");
            }))
            ->orderBy('nama_mk')
            ->paginate(12);

        // Opsi Program Studi untuk Modal Form
        $activeFakultasId = $this->fakultas_id ?: ($user?->fakultas_id ?: null);
        $programStudiQuery = ProgramStudi::orderBy('nama_program_studi');
        if ($activeFakultasId) {
            $programStudiQuery->where('fakultas_id', $activeFakultasId);
        }
        $programStudi = $programStudiQuery->get();

        return view('livewire.admin.master-data.mata-kuliah-index', [
            'mataKuliah' => $mataKuliah,
            'programStudi' => $programStudi,
            'fakultasList' => Fakultas::orderBy('nama_fakultas')->get(),
            'isSuperAdmin' => $user && $user->hasSuperAdminView(),
            'userFakultas' => $user?->fakultas,
        ])->layout('components.layouts.admin', ['title' => 'Master Data — Mata Kuliah']);
    }
}
