<?php

namespace App\Livewire\Admin\MasterData;

use App\Models\Dosen;
use App\Models\Fakultas;
use Livewire\Component;
use Livewire\WithPagination;

class DosenIndex extends Component
{
    use WithPagination;

    public ?int $editId = null;

    public string $nidn = '';

    public string $kode_dosen = '';

    public string $nama = '';

    public string $email = '';

    public string $status = 'aktif';

    public bool $showModal = false;

    public string $search = '';

    public ?int $confirmDeleteId = null;

    // Filter Fakultas khusus Super Admin
    public string $filterFakultas = '';

    // Scope untuk Operator: 'fakultas' = Dosen di fakultasnya, 'semua' = Seluruh dosen universitas
    public string $viewScope = 'fakultas';

    // Mode tampilan: 'grid' (card) atau 'table'
    public string $viewMode = 'grid';

    public function updatedKodeDosen(string $value): void
    {
        $this->nidn = $value;
    }

    public function updatedNidn(string $value): void
    {
        $this->kode_dosen = $value;
    }

    protected function rules(): array
    {
        return [
            'nidn' => 'required|string|max:30|unique:dosen,nidn,'.$this->editId,
            'kode_dosen' => 'nullable|string|max:30',
            'nama' => 'required|string|max:150',
            'email' => 'nullable|email|max:150|unique:dosen,email,'.$this->editId,
            'status' => 'required|in:aktif,nonaktif,cuti,keluar',
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

    public function updatingViewScope(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->reset(['editId', 'nidn', 'kode_dosen', 'nama', 'email']);
        $this->status = 'aktif';
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $dosen = Dosen::findOrFail($id);

        $this->editId = $dosen->id;
        $this->nidn = $dosen->nidn;
        $this->kode_dosen = $dosen->nidn;
        $this->nama = $dosen->nama;
        $this->email = $dosen->email;
        $this->status = $dosen->status;
        $this->showModal = true;
    }

    public function save(): void
    {
        if (empty($this->nidn) && ! empty($this->kode_dosen)) {
            $this->nidn = $this->kode_dosen;
        }
        if (empty($this->kode_dosen) && ! empty($this->nidn)) {
            $this->kode_dosen = $this->nidn;
        }

        $validated = $this->validate();
        $validated['nidn'] = $this->nidn;
        unset($validated['kode_dosen']);

        // Jika email tidak diisi oleh operator, buat alias institusi berbasis NIDN untuk kesesuaian API Bridge Pustikom
        if (empty($validated['email'])) {
            $validated['email'] = "{$this->nidn}@uinssc.ac.id";
        }

        Dosen::updateOrCreate(['id' => $this->editId], $validated);

        $this->showModal = false;
        $this->reset(['editId', 'nidn', 'kode_dosen', 'nama', 'email']);
        session()->flash('success', $this->editId ? 'Dosen berhasil diperbarui.' : 'Dosen berhasil ditambahkan.');
    }

    public function confirmDelete(int $id): void
    {
        $this->confirmDeleteId = $id;
    }

    public function delete(): void
    {
        $dosen = Dosen::find($this->confirmDeleteId);

        if ($dosen) {
            if ($dosen->jadwalPraktikum()->exists()) {
                session()->flash('error', 'Dosen tidak bisa dihapus karena masih memiliki Jadwal Praktikum.');
            } else {
                $dosen->delete();
                session()->flash('success', 'Dosen berhasil dihapus.');
            }
        }

        $this->confirmDeleteId = null;
    }

    public function render()
    {
        $user = auth()->user();
        $query = Dosen::query()
            ->with([
                'user.fakultas',
                'jadwalPraktikum' => function ($jq) {
                    $jq->with(['mataKuliah.programStudi.fakultas', 'ruangPraktikum.fakultas']);
                },
            ]);

        // Filter role Operator: hanya dosen yang mengajar / berjadwal di fakultas operator
        if ($user && $user->hasRole('operator') && $user->fakultas_id && $this->viewScope === 'fakultas') {
            $fakultasId = $user->fakultas_id;
            $query->where(function ($q) use ($fakultasId) {
                $q->whereHas('jadwalPraktikum', function ($jq) use ($fakultasId) {
                    $jq->whereHas('mataKuliah.programStudi', fn ($ps) => $ps->where('fakultas_id', $fakultasId))
                        ->orWhereHas('ruangPraktikum', fn ($rq) => $rq->where('fakultas_id', $fakultasId));
                })
                    ->orWhereHas('user', fn ($uq) => $uq->where('fakultas_id', $fakultasId));
            });
        }

        // Filter tab Fakultas untuk Super Admin
        if ($user && $user->hasRole('super-admin') && ! empty($this->filterFakultas)) {
            $fakultasId = (int) $this->filterFakultas;
            $query->where(function ($q) use ($fakultasId) {
                $q->whereHas('jadwalPraktikum', function ($jq) use ($fakultasId) {
                    $jq->whereHas('mataKuliah.programStudi', fn ($ps) => $ps->where('fakultas_id', $fakultasId))
                        ->orWhereHas('ruangPraktikum', fn ($rq) => $rq->where('fakultas_id', $fakultasId));
                })
                    ->orWhereHas('user', fn ($uq) => $uq->where('fakultas_id', $fakultasId));
            });
        }

        // Fitur pencarian NIDN, Nama, atau Email
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nama', 'like', "%{$this->search}%")
                    ->orWhere('nidn', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            });
        }

        $dosen = $query->orderBy('nama')->paginate(12);
        $fakultasList = Fakultas::orderBy('nama_fakultas')->get();

        return view('livewire.admin.master-data.dosen-index', [
            'dosen' => $dosen,
            'fakultasList' => $fakultasList,
            'userFakultas' => $user?->fakultas,
            'isOperator' => $user && $user->hasRole('operator'),
            'isSuperAdmin' => $user && $user->hasRole('super-admin'),
        ])->layout('components.layouts.admin', ['title' => 'Master Data — Dosen']);
    }
}
