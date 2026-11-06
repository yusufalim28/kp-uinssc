<?php

namespace App\Livewire\Public;

use App\Models\Fakultas;
use App\Models\JadwalPraktikum;
use App\Models\RuangPraktikum;
use App\Models\TahunAkademik;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layouts.guest')]
#[Title('Jadwal Penggunaan Ruang & Laboratorium — SI Jadwal Praktikum')]
class JadwalRuangan extends Component
{
    #[Url(as: 'ta')]
    public ?int $tahunAkademikId = null;

    #[Url(as: 'fakultas')]
    public ?int $fakultasId = null;

    #[Url(as: 'ruang')]
    public ?int $ruangPraktikumId = null;

    public bool $hasSearched = false;

    /**
     * Slot waktu standar praktikum (12 sesi @ 45 menit)
     * Mengikuti format resmi universitas (Gambar 4)
     *
     * @var array<int, array{start: string, end: string, label: string}>
     */
    public array $timeSlots = [
        ['start' => '07:30', 'end' => '08:15', 'label' => '07:30 - 08:15'],
        ['start' => '08:15', 'end' => '09:00', 'label' => '08:15 - 09:00'],
        ['start' => '09:00', 'end' => '09:45', 'label' => '09:00 - 09:45'],
        ['start' => '09:45', 'end' => '10:30', 'label' => '09:45 - 10:30'],
        ['start' => '10:30', 'end' => '11:15', 'label' => '10:30 - 11:15'],
        ['start' => '11:15', 'end' => '12:00', 'label' => '11:15 - 12:00'],
        ['start' => '13:00', 'end' => '13:45', 'label' => '13:00 - 13:45'],
        ['start' => '13:45', 'end' => '14:30', 'label' => '13:45 - 14:30'],
        ['start' => '14:30', 'end' => '15:15', 'label' => '14:30 - 15:15'],
        ['start' => '15:15', 'end' => '16:00', 'label' => '15:15 - 16:00'],
        ['start' => '16:00', 'end' => '16:45', 'label' => '16:00 - 16:45'],
        ['start' => '16:45', 'end' => '17:30', 'label' => '16:45 - 17:30'],
    ];

    /**
     * Hari aktif perkuliahan (Senin s/d Jum'at — Sabtu libur)
     *
     * @var array<int, string>
     */
    public array $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', "Jum'at"];

    public function mount(): void
    {
        if (! $this->tahunAkademikId) {
            $activeTa = TahunAkademik::where('is_aktif', true)->first();
            $this->tahunAkademikId = $activeTa?->id;
        }

        if ($this->ruangPraktikumId) {
            $ruang = RuangPraktikum::find($this->ruangPraktikumId);
            if ($ruang) {
                if ($ruang->fakultas_id && ! $this->fakultasId) {
                    $this->fakultasId = $ruang->fakultas_id;
                }
                $this->hasSearched = true;
            }
        }
    }

    public function updatedFakultasId(): void
    {
        $this->ruangPraktikumId = null;
        $this->hasSearched = false;
    }

    public function updatedRuangPraktikumId(): void
    {
        if ($this->ruangPraktikumId) {
            $this->hasSearched = true;
        } else {
            $this->hasSearched = false;
        }
    }

    public function selectRuang(int $ruangId): void
    {
        $this->ruangPraktikumId = $ruangId;
        $ruang = RuangPraktikum::find($ruangId);
        if ($ruang && $ruang->fakultas_id) {
            $this->fakultasId = $ruang->fakultas_id;
        }
        $this->hasSearched = true;
    }

    public function render(): View
    {
        $tahunAkademikOptions = TahunAkademik::orderByDesc('tahun_mulai')
            ->orderByDesc('semester')
            ->get();

        $selectedTa = $this->tahunAkademikId
            ? TahunAkademik::find($this->tahunAkademikId)
            : TahunAkademik::where('is_aktif', true)->first();

        $fakultasList = Fakultas::where('status', 'aktif')->orderBy('nama_fakultas')->get();

        $ruangQuery = RuangPraktikum::where('status', 'aktif');
        if ($this->fakultasId) {
            $ruangQuery->where(function ($q) {
                $q->where('fakultas_id', $this->fakultasId)
                    ->orWhereNull('fakultas_id')
                    ->orWhere('cakupan_akses', 'umum_universitas');
            });
        }
        $ruangList = $ruangQuery->orderBy('nama_ruang')->get();

        $selectedRuang = $this->ruangPraktikumId
            ? RuangPraktikum::with('fakultas')->find($this->ruangPraktikumId)
            : null;

        $matrix = [];
        $rawJadwals = collect();

        if ($this->hasSearched && $selectedRuang) {
            $jadwalQuery = JadwalPraktikum::with(['mataKuliah.programStudi', 'kelas', 'dosen', 'ruangPraktikum'])
                ->where('ruang_praktikum_id', $selectedRuang->id)
                ->where('status', 'aktif');

            if ($this->tahunAkademikId) {
                $jadwalQuery->where('tahun_akademik_id', $this->tahunAkademikId);
            }

            $rawJadwals = $jadwalQuery->get();

            // Bangun matriks per hari & slot
            foreach ($this->hariList as $hari) {
                $matrix[$hari] = $this->buildDayRow($hari, $rawJadwals);
            }
        }

        return view('livewire.public.jadwal-ruangan', [
            'tahunAkademikOptions' => $tahunAkademikOptions,
            'selectedTa' => $selectedTa,
            'fakultasList' => $fakultasList,
            'ruangList' => $ruangList,
            'selectedRuang' => $selectedRuang,
            'matrix' => $matrix,
            'totalJadwal' => $rawJadwals->count(),
        ]);
    }

    /**
     * Membangun representasi sel untuk 1 hari dengan penanganan colspan
     *
     * @param  Collection<int, JadwalPraktikum>  $allJadwals
     * @return array<int, array{type: string, colspan: int, jadwal?: JadwalPraktikum}>
     */
    private function buildDayRow(string $hari, $allJadwals): array
    {
        // Standarisasi pencarian hari (menangani "Jumat" vs "Jum'at")
        $dayJadwals = $allJadwals->filter(function ($j) use ($hari) {
            return str_replace("'", '', strtolower($j->hari)) === str_replace("'", '', strtolower($hari));
        });

        $cells = [];
        $skipCount = 0;
        $totalSlots = count($this->timeSlots);

        for ($i = 0; $i < $totalSlots; $i++) {
            if ($skipCount > 0) {
                $skipCount--;

                continue;
            }

            $slot = $this->timeSlots[$i];

            // Cari jadwal yang aktif pada rentang waktu slot ini
            $matched = $dayJadwals->first(function ($j) use ($slot) {
                $mulai = substr($j->jam_mulai, 0, 5);
                $selesai = substr($j->jam_selesai, 0, 5);

                return $mulai < $slot['end'] && $selesai > $slot['start'];
            });

            if ($matched) {
                // Hitung berapa slot yang dicakup oleh jadwal ini mulai dari indeks $i
                $colspan = 1;
                for ($k = $i + 1; $k < $totalSlots; $k++) {
                    $nextSlot = $this->timeSlots[$k];
                    $mulai = substr($matched->jam_mulai, 0, 5);
                    $selesai = substr($matched->jam_selesai, 0, 5);

                    if ($mulai < $nextSlot['end'] && $selesai > $nextSlot['start']) {
                        $colspan++;
                    } else {
                        break;
                    }
                }

                $cells[] = [
                    'type' => 'jadwal',
                    'colspan' => $colspan,
                    'jadwal' => $matched,
                ];

                $skipCount = $colspan - 1;
            } else {
                $cells[] = [
                    'type' => 'empty',
                    'colspan' => 1,
                ];
            }
        }

        return $cells;
    }
}
