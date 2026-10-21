<?php

namespace App\Livewire\Admin;

use App\Imports\JadwalImport;
use App\Models\Dosen;
use App\Models\JadwalPraktikum;
use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\RuangPraktikum;
use App\Models\TahunAkademik;
use App\Services\JadwalConflictService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class JadwalManage extends Component
{
    use WithFileUploads, WithPagination;

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

    // Search & Filter Properties
    public string $search = '';

    public string $filterHari = '';

    public ?int $filterRuang = null;

    // Excel Import Properties
    public bool $showImportModal = false;

    public $excelFile = null;

    public int $importSuccessCount = 0;

    public int $importSkippedCount = 0;

    public array $importErrors = [];

    public ?int $confirmDeleteId = null;

    // Pesan bentrok ditampilkan terpisah dari error validasi biasa,
    // supaya bisa menunjukkan jadwal mana yang bentrok.
    public ?string $conflictMessage = null;

    public array $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', "Jum'at", 'Sabtu'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterHari(): void
    {
        $this->resetPage();
    }

    public function updatingFilterRuang(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'filterHari', 'filterRuang']);
        $this->resetPage();
    }

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
            'status' => 'required|in:aktif,nonaktif,dibatalkan,reschedule',
            'keterangan' => 'nullable|string|max:255',
        ];
    }

    public function openImportModal(): void
    {
        $this->reset(['excelFile', 'importSuccessCount', 'importSkippedCount', 'importErrors']);
        $this->showImportModal = true;
    }

    public function importExcel(): void
    {
        $this->validate([
            'excelFile' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ], [
            'excelFile.required' => 'Pilih file Excel yang akan diunggah.',
            'excelFile.mimes' => 'Format file harus berupa Excel (.xlsx, .xls) atau .csv',
            'excelFile.max' => 'Ukuran file maksimal 5MB.',
        ]);

        try {
            $import = new JadwalImport;
            Excel::import($import, $this->excelFile->getRealPath());

            $this->importSuccessCount = $import->successCount;
            $this->importSkippedCount = $import->skippedCount;
            $this->importErrors = $import->errors;

            if ($this->importSuccessCount > 0) {
                session()->flash('success', "Berhasil mengimpor {$this->importSuccessCount} jadwal praktikum!");
            }
        } catch (\Throwable $e) {
            $this->importErrors = ['Terjadi kesalahan saat membaca file: '.$e->getMessage()];
            $this->importSkippedCount = 1;
        }

        $this->reset('excelFile');
    }

    public function openCreate(): void
    {
        $this->reset([
            'editId', 'mata_kuliah_id', 'kelas_id', 'dosen_id',
            'ruang_praktikum_id', 'tahun_akademik_id', 'hari',
            'jam_mulai', 'jam_selesai', 'status', 'keterangan',
            'conflictMessage',
        ]);
        $this->tahun_akademik_id = TahunAkademik::where('is_aktif', true)->value('id');
        $this->status = 'aktif';
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $j = JadwalPraktikum::findOrFail($id);

        $this->editId = $j->id;
        $this->mata_kuliah_id = $j->mata_kuliah_id;
        $this->kelas_id = $j->kelas_id;
        $this->dosen_id = $j->dosen_id;
        $this->ruang_praktikum_id = $j->ruang_praktikum_id;
        $this->tahun_akademik_id = $j->tahun_akademik_id;
        $this->hari = $j->hari;
        $this->jam_mulai = substr($j->jam_mulai, 0, 5);
        $this->jam_selesai = substr($j->jam_selesai, 0, 5);
        $this->status = $j->status;
        $this->keterangan = $j->keterangan ?? '';
        $this->conflictMessage = null;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        $this->conflictMessage = JadwalConflictService::checkConflict(
            ruangPraktikumId: $this->ruang_praktikum_id,
            dosenId: $this->dosen_id,
            hari: $this->hari,
            jamMulai: $this->jam_mulai,
            jamSelesai: $this->jam_selesai,
            excludeId: $this->editId,
            tahunAkademikId: $this->tahun_akademik_id,
        );

        if ($this->conflictMessage) {
            return;
        }

        JadwalPraktikum::updateOrCreate(['id' => $this->editId], $validated);

        $this->showModal = false;
        $this->reset([
            'editId', 'mata_kuliah_id', 'kelas_id', 'dosen_id',
            'ruang_praktikum_id', 'tahun_akademik_id', 'hari',
            'jam_mulai', 'jam_selesai', 'status', 'keterangan',
            'conflictMessage',
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
        $user = auth()->user();

        // Query Jadwal dengan Scoping Hak Akses Fakultas / Prodi
        $jadwalQuery = JadwalPraktikum::query()
            ->with(['mataKuliah', 'kelas', 'dosen', 'ruangPraktikum', 'tahunAkademik']);

        $mkQuery = MataKuliah::where('status', 'aktif');
        $kelasQuery = Kelas::where('status', 'aktif');
        $ruangQuery = RuangPraktikum::whereIn('status', ['aktif', 'tersedia']);

        if ($user && $user->hasRole('operator')) {
            if ($user->program_studi_id) {
                // Operator Prodi: hanya jadwal prodinya sendiri
                $jadwalQuery->whereHas('kelas', fn ($k) => $k->where('program_studi_id', $user->program_studi_id));
                $mkQuery->where('program_studi_id', $user->program_studi_id);
                $kelasQuery->where('program_studi_id', $user->program_studi_id);

                // Ruang: lab milik fakultas dari operator atau milik prodi operator
                $ruangQuery->where(function ($q) use ($user) {
                    if ($user->fakultas_id) {
                        $q->where('fakultas_id', $user->fakultas_id);
                    }
                    if ($user->program_studi_id) {
                        $q->orWhere('program_studi_id', $user->program_studi_id);
                    }
                });
            } elseif ($user->fakultas_id) {
                // Operator Fakultas: prodi yang bernaung di bawah fakultasnya
                $jadwalQuery->whereHas('kelas.programStudi', fn ($ps) => $ps->where('fakultas_id', $user->fakultas_id));
                $mkQuery->whereHas('programStudi', fn ($ps) => $ps->where('fakultas_id', $user->fakultas_id));
                $kelasQuery->whereHas('programStudi', fn ($ps) => $ps->where('fakultas_id', $user->fakultas_id));

                // Ruang: seluruh ruang laboratorium milik fakultas
                $ruangQuery->where('fakultas_id', $user->fakultas_id);
            }
        }

        if ($this->search) {
            $jadwalQuery->where(function ($q) {
                $q->whereHas('mataKuliah', fn ($mk) => $mk->where('nama_mk', 'like', "%{$this->search}%")->orWhere('kode_mk', 'like', "%{$this->search}%"))
                    ->orWhereHas('dosen', fn ($d) => $d->where('nama', 'like', "%{$this->search}%")->orWhere('nidn', 'like', "%{$this->search}%"))
                    ->orWhereHas('kelas', fn ($k) => $k->where('nama_kelas', 'like', "%{$this->search}%"))
                    ->orWhereHas('ruangPraktikum', fn ($r) => $r->where('nama_ruang', 'like', "%{$this->search}%")->orWhere('kode_ruang', 'like', "%{$this->search}%"));
            });
        }

        if ($this->filterHari) {
            $jadwalQuery->where('hari', $this->filterHari);
        }

        if ($this->filterRuang) {
            $jadwalQuery->where('ruang_praktikum_id', $this->filterRuang);
        }

        $jadwal = $jadwalQuery
            ->orderByRaw("CASE hari WHEN 'Senin' THEN 1 WHEN 'Selasa' THEN 2 WHEN 'Rabu' THEN 3 WHEN 'Kamis' THEN 4 WHEN 'Jumat' THEN 5 WHEN 'Jum''at' THEN 5 WHEN 'Sabtu' THEN 6 ELSE 7 END")
            ->orderBy('jam_mulai')
            ->paginate(10);

        return view('livewire.admin.jadwal-manage', [
            'jadwal' => $jadwal,
            'mataKuliah' => $mkQuery->orderBy('nama_mk')->get(),
            'kelasList' => $kelasQuery->orderBy('nama_kelas')->get(),
            'dosenList' => Dosen::where('status', 'aktif')->orderBy('nama')->get(),
            'ruangList' => $ruangQuery->orderBy('nama_ruang')->get(),
            'tahunList' => TahunAkademik::orderByDesc('tahun_mulai')->get(),
        ])->layout('components.layouts.admin', ['title' => 'Kelola Jadwal Praktikum']);
    }
}
