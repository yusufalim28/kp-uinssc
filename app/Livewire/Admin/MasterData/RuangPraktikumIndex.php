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

    public string $cakupan_akses = 'bersama_fakultas';

    public array $selected_prodi_ids = [];

    public string $status = 'aktif';

    public bool $showModal = false;

    public string $search = '';

    public string $filterTipe = '';

    public string $filterCakupan = '';

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
            'cakupan_akses' => 'required|in:bersama_fakultas,eksklusif,khusus_multi_prodi,umum_universitas',
            'selected_prodi_ids' => 'array',
            'selected_prodi_ids.*' => 'exists:program_studi,id',
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

    public function updatingFilterCakupan(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $user = auth()->user();
        $this->reset(['editId', 'fakultas_id', 'program_studi_id', 'kode_ruang', 'nama_ruang', 'lokasi', 'kapasitas', 'selected_prodi_ids']);

        if ($user && $user->hasRole('operator')) {
            $this->fakultas_id = $user->fakultas_id;
            $this->program_studi_id = $user->program_studi_id;
        }

        $this->tipe_ruang = 'laboratorium';
        $this->cakupan_akses = 'bersama_fakultas';
        $this->status = 'aktif';

        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $user = auth()->user();
        $ruang = RuangPraktikum::with('prodiPengguna')->findOrFail($id);

        if ($user && $user->hasRole('operator')) {
            $hasAccess = ($user->fakultas_id && $ruang->fakultas_id === $user->fakultas_id)
                || ($user->program_studi_id && $ruang->program_studi_id === $user->program_studi_id)
                || $ruang->prodiPengguna->contains(fn ($p) => $p->fakultas_id === $user->fakultas_id);

            abort_if(! $hasAccess, 403, 'Operator hanya dapat mengelola ruangan di bawah wewenang fakultas sendiri.');
        }

        $this->editId = $ruang->id;
        $this->fakultas_id = $ruang->fakultas_id;
        $this->program_studi_id = $ruang->program_studi_id;
        $this->kode_ruang = $ruang->kode_ruang;
        $this->nama_ruang = $ruang->nama_ruang;
        $this->lokasi = $ruang->lokasi;
        $this->kapasitas = $ruang->kapasitas;
        $this->tipe_ruang = $ruang->tipe_ruang ?? 'laboratorium';
        $this->cakupan_akses = $ruang->cakupan_akses ?? 'bersama_fakultas';
        $this->selected_prodi_ids = $ruang->prodiPengguna->pluck('id')->toArray();
        $this->status = $ruang->status;
        $this->showModal = true;
    }

    public function save(): void
    {
        $user = auth()->user();
        if ($user && $user->hasRole('operator')) {
            if ($this->editId) {
                $existing = RuangPraktikum::with('prodiPengguna')->findOrFail($this->editId);
                $hasAccess = ($user->fakultas_id && $existing->fakultas_id === $user->fakultas_id)
                    || ($user->program_studi_id && $existing->program_studi_id === $user->program_studi_id)
                    || $existing->prodiPengguna->contains(fn ($p) => $p->fakultas_id === $user->fakultas_id);

                abort_if(! $hasAccess, 403, 'Operator hanya dapat mengelola ruangan di bawah wewenang fakultas sendiri.');
            }
            if ($user->fakultas_id && ! $this->fakultas_id) {
                $this->fakultas_id = $user->fakultas_id;
            }
        }

        if ($this->cakupan_akses === 'khusus_multi_prodi' && empty($this->selected_prodi_ids)) {
            $this->addError('selected_prodi_ids', 'Pilih minimal satu program studi pemakai untuk ruangan bertipe khusus multi-prodi.');

            return;
        }

        if ($this->cakupan_akses === 'eksklusif' && empty($this->program_studi_id)) {
            $this->addError('program_studi_id', 'Program studi wajib dipilih jika hak pakai ruangan bersifat eksklusif prodi.');

            return;
        }

        $validated = $this->validate();
        unset($validated['selected_prodi_ids']);

        $ruang = RuangPraktikum::updateOrCreate(['id' => $this->editId], $validated);

        if ($this->cakupan_akses === 'khusus_multi_prodi') {
            $ruang->prodiPengguna()->sync($this->selected_prodi_ids);
        } else {
            $ruang->prodiPengguna()->detach();
        }

        $this->showModal = false;
        $this->reset(['editId', 'fakultas_id', 'program_studi_id', 'kode_ruang', 'nama_ruang', 'lokasi', 'kapasitas', 'selected_prodi_ids']);
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

        $query = RuangPraktikum::query()->with(['fakultas', 'programStudi', 'prodiPengguna.fakultas']);

        // Scoping akses operator
        if ($user && $user->hasRole('operator')) {
            $query->where(function ($q) use ($user) {
                if ($user->fakultas_id) {
                    $q->where('fakultas_id', $user->fakultas_id)
                        ->orWhereHas('prodiPengguna', fn ($p) => $p->where('fakultas_id', $user->fakultas_id));
                }
                if ($user->program_studi_id) {
                    $q->orWhere('program_studi_id', $user->program_studi_id)
                        ->orWhereHas('prodiPengguna', fn ($p) => $p->where('program_studi.id', $user->program_studi_id));
                }
                $q->orWhere('cakupan_akses', 'umum_universitas')
                    ->orWhereNull('fakultas_id');
            });
        }

        if ($this->filterTipe) {
            $query->where('tipe_ruang', $this->filterTipe);
        }

        if ($this->filterCakupan) {
            $query->where('cakupan_akses', $this->filterCakupan);
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

        $allProdiGrouped = ProgramStudi::with('fakultas')
            ->where('status', 'aktif')
            ->orderBy('nama_program_studi')
            ->get()
            ->groupBy(fn ($p) => $p->fakultas?->nama_fakultas ?? 'Fakultas / Unit Lain');

        return view('livewire.admin.master-data.ruang-praktikum-index', [
            'ruang' => $ruang,
            'fakultasList' => $fakultasList,
            'prodiList' => $prodiList,
            'allProdiGrouped' => $allProdiGrouped,
        ])->layout('components.layouts.admin', ['title' => 'Master Data — Ruang & Auditorium']);
    }
}
