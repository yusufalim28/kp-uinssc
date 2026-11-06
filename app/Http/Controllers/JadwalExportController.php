<?php

namespace App\Http\Controllers;

use App\Exports\JadwalExport;
use App\Models\JadwalPraktikum;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class JadwalExportController extends Controller
{
    /**
     * Query yang sama persis dengan filter di DaftarJadwal (Public),
     * supaya PDF/Excel yang diunduh = apa yang sedang dilihat pengguna.
     */
    protected function filteredJadwal(Request $request)
    {
        return JadwalPraktikum::query()
            ->with(['mataKuliah.programStudi', 'dosen', 'kelas', 'ruangPraktikum.fakultas'])
            ->where('status', 'aktif')
            ->when($request->query('tahun_akademik_id'), fn ($q, $id) => $q->where('tahun_akademik_id', $id))
            ->when($request->query('fakultas_id'), function ($q, $fid) {
                $q->where(function ($sub) use ($fid) {
                    $sub->whereHas('mataKuliah.programStudi', fn ($ps) => $ps->where('fakultas_id', $fid))
                        ->orWhereHas('ruangPraktikum', fn ($r) => $r->where('fakultas_id', $fid));
                });
            })
            ->when($request->query('program_studi_id'), fn ($q, $id) => $q->whereHas(
                'mataKuliah',
                fn ($q2) => $q2->where('program_studi_id', $id)
            ))
            ->when($request->query('ruang_praktikum_id'), fn ($q, $id) => $q->where('ruang_praktikum_id', $id))
            ->when($request->query('mata_kuliah_id'), fn ($q, $id) => $q->where('mata_kuliah_id', $id))
            ->when($request->query('dosen_id'), fn ($q, $id) => $q->where('dosen_id', $id))
            ->when($request->query('hari'), fn ($q, $hari) => $q->where('hari', $hari))
            ->orderByRaw("CASE hari WHEN 'Senin' THEN 1 WHEN 'Selasa' THEN 2 WHEN 'Rabu' THEN 3 WHEN 'Kamis' THEN 4 WHEN 'Jumat' THEN 5 WHEN 'Jum''at' THEN 5 ELSE 6 END")
            ->orderBy('jam_mulai')
            ->get();
    }

    public function pdf(Request $request)
    {
        $jadwal = $this->filteredJadwal($request);

        $pdf = Pdf::loadView('exports.jadwal-pdf', [
            'jadwal' => $jadwal,
            'tanggalExport' => now()->translatedFormat('d F Y, H:i'),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('jadwal-praktikum-'.now()->format('Y-m-d').'.pdf');
    }

    public function excel(Request $request)
    {
        $jadwal = $this->filteredJadwal($request);

        return Excel::download(
            new JadwalExport($jadwal),
            'jadwal-praktikum-'.now()->format('Y-m-d').'.xlsx'
        );
    }
}
