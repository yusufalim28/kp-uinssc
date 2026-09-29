<?php

namespace App\Services;

use App\Models\JadwalPraktikum;

class JadwalConflictService
{
    public function cekBentrokRuang(
        int $ruangPraktikumId,
        string $hari,
        string $jamMulai,
        string $jamSelesai,
        int $tahunAkademikId,
    ): bool {
        return JadwalPraktikum::query()
            ->where('ruang_praktikum_id', $ruangPraktikumId)
            ->where('tahun_akademik_id', $tahunAkademikId)
            ->where('hari', $hari)
            ->where('status', '!=', 'dibatalkan')
            ->where('jam_mulai', '<', $jamSelesai)
            ->where('jam_selesai', '>', $jamMulai)
            ->exists();
    }
}
