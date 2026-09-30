<?php

namespace App\Livewire\Admin;

use App\Models\Dosen;
use App\Models\JadwalPraktikum;
use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\RuangPraktikum;
use App\Models\TahunAkademik;
use App\Services\JadwalConflictService;
use Livewire\Component;
use Livewire\WithPagination;

class JadwalManage extends Component
{
    use WithPagination;

    public ?int $editId = null;

    public ?int $mata_kuliah_id = null;

    public ?int $kelas_id = null;

    public ?int $dosen_id = null;

    public ?int $ruang_praktikum_id = null;

    public ?int $tahun_akademik_id = null;

    public string $hari = '';

    public string $jam_mulai = '';

    public string $jam_selesai = '';

    public string $status = 'aktif';

    public string $keterangan = '';

    public bool $showModal = false;

    public ?int $confirmDeleteId = null;

    // Pesan bentrok ditampilkan terpisah dari error validasi biasa,
    // supaya bisa menunjukkan jadwal mana yang bentrok.
    public ?string $conflictMessage = null;

    public array $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', "Jum'at", 'Sabtu'];

    protected function rules(): array
    {
        return [
            'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
            'kelas_id' => 'required|exists:kelas,id',
            'dosen_id' => 'required|exists:dosen,id',
            'ruang_praktikum_id' => 'required|exists:ruang_praktikum,id',
            'tahun_akademik_id' => 'required|exists:tahun_akademik,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jum\'at,Sabtu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'status' => 'required|in:aktif,nonaktif,dibatalkan',
            'keterangan' => 'nullable|string|max:255',
        ];
    }

    public function openCreate(): void
    {
        $this->reset([
            'editId', 'mata_kuliah_id', 'kelas_id', 'dosen_id',
            'ruang_praktikum_id', 'tahun_akademik_id', 'hari',
            'jam_mulai', 'jam_selesai', 'keterangan', 'conflictMessage',
        ]);
        $this->status = 'aktif';
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $jadwal = JadwalPraktikum::findOrFail($id);

        $this->editId = $jadwal->id;
        $this->mata_kuliah_id = $jadwal->mata_kuliah_id;
        $this->kelas_id = $jadwal->kelas_id;
        $this->dosen_id = $jadwal->dosen_id;
        $this->ruang_praktikum_id = $jadwal->ruang_praktikum_id;
        $this->tahun_akademik_id = $jadwal->tahun_akademik_id;
        $this->hari = $jadwal->hari;
        $this->jam_mulai = substr($jadwal->jam_mulai, 0, 5);
        $this->jam_selesai = substr($jadwal->jam_selesai, 0, 5);
        $this->status = $jadwal->status;
        $this->keterangan = (string) $jadwal->keterangan;
        $this->conflictMessage = null;
        $this->showModal = true;
    }

    public function save(JadwalConflictService $conflictService): void
    {
        $validated = $this->validate();
        $this->conflictMessage = null;

        // 1) Cek bentrok ruang
        $bentrokRuang = $conflictService->cekBentrokRuang(
            $this->ruang_praktikum_id, $this->hari, $this->jam_mulai, $this->jam_selesai,
            $this->tahun_akademik_id, $this->editId
        );

        if ($bentrokRuang) {
            $this->conflictMessage = "Ruang sudah dipakai untuk {$bentrokRuang->mataKuliah->nama_mk} "
                ."oleh {$bentrokRuang->dosen->nama} ({$bentrokRuang->jam_mulai}–{$bentrokRuang->jam_selesai}).";

            return;
        }

        // 2) Cek bentrok dosen
        $bentrokDosen = $conflictService->cekBentrokDosen(
            $this->dosen_id, $this->hari, $this->jam_mulai, $this->jam_selesai,
            $this->tahun_akademik_id, $this->editId
        );

        if ($bentrokDosen) {
            $this->conflictMessage = "Dosen sudah mengajar {$bentrokDosen->mataKuliah->nama_mk} "
                ."di ruang {$bentrokDosen->ruangPraktikum->nama_ruang} ({$bentrokDosen->jam_mulai}–{$bentrokDosen->jam_selesai}).";

            return;
        }

        // 3) Lolos validasi bentrok, baru simpan
        JadwalPraktikum::updateOrCreate(['id' => $this->editId], $validated);

        $this->showModal = false;
        $this->reset([
            'editId', 'mata_kuliah_id', 'kelas_id', 'dosen_id',
            'ruang_praktikum_id', 'tahun_akademik_id', 'hari',
            'jam_mulai', 'jam_selesai', 'keterangan',
        ]);
        session()->flash('success', $this->editId ? 'Jadwal berhasil diperbarui.' : 'Jadwal berhasil ditambahkan.');
    }

    public function confirmDelete(int $id): void
    {
        $this->confirmDeleteId = $id;
    }

    public function delete(): void
    {
        $jadwal = JadwalPraktikum::find($this->confirmDeleteId);

        if ($jadwal) {
            if ($jadwal->pengajuanPerubahan()->exists()) {
                session()->flash('error', 'Jadwal tidak bisa dihapus karena masih memiliki riwayat Pengajuan Perubahan.');
            } else {
                $jadwal->delete();
                session()->flash('success', 'Jadwal berhasil dihapus.');
            }
        }

        $this->confirmDeleteId = null;
    }

    public function render()
    {
        $jadwal = JadwalPraktikum::query()
            ->with(['mataKuliah', 'kelas', 'dosen', 'ruangPraktikum', 'tahunAkademik'])
            ->orderByRaw("FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jum''at','Sabtu')")
            ->orderBy('jam_mulai')
            ->paginate(10);

        return view('livewire.admin.jadwal-manage', [
            'jadwal' => $jadwal,
            'mataKuliah' => MataKuliah::where('status', 'aktif')->orderBy('nama_mk')->get(),
            'kelasList' => Kelas::where('status', 'aktif')->orderBy('nama_kelas')->get(),
            'dosenList' => Dosen::where('status', 'aktif')->orderBy('nama')->get(),
            'ruangList' => RuangPraktikum::where('status', 'aktif')->orderBy('nama_ruang')->get(),
            'tahunList' => TahunAkademik::orderByDesc('tahun_mulai')->get(),
        ])->layout('components.layouts.admin', ['title' => 'Kelola Jadwal Praktikum']);
    }
}
