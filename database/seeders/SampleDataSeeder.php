<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\JadwalPraktikum;
use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use App\Models\RuangPraktikum;
use App\Models\TahunAkademik;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SampleDataSeeder extends Seeder
{
    /**
     * Seed sample data untuk semua tabel master dan buat user accounts.
     */
    public function run(): void
    {
        // =========================================================
        // 1. USERS & ROLES
        // =========================================================
        // Pastikan roles ada
        $roles = ['super-admin', 'operator', 'dosen'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@praktikum.ac.id'],
            [
                'name' => 'Dr. Ahmad Fauzi, M.T.',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        if (!$superAdmin->hasRole('super-admin')) {
            $superAdmin->assignRole('super-admin');
        }

        // Operator / Laboran
        $operator = User::firstOrCreate(
            ['email' => 'operator@praktikum.ac.id'],
            [
                'name' => 'Siti Nurhaliza, S.Kom.',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        if (!$operator->hasRole('operator')) {
            $operator->assignRole('operator');
        }

        // Dosen users (akan di-link ke tabel dosen)
        $dosenUsers = [];
        $dosenData = [
            ['name' => 'Prof. Dr. Budi Santoso, M.Cs.', 'email' => 'budi.santoso@praktikum.ac.id'],
            ['name' => 'Dr. Ratna Dewi, M.Kom.', 'email' => 'ratna.dewi@praktikum.ac.id'],
            ['name' => 'Ir. Hendra Wijaya, M.T.', 'email' => 'hendra.wijaya@praktikum.ac.id'],
            ['name' => 'Dr. Lina Mariani, M.Si.', 'email' => 'lina.mariani@praktikum.ac.id'],
            ['name' => 'Andi Prasetyo, S.T., M.Eng.', 'email' => 'andi.prasetyo@praktikum.ac.id'],
            ['name' => 'Dr. Fajar Rahman, M.Kom.', 'email' => 'fajar.rahman@praktikum.ac.id'],
            ['name' => 'Dra. Maya Sari, M.Pd.', 'email' => 'maya.sari@praktikum.ac.id'],
            ['name' => 'Dr. Rizki Amelia, S.Kom., M.T.', 'email' => 'rizki.amelia@praktikum.ac.id'],
        ];

        foreach ($dosenData as $dd) {
            $user = User::firstOrCreate(
                ['email' => $dd['email']],
                [
                    'name' => $dd['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
            if (!$user->hasRole('dosen')) {
                $user->assignRole('dosen');
            }
            $dosenUsers[] = $user;
        }

        // =========================================================
        // 2. FAKULTAS
        // =========================================================
        $fakultasData = [
            ['kode_fakultas' => 'FTIK', 'nama_fakultas' => 'Fakultas Tarbiyah dan Ilmu Keguruan'],
            ['kode_fakultas' => 'FEBI', 'nama_fakultas' => 'Fakultas Ekonomi dan Bisnis Islam'],
            ['kode_fakultas' => 'FSH', 'nama_fakultas' => 'Fakultas Syariah dan Hukum'],
            ['kode_fakultas' => 'FUAD', 'nama_fakultas' => 'Fakultas Ushuluddin dan Adab'],
            ['kode_fakultas' => 'FDIK', 'nama_fakultas' => 'Fakultas Dakwah dan Ilmu Komunikasi'],
        ];

        $fakultasModels = [];
        foreach ($fakultasData as $fd) {
            $fakultasModels[$fd['kode_fakultas']] = Fakultas::firstOrCreate(
                ['kode_fakultas' => $fd['kode_fakultas']],
                ['nama_fakultas' => $fd['nama_fakultas']]
            );
        }

        // =========================================================
        // 3. PROGRAM STUDI
        // =========================================================
        $prodiData = [
            // FTIK
            ['fakultas' => 'FTIK', 'kode' => 'IF', 'nama' => 'Informatika'],
            ['fakultas' => 'FTIK', 'kode' => 'SI', 'nama' => 'Sistem Informasi'],
            ['fakultas' => 'FTIK', 'kode' => 'PTI', 'nama' => 'Pendidikan Teknologi Informasi'],
            ['fakultas' => 'FTIK', 'kode' => 'PAI', 'nama' => 'Pendidikan Agama Islam'],
            ['fakultas' => 'FTIK', 'kode' => 'PGMI', 'nama' => 'Pendidikan Guru Madrasah Ibtidaiyah'],
            // FEBI
            ['fakultas' => 'FEBI', 'kode' => 'ES', 'nama' => 'Ekonomi Syariah'],
            ['fakultas' => 'FEBI', 'kode' => 'PBS', 'nama' => 'Perbankan Syariah'],
            ['fakultas' => 'FEBI', 'kode' => 'AKS', 'nama' => 'Akuntansi Syariah'],
            // FSH
            ['fakultas' => 'FSH', 'kode' => 'HKI', 'nama' => 'Hukum Keluarga Islam'],
            ['fakultas' => 'FSH', 'kode' => 'HES', 'nama' => 'Hukum Ekonomi Syariah'],
            // FUAD
            ['fakultas' => 'FUAD', 'kode' => 'IAT', 'nama' => 'Ilmu Al-Quran dan Tafsir'],
            ['fakultas' => 'FUAD', 'kode' => 'SAA', 'nama' => 'Sastra Arab'],
            // FDIK
            ['fakultas' => 'FDIK', 'kode' => 'KPI', 'nama' => 'Komunikasi dan Penyiaran Islam'],
            ['fakultas' => 'FDIK', 'kode' => 'BKI', 'nama' => 'Bimbingan Konseling Islam'],
        ];

        $prodiModels = [];
        foreach ($prodiData as $pd) {
            $prodiModels[$pd['kode']] = ProgramStudi::firstOrCreate(
                ['kode_program_studi' => $pd['kode']],
                [
                    'fakultas_id' => $fakultasModels[$pd['fakultas']]->id,
                    'nama_program_studi' => $pd['nama'],
                ]
            );
        }

        // =========================================================
        // 4. KELAS
        // =========================================================
        $kelasData = [
            // IF
            ['prodi' => 'IF', 'nama' => 'IF-1A', 'semester' => 1],
            ['prodi' => 'IF', 'nama' => 'IF-1B', 'semester' => 1],
            ['prodi' => 'IF', 'nama' => 'IF-3A', 'semester' => 3],
            ['prodi' => 'IF', 'nama' => 'IF-3B', 'semester' => 3],
            ['prodi' => 'IF', 'nama' => 'IF-5A', 'semester' => 5],
            ['prodi' => 'IF', 'nama' => 'IF-5B', 'semester' => 5],
            ['prodi' => 'IF', 'nama' => 'IF-7A', 'semester' => 7],
            ['prodi' => 'IF', 'nama' => 'IF-7B', 'semester' => 7],
            // SI
            ['prodi' => 'SI', 'nama' => 'SI-1A', 'semester' => 1],
            ['prodi' => 'SI', 'nama' => 'SI-3A', 'semester' => 3],
            ['prodi' => 'SI', 'nama' => 'SI-5A', 'semester' => 5],
            ['prodi' => 'SI', 'nama' => 'SI-7A', 'semester' => 7],
            // PTI
            ['prodi' => 'PTI', 'nama' => 'PTI-1A', 'semester' => 1],
            ['prodi' => 'PTI', 'nama' => 'PTI-3A', 'semester' => 3],
            ['prodi' => 'PTI', 'nama' => 'PTI-5A', 'semester' => 5],
            // ES
            ['prodi' => 'ES', 'nama' => 'ES-1A', 'semester' => 1],
            ['prodi' => 'ES', 'nama' => 'ES-3A', 'semester' => 3],
        ];

        $kelasModels = [];
        foreach ($kelasData as $kd) {
            $kelasModels[$kd['nama']] = Kelas::firstOrCreate(
                ['nama_kelas' => $kd['nama']],
                [
                    'program_studi_id' => $prodiModels[$kd['prodi']]->id,
                    'semester' => $kd['semester'],
                ]
            );
        }

        // =========================================================
        // 5. MATA KULIAH
        // =========================================================
        $mkData = [
            // IF
            ['prodi' => 'IF', 'kode' => 'IF101', 'nama' => 'Algoritma dan Pemrograman', 'sks' => 3, 'semester' => 1],
            ['prodi' => 'IF', 'kode' => 'IF102', 'nama' => 'Pengantar Teknologi Informasi', 'sks' => 2, 'semester' => 1],
            ['prodi' => 'IF', 'kode' => 'IF201', 'nama' => 'Struktur Data', 'sks' => 3, 'semester' => 3],
            ['prodi' => 'IF', 'kode' => 'IF202', 'nama' => 'Basis Data', 'sks' => 3, 'semester' => 3],
            ['prodi' => 'IF', 'kode' => 'IF203', 'nama' => 'Pemrograman Web', 'sks' => 3, 'semester' => 3],
            ['prodi' => 'IF', 'kode' => 'IF301', 'nama' => 'Jaringan Komputer', 'sks' => 3, 'semester' => 5],
            ['prodi' => 'IF', 'kode' => 'IF302', 'nama' => 'Pemrograman Berorientasi Objek', 'sks' => 3, 'semester' => 5],
            ['prodi' => 'IF', 'kode' => 'IF303', 'nama' => 'Rekayasa Perangkat Lunak', 'sks' => 3, 'semester' => 5],
            ['prodi' => 'IF', 'kode' => 'IF401', 'nama' => 'Kecerdasan Buatan', 'sks' => 3, 'semester' => 7],
            ['prodi' => 'IF', 'kode' => 'IF402', 'nama' => 'Keamanan Sistem Informasi', 'sks' => 3, 'semester' => 7],
            // SI
            ['prodi' => 'SI', 'kode' => 'SI101', 'nama' => 'Dasar-dasar Sistem Informasi', 'sks' => 3, 'semester' => 1],
            ['prodi' => 'SI', 'kode' => 'SI201', 'nama' => 'Analisis dan Perancangan Sistem', 'sks' => 3, 'semester' => 3],
            ['prodi' => 'SI', 'kode' => 'SI301', 'nama' => 'Manajemen Proyek TI', 'sks' => 3, 'semester' => 5],
            ['prodi' => 'SI', 'kode' => 'SI401', 'nama' => 'Enterprise Resource Planning', 'sks' => 3, 'semester' => 7],
            // PTI
            ['prodi' => 'PTI', 'kode' => 'PTI101', 'nama' => 'Media Pembelajaran Digital', 'sks' => 2, 'semester' => 1],
            ['prodi' => 'PTI', 'kode' => 'PTI201', 'nama' => 'Desain Grafis', 'sks' => 3, 'semester' => 3],
            ['prodi' => 'PTI', 'kode' => 'PTI301', 'nama' => 'Multimedia Interaktif', 'sks' => 3, 'semester' => 5],
            // ES
            ['prodi' => 'ES', 'kode' => 'ES101', 'nama' => 'Pengantar Ekonomi Islam', 'sks' => 2, 'semester' => 1],
            ['prodi' => 'ES', 'kode' => 'ES201', 'nama' => 'Akuntansi Dasar', 'sks' => 3, 'semester' => 3],
        ];

        $mkModels = [];
        foreach ($mkData as $mk) {
            $mkModels[$mk['kode']] = MataKuliah::firstOrCreate(
                ['kode_mk' => $mk['kode']],
                [
                    'program_studi_id' => $prodiModels[$mk['prodi']]->id,
                    'nama_mk' => $mk['nama'],
                    'sks' => $mk['sks'],
                    'semester' => $mk['semester'],
                ]
            );
        }

        // =========================================================
        // 6. DOSEN (tabel dosen, link ke user)
        // =========================================================
        $dosenTableData = [
            ['nidn' => '0501198501', 'nama' => 'Prof. Dr. Budi Santoso, M.Cs.', 'email' => 'budi.santoso@praktikum.ac.id', 'user_index' => 0],
            ['nidn' => '0612199002', 'nama' => 'Dr. Ratna Dewi, M.Kom.', 'email' => 'ratna.dewi@praktikum.ac.id', 'user_index' => 1],
            ['nidn' => '0723198803', 'nama' => 'Ir. Hendra Wijaya, M.T.', 'email' => 'hendra.wijaya@praktikum.ac.id', 'user_index' => 2],
            ['nidn' => '0834199204', 'nama' => 'Dr. Lina Mariani, M.Si.', 'email' => 'lina.mariani@praktikum.ac.id', 'user_index' => 3],
            ['nidn' => '0945198705', 'nama' => 'Andi Prasetyo, S.T., M.Eng.', 'email' => 'andi.prasetyo@praktikum.ac.id', 'user_index' => 4],
            ['nidn' => '1056199306', 'nama' => 'Dr. Fajar Rahman, M.Kom.', 'email' => 'fajar.rahman@praktikum.ac.id', 'user_index' => 5],
            ['nidn' => '1167198607', 'nama' => 'Dra. Maya Sari, M.Pd.', 'email' => 'maya.sari@praktikum.ac.id', 'user_index' => 6],
            ['nidn' => '1278199108', 'nama' => 'Dr. Rizki Amelia, S.Kom., M.T.', 'email' => 'rizki.amelia@praktikum.ac.id', 'user_index' => 7],
        ];

        $dosenModels = [];
        foreach ($dosenTableData as $dt) {
            $dosenModels[$dt['nidn']] = Dosen::firstOrCreate(
                ['nidn' => $dt['nidn']],
                [
                    'nama' => $dt['nama'],
                    'email' => $dt['email'],
                    'user_id' => $dosenUsers[$dt['user_index']]->id,
                ]
            );
        }
        $dosenArray = array_values($dosenModels);

        // =========================================================
        // 7. RUANG PRAKTIKUM
        // =========================================================
        $ruangData = [
            ['kode' => 'LAB-01', 'nama' => 'Lab Komputer 1', 'lokasi' => 'Gedung A Lt. 1', 'kapasitas' => 40, 'keterangan' => 'Lab utama dengan AC dan proyektor'],
            ['kode' => 'LAB-02', 'nama' => 'Lab Komputer 2', 'lokasi' => 'Gedung A Lt. 1', 'kapasitas' => 35, 'keterangan' => 'Lab dengan spesifikasi tinggi untuk desain grafis'],
            ['kode' => 'LAB-03', 'nama' => 'Lab Komputer 3', 'lokasi' => 'Gedung A Lt. 2', 'kapasitas' => 30, 'keterangan' => 'Lab untuk jaringan komputer'],
            ['kode' => 'LAB-04', 'nama' => 'Lab Multimedia', 'lokasi' => 'Gedung B Lt. 1', 'kapasitas' => 25, 'keterangan' => 'Lab multimedia dengan peralatan audio-visual'],
            ['kode' => 'LAB-05', 'nama' => 'Lab Jaringan', 'lokasi' => 'Gedung B Lt. 2', 'kapasitas' => 20, 'keterangan' => 'Lab khusus praktek jaringan dan IoT'],
            ['kode' => 'LAB-06', 'nama' => 'Lab Server', 'lokasi' => 'Gedung A Lt. 3', 'kapasitas' => 15, 'keterangan' => 'Lab server dan cloud computing'],
        ];

        $ruangModels = [];
        foreach ($ruangData as $rd) {
            $ruangModels[$rd['kode']] = RuangPraktikum::firstOrCreate(
                ['kode_ruang' => $rd['kode']],
                [
                    'nama_ruang' => $rd['nama'],
                    'lokasi' => $rd['lokasi'],
                    'kapasitas' => $rd['kapasitas'],
                    'keterangan' => $rd['keterangan'],
                ]
            );
        }
        $ruangArray = array_values($ruangModels);

        // =========================================================
        // 8. TAHUN AKADEMIK
        // =========================================================
        $taData = [
            ['nama' => '2025/2026', 'semester' => 'Ganjil', 'tahun_mulai' => 2025, 'tahun_selesai' => 2026, 'is_aktif' => false],
            ['nama' => '2025/2026', 'semester' => 'Genap', 'tahun_mulai' => 2025, 'tahun_selesai' => 2026, 'is_aktif' => false],
            ['nama' => '2026/2027', 'semester' => 'Ganjil', 'tahun_mulai' => 2026, 'tahun_selesai' => 2027, 'is_aktif' => true],
        ];

        $taModels = [];
        foreach ($taData as $ta) {
            $taModels[] = TahunAkademik::firstOrCreate(
                ['nama' => $ta['nama'], 'semester' => $ta['semester']],
                [
                    'tahun_mulai' => $ta['tahun_mulai'],
                    'tahun_selesai' => $ta['tahun_selesai'],
                    'is_aktif' => $ta['is_aktif'],
                ]
            );
        }
        // Ambil tahun akademik aktif
        $taAktif = TahunAkademik::where('is_aktif', true)->first();

        // =========================================================
        // 9. JADWAL PRAKTIKUM
        // =========================================================
        if ($taAktif && JadwalPraktikum::count() === 0) {
            $jadwalData = [
                // Senin
                ['mk' => 'IF101', 'kelas' => 'IF-1A', 'dosen_idx' => 0, 'ruang_idx' => 0, 'hari' => 'Senin', 'mulai' => '08:00', 'selesai' => '10:30'],
                ['mk' => 'IF201', 'kelas' => 'IF-3A', 'dosen_idx' => 1, 'ruang_idx' => 1, 'hari' => 'Senin', 'mulai' => '08:00', 'selesai' => '10:30'],
                ['mk' => 'IF301', 'kelas' => 'IF-5A', 'dosen_idx' => 2, 'ruang_idx' => 2, 'hari' => 'Senin', 'mulai' => '10:30', 'selesai' => '13:00'],
                ['mk' => 'SI101', 'kelas' => 'SI-1A', 'dosen_idx' => 3, 'ruang_idx' => 0, 'hari' => 'Senin', 'mulai' => '13:00', 'selesai' => '15:30'],
                // Selasa
                ['mk' => 'IF102', 'kelas' => 'IF-1B', 'dosen_idx' => 4, 'ruang_idx' => 0, 'hari' => 'Selasa', 'mulai' => '08:00', 'selesai' => '10:00'],
                ['mk' => 'IF202', 'kelas' => 'IF-3B', 'dosen_idx' => 5, 'ruang_idx' => 1, 'hari' => 'Selasa', 'mulai' => '08:00', 'selesai' => '10:30'],
                ['mk' => 'IF302', 'kelas' => 'IF-5B', 'dosen_idx' => 0, 'ruang_idx' => 2, 'hari' => 'Selasa', 'mulai' => '10:30', 'selesai' => '13:00'],
                ['mk' => 'PTI101', 'kelas' => 'PTI-1A', 'dosen_idx' => 6, 'ruang_idx' => 3, 'hari' => 'Selasa', 'mulai' => '13:00', 'selesai' => '15:00'],
                // Rabu
                ['mk' => 'IF203', 'kelas' => 'IF-3A', 'dosen_idx' => 1, 'ruang_idx' => 0, 'hari' => 'Rabu', 'mulai' => '08:00', 'selesai' => '10:30'],
                ['mk' => 'IF303', 'kelas' => 'IF-5A', 'dosen_idx' => 2, 'ruang_idx' => 1, 'hari' => 'Rabu', 'mulai' => '08:00', 'selesai' => '10:30'],
                ['mk' => 'SI201', 'kelas' => 'SI-3A', 'dosen_idx' => 3, 'ruang_idx' => 2, 'hari' => 'Rabu', 'mulai' => '10:30', 'selesai' => '13:00'],
                ['mk' => 'ES101', 'kelas' => 'ES-1A', 'dosen_idx' => 7, 'ruang_idx' => 0, 'hari' => 'Rabu', 'mulai' => '13:00', 'selesai' => '15:00'],
                // Kamis
                ['mk' => 'IF401', 'kelas' => 'IF-7A', 'dosen_idx' => 5, 'ruang_idx' => 0, 'hari' => 'Kamis', 'mulai' => '08:00', 'selesai' => '10:30'],
                ['mk' => 'IF402', 'kelas' => 'IF-7B', 'dosen_idx' => 0, 'ruang_idx' => 1, 'hari' => 'Kamis', 'mulai' => '08:00', 'selesai' => '10:30'],
                ['mk' => 'SI301', 'kelas' => 'SI-5A', 'dosen_idx' => 4, 'ruang_idx' => 2, 'hari' => 'Kamis', 'mulai' => '10:30', 'selesai' => '13:00'],
                ['mk' => 'PTI201', 'kelas' => 'PTI-3A', 'dosen_idx' => 6, 'ruang_idx' => 3, 'hari' => 'Kamis', 'mulai' => '13:00', 'selesai' => '15:30'],
                // Jumat
                ['mk' => 'IF101', 'kelas' => 'IF-1B', 'dosen_idx' => 0, 'ruang_idx' => 0, 'hari' => 'Jumat', 'mulai' => '08:00', 'selesai' => '10:30'],
                ['mk' => 'SI401', 'kelas' => 'SI-7A', 'dosen_idx' => 7, 'ruang_idx' => 1, 'hari' => 'Jumat', 'mulai' => '08:00', 'selesai' => '10:30'],
                ['mk' => 'ES201', 'kelas' => 'ES-3A', 'dosen_idx' => 3, 'ruang_idx' => 2, 'hari' => 'Jumat', 'mulai' => '10:30', 'selesai' => '13:00'],
                ['mk' => 'PTI301', 'kelas' => 'PTI-5A', 'dosen_idx' => 6, 'ruang_idx' => 3, 'hari' => 'Jumat', 'mulai' => '13:00', 'selesai' => '15:30'],
                // Sabtu
                ['mk' => 'IF201', 'kelas' => 'IF-3B', 'dosen_idx' => 1, 'ruang_idx' => 0, 'hari' => 'Sabtu', 'mulai' => '08:00', 'selesai' => '10:30'],
                ['mk' => 'IF301', 'kelas' => 'IF-5B', 'dosen_idx' => 2, 'ruang_idx' => 1, 'hari' => 'Sabtu', 'mulai' => '08:00', 'selesai' => '10:30'],
            ];

            foreach ($jadwalData as $jd) {
                JadwalPraktikum::create([
                    'mata_kuliah_id' => $mkModels[$jd['mk']]->id,
                    'kelas_id' => $kelasModels[$jd['kelas']]->id,
                    'dosen_id' => $dosenArray[$jd['dosen_idx']]->id,
                    'ruang_praktikum_id' => $ruangArray[$jd['ruang_idx']]->id,
                    'tahun_akademik_id' => $taAktif->id,
                    'hari' => $jd['hari'],
                    'jam_mulai' => $jd['mulai'],
                    'jam_selesai' => $jd['selesai'],
                    'status' => 'aktif',
                ]);
            }
        }

        echo "✅ Sample data seeded successfully!\n";
        echo "   - " . User::count() . " users\n";
        echo "   - " . Fakultas::count() . " fakultas\n";
        echo "   - " . ProgramStudi::count() . " program studi\n";
        echo "   - " . Kelas::count() . " kelas\n";
        echo "   - " . MataKuliah::count() . " mata kuliah\n";
        echo "   - " . Dosen::count() . " dosen\n";
        echo "   - " . RuangPraktikum::count() . " ruang praktikum\n";
        echo "   - " . TahunAkademik::count() . " tahun akademik\n";
        echo "   - " . JadwalPraktikum::count() . " jadwal praktikum\n";
    }
}
