<?php

namespace App\Livewire\Dosen;

use App\Models\Dosen;
use App\Models\JadwalPraktikum;
use App\Models\TahunAkademik;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Jadwal Mengajar Saya')]
class JadwalSaya extends Component
{
    public ?int $selectedTahunId = null;

    public function mount(): void
    {
        $this->selectedTahunId = TahunAkademik::where('is_aktif', true)->value('id');
    }

    public function render()
    {
        abort_if(! auth()->user()->hasRole('dosen'), 403);

        $dosen = Dosen::where('user_id', auth()->id())->first();

        $jadwalList = collect();
        if ($dosen) {
            $query = JadwalPraktikum::where('dosen_id', $dosen->id)
                ->with(['mataKuliah', 'kelas', 'ruangPraktikum', 'tahunAkademik']);

            if ($this->selectedTahunId) {
                $query->where('tahun_akademik_id', $this->selectedTahunId);
            }

            $jadwalList = $query->orderByRaw("CASE hari WHEN 'Senin' THEN 1 WHEN 'Selasa' THEN 2 WHEN 'Rabu' THEN 3 WHEN 'Kamis' THEN 4 WHEN 'Jumat' THEN 5 WHEN 'Jum''at' THEN 5 WHEN 'Sabtu' THEN 6 ELSE 7 END")
                ->orderBy('jam_mulai')
                ->get();
        }

        $tahunList = TahunAkademik::orderByDesc('tahun_mulai')->get();

        return view('livewire.dosen.jadwal-saya', [
            'dosen' => $dosen,
            'jadwalList' => $jadwalList,
            'tahunList' => $tahunList,
        ])->layout('components.layouts.admin', ['title' => 'Jadwal Mengajar Saya']);
    }
}
