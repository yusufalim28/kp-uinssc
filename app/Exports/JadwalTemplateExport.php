<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class JadwalTemplateExport implements FromCollection, ShouldAutoSize, WithHeadings, WithStyles
{
    public function collection(): Collection
    {
        return collect([
            [
                'kode_mk' => 'IF101',
                'nama_kelas' => 'IF-1A',
                'nidn_dosen' => '0501198501',
                'kode_ruang' => 'LAB-01',
                'hari' => 'Senin',
                'jam_mulai' => '13:00',
                'jam_selesai' => '15:00',
                'tahun_akademik' => '2025/2026 Ganjil',
            ],
            [
                'kode_mk' => 'IF201',
                'nama_kelas' => 'IF-3A',
                'nidn_dosen' => '0612199002',
                'kode_ruang' => 'LAB-02',
                'hari' => 'Selasa',
                'jam_mulai' => '13:00',
                'jam_selesai' => '15:00',
                'tahun_akademik' => '2025/2026 Ganjil',
            ],
            [
                'kode_mk' => 'IF301',
                'nama_kelas' => 'IF-5A',
                'nidn_dosen' => '0723198803',
                'kode_ruang' => 'LAB-03',
                'hari' => 'Kamis',
                'jam_mulai' => '13:00',
                'jam_selesai' => '15:00',
                'tahun_akademik' => '2025/2026 Ganjil',
            ],
        ]);
    }

    public function headings(): array
    {
        return [
            'Kode Mata Kuliah',
            'Nama Kelas',
            'NIDN / NIP Dosen',
            'Kode Ruang',
            'Hari (Senin/Selasa/Rabu/Kamis/Jum\'at/Sabtu)',
            'Jam Mulai (HH:MM)',
            'Jam Selesai (HH:MM)',
            'Tahun Akademik (Opsional - default: tahun aktif)',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '005E3D'],
                ],
            ],
        ];
    }
}
