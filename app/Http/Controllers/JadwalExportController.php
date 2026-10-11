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
            ->with(['mataKuliah', 'dosen', 'kelas', 'ruangPraktikum'])
            ->where('status', 'aktif')
            ->when($request->query('program_studi_id'), fn ($q, $id) => $q->whereHas(
                'mataKuliah',
                fn ($q2) => $q2->where('program_studi_id', $id)
            ))
            ->when($request->query('mata_kuliah_id'), fn ($q, $id) => $q->where('mata_kuliah_id', $id))
            ->when($request->query('dosen_id'), fn ($q, $id) => $q->where('dosen_id', $id))
            ->when($request->query('hari'), fn ($q, $hari) => $q->where('hari', $hari))
            ->orderByRaw("FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jum''at','Sabtu')")
            ->orderBy('jam_mulai')
            ->get();
    }

    public function pdf(Request $request)
    {
        $jadwal = $this->filteredJadwal($request);

        $pdf = Pdf::loadView('exports.jadwal-pdf', [
            'jadwal'      => $jadwal,
            'tanggalExport' => now()->translatedFormat('d F Y, H:i'),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('jadwal-praktikum-' . now()->format('Y-m-d') . '.pdf');
    }

    public function excel(Request $request)
    {
        $jadwal = $this->filteredJadwal($request);

        return Excel::download(
            new JadwalExport($jadwal),
            'jadwal-praktikum-' . now()->format('Y-m-d') . '.xlsx'
        );
    }
}
