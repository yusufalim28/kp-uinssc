<?php

use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\JadwalPraktikum;
use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use App\Models\RuangPraktikum;
use App\Models\TahunAkademik;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Spatie\Activitylog\Models\Activity;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * Pembersihan otomatis riwayat log aktivitas dan tempat sampah yang lebih dari 30 hari (1 bulan).
 */
Artisan::command('app:cleanup-logs-and-trash {--days=30 : Batas hari penyimpanan sebelum dihapus}', function () {
    $days = (int) $this->option('days') ?: 30;
    $cutoff = now()->subDays($days);

    // 1. Bersihkan Activity Log > 30 hari
    $logCount = Activity::where('created_at', '<', $cutoff)->delete();
    $this->info("Berhasil membersihkan {$logCount} log aktivitas lebih lama dari {$days} hari.");

    // 2. Bersihkan Soft Deleted data (Sampah) > 30 hari
    $models = [
        'Fakultas' => Fakultas::class,
        'Program Studi' => ProgramStudi::class,
        'Kelas' => Kelas::class,
        'Mata Kuliah' => MataKuliah::class,
        'Dosen' => Dosen::class,
        'Ruang Praktikum' => RuangPraktikum::class,
        'Tahun Akademik' => TahunAkademik::class,
        'Jadwal Praktikum' => JadwalPraktikum::class,
    ];

    $totalTrash = 0;
    foreach ($models as $name => $class) {
        $count = $class::onlyTrashed()->where('deleted_at', '<', $cutoff)->forceDelete();
        if ($count > 0) {
            $this->line("- {$name}: {$count} data dihapus permanen.");
            $totalTrash += $count;
        }
    }

    $this->info("Total {$totalTrash} data sampah lawas (> {$days} hari) berhasil dibersihkan permanen.");
})->purpose('Bersihkan riwayat log aktivitas dan soft deletes tempat sampah yang lebih dari 30 hari');

// Jadwalkan otomatis dijalankan setiap hari
Schedule::command('app:cleanup-logs-and-trash --days=30')->daily()->at('01:00');
