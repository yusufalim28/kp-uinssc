<?php

namespace App\Livewire\Admin\MasterData;

use App\Models\Fakultas;
use App\Models\ProgramStudi;
use App\Models\RuangPraktikum;
use Livewire\Component;
use Livewire\WithPagination;

class RuangPraktikumIndex extends Component
{
    use WithPagination;

    public ?int $editId = null;

    public ?int $fakultas_id = null;

    public ?int $program_studi_id = null;

    public string $kode_ruang = '';

    public string $nama_ruang = '';

    public string $lokasi = '';

    public ?int $kapasitas = null;

    public string $tipe_ruang = 'laboratorium';

    public string $status = 'aktif';

    public bool $showModal = false;

    public string $search = '';

    public string $filterTipe = '';

    public ?int $confirmDeleteId = null;

    protected function rules(): array
    {
        return [
            'fakultas_id' => 'nullable|exists:fakultas,id',
            'program_studi_id' => 'nullable|exists:program_studi,id',
            'kode_ruang' => 'required|string|max:20|unique:ruang_praktikum,kode_ruang,'.$this->editId,
            'nama_ruang' => 'required|string|max:100',
            'lokasi' => 'required|string|max:150',
            'kapasitas' => 'required|integer|min:1|max:1000',
            'tipe_ruang' => 'required|in:laboratorium,auditorium,studio,kelas',
            'status' => 'required|in:aktif,nonaktif,perbaikan,tersedia',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterTipe(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $user = auth()->user();
        $this->reset(['editId', 'fakultas_id', 'program_studi_id', 'kode_ruang', 'nama_ruang', 'lokasi', 'kapasitas']);

        if ($user && $user->hasRole('operator')) {
            $this->fakultas_id = $user->fakultas_id;
            $this->program_studi_id = $user->program_studi_id;
        }

        $this->tipe_ruang = 'laboratorium';
        $this->status = 'aktif';

        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $user = auth()->user();
        $ruang = RuangPraktikum::findOrFail($id);

        if ($user && $user->hasRole('operator')) {
            abort_if($user->fakultas_id && $ruang->fakultas_id !== $user->fakultas_id, 403, 'Operator hanya dapat mengelola ruangan di bawah fakultas sendiri.');
        }

        $this->editId = $ruang->id;
        $this->fakultas_id = $ruang->fakultas_id;
        $this->program_studi_id = $ruang->program_studi_id;
        $this->kode_ruang = $ruang->kode_ruang;
        $this->nama_ruang = $ruang->nama_ruang;
        $this->lokasi = $ruang->lokasi;
        $this->kapasitas = $ruang->kapasitas;
        $this->tipe_ruang = $ruang->tipe_ruang ?? 'laboratorium';
        $this->status = $ruang->status;
        $this->showModal = true;
    }

    public function save(): void
    {
        $user = auth()->user();
        if ($user && $user->hasRole('operator')) {
            if ($this->editId) {
                $existing = RuangPraktikum::findOrFail($this->editId);
                abort_if($user->fakultas_id && $existing->fakultas_id !== $user->fakultas_id, 403, 'Operator hanya dapat mengelola ruangan di bawah fakultas sendiri.');
            }
            if ($user->fakultas_id) {
                $this->fakultas_id = $user->fakultas_id;
            }
        }

        $validated = $this->validate();

        RuangPraktikum::updateOrCreate(['id' => $this->editId], $validated);

        $this->showModal = false;
        $this->reset(['editId', 'fakultas_id', 'program_studi_id', 'kode_ruang', 'nama_ruang', 'lokasi', 'kapasitas']);
        session()->flash('success', $this->editId ? 'Ruang berhasil diperbarui.' : 'Ruang berhasil ditambahkan.');
    }

    public function confirmDelete(int $id): void
    {
        $user = auth()->user();
        if ($user && $user->hasRole('operator')) {
            $ruang = RuangPraktikum::findOrFail($id);
            abort_if($user->fakultas_id && $ruang->fakultas_id !== $user->fakultas_id, 403, 'Operator hanya dapat menghapus ruangan di bawah fakultas sendiri.');
        }

        $this->confirmDeleteId = $id;
    }

    public function delete(): void
    {
        $user = auth()->user();
        $ruang = RuangPraktikum::find($this->confirmDeleteId);

        if ($ruang) {
            if ($user && $user->hasRole('operator')) {
                abort_if($user->fakultas_id && $ruang->fakultas_id !== $user->fakultas_id, 403, 'Operator hanya dapat menghapus ruangan di bawah fakultas sendiri.');
            }

            if ($ruang->jadwalPraktikum()->exists()) {
                session()->flash('error', 'Ruang tidak bisa dihapus karena masih dipakai di Jadwal Praktikum.');
            } else {
                $ruang->delete();
                session()->flash('success', 'Ruang berhasil dihapus.');
            }
        }

        $this->confirmDeleteId = null;
    }

    public function render()
    {
        $user = auth()->user();

        $query = RuangPraktikum::query()->with(['fakultas', 'programStudi']);

        // Scoping akses operator
        if ($user && $user->hasRole('operator')) {
            $query->where(function ($q) use ($user) {
                if ($user->fakultas_id) {
                    $q->where('fakultas_id', $user->fakultas_id);
                }
                if ($user->program_studi_id) {
                    $q->orWhere('program_studi_id', $user->program_studi_id);
                }
                $q->orWhereNull('fakultas_id');
            });
        }

        if ($this->filterTipe) {
            $query->where('tipe_ruang', $this->filterTipe);
        }

        $ruang = $query
            ->when($this->search, fn ($q) => $q->where(function ($inner) {
                $inner->where('nama_ruang', 'like', "%{$this->search}%")
                    ->orWhere('kode_ruang', 'like', "%{$this->search}%")
                    ->orWhere('lokasi', 'like', "%{$this->search}%");
            }))
            ->orderByRaw("CASE WHEN tipe_ruang = 'auditorium' THEN 1 ELSE 2 END")
            ->orderBy('nama_ruang')
            ->paginate(10);

        $fakultasList = ($user && $user->hasRole('operator') && $user->fakultas_id)
            ? Fakultas::where('id', $user->fakultas_id)->get()
            : Fakultas::orderBy('nama_fakultas')->get();

        $prodiList = ($user && $user->hasRole('operator') && $user->fakultas_id)
            ? ProgramStudi::where('fakultas_id', $user->fakultas_id)->orderBy('nama_program_studi')->get()
            : ProgramStudi::orderBy('nama_program_studi')->get();

        return view('livewire.admin.master-data.ruang-praktikum-index', [
            'ruang' => $ruang,
            'fakultasList' => $fakultasList,
            'prodiList' => $prodiList,
        ])->layout('components.layouts.admin', ['title' => 'Master Data — Ruang & Auditorium']);
    }
}
