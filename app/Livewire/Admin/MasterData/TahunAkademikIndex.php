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

    public string $semester = 'Ganjil';

    public ?int $tahun_mulai = null;

    public ?int $tahun_selesai = null;

    public bool $is_aktif = false;

    public ?bool $status_aktif = null;

    public bool $showModal = false;

    public ?int $confirmDeleteId = null;

    public function updatedStatusAktif($value): void
    {
        $this->is_aktif = (bool) $value;
    }

    public function updatedIsAktif($value): void
    {
        $this->status_aktif = (bool) $value;
    }

    protected function rules(): array
    {
        return [
            'nama' => 'required|string|max:50|unique:tahun_akademik,nama,'.$this->editId,
            'semester' => 'required|in:Ganjil,Genap',
            'tahun_mulai' => 'required|integer|min:2000|max:2100',
            'tahun_selesai' => 'nullable|integer|min:2000|max:2100',
            'is_aktif' => 'boolean',
            'status_aktif' => 'nullable|boolean',
        ];
    }

    public function openCreate(): void
    {
        $this->reset(['editId', 'nama', 'tahun_mulai', 'tahun_selesai']);
        $this->semester = 'Ganjil';
        $this->is_aktif = false;
        $this->status_aktif = false;
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $tahun = TahunAkademik::findOrFail($id);

        $this->editId = $tahun->id;
        $this->nama = $tahun->nama;
        $this->semester = $tahun->semester ?: 'Ganjil';
        $this->tahun_mulai = $tahun->tahun_mulai;
        $this->tahun_selesai = $tahun->tahun_selesai ?: ($tahun->tahun_mulai ? $tahun->tahun_mulai + 1 : null);
        $this->is_aktif = (bool) $tahun->is_aktif;
        $this->status_aktif = (bool) $tahun->is_aktif;
        $this->showModal = true;
    }

    public function save(): void
    {
        if (empty($this->tahun_selesai) && ! empty($this->tahun_mulai)) {
            $this->tahun_selesai = $this->tahun_mulai + 1;
        }

        if ($this->status_aktif !== null) {
            $this->is_aktif = (bool) $this->status_aktif;
        }

        $validated = $this->validate();
        unset($validated['status_aktif']);

        // Kalau yang baru disimpan diset aktif, matikan status aktif tahun lain dulu
        if (! empty($validated['is_aktif'])) {
            TahunAkademik::where('id', '!=', $this->editId)->update(['is_aktif' => false]);
        }

        TahunAkademik::updateOrCreate(['id' => $this->editId], $validated);

        $this->showModal = false;
        $this->reset(['editId', 'nama', 'semester', 'tahun_mulai', 'tahun_selesai', 'is_aktif', 'status_aktif']);
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
