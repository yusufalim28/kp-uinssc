<?php

namespace App\Livewire\Admin;

use App\Models\PengajuanPerubahan;
use Livewire\Component;
use Livewire\WithPagination;

class PengajuanIndex extends Component
{
    use WithPagination;

    public string $filterStatus = 'menunggu';

    public ?int $processId = null;
    public string $processAction = ''; // 'approve' | 'reject'
    public string $catatan_penolakan = '';

    public function updatingFilterStatus(): void
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
        $pengajuan = PengajuanPerubahan::findOrFail($this->processId);

        if ($this->processAction === 'reject') {
            $this->validate([
                'catatan_penolakan' => 'required|string|max:500',
            ]);

            $pengajuan->update([
                'status'             => 'ditolak',
                'catatan_penolakan'  => $this->catatan_penolakan,
            ]);

            session()->flash('success', 'Pengajuan berhasil ditolak.');
        } else {
            $pengajuan->update([
                'status'             => 'disetujui',
                'catatan_penolakan'  => null,
            ]);

            // Catatan: menyetujui pengajuan TIDAK otomatis mengubah data
            // di tabel jadwal_praktikum — perubahan teks bebas ("perubahan")
            // perlu diterapkan manual oleh operator lewat menu Kelola Jadwal,
            // supaya tetap lewat JadwalConflictService dan tidak menimbulkan
            // bentrok baru.
            session()->flash('success', 'Pengajuan disetujui. Jangan lupa terapkan perubahannya di menu Kelola Jadwal.');
        }

        $this->reset(['processId', 'processAction', 'catatan_penolakan']);
    }

    public function render()
    {
        $pengajuan = PengajuanPerubahan::query()
            ->with(['dosen', 'jadwalPraktikum.mataKuliah'])
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.pengajuan-index', [
            'pengajuan' => $pengajuan,
        ])->layout('components.layouts.admin', ['title' => 'Pengajuan Perubahan Jadwal']);
    }
}
