<?php

namespace App\Livewire\Admin\MasterData;

use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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

    public string $kategori = 'dosen'; // 'dosen', 'tutor', 'asisten'

    public string $password = ''; // Password akun tutor

    public string $filterKategori = '';

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

    protected function rules(): array
    {
        $rules = [
            'nama' => 'required|string|max:150',
            'kategori' => 'required|in:dosen,tutor,asisten',
            'status' => 'required|in:aktif,nonaktif,cuti,keluar',
        ];

        if ($this->kategori === 'dosen') {
            $rules['nidn'] = 'required|string|max:30|unique:dosen,nidn,'.$this->editId;
            $rules['kode_dosen'] = 'nullable|string|max:30';
            $rules['email'] = 'nullable|email|max:150|unique:dosen,email,'.$this->editId;
        } else {
            // Tutor atau Asisten Lab: NIDN opsional (bisa NIM), Email (Gmail) wajib
            $rules['nidn'] = 'nullable|string|max:30|unique:dosen,nidn,'.$this->editId;
            $rules['kode_dosen'] = 'nullable|string|max:30';
            $rules['email'] = 'required|email|max:150|unique:dosen,email,'.$this->editId;
            $rules['password'] = 'nullable|string|min:6';
        }

        return $rules;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterFakultas(): void
    {
        $this->resetPage();
    }

    public function updatingFilterKategori(): void
    {
        $this->resetPage();
    }

    public function updatingViewScope(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->reset(['editId', 'nidn', 'kode_dosen', 'nama', 'email', 'password']);
        $this->kategori = 'dosen';
        $this->status = 'aktif';
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $dosen = Dosen::findOrFail($id);

        $this->editId = $dosen->id;
        $this->nidn = $dosen->nidn ?? '';
        $this->kode_dosen = $dosen->nidn ?? '';
        $this->nama = $dosen->nama;
        $this->email = $dosen->email ?? '';
        $this->kategori = $dosen->kategori ?? 'dosen';
        $this->password = '';
        $this->status = $dosen->status;
        $this->showModal = true;
    }

    public function save(): void
    {
        if ($this->kategori === 'dosen') {
            if (empty($this->nidn) && ! empty($this->kode_dosen)) {
                $this->nidn = $this->kode_dosen;
            }
            if (empty($this->kode_dosen) && ! empty($this->nidn)) {
                $this->kode_dosen = $this->nidn;
            }
        } else {
            // Tutor / Asisten Lab
            if (empty($this->nidn) && ! empty($this->kode_dosen)) {
                $this->nidn = $this->kode_dosen;
            }
            if (empty($this->nidn)) {
                $this->nidn = 'TUTOR-'.strtoupper(Str::random(6));
            }
        }

        $validated = $this->validate();
        $validated['nidn'] = $this->nidn;
        $validated['kategori'] = $this->kategori;
        unset($validated['kode_dosen'], $validated['password']);

        // Jika email dosen tidak diisi oleh operator, buat alias institusi berbasis NIDN
        if ($this->kategori === 'dosen' && empty($validated['email'])) {
            $validated['email'] = "{$this->nidn}@uinssc.ac.id";
        }

        // Buat atau hubungkan akun User jika Tutor/Asisten memiliki email
        if (! empty($validated['email'])) {
            $userPassword = ! empty($this->password) ? Hash::make($this->password) : Hash::make('password');
            $user = User::firstOrCreate(
                ['email' => $validated['email']],
                [
                    'name' => $validated['nama'],
                    'password' => $userPassword,
                    'email_verified_at' => now(),
                ]
            );

            // Update nama jika berubah
            if ($user->name !== $validated['nama']) {
                $user->update(['name' => $validated['nama']]);
            }

            // Update password jika diisi saat edit
            if (! empty($this->password)) {
                $user->update(['password' => Hash::make($this->password)]);
            }

            // Pastikan memiliki role dosen agar bisa melihat Jadwal Saya & Pengajuan
            if (! $user->hasRole('dosen')) {
                $user->assignRole('dosen');
            }

            $validated['user_id'] = $user->id;
        }

        Dosen::updateOrCreate(['id' => $this->editId], $validated);

        $this->showModal = false;
        $label = match ($this->kategori) {
            'tutor' => 'Tutor Lab',
            'asisten' => 'Asisten Lab',
            default => 'Dosen',
        };
        $this->reset(['editId', 'nidn', 'kode_dosen', 'nama', 'email', 'password']);
        $this->kategori = 'dosen';
        session()->flash('success', $this->editId ? "{$label} berhasil diperbarui." : "{$label} berhasil ditambahkan.");
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

        // Filter role Operator / Ketua Lab: hanya dosen yang mengajar / berjadwal di fakultasnya
        if ($user && $user->hasRole(['operator', 'ketua-lab']) && $user->fakultas_id && $this->viewScope === 'fakultas') {
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
        if ($user && $user->hasSuperAdminView() && ! empty($this->filterFakultas)) {
            $fakultasId = (int) $this->filterFakultas;
            $query->where(function ($q) use ($fakultasId) {
                $q->whereHas('jadwalPraktikum', function ($jq) use ($fakultasId) {
                    $jq->whereHas('mataKuliah.programStudi', fn ($ps) => $ps->where('fakultas_id', $fakultasId))
                        ->orWhereHas('ruangPraktikum', fn ($rq) => $rq->where('fakultas_id', $fakultasId));
                })
                    ->orWhereHas('user', fn ($uq) => $uq->where('fakultas_id', $fakultasId));
            });
        }

        // Filter Kategori (dosen, tutor, asisten)
        if (! empty($this->filterKategori)) {
            $query->where('kategori', $this->filterKategori);
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
            'isOperator' => $user && $user->hasRole(['operator', 'ketua-lab']),
            'isSuperAdmin' => $user && $user->hasSuperAdminView(),
        ])->layout('components.layouts.admin', ['title' => 'Master Data — Dosen']);
    }
}
