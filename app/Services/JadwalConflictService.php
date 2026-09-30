<?php

namespace App\Services;

use App\Models\JadwalPraktikum;

class JadwalConflictService
{
    /**
     * Cek apakah ruang sudah dipakai jadwal lain di hari & jam yang overlap,
     * pada tahun akademik yang sama.
     *
     * $excludeId dipakai saat EDIT — supaya jadwal yang sedang diedit tidak
     * membandingkan dirinya sendiri sebagai "bentrok".
     */
    public function cekBentrokRuang(
        int $ruangId,
        string $hari,
        string $jamMulai,
        string $jamSelesai,
        int $tahunAkademikId,
        ?int $excludeId = null
    ): ?JadwalPraktikum {
        return JadwalPraktikum::with(['mataKuliah', 'dosen'])
            ->where('ruang_praktikum_id', $ruangId)
            ->where('hari', $hari)
            ->where('tahun_akademik_id', $tahunAkademikId)
            ->where('status', 'aktif')
            ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
            ->where(function ($q) use ($jamMulai, $jamSelesai) {
                $q->where('jam_mulai', '<', $jamSelesai)
                    ->where('jam_selesai', '>', $jamMulai);
            })
            ->first();
    }

    /**
     * Cek apakah dosen sudah mengajar jadwal lain yang overlap waktu,
     * pada hari & tahun akademik yang sama (walaupun ruangnya beda).
     */
    public function cekBentrokDosen(
        int $dosenId,
        string $hari,
        string $jamMulai,
        string $jamSelesai,
        int $tahunAkademikId,
        ?int $excludeId = null
    ): ?JadwalPraktikum {
        return JadwalPraktikum::with(['mataKuliah', 'ruangPraktikum'])
            ->where('dosen_id', $dosenId)
            ->where('hari', $hari)
            ->where('tahun_akademik_id', $tahunAkademikId)
            ->where('status', 'aktif')
            ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
            ->where(function ($q) use ($jamMulai, $jamSelesai) {
                $q->where('jam_mulai', '<', $jamSelesai)
                    ->where('jam_selesai', '>', $jamMulai);
            })
            ->first();
    }
}
