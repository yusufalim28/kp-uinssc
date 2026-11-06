<?php

namespace App\Livewire\Public;

use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\JadwalPraktikum;
use App\Models\MataKuliah;
use App\Models\RuangPraktikum;
use App\Models\TahunAkademik;
use Livewire\Component;

class Home extends Component
{
    public string $filterFakultasRuang = 'semua';

    public ?int $selectedRuangId = null;

    public bool $showAllRooms = false;

    public function updatedFilterFakultasRuang(): void
    {
        $this->selectedRuangId = null;
    }

    public function toggleShowAllRooms(): void
    {
        $this->showAllRooms = ! $this->showAllRooms;
    }

    public function render()
    {
        $hariMap = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => "Jum'at",
            'Saturday' => 'Sabtu',
        ];
        $hariIni = $hariMap[now()->format('l')] ?? now()->format('l');
        $jamSekarang = now()->format('H:i:s');
        $isHariKerja = in_array($hariIni, ['Senin', 'Selasa', 'Rabu', 'Kamis', "Jum'at"]);

        $taAktif = TahunAkademik::where('is_aktif', true)->first();
        $taId = $taAktif?->id;

        // Jadwal Hari Ini pada Tahun Akademik Aktif
        $jadwalHariIniQuery = JadwalPraktikum::with(['mataKuliah.programStudi', 'dosen', 'ruangPraktikum', 'kelas'])
            ->where('status', 'aktif')
            ->where('hari', $hariIni);

        if ($taId) {
            $jadwalHariIniQuery->where('tahun_akademik_id', $taId);
        }

        $jadwalHariIni = $jadwalHariIniQuery->orderBy('jam_mulai')->get();

        // Seluruh Ruangan & Status Real-time
        $ruangQuery = RuangPraktikum::with('fakultas')
            ->when($this->filterFakultasRuang !== 'semua', fn ($q) => $q->where('fakultas_id', $this->filterFakultasRuang));

        $ruangList = $ruangQuery->orderBy('nama_ruang')->get();

        $ruangStatusList = $ruangList->map(function ($ruang) use ($hariIni, $jamSekarang, $taId, $isHariKerja) {
            if ($ruang->status === 'perbaikan' || $ruang->status === 'nonaktif') {
                return [
                    'ruang' => $ruang,
                    'status' => 'perbaikan',
                    'label' => 'Perbaikan',
                    'sesi_aktif' => null,
                    'sesi_berikutnya' => null,
                ];
            }

            if (! $isHariKerja) {
                return [
                    'ruang' => $ruang,
                    'status' => 'libur',
                    'label' => 'Libur',
                    'sesi_aktif' => null,
                    'sesi_berikutnya' => null,
                ];
            }

            // Cari sesi yang sedang berlangsung saat ini
            $sesiAktifQuery = JadwalPraktikum::with(['mataKuliah', 'dosen', 'kelas'])
                ->where('ruang_praktikum_id', $ruang->id)
                ->where('hari', $hariIni)
                ->where('status', 'aktif')
                ->whereTime('jam_mulai', '<=', $jamSekarang)
                ->whereTime('jam_selesai', '>=', $jamSekarang);

            if ($taId) {
                $sesiAktifQuery->where('tahun_akademik_id', $taId);
            }

            $sesiAktif = $sesiAktifQuery->first();

            // Cari sesi berikutnya hari ini (jika ada)
            $sesiBerikutnya = null;
            if (! $sesiAktif) {
                $sesiBerikutnyaQuery = JadwalPraktikum::with(['mataKuliah', 'dosen', 'kelas'])
                    ->where('ruang_praktikum_id', $ruang->id)
                    ->where('hari', $hariIni)
                    ->where('status', 'aktif')
                    ->whereTime('jam_mulai', '>', $jamSekarang)
                    ->orderBy('jam_mulai');

                if ($taId) {
                    $sesiBerikutnyaQuery->where('tahun_akademik_id', $taId);
                }

                $sesiBerikutnya = $sesiBerikutnyaQuery->first();
            }

            return [
                'ruang' => $ruang,
                'status' => $sesiAktif ? 'terpakai' : 'tersedia',
                'label' => $sesiAktif ? 'Terpakai' : 'Kosong',
                'sesi_aktif' => $sesiAktif,
                'sesi_berikutnya' => $sesiBerikutnya,
            ];
        });

        $totalTerpakai = $ruangStatusList->where('status', 'terpakai')->count();
        $totalTersedia = $ruangStatusList->where('status', 'tersedia')->count();

        // Ruangan terpilih untuk detail cepat
        $selectedItem = null;
        if ($this->selectedRuangId) {
            $selectedItem = $ruangStatusList->firstWhere('ruang.id', $this->selectedRuangId);
        }

        return view('livewire.public.home', [
            'taAktif' => $taAktif,
            'totalJadwalAktif' => JadwalPraktikum::where('status', 'aktif')->when($taId, fn ($q) => $q->where('tahun_akademik_id', $taId))->count(),
            'totalMataKuliah' => MataKuliah::where('status', 'aktif')->count(),
            'totalDosen' => Dosen::where('status', 'aktif')->count(),
            'hariIni' => $hariIni,
            'jamSekarang' => now()->format('H:i'),
            'isHariKerja' => $isHariKerja,
            'jadwalHariIni' => $jadwalHariIni,
            'ruangStatusList' => $ruangStatusList,
            'selectedItem' => $selectedItem,
            'totalTerpakai' => $totalTerpakai,
            'totalTersedia' => $totalTersedia,
            'fakultasList' => Fakultas::where('status', 'aktif')->orderBy('nama_fakultas')->get(),
        ])->layout('components.layouts.guest', ['title' => 'Beranda - Sistem Jadwal Praktikum']);
    }
}
