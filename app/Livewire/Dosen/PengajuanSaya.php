<?php

namespace App\Livewire\Dosen;

use App\Models\Dosen;
use App\Models\JadwalPraktikum;
use App\Models\PengajuanPerubahan;
use App\Models\RuangPraktikum;
use App\Services\JadwalConflictService;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class PengajuanSaya extends Component
{
    use WithPagination;

    public ?int $jadwal_praktikum_id = null;

    public string $perubahan = '';

    public bool $tidakAdaProfilDosen = false;

    // Opsi Terstruktur untuk Perubahan
    public string $jenis_perubahan = 'ganti_jadwal'; // 'ganti_jadwal', 'ganti_ruang', 'ganti_jadwal_ruang', 'reschedule', 'lainnya'

    public string $hari_baru = 'Senin';

    public string $jam_mulai_baru = '08:00';

    public string $jam_selesai_baru = '10:30';

    public ?int $ruang_praktikum_id_baru = null;

    public string $tanggal_pengganti = '';

    public string $detail_kustom = '';

    public string $alasan = '';

    public bool $showModal = false;

    // Status ketersediaan slot real-time
    public bool $isSlotAvailable = false;

    public ?string $conflictMessage = null;

    public array $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', "Jum'at", 'Sabtu'];

    public function mount(): void
    {
        $this->tidakAdaProfilDosen = is_null($this->dosenSaya());
    }

    protected function rules(): array
    {
        return [
            'jadwal_praktikum_id' => 'required|exists:jadwal_praktikum,id',
            'jenis_perubahan' => 'required|in:ganti_jadwal,ganti_ruang,ganti_jadwal_ruang,reschedule,lainnya',
            'perubahan' => 'nullable|string|max:255',
            'alasan' => 'required|string|max:500',
            'hari_baru' => 'nullable|string',
            'jam_mulai_baru' => 'nullable|string',
            'jam_selesai_baru' => 'nullable|string',
            'ruang_praktikum_id_baru' => 'nullable|exists:ruang_praktikum,id',
            'tanggal_pengganti' => 'nullable|string',
            'detail_kustom' => 'nullable|string|max:255',
        ];
    }

    /**
     * Ambil data Dosen yang terhubung dengan akun login saat ini.
     */
    protected function dosenSaya(): ?Dosen
    {
        return Dosen::where('user_id', auth()->id())->first();
    }

    public function updatedJadwalPraktikumId(?int $value): void
    {
        if ($value) {
            $jadwal = JadwalPraktikum::find($value);
            if ($jadwal) {
                $this->hari_baru = $jadwal->hari;
                $this->jam_mulai_baru = substr($jadwal->jam_mulai, 0, 5);
                $this->jam_selesai_baru = substr($jadwal->jam_selesai, 0, 5);
                $this->ruang_praktikum_id_baru = $jadwal->ruang_praktikum_id;
            }
        }
        $this->checkSlotAvailability();
    }

    public function updatedJenisPerubahan(): void
    {
        $this->checkSlotAvailability();
    }

    public function updatedHariBaru(): void
    {
        $this->checkSlotAvailability();
    }

    public function updatedJamMulaiBaru(): void
    {
        $this->checkSlotAvailability();
    }

    public function updatedJamSelesaiBaru(): void
    {
        $this->checkSlotAvailability();
    }

    public function updatedRuangPraktikumIdBaru(): void
    {
        $this->checkSlotAvailability();
    }

    public function updatedTanggalPengganti(): void
    {
        $this->checkSlotAvailability();
    }

    /**
     * Cek apakah slot yang diminta kosong (bebas bentrok ruang & dosen)
     */
    public function checkSlotAvailability(): void
    {
        $this->conflictMessage = null;
        $this->isSlotAvailable = false;

        if (! $this->jadwal_praktikum_id || $this->jenis_perubahan === 'lainnya') {
            return;
        }

        $jadwal = JadwalPraktikum::find($this->jadwal_praktikum_id);
        if (! $jadwal) {
            return;
        }

        $targetRuangId = in_array($this->jenis_perubahan, ['ganti_ruang', 'ganti_jadwal_ruang'])
            ? $this->ruang_praktikum_id_baru
            : $jadwal->ruang_praktikum_id;

        $targetHari = match ($this->jenis_perubahan) {
            'ganti_jadwal', 'ganti_jadwal_ruang' => $this->hari_baru,
            'reschedule' => $this->tanggal_pengganti ? $this->getHariFromTanggal($this->tanggal_pengganti) : $this->hari_baru,
            default => $jadwal->hari,
        };

        $targetJamMulai = $this->jam_mulai_baru ?: substr($jadwal->jam_mulai, 0, 5);
        $targetJamSelesai = $this->jam_selesai_baru ?: substr($jadwal->jam_selesai, 0, 5);

        if (! $targetRuangId || ! $targetHari || ! $targetJamMulai || ! $targetJamSelesai) {
            return;
        }

        if ($targetJamMulai >= $targetJamSelesai) {
            $this->conflictMessage = 'Jam mulai harus lebih awal daripada jam selesai.';

            return;
        }

        $conflictService = new JadwalConflictService;

        // 1. Cek bentrok ruang
        $bentrokRuang = $conflictService->cekBentrokRuang(
            (int) $targetRuangId,
            $targetHari,
            $targetJamMulai,
            $targetJamSelesai,
            $jadwal->tahun_akademik_id,
            $jadwal->id
        );

        if ($bentrokRuang) {
            $ruangNama = $bentrokRuang->ruangPraktikum?->nama_ruang ?? 'Ruang';
            $mkNama = $bentrokRuang->mataKuliah?->nama_mk ?? 'Jadwal lain';
            $this->conflictMessage = "Ruangan {$ruangNama} sudah terpakai oleh '{$mkNama}' pada hari {$targetHari} pukul ".substr($bentrokRuang->jam_mulai, 0, 5).'–'.substr($bentrokRuang->jam_selesai, 0, 5).'.';

            return;
        }

        // 2. Cek bentrok dosen
        $bentrokDosen = $conflictService->cekBentrokDosen(
            $jadwal->dosen_id,
            $targetHari,
            $targetJamMulai,
            $targetJamSelesai,
            $jadwal->tahun_akademik_id,
            $jadwal->id
        );

        if ($bentrokDosen) {
            $mkNama = $bentrokDosen->mataKuliah?->nama_mk ?? 'Jadwal lain';
            $ruangNama = $bentrokDosen->ruangPraktikum?->nama_ruang ?? 'Ruang lain';
            $this->conflictMessage = "Anda sudah memiliki jadwal mengajar '{$mkNama}' di {$ruangNama} pada hari {$targetHari} jam tersebut.";

            return;
        }

        $this->isSlotAvailable = true;
    }

    protected function getHariFromTanggal(string $tanggal): string
    {
        try {
            $dayOfWeek = Carbon::parse($tanggal)->dayOfWeek;

            return match ($dayOfWeek) {
                1 => 'Senin',
                2 => 'Selasa',
                3 => 'Rabu',
                4 => 'Kamis',
                5 => "Jum'at",
                6 => 'Sabtu',
                default => 'Minggu',
            };
        } catch (\Throwable) {
            return 'Senin';
        }
    }

    public function openCreate(): void
    {
        $this->reset([
            'jadwal_praktikum_id', 'perubahan', 'jenis_perubahan', 'hari_baru',
            'jam_mulai_baru', 'jam_selesai_baru', 'ruang_praktikum_id_baru',
            'tanggal_pengganti', 'detail_kustom', 'alasan', 'isSlotAvailable', 'conflictMessage',
        ]);
        $this->jenis_perubahan = 'ganti_jadwal';
        $this->hari_baru = 'Senin';
        $this->jam_mulai_baru = '08:00';
        $this->jam_selesai_baru = '10:30';
        $this->showModal = true;
    }

    /**
     * Reschedule mandiri instan jika slot terbukti kosong (conflict-free)
     */
    public function rescheduleInstant(): void
    {
        $dosen = $this->dosenSaya();
        if (! $dosen) {
            session()->flash('error', 'Akun Anda belum terhubung ke data Dosen.');
            $this->showModal = false;

            return;
        }

        $this->validate([
            'jadwal_praktikum_id' => 'required|exists:jadwal_praktikum,id',
            'jenis_perubahan' => 'required|in:ganti_jadwal,ganti_ruang,ganti_jadwal_ruang,reschedule',
            'alasan' => 'required|string|max:500',
            'hari_baru' => 'nullable|string',
            'jam_mulai_baru' => 'nullable|string',
            'jam_selesai_baru' => 'nullable|string',
            'ruang_praktikum_id_baru' => 'nullable|exists:ruang_praktikum,id',
            'tanggal_pengganti' => 'nullable|string',
        ]);

        $this->checkSlotAvailability();

        if (! $this->isSlotAvailable) {
            session()->flash('error', $this->conflictMessage ?: 'Slot jadwal yang dipilih tidak tersedia atau bentrok.');

            return;
        }

        $jadwal = JadwalPraktikum::findOrFail($this->jadwal_praktikum_id);

        $targetRuangId = in_array($this->jenis_perubahan, ['ganti_ruang', 'ganti_jadwal_ruang'])
            ? (int) $this->ruang_praktikum_id_baru
            : $jadwal->ruang_praktikum_id;

        $targetHari = match ($this->jenis_perubahan) {
            'ganti_jadwal', 'ganti_jadwal_ruang' => $this->hari_baru,
            'reschedule' => $this->tanggal_pengganti ? $this->getHariFromTanggal($this->tanggal_pengganti) : $this->hari_baru,
            default => $jadwal->hari,
        };

        $targetJamMulai = $this->jam_mulai_baru ?: substr($jadwal->jam_mulai, 0, 5);
        $targetJamSelesai = $this->jam_selesai_baru ?: substr($jadwal->jam_selesai, 0, 5);

        $ruangBaruNama = RuangPraktikum::find($targetRuangId)?->nama_ruang ?? 'Ruang';

        $perubahanDeskripsi = match ($this->jenis_perubahan) {
            'ganti_jadwal' => "Pindah ke Hari {$targetHari} ({$targetJamMulai}–{$targetJamSelesai})",
            'ganti_ruang' => "Pindah ke Ruang {$ruangBaruNama}",
            'ganti_jadwal_ruang' => "Pindah ke Hari {$targetHari} ({$targetJamMulai}–{$targetJamSelesai}) di {$ruangBaruNama}",
            'reschedule' => 'Kuliah Pengganti pada '.($this->tanggal_pengganti ?: $targetHari)." ({$targetJamMulai}–{$targetJamSelesai}) di {$ruangBaruNama}",
            default => 'Perubahan Jadwal Mandiri',
        };

        // Update Jadwal Praktikum secara langsung
        $jadwal->update([
            'hari' => $targetHari,
            'jam_mulai' => $targetJamMulai,
            'jam_selesai' => $targetJamSelesai,
            'ruang_praktikum_id' => $targetRuangId,
            'status' => 'reschedule',
            'keterangan' => "Reschedule mandiri oleh Dosen: {$this->alasan}",
        ]);

        // Catat pengajuan dengan status disetujui otomatis
        PengajuanPerubahan::create([
            'jadwal_praktikum_id' => $jadwal->id,
            'dosen_id' => $dosen->id,
            'jenis_perubahan' => $this->jenis_perubahan,
            'perubahan' => $perubahanDeskripsi,
            'alasan' => $this->alasan,
            'status' => 'disetujui',
            'catatan_penolakan' => 'Disetujui otomatis oleh sistem (Ruangan & Jadwal bebas bentrok)',
        ]);

        $this->showModal = false;
        $this->reset([
            'jadwal_praktikum_id', 'jenis_perubahan', 'hari_baru',
            'jam_mulai_baru', 'jam_selesai_baru', 'ruang_praktikum_id_baru',
            'tanggal_pengganti', 'detail_kustom', 'alasan', 'isSlotAvailable', 'conflictMessage',
        ]);

        session()->flash('success', "⚡ Jadwal praktikum berhasil dipindahkan langsung! Ruang {$ruangBaruNama} pada hari {$targetHari} ({$targetJamMulai}–{$targetJamSelesai}) telah aktif secara instan.");
    }

    public function save(): void
    {
        $dosen = $this->dosenSaya();

        if (! $dosen) {
            session()->flash('error', 'Akun Anda belum terhubung ke data Dosen. Hubungi operator/admin untuk menautkannya.');
            $this->showModal = false;

            return;
        }

        $validated = $this->validate();

        $perubahanDeskripsi = $this->perubahan ?: match ($this->jenis_perubahan) {
            'ganti_jadwal' => "Pindah ke Hari {$this->hari_baru} ({$this->jam_mulai_baru} - {$this->jam_selesai_baru})",
            'ganti_ruang' => 'Pindah ke '.(RuangPraktikum::find($this->ruang_praktikum_id_baru)?->nama_ruang ?? 'Ruang Baru'),
            'ganti_jadwal_ruang' => "Pindah ke Hari {$this->hari_baru} ({$this->jam_mulai_baru} - {$this->jam_selesai_baru}) di ".(RuangPraktikum::find($this->ruang_praktikum_id_baru)?->nama_ruang ?? 'Ruang Baru'),
            'reschedule' => 'Kuliah Pengganti pada '.($this->tanggal_pengganti ?: '-')." ({$this->jam_mulai_baru} - {$this->jam_selesai_baru})",
            'lainnya' => $this->detail_kustom ?: 'Permintaan khusus',
            default => $this->detail_kustom ?: 'Perubahan jadwal',
        };

        PengajuanPerubahan::create([
            'jadwal_praktikum_id' => $validated['jadwal_praktikum_id'],
            'dosen_id' => $dosen->id,
            'jenis_perubahan' => $this->jenis_perubahan,
            'perubahan' => $perubahanDeskripsi,
            'alasan' => $validated['alasan'],
            'status' => 'menunggu',
        ]);

        $this->showModal = false;
        $this->reset([
            'jadwal_praktikum_id', 'jenis_perubahan', 'hari_baru',
            'jam_mulai_baru', 'jam_selesai_baru', 'ruang_praktikum_id_baru',
            'tanggal_pengganti', 'detail_kustom', 'alasan', 'isSlotAvailable', 'conflictMessage',
        ]);
        session()->flash('success', 'Pengajuan perubahan berhasil dikirim, menunggu review operator/admin.');
    }

    public function render()
    {
        $dosen = $this->dosenSaya();

        $pengajuan = PengajuanPerubahan::query()
            ->where('dosen_id', $dosen?->id ?? 0)
            ->with(['jadwalPraktikum.mataKuliah', 'jadwalPraktikum.ruangPraktikum'])
            ->latest()
            ->paginate(10);

        $jadwalSaya = JadwalPraktikum::query()
            ->where('dosen_id', $dosen?->id ?? 0)
            ->whereIn('status', ['aktif', 'reschedule'])
            ->with(['mataKuliah', 'ruangPraktikum'])
            ->get();

        $ruangList = RuangPraktikum::whereIn('status', ['aktif', 'tersedia'])
            ->orderBy('nama_ruang')
            ->get();

        return view('livewire.dosen.pengajuan-saya', [
            'pengajuan' => $pengajuan,
            'jadwalSaya' => $jadwalSaya,
            'ruangList' => $ruangList,
            'tidakAdaProfilDosen' => is_null($dosen),
        ])->layout('components.layouts.admin', ['title' => 'Pengajuan Perubahan Saya']);
    }
}
