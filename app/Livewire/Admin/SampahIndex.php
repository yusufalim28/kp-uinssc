<?php

namespace App\Livewire\Admin;

use App\Models\Fakultas;
use App\Models\ProgramStudi;
use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\Dosen;
use App\Models\RuangPraktikum;
use App\Models\TahunAkademik;
use App\Models\JadwalPraktikum;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;

class SampahIndex extends Component
{
    public string $filterJenis = '';

    /**
     * Peta jenis data yang bisa di-restore/dihapus permanen di sini.
     * 'nameField' = null berarti nama ditampilkan lewat closure khusus
     * (dipakai untuk Jadwal Praktikum, yang tidak punya 1 kolom nama).
     */
    protected function jenisMap(): array
    {
        return [
            'fakultas'         => ['model' => Fakultas::class, 'label' => 'Fakultas', 'nameField' => 'nama_fakultas'],
            'program_studi'    => ['model' => ProgramStudi::class, 'label' => 'Program Studi', 'nameField' => 'nama_program_studi'],
            'kelas'            => ['model' => Kelas::class, 'label' => 'Kelas', 'nameField' => 'nama_kelas'],
            'mata_kuliah'      => ['model' => MataKuliah::class, 'label' => 'Mata Kuliah', 'nameField' => 'nama_mk'],
            'dosen'            => ['model' => Dosen::class, 'label' => 'Dosen', 'nameField' => 'nama'],
            'ruang_praktikum'  => ['model' => RuangPraktikum::class, 'label' => 'Ruang Praktikum', 'nameField' => 'nama_ruang'],
            'tahun_akademik'   => ['model' => TahunAkademik::class, 'label' => 'Tahun Akademik', 'nameField' => 'nama'],
            'jadwal_praktikum' => ['model' => JadwalPraktikum::class, 'label' => 'Jadwal Praktikum', 'nameField' => null],
        ];
    }

    public function restore(string $jenis, int $id): void
    {
        $modelClass = $this->jenisMap()[$jenis]['model'] ?? null;

        if (! $modelClass) {
            return;
        }

        $modelClass::withTrashed()->findOrFail($id)->restore();

        session()->flash('success', 'Data berhasil dipulihkan.');
    }

    public function forceDelete(string $jenis, int $id): void
    {
        $modelClass = $this->jenisMap()[$jenis]['model'] ?? null;

        if (! $modelClass) {
            return;
        }

        $modelClass::withTrashed()->findOrFail($id)->forceDelete();

        session()->flash('success', 'Data berhasil dihapus permanen dan tidak bisa dikembalikan.');
    }

    public function render()
    {
        $jenisMap = $this->jenisMap();
        $items = collect();

        foreach ($jenisMap as $key => $config) {
            if ($this->filterJenis && $this->filterJenis !== $key) {
                continue;
            }

            $rows = $config['model']::onlyTrashed()->get();

            foreach ($rows as $row) {
                $nama = $config['nameField']
                    ? $row->{$config['nameField']}
                    : (($row->mataKuliah->nama_mk ?? 'Mata kuliah terhapus') . ' — ' . $row->hari);

                $items->push([
                    'jenis'       => $key,
                    'label_jenis' => $config['label'],
                    'id'          => $row->id,
                    'nama'        => $nama,
                    'deleted_at'  => $row->deleted_at,
                ]);
            }
        }

        $items = $items->sortByDesc('deleted_at')->values();

        $page    = (int) request()->get('page', 1);
        $perPage = 15;

        $paged = new LengthAwarePaginator(
            $items->forPage($page, $perPage),
            $items->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('livewire.admin.sampah-index', [
            'items'    => $paged,
            'jenisMap' => $jenisMap,
        ])->layout('components.layouts.admin', ['title' => 'Sampah — Data Terhapus']);
    }
}
