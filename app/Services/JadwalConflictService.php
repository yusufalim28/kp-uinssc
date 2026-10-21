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
            ->whereIn('status', ['aktif', 'reschedule'])
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
            ->whereIn('status', ['aktif', 'reschedule'])
            ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
            ->where(function ($q) use ($jamMulai, $jamSelesai) {
                $q->where('jam_mulai', '<', $jamSelesai)
                    ->where('jam_selesai', '>', $jamMulai);
            })
            ->first();
    }

    /**
     * Helper statis untuk mengecek bentrok ruang & dosen sekaligus.
     * Mengembalikan pesan bentrok jika ada, atau null jika slot kosong/aman.
     */
    public static function checkConflict(
        int $ruangPraktikumId,
        int $dosenId,
        string $hari,
        string $jamMulai,
        string $jamSelesai,
        ?int $excludeId,
        int $tahunAkademikId
    ): ?string {
        $service = new self;

        $bentrokRuang = $service->cekBentrokRuang(
            $ruangPraktikumId,
            $hari,
            $jamMulai,
            $jamSelesai,
            $tahunAkademikId,
            $excludeId
        );

        if ($bentrokRuang) {
            $ruangNama = $bentrokRuang->ruangPraktikum?->nama_ruang ?? 'Ruangan';
            $mkNama = $bentrokRuang->mataKuliah?->nama_mk ?? 'Jadwal lain';
            $jamMulaiStr = substr($bentrokRuang->jam_mulai, 0, 5);
            $jamSelesaiStr = substr($bentrokRuang->jam_selesai, 0, 5);

            return "Ruangan '{$ruangNama}' sudah digunakan untuk '{$mkNama}' pada hari {$hari} ({$jamMulaiStr}–{$jamSelesaiStr}).";
        }

        $bentrokDosen = $service->cekBentrokDosen(
            $dosenId,
            $hari,
            $jamMulai,
            $jamSelesai,
            $tahunAkademikId,
            $excludeId
        );

        if ($bentrokDosen) {
            $dosenNama = $bentrokDosen->dosen?->nama ?? 'Dosen';
            $mkNama = $bentrokDosen->mataKuliah?->nama_mk ?? 'Jadwal lain';

            return "Dosen '{$dosenNama}' sudah memiliki jadwal mengajar '{$mkNama}' pada hari {$hari} jam tersebut.";
        }

        return null;
    }
}
