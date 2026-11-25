<?php

namespace App\Livewire\Admin;

use App\Models\Fakultas;
use App\Models\PengajuanPerubahan;
use App\Notifications\PengajuanStatusDiperbarui;
use Livewire\Component;
use Livewire\WithPagination;

class PengajuanIndex extends Component
{
    use WithPagination;

    public string $viewMode = 'grid';

    public string $filterStatus = 'menunggu';

    public ?int $filterFakultas = null;

    public string $search = '';

    public ?int $processId = null;

    public string $processAction = ''; // 'approve' | 'reject'

    public string $catatan_penolakan = '';

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function updatingFilterFakultas(): void
    {
        $this->resetPage();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openApprove(int $id): void
    {
        $this->processId = $id;
        $this->processAction = 'approve';
        $this->catatan_penolakan = '';
    }

    public function openReject(int $id): void
    {
        $this->processId = $id;
        $this->processAction = 'reject';
        $this->catatan_penolakan = '';
    }

    public function confirmProcess(): void
    {
        $pengajuan = PengajuanPerubahan::with(['jadwalPraktikum.ruangPraktikum', 'jadwalPraktikum.mataKuliah.programStudi', 'jadwalPraktikum.kelas.programStudi'])->findOrFail($this->processId);
        $user = auth()->user();

        // Poin 5: Validasi hak akses operator hanya untuk fakultas miliknya
        if ($user && $user->hasRole('operator') && $user->fakultas_id) {
            $jadwal = $pengajuan->jadwalPraktikum;
            $isAllowed = $jadwal && (
                ($jadwal->ruangPraktikum && $jadwal->ruangPraktikum->fakultas_id === $user->fakultas_id) ||
                ($jadwal->mataKuliah?->programStudi && $jadwal->mataKuliah->programStudi->fakultas_id === $user->fakultas_id) ||
                ($jadwal->kelas?->programStudi && $jadwal->kelas->programStudi->fakultas_id === $user->fakultas_id)
            );

            abort_if(! $isAllowed, 403, 'Operator hanya dapat memproses pengajuan jadwal pada fakultas sendiri.');
        }

        // Poin 3: Validasi hak akses Ketua Lab untuk laboratorium yang dinaungi
        if ($user && $user->hasRole('ketua-lab')) {
            $jadwal = $pengajuan->jadwalPraktikum;
            $isAllowed = $jadwal && (
                ($user->ruang_praktikum_id && $jadwal->ruang_praktikum_id === $user->ruang_praktikum_id) ||
                (! $user->ruang_praktikum_id && $user->fakultas_id && $jadwal->ruangPraktikum && $jadwal->ruangPraktikum->fakultas_id === $user->fakultas_id)
            );

            abort_if(! $isAllowed, 403, 'Ketua Lab hanya dapat memproses pengajuan jadwal pada laboratorium yang dinaungi.');
        }

        if ($this->processAction === 'reject') {
            $this->validate([
                'catatan_penolakan' => 'required|string|max:500',
            ]);

            $pengajuan->update([
                'status' => 'ditolak',
                'catatan_penolakan' => $this->catatan_penolakan,
            ]);

            $pengajuan->dosen->user?->notify(new PengajuanStatusDiperbarui($pengajuan));

            session()->flash('success', 'Pengajuan berhasil ditolak.');
        } else {
            $pengajuan->update([
                'status' => 'disetujui',
                'catatan_penolakan' => null,
            ]);

            $pengajuan->dosen->user?->notify(new PengajuanStatusDiperbarui($pengajuan));

            session()->flash('success', 'Pengajuan disetujui. Jangan lupa terapkan perubahannya di menu Kelola Jadwal.');
        }

        $this->reset(['processId', 'processAction', 'catatan_penolakan']);
    }

    public function render()
    {
        $user = auth()->user();

        $query = PengajuanPerubahan::query()
            ->with(['dosen', 'jadwalPraktikum.mataKuliah.programStudi', 'jadwalPraktikum.ruangPraktikum', 'jadwalPraktikum.kelas.programStudi']);

        // Poin 5: Scoping ketat untuk Operator Fakultas
        if ($user && $user->hasRole('operator') && $user->fakultas_id) {
            $fakultasId = $user->fakultas_id;
            $query->whereHas('jadwalPraktikum', function ($jq) use ($fakultasId) {
                $jq->where(function ($sub) use ($fakultasId) {
                    $sub->whereHas('ruangPraktikum', fn ($r) => $r->where('fakultas_id', $fakultasId))
                        ->orWhereHas('mataKuliah.programStudi', fn ($p) => $p->where('fakultas_id', $fakultasId))
                        ->orWhereHas('kelas.programStudi', fn ($k) => $k->where('fakultas_id', $fakultasId));
                });
            });
        }

        // Poin 3: Scoping untuk Ketua Lab (hanya jadwal lab dinaungi)
        if ($user && $user->hasRole('ketua-lab')) {
            if ($user->ruang_praktikum_id) {
                $query->whereHas('jadwalPraktikum', fn ($jq) => $jq->where('ruang_praktikum_id', $user->ruang_praktikum_id));
            } elseif ($user->fakultas_id) {
                $query->whereHas('jadwalPraktikum.ruangPraktikum', fn ($r) => $r->where('fakultas_id', $user->fakultas_id));
            }
        }

        // Poin 6: Filter Fakultas untuk Super Admin
        if ($user && $user->hasSuperAdminView() && ! empty($this->filterFakultas)) {
            $fakultasId = (int) $this->filterFakultas;
            $query->whereHas('jadwalPraktikum', function ($jq) use ($fakultasId) {
                $jq->where(function ($sub) use ($fakultasId) {
                    $sub->whereHas('ruangPraktikum', fn ($r) => $r->where('fakultas_id', $fakultasId))
                        ->orWhereHas('mataKuliah.programStudi', fn ($p) => $p->where('fakultas_id', $fakultasId))
                        ->orWhereHas('kelas.programStudi', fn ($k) => $k->where('fakultas_id', $fakultasId));
                });
            });
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('dosen', fn ($d) => $d->where('nama', 'like', "%{$this->search}%")->orWhere('nidn', 'like', "%{$this->search}%"))
                    ->orWhereHas('jadwalPraktikum.mataKuliah', fn ($mk) => $mk->where('nama_mk', 'like', "%{$this->search}%"))
                    ->orWhere('perubahan', 'like', "%{$this->search}%")
                    ->orWhere('alasan', 'like', "%{$this->search}%");
            });
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        $pengajuan = $query->latest()->paginate(12);
        $fakultasList = Fakultas::orderBy('nama_fakultas')->get();

        return view('livewire.admin.pengajuan-index', [
            'pengajuan' => $pengajuan,
            'fakultasList' => $fakultasList,
            'isSuperAdmin' => $user && $user->hasSuperAdminView(),
            'isKetuaLab' => $user && $user->hasRole('ketua-lab'),
            'userFakultas' => $user?->fakultas,
            'userRuang' => $user?->ruangPraktikum,
        ])->layout('components.layouts.admin', ['title' => 'Pengajuan Perubahan Jadwal']);
    }
}
