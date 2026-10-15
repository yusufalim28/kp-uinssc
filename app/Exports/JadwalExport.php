<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class JadwalExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
{
    public function __construct(protected Collection $jadwal) {}

    public function collection(): Collection
    {
        return $this->jadwal;
    }

    public function headings(): array
    {
        return ['Hari', 'Jam Mulai', 'Jam Selesai', 'Mata Kuliah', 'SKS', 'Kelas', 'Dosen', 'Ruang', 'Lokasi'];
    }

    public function map($jadwal): array
    {
        return [
            $jadwal->hari,
            substr($jadwal->jam_mulai, 0, 5),
            substr($jadwal->jam_selesai, 0, 5),
            $jadwal->mataKuliah->nama_mk,
            $jadwal->mataKuliah->sks,
            $jadwal->kelas->nama_kelas,
            $jadwal->dosen->nama,
            $jadwal->ruangPraktikum->nama_ruang,
            $jadwal->ruangPraktikum->lokasi,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]], // baris header ditebalkan
        ];
    }
}
