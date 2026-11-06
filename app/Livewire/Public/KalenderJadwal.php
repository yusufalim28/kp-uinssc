<?php

namespace App\Livewire\Public;

use App\Models\Fakultas;
use App\Models\JadwalPraktikum;
use App\Models\ProgramStudi;
use App\Models\RuangPraktikum;
use App\Models\TahunAkademik;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Kalender Jadwal Praktikum Mingguan')]
class KalenderJadwal extends Component
{
    public string $tahunAkademikId = '';

    public string $fakultasId = '';

    public string $programStudiId = '';

    public string $ruangPraktikumId = '';

    public bool $showAll = false;

    protected $queryString = [
        'tahunAkademikId' => ['except' => ''],
        'fakultasId' => ['except' => ''],
        'programStudiId' => ['except' => ''],
        'ruangPraktikumId' => ['except' => ''],
        'showAll' => ['except' => false],
    ];

    public function mount(): void
    {
        if (! $this->tahunAkademikId) {
            $taAktif = TahunAkademik::where('is_aktif', true)->first();
            if ($taAktif) {
                $this->tahunAkademikId = (string) $taAktif->id;
            }
        }
    }

    public function updatedFakultasId(): void
    {
        $this->programStudiId = '';
        $this->ruangPraktikumId = '';
    }

    public function resetFilter(): void
    {
        $taAktif = TahunAkademik::where('is_aktif', true)->first();
        $this->reset(['fakultasId', 'programStudiId', 'ruangPraktikumId', 'showAll']);
        $this->tahunAkademikId = (string) ($taAktif?->id ?? '');
    }

    public function setRuang(int $id): void
    {
        $this->ruangPraktikumId = (string) $id;
    }

    public function setProdi(int $id): void
    {
        $this->programStudiId = (string) $id;
    }

    public function lihatSemuaKalender(): void
    {
        $this->showAll = true;
    }

    public function render()
    {
        // Hari aktif praktikum: Senin s/d Jum'at (Sabtu libur)
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', "Jum'at"];

        // Cek apakah filter aktif sudah dipilih oleh pengguna
        $hasActiveFilter = ! empty($this->ruangPraktikumId)
            || ! empty($this->programStudiId)
            || ! empty($this->fakultasId)
            || $this->showAll;

        $allJadwal = collect();
        $jadwalPerHari = collect();

        if ($hasActiveFilter) {
            $query = JadwalPraktikum::with(['mataKuliah.programStudi', 'dosen', 'kelas', 'ruangPraktikum.fakultas'])
                ->where('status', 'aktif');

            if ($this->tahunAkademikId) {
                $query->where('tahun_akademik_id', $this->tahunAkademikId);
            }

            if ($this->fakultasId) {
                $query->where(function ($q) {
                    $q->whereHas('mataKuliah.programStudi', fn ($ps) => $ps->where('fakultas_id', $this->fakultasId))
                        ->orWhereHas('ruangPraktikum', fn ($r) => $r->where('fakultas_id', $this->fakultasId));
                });
            }

            if ($this->programStudiId) {
                $query->whereHas('mataKuliah', fn ($mk) => $mk->where('program_studi_id', $this->programStudiId));
            }

            if ($this->ruangPraktikumId) {
                $query->where('ruang_praktikum_id', $this->ruangPraktikumId);
            }

            $allJadwal = $query->orderBy('jam_mulai')->get();
            $jadwalPerHari = $allJadwal->groupBy('hari');
        }

        // Master Data untuk filter
        $tahunAkademikList = TahunAkademik::orderByDesc('is_aktif')->orderByDesc('tahun_mulai')->get();
        $fakultasList = Fakultas::where('status', 'aktif')->orderBy('nama_fakultas')->get();

        $prodiQuery = ProgramStudi::where('status', 'aktif');
        if ($this->fakultasId) {
            $prodiQuery->where('fakultas_id', $this->fakultasId);
        }
        $programStudiList = $prodiQuery->orderBy('nama_program_studi')->get();

        $ruangQuery = RuangPraktikum::whereIn('status', ['aktif', 'tersedia']);
        if ($this->fakultasId) {
            $ruangQuery->where(function ($q) {
                $q->where('fakultas_id', $this->fakultasId)
                    ->orWhere('cakupan_akses', 'umum_universitas')
                    ->orWhereNull('fakultas_id');
            });
        }
        $ruangList = $ruangQuery->orderBy('nama_ruang')->get();

        $selectedRuang = $this->ruangPraktikumId ? RuangPraktikum::find($this->ruangPraktikumId) : null;
        $selectedProdi = $this->programStudiId ? ProgramStudi::find($this->programStudiId) : null;
        $selectedTahun = $this->tahunAkademikId ? TahunAkademik::find($this->tahunAkademikId) : null;

        return view('livewire.public.kalender-jadwal', [
            'hasActiveFilter' => $hasActiveFilter,
            'hariList' => $hariList,
            'jadwalPerHari' => $jadwalPerHari,
            'totalJadwal' => $allJadwal->count(),
            'tahunAkademikList' => $tahunAkademikList,
            'selectedTahun' => $selectedTahun,
            'fakultasList' => $fakultasList,
            'programStudiList' => $programStudiList,
            'ruangList' => $ruangList,
            'selectedRuang' => $selectedRuang,
            'selectedProdi' => $selectedProdi,
        ])->layout('components.layouts.guest', ['title' => 'Kalender Jadwal Praktikum']);
    }
}
