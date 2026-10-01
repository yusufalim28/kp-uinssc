<?php

namespace App\Livewire\Dosen;

use App\Models\Dosen;
use App\Models\JadwalPraktikum;
use App\Models\PengajuanPerubahan;
use Livewire\Component;
use Livewire\WithPagination;

class PengajuanSaya extends Component
{
    use WithPagination;

    public ?int $jadwal_praktikum_id = null;
    public string $perubahan = '';
    public string $alasan = '';
    public bool $showModal = false;

    protected function rules(): array
    {
        return [
            'jadwal_praktikum_id' => 'required|exists:jadwal_praktikum,id',
            'perubahan'           => 'required|string|max:255',
            'alasan'              => 'required|string|max:500',
        ];
    }

    /**
     * Ambil data Dosen yang terhubung dengan akun login saat ini.
     * Lihat migration add_user_id_to_dosen_table.
     */
    protected function dosenSaya(): ?Dosen
    {
        return Dosen::where('user_id', auth()->id())->first();
    }

    public function openCreate(): void
    {
        $this->reset(['jadwal_praktikum_id', 'perubahan', 'alasan']);
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

        PengajuanPerubahan::create([
            'jadwal_praktikum_id' => $validated['jadwal_praktikum_id'],
            'dosen_id'            => $dosen->id,
            'perubahan'           => $validated['perubahan'],
            'alasan'              => $validated['alasan'],
            'status'              => 'menunggu',
        ]);

        $this->showModal = false;
        $this->reset(['jadwal_praktikum_id', 'perubahan', 'alasan']);
        session()->flash('success', 'Pengajuan perubahan berhasil dikirim, menunggu review operator/admin.');
    }

    public function render()
    {
        $dosen = $this->dosenSaya();

        $pengajuan = PengajuanPerubahan::query()
            ->where('dosen_id', $dosen?->id ?? 0)
            ->with('jadwalPraktikum.mataKuliah')
            ->latest()
            ->paginate(10);

        $jadwalSaya = JadwalPraktikum::query()
            ->where('dosen_id', $dosen?->id ?? 0)
            ->where('status', 'aktif')
            ->with('mataKuliah')
            ->get();

        return view('livewire.dosen.pengajuan-saya', [
            'pengajuan'  => $pengajuan,
            'jadwalSaya' => $jadwalSaya,
            'tidakAdaProfilDosen' => is_null($dosen),
        ])->layout('components.layouts.admin', ['title' => 'Pengajuan Perubahan Saya']);
    }
}
