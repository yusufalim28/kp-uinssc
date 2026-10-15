<?php

namespace App\Livewire\Dosen;

use App\Models\Dosen;
use App\Models\JadwalPraktikum;
use App\Models\PengajuanPerubahan;
use App\Models\RuangPraktikum;
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
    }

    public function openCreate(): void
    {
        $this->reset([
            'jadwal_praktikum_id', 'perubahan', 'jenis_perubahan', 'hari_baru',
            'jam_mulai_baru', 'jam_selesai_baru', 'ruang_praktikum_id_baru',
            'tanggal_pengganti', 'detail_kustom', 'alasan',
        ]);
        $this->jenis_perubahan = 'ganti_jadwal';
        $this->hari_baru = 'Senin';
        $this->jam_mulai_baru = '08:00';
        $this->jam_selesai_baru = '10:30';
        $this->showModal = true;
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

        // Buat ringkasan teks otomatis dari opsi terstruktur atau gunakan $this->perubahan jika sudah diisi
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
            'tanggal_pengganti', 'detail_kustom', 'alasan',
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
            ->where('status', 'aktif')
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
