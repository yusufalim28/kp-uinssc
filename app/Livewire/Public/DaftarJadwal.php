<?php

namespace App\Livewire\Public;

use App\Models\JadwalPraktikum;
use App\Models\ProgramStudi;
use App\Models\MataKuliah;
use App\Models\Dosen;
use Livewire\Component;
use Livewire\WithPagination;

class DaftarJadwal extends Component
{
    use WithPagination;

    public string $programStudiId = '';
    public string $mataKuliahId = '';
    public string $dosenId = '';
    public string $hari = '';

    // Simpan filter di URL supaya link bisa di-share/bookmark
    protected $queryString = [
        'programStudiId' => ['except' => ''],
        'mataKuliahId'    => ['except' => ''],
        'dosenId'         => ['except' => ''],
        'hari'            => ['except' => ''],
    ];

    public function updating($field, $value): void
    {
        // Reset ke halaman 1 setiap kali salah satu filter berubah
        $this->resetPage();
    }

    public function resetFilter(): void
    {
        $this->reset(['programStudiId', 'mataKuliahId', 'dosenId', 'hari']);
    }

    public function render()
    {
        $jadwal = JadwalPraktikum::query()
            ->with(['mataKuliah', 'dosen', 'kelas', 'ruangPraktikum'])
            ->where('status', 'aktif')
            ->when($this->programStudiId, fn ($q) => $q->whereHas(
                'mataKuliah',
                fn ($q2) => $q2->where('program_studi_id', $this->programStudiId)
            ))
            ->when($this->mataKuliahId, fn ($q) => $q->where('mata_kuliah_id', $this->mataKuliahId))
            ->when($this->dosenId, fn ($q) => $q->where('dosen_id', $this->dosenId))
            ->when($this->hari, fn ($q) => $q->where('hari', $this->hari))
            ->orderByRaw("FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jum''at','Sabtu')")
            ->orderBy('jam_mulai')
            ->paginate(10);

        return view('livewire.public.daftar-jadwal', [
            'jadwal'       => $jadwal,
            'programStudi' => ProgramStudi::orderBy('nama_program_studi')->get(),
            'mataKuliah'   => MataKuliah::orderBy('nama_mk')->get(),
            'dosen'        => Dosen::orderBy('nama')->get(),
            'hariList'     => ['Senin', 'Selasa', 'Rabu', 'Kamis', "Jum'at", 'Sabtu'],
        ])->layout('components.layouts.guest', ['title' => 'Daftar Jadwal']);
    }
}
