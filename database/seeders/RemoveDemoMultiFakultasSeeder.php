<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\JadwalPraktikum;
use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\PengajuanPerubahan;
use App\Models\ProgramStudi;
use App\Models\RuangPraktikum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RemoveDemoMultiFakultasSeeder extends Seeder
{
    public function run(): void
    {
        $dummyKodeRuang = [
            'LAB-FITK-01',
            'LAB-FITK-02',
            'LAB-FASYA-01',
            'LAB-FASYA-02',
            'LAB-FDKI-01',
            'LAB-FDKI-02',
        ];

        $dummyNidn = [
            'FITK-D01',
            'FITK-D02',
            'TUTOR-FITK-01',
            'FASYA-D01',
            'FASYA-D02',
            'TUTOR-FASYA-01',
            'FDKI-D01',
            'FDKI-D02',
            'TUTOR-FDKI-01',
        ];

        $dummyEmails = [
            'farhan.hidayat@uinssc.ac.id',
            'nisaul.m@uinssc.ac.id',
            'rizky.tutorfitk@gmail.com',
            'mahrus.ali@uinssc.ac.id',
            'zaki.mubarak@uinssc.ac.id',
            'syarif.falak@gmail.com',
            'rahmat.subarkah@uinssc.ac.id',
            'dewi.anggraeni@uinssc.ac.id',
            'bagas.broadcast@gmail.com',
        ];

        $dummyKodeMk = [
            'IF-301',
            'IF-502',
            'PTI-301',
            'PAI-503',
            'HKI-501',
            'HKI-502',
            'HES-501',
            'HTN-501',
            'KPI-301',
            'KPI-502',
            'PMI-301',
            'BKI-501',
        ];

        $dummyNamaKelas = [
            'IF-3A',
            'IF-3B',
            'IF-5A',
            'PTI-3A',
            'PAI-5A',
            'PAI-5B',
            'HKI-5A',
            'HKI-5B',
            'HES-5A',
            'HTN-5A',
            'KPI-3A',
            'KPI-5A',
            'PMI-3A',
            'BKI-5A',
            'BKI-5B',
        ];

        DB::transaction(function () use ($dummyKodeRuang, $dummyNidn, $dummyEmails, $dummyKodeMk, $dummyNamaKelas): void {
            $targetFakultasIds = Fakultas::whereIn('kode_fakultas', ['FITK', 'FASYA', 'FDKI'])->pluck('id');
            $targetProdiIds = ProgramStudi::whereIn('fakultas_id', $targetFakultasIds)->pluck('id');

            $ruangIds = RuangPraktikum::withTrashed()
                ->whereIn('kode_ruang', $dummyKodeRuang)
                ->pluck('id');

            $dosenIds = Dosen::withTrashed()
                ->whereIn('nidn', $dummyNidn)
                ->pluck('id');

            $userIds = User::whereIn('email', $dummyEmails)->pluck('id');

            $mkIds = MataKuliah::withTrashed()
                ->whereIn('kode_mk', $dummyKodeMk)
                ->pluck('id');

            $kelasIds = Kelas::withTrashed()
                ->whereIn('program_studi_id', $targetProdiIds)
                ->whereIn('nama_kelas', $dummyNamaKelas)
                ->pluck('id');

            // Tangkap semua jadwal dummy (termasuk yang mungkin dimodifikasi saat demo video)
            $jadwalIds = JadwalPraktikum::withTrashed()
                ->where(function ($q) use ($ruangIds, $dosenIds, $mkIds, $kelasIds): void {
                    $q->whereIn('ruang_praktikum_id', $ruangIds)
                        ->orWhereIn('dosen_id', $dosenIds)
                        ->orWhereIn('mata_kuliah_id', $mkIds)
                        ->orWhereIn('kelas_id', $kelasIds);
                })
                ->pluck('id');

            // 1. Hapus Pengajuan Perubahan terkait data dummy
            $pengajuanIds = PengajuanPerubahan::whereIn('jadwal_praktikum_id', $jadwalIds)
                ->orWhereIn('dosen_id', $dosenIds)
                ->pluck('id');

            PengajuanPerubahan::whereIn('id', $pengajuanIds)->delete();

            // 2. Hapus notifikasi database untuk user dosen/tutor dummy
            if ($userIds->isNotEmpty()) {
                DB::table('notifications')
                    ->where('notifiable_type', User::class)
                    ->whereIn('notifiable_id', $userIds)
                    ->delete();
            }

            // 3. Bersihkan log aktivitas yang dibuat atas objek dummy
            DB::table('activity_log')
                ->where(function ($q) use ($pengajuanIds, $jadwalIds, $ruangIds, $dosenIds, $mkIds, $kelasIds, $userIds): void {
                    if ($pengajuanIds->isNotEmpty()) {
                        $q->orWhere(function ($sub) use ($pengajuanIds): void {
                            $sub->where('subject_type', PengajuanPerubahan::class)->whereIn('subject_id', $pengajuanIds);
                        });
                    }
                    if ($jadwalIds->isNotEmpty()) {
                        $q->orWhere(function ($sub) use ($jadwalIds): void {
                            $sub->where('subject_type', JadwalPraktikum::class)->whereIn('subject_id', $jadwalIds);
                        });
                    }
                    if ($ruangIds->isNotEmpty()) {
                        $q->orWhere(function ($sub) use ($ruangIds): void {
                            $sub->where('subject_type', RuangPraktikum::class)->whereIn('subject_id', $ruangIds);
                        });
                    }
                    if ($dosenIds->isNotEmpty()) {
                        $q->orWhere(function ($sub) use ($dosenIds): void {
                            $sub->where('subject_type', Dosen::class)->whereIn('subject_id', $dosenIds);
                        });
                    }
                    if ($mkIds->isNotEmpty()) {
                        $q->orWhere(function ($sub) use ($mkIds): void {
                            $sub->where('subject_type', MataKuliah::class)->whereIn('subject_id', $mkIds);
                        });
                    }
                    if ($kelasIds->isNotEmpty()) {
                        $q->orWhere(function ($sub) use ($kelasIds): void {
                            $sub->where('subject_type', Kelas::class)->whereIn('subject_id', $kelasIds);
                        });
                    }
                    if ($userIds->isNotEmpty()) {
                        $q->orWhere(function ($sub) use ($userIds): void {
                            $sub->where('causer_type', User::class)->whereIn('causer_id', $userIds);
                        });
                    }
                })
                ->delete();

            // 4. Force delete semua jadwal dummy
            if ($jadwalIds->isNotEmpty()) {
                JadwalPraktikum::withTrashed()->whereIn('id', $jadwalIds)->forceDelete();
            }

            // 5. Force delete kelas & mata kuliah dummy
            if ($kelasIds->isNotEmpty()) {
                Kelas::withTrashed()->whereIn('id', $kelasIds)->forceDelete();
            }

            if ($mkIds->isNotEmpty()) {
                MataKuliah::withTrashed()->whereIn('id', $mkIds)->forceDelete();
            }

            // 6. Force delete ruang dummy
            foreach (RuangPraktikum::withTrashed()->whereIn('id', $ruangIds)->get() as $ruang) {
                $ruang->prodiPengguna()->detach();
                $ruang->forceDelete();
            }

            // 7. Force delete dosen/tutor dummy
            if ($dosenIds->isNotEmpty()) {
                Dosen::withTrashed()->whereIn('id', $dosenIds)->forceDelete();
            }

            // 8. Hapus akun User dosen/tutor dummy (operator/admin/FUA/FEBI tetap aman)
            foreach (User::whereIn('id', $userIds)->get() as $user) {
                $user->syncRoles([]);
                $user->delete();
            }
        });

        $this->command->info('Seluruh data dummy FITK, FASYA, dan FDKI berhasil dihapus permanen tanpa menyentuh data asli FUA, FEBI, maupun akun Operator/Admin!');
    }
}
