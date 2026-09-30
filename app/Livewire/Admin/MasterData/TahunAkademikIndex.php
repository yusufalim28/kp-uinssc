<?php

namespace App\Livewire\Admin\MasterData;

use App\Models\TahunAkademik;
use Livewire\Component;
use Livewire\WithPagination;

class TahunAkademikIndex extends Component
{
    use WithPagination;

    public ?int $editId = null;

    public string $nama = '';

    public ?int $tahun_mulai = null;

    public bool $status_aktif = false;   // <-- boolean, bukan enum seperti tabel lain

    public bool $showModal = false;

    public ?int $confirmDeleteId = null;

    protected function rules(): array
    {
        return [
            'nama' => 'required|string|max:50|unique:tahun_akademik,nama,'.$this->editId,
            'tahun_mulai' => 'required|integer|min:2000|max:2100',
            'status_aktif' => 'boolean',
        ];
    }

    public function openCreate(): void
    {
        $this->reset(['editId', 'nama', 'tahun_mulai', 'status_aktif']);
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $tahun = TahunAkademik::findOrFail($id);

        $this->editId = $tahun->id;
        $this->nama = $tahun->nama;
        $this->tahun_mulai = $tahun->tahun_mulai;
        $this->status_aktif = (bool) $tahun->status_aktif;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        // Kalau yang baru disimpan diset aktif, matikan status aktif tahun lain dulu
        // supaya hanya ada 1 tahun akademik aktif pada satu waktu.
        if ($validated['status_aktif']) {
            TahunAkademik::where('id', '!=', $this->editId)->update(['status_aktif' => false]);
        }

        TahunAkademik::updateOrCreate(['id' => $this->editId], $validated);

        $this->showModal = false;
        $this->reset(['editId', 'nama', 'tahun_mulai', 'status_aktif']);
        session()->flash('success', $this->editId ? 'Tahun Akademik berhasil diperbarui.' : 'Tahun Akademik berhasil ditambahkan.');
    }

    public function confirmDelete(int $id): void
    {
        $this->confirmDeleteId = $id;
    }

    public function delete(): void
    {
        $tahun = TahunAkademik::find($this->confirmDeleteId);

        if ($tahun) {
            if ($tahun->jadwalPraktikum()->exists()) {
                session()->flash('error', 'Tahun Akademik tidak bisa dihapus karena masih dipakai di Jadwal Praktikum.');
            } else {
                $tahun->delete();
                session()->flash('success', 'Tahun Akademik berhasil dihapus.');
            }
        }

        $this->confirmDeleteId = null;
    }

    public function render()
    {
        $tahun = TahunAkademik::query()
            ->orderByDesc('tahun_mulai')
            ->paginate(10);

        return view('livewire.admin.master-data.tahun-akademik-index', [
            'tahun' => $tahun,
        ])->layout('components.layouts.admin', ['title' => 'Master Data — Tahun Akademik']);
    }
}
