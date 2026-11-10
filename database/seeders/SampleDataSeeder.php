<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\ProgramStudi;
use App\Models\TahunAkademik;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SampleDataSeeder extends Seeder
{
    /**
     * Seed master data dasar (Fakultas, Prodi, Tahun Akademik, dan Akun Resmi).
     * Data operasional (jadwal, ruangan, kelas, mata kuliah) dibiarkan bersih untuk diisi data nyata.
     */
    public function run(): void
    {
        // =========================================================
        // 1. ROLES & PERMISSIONS
        // =========================================================
        $roles = ['super-admin', 'operator', 'dosen'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // =========================================================
        // 2. FAKULTAS RESMI
        // =========================================================
        $fakultasData = [
            ['kode' => 'FITK',  'nama' => 'Fakultas Ilmu Tarbiyah dan Keguruan'],
            ['kode' => 'FEBI',  'nama' => 'Fakultas Ekonomi dan Bisnis Islam'],
            ['kode' => 'FASYA', 'nama' => 'Fakultas Syariah'],
            ['kode' => 'FUA',   'nama' => 'Fakultas Ushuluddin dan Adab'],
            ['kode' => 'FDKI',  'nama' => 'Fakultas Dakwah dan Komunikasi Islam'],
        ];

        $fakultasModels = [];
        foreach ($fakultasData as $f) {
            $fakultasModels[$f['kode']] = Fakultas::updateOrCreate(
                ['kode_fakultas' => $f['kode']],
                ['nama_fakultas' => $f['nama'], 'status' => 'aktif']
            );
        }

        // =========================================================
        // 3. PROGRAM STUDI TIAP FAKULTAS
        // =========================================================
        $prodiData = [
            // FITK (Fakultas Ilmu Tarbiyah dan Keguruan)
            ['fakultas' => 'FITK', 'kode' => 'IF',  'nama' => 'Informatika'],
            ['fakultas' => 'FITK', 'kode' => 'SI',  'nama' => 'Sistem Informasi'],
            ['fakultas' => 'FITK', 'kode' => 'PTI', 'nama' => 'Pendidikan Teknologi Informasi'],
            ['fakultas' => 'FITK', 'kode' => 'PAI', 'nama' => 'Pendidikan Agama Islam'],
            ['fakultas' => 'FITK', 'kode' => 'PBA', 'nama' => 'Pendidikan Bahasa Arab'],
            // FEBI (Fakultas Ekonomi dan Bisnis Islam)
            ['fakultas' => 'FEBI', 'kode' => 'PBS', 'nama' => 'Perbankan Syariah'],
            ['fakultas' => 'FEBI', 'kode' => 'AKS', 'nama' => 'Akuntansi Syariah'],
            ['fakultas' => 'FEBI', 'kode' => 'ES',  'nama' => 'Ekonomi Syariah'],
            // FASYA (Fakultas Syariah)
            ['fakultas' => 'FASYA', 'kode' => 'HKI', 'nama' => 'Hukum Keluarga Islam'],
            ['fakultas' => 'FASYA', 'kode' => 'HES', 'nama' => 'Hukum Ekonomi Syariah'],
            ['fakultas' => 'FASYA', 'kode' => 'HTN', 'nama' => 'Hukum Tata Negara'],
            // FUA (Fakultas Ushuluddin dan Adab)
            ['fakultas' => 'FUA',  'kode' => 'TP',  'nama' => 'Tasawuf dan Psikoterapi'],
            // FDKI (Fakultas Dakwah dan Komunikasi Islam)
            ['fakultas' => 'FDKI', 'kode' => 'KPI', 'nama' => 'Komunikasi dan Penyiaran Islam'],
            ['fakultas' => 'FDKI', 'kode' => 'BKI', 'nama' => 'Bimbingan dan Konseling Islam'],
            ['fakultas' => 'FDKI', 'kode' => 'PMI', 'nama' => 'Pengembangan Masyarakat Islam'],
        ];

        foreach ($prodiData as $pd) {
            ProgramStudi::updateOrCreate(
                ['kode_program_studi' => $pd['kode']],
                [
                    'fakultas_id' => $fakultasModels[$pd['fakultas']]->id,
                    'nama_program_studi' => $pd['nama'],
                    'status' => 'aktif',
                ]
            );
        }

        // =========================================================
        // 4. TAHUN AKADEMIK AKTIF
        // =========================================================
        TahunAkademik::updateOrCreate(
            ['nama' => '2026/2027', 'semester' => 'Ganjil'],
            [
                'is_aktif' => true,
                'tahun_mulai' => 2026,
                'tahun_selesai' => 2027,
            ]
        );

        // =========================================================
        // 5. AKUN USER UTAMA
        // =========================================================
        // Super Admin
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@praktikum.ac.id'],
            [
                'name' => 'Dr. Ahmad Fauzi, M.T.',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'fakultas_id' => null,
                'program_studi_id' => null,
            ]
        );
        $superAdmin->syncRoles(['super-admin']);

        // Akun Operator Per Fakultas (program_studi_id = null untuk cakupan fakultas menyeluruh)
        $operatorList = [
            [
                'email' => 'operator.fitk@praktikum.ac.id',
                'name' => 'Operator FITK',
                'fakultas' => 'FITK',
            ],
            [
                'email' => 'operator.febi@praktikum.ac.id',
                'name' => 'Operator FEBI',
                'fakultas' => 'FEBI',
            ],
            [
                'email' => 'operator.fasya@praktikum.ac.id',
                'name' => 'Operator FASYA',
                'fakultas' => 'FASYA',
            ],
            [
                'email' => 'operator.fua@praktikum.ac.id',
                'name' => 'Operator FUA',
                'fakultas' => 'FUA',
            ],
            [
                'email' => 'operator.fdki@praktikum.ac.id',
                'name' => 'Operator FDKI',
                'fakultas' => 'FDKI',
            ],
        ];

        foreach ($operatorList as $op) {
            $userOp = User::updateOrCreate(
                ['email' => $op['email']],
                [
                    'name' => $op['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'fakultas_id' => $fakultasModels[$op['fakultas']]->id,
                    'program_studi_id' => null,
                ]
            );
            $userOp->syncRoles(['operator']);
        }

        // =========================================================
        // 6. DOSEN PERCONTOHAN (1 AKUN UNTUK PENGUJIAN LOKAL)
        // =========================================================
        $dosenUser = User::updateOrCreate(
            ['email' => 'andi.prasetyo@praktikum.ac.id'],
            [
                'name' => 'Andi Prasetyo, S.T., M.Eng.',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'fakultas_id' => null,
                'program_studi_id' => null,
            ]
        );
        $dosenUser->syncRoles(['dosen']);

        Dosen::updateOrCreate(
            ['nidn' => '0945198705'],
            [
                'user_id' => $dosenUser->id,
                'nama' => 'Andi Prasetyo, S.T., M.Eng.',
                'email' => 'andi.prasetyo@praktikum.ac.id',
                'status' => 'aktif',
            ]
        );
    }
}
