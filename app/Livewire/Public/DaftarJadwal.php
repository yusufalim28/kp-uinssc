<?php

namespace App\Livewire\Public;

use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\JadwalPraktikum;
use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use App\Models\RuangPraktikum;
use App\Models\TahunAkademik;
use Livewire\Component;
use Livewire\WithPagination;

class DaftarJadwal extends Component
{
    use WithPagination;

    public string $tahunAkademikId = '';

    public string $fakultasId = '';

    public string $programStudiId = '';

    public string $ruangPraktikumId = '';

    public string $mataKuliahId = '';

    public string $dosenId = '';

    public string $hari = '';

    public string $search = '';

    public bool $showAll = false;

    // Simpan filter di URL supaya link bisa di-share/bookmark
    protected $queryString = [
        'tahunAkademikId' => ['except' => ''],
        'fakultasId' => ['except' => ''],
        'programStudiId' => ['except' => ''],
        'ruangPraktikumId' => ['except' => ''],
        'mataKuliahId' => ['except' => ''],
        'dosenId' => ['except' => ''],
        'hari' => ['except' => ''],
        'search' => ['except' => ''],
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
        // Reset prodi & ruang jika fakultas berubah
        $this->programStudiId = '';
        $this->ruangPraktikumId = '';
        $this->resetPage();
    }

    public function updating($field, $value): void
    {
        // Reset ke halaman 1 setiap kali salah satu filter berubah
        $this->resetPage();
    }

    public function resetFilter(): void
    {
        $taAktif = TahunAkademik::where('is_aktif', true)->first();
        $this->reset(['fakultasId', 'programStudiId', 'ruangPraktikumId', 'mataKuliahId', 'dosenId', 'hari', 'search', 'showAll']);
        $this->tahunAkademikId = (string) ($taAktif?->id ?? '');
        $this->resetPage();
    }

    public function lihatSemuaJadwal(): void
    {
        $this->showAll = true;
        $this->resetPage();
    }

    public function pilihRuang(int $ruangId): void
    {
        $this->ruangPraktikumId = (string) $ruangId;
        $this->resetPage();
    }

    public function pilihProdi(int $prodiId): void
    {
        $this->programStudiId = (string) $prodiId;
        $this->resetPage();
    }

    public function render()
    {
        // Cek apakah ada filter yang sedang aktif
        $hasActiveFilter = ! empty($this->fakultasId)
            || ! empty($this->programStudiId)
            || ! empty($this->ruangPraktikumId)
            || ! empty($this->mataKuliahId)
            || ! empty($this->dosenId)
            || ! empty($this->hari)
            || ! empty($this->search)
            || $this->showAll;

        $jadwal = null;
        $selectedRuang = null;
        $selectedProdi = null;

        if ($hasActiveFilter) {
            $jadwalQuery = JadwalPraktikum::query()
                ->with(['mataKuliah.programStudi', 'dosen', 'kelas', 'ruangPraktikum.fakultas'])
                ->where('status', 'aktif');

            if ($this->tahunAkademikId) {
                $jadwalQuery->where('tahun_akademik_id', $this->tahunAkademikId);
            }

            if ($this->fakultasId) {
                $jadwalQuery->where(function ($q) {
                    $q->whereHas('mataKuliah.programStudi', fn ($ps) => $ps->where('fakultas_id', $this->fakultasId))
                        ->orWhereHas('ruangPraktikum', fn ($r) => $r->where('fakultas_id', $this->fakultasId));
                });
            }

            if ($this->programStudiId) {
                $jadwalQuery->whereHas('mataKuliah', fn ($mk) => $mk->where('program_studi_id', $this->programStudiId));
            }

            if ($this->ruangPraktikumId) {
                $jadwalQuery->where('ruang_praktikum_id', $this->ruangPraktikumId);
            }

            if ($this->mataKuliahId) {
                $jadwalQuery->where('mata_kuliah_id', $this->mataKuliahId);
            }

            if ($this->dosenId) {
                $jadwalQuery->where('dosen_id', $this->dosenId);
            }

            if ($this->hari) {
                $jadwalQuery->where('hari', $this->hari);
            }

            if ($this->search) {
                $s = trim($this->search);
                $jadwalQuery->where(function ($q) use ($s) {
                    $q->whereHas('mataKuliah', fn ($mk) => $mk->where('nama_mk', 'like', "%{$s}%")->orWhere('kode_mk', 'like', "%{$s}%"))
                        ->orWhereHas('dosen', fn ($d) => $d->where('nama', 'like', "%{$s}%"))
                        ->orWhereHas('kelas', fn ($k) => $k->where('nama_kelas', 'like', "%{$s}%"))
                        ->orWhereHas('ruangPraktikum', fn ($r) => $r->where('nama_ruang', 'like', "%{$s}%"));
                });
            }

            $jadwal = $jadwalQuery
                ->orderByRaw("CASE hari WHEN 'Senin' THEN 1 WHEN 'Selasa' THEN 2 WHEN 'Rabu' THEN 3 WHEN 'Kamis' THEN 4 WHEN 'Jumat' THEN 5 WHEN 'Jum''at' THEN 5 ELSE 6 END")
                ->orderBy('jam_mulai')
                ->paginate(10);

            if ($this->ruangPraktikumId) {
                $selectedRuang = RuangPraktikum::with('fakultas')->find($this->ruangPraktikumId);
            }
            if ($this->programStudiId) {
                $selectedProdi = ProgramStudi::with('fakultas')->find($this->programStudiId);
            }
        }

        // Ambil data pendukung filter
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

        $mkQuery = MataKuliah::where('status', 'aktif');
        if ($this->programStudiId) {
            $mkQuery->where('program_studi_id', $this->programStudiId);
        }
        $mataKuliahList = $mkQuery->orderBy('nama_mk')->get();

        $tahunAkademikList = TahunAkademik::orderByDesc('is_aktif')->orderByDesc('tahun_mulai')->get();
        $selectedTahun = $this->tahunAkademikId ? TahunAkademik::find($this->tahunAkademikId) : null;

        return view('livewire.public.daftar-jadwal', [
            'hasActiveFilter' => $hasActiveFilter,
            'jadwal' => $jadwal,
            'selectedRuang' => $selectedRuang,
            'selectedProdi' => $selectedProdi,
            'selectedTahun' => $selectedTahun,
            'tahunAkademikList' => $tahunAkademikList,
            'fakultasList' => $fakultasList,
            'programStudiList' => $programStudiList,
            'ruangList' => $ruangList,
            'mataKuliahList' => $mataKuliahList,
            'dosenList' => Dosen::where('status', 'aktif')->orderBy('nama')->get(),
            'hariList' => ['Senin', 'Selasa', 'Rabu', 'Kamis', "Jum'at"],
        ])->layout('components.layouts.guest', ['title' => 'Daftar Jadwal Praktikum']);
    }
}
