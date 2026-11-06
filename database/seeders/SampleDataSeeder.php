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
     * Seed sample data untuk semua tabel master dan akun pengguna.
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
        // 2. FAKULTAS (5 FAKULTAS UNIVERSITAS)
        // =========================================================
        $fakultasData = [
            ['kode' => 'FTIK', 'nama' => 'Fakultas Tarbiyah dan Ilmu Keguruan'],
            ['kode' => 'FEBI', 'nama' => 'Fakultas Ekonomi dan Bisnis Islam'],
            ['kode' => 'FSH',  'nama' => 'Fakultas Syariah dan Hukum'],
            ['kode' => 'FUA',  'nama' => 'Fakultas Ushuluddin dan Adab'],
            ['kode' => 'FDIK', 'nama' => 'Fakultas Dakwah dan Ilmu Komunikasi'],
        ];

        $fakultasModels = [];
        foreach ($fakultasData as $f) {
            $fakultasModels[$f['kode']] = Fakultas::firstOrCreate(
                ['kode_fakultas' => $f['kode']],
                ['nama_fakultas' => $f['nama'], 'status' => 'aktif']
            );
        }

        // =========================================================
        // 3. PROGRAM STUDI LENGKAP TIAP FAKULTAS
        // =========================================================
        $prodiData = [
            // FTIK (Informatika & Pendidikan)
            ['fakultas' => 'FTIK', 'kode' => 'IF',  'nama' => 'Informatika'],
            ['fakultas' => 'FTIK', 'kode' => 'SI',  'nama' => 'Sistem Informasi'],
            ['fakultas' => 'FTIK', 'kode' => 'PTI', 'nama' => 'Pendidikan Teknologi Informasi'],
            ['fakultas' => 'FTIK', 'kode' => 'PAI', 'nama' => 'Pendidikan Agama Islam'],
            ['fakultas' => 'FTIK', 'kode' => 'PBA', 'nama' => 'Pendidikan Bahasa Arab'],
            // FEBI
            ['fakultas' => 'FEBI', 'kode' => 'PBS', 'nama' => 'Perbankan Syariah'],
            ['fakultas' => 'FEBI', 'kode' => 'AKS', 'nama' => 'Akuntansi Syariah'],
            ['fakultas' => 'FEBI', 'kode' => 'ES',  'nama' => 'Ekonomi Syariah'],
            // FSH
            ['fakultas' => 'FSH',  'kode' => 'HKI', 'nama' => 'Hukum Keluarga Islam'],
            ['fakultas' => 'FSH',  'kode' => 'HES', 'nama' => 'Hukum Ekonomi Syariah'],
            ['fakultas' => 'FSH',  'kode' => 'HTN', 'nama' => 'Hukum Tata Negara'],
            // FUA (Fakultas Ushuluddin dan Adab)
            ['fakultas' => 'FUA',  'kode' => 'TP',  'nama' => 'Tasawuf dan Psikoterapi'],
            // FDIK
            ['fakultas' => 'FDIK', 'kode' => 'KPI', 'nama' => 'Komunikasi dan Penyiaran Islam'],
            ['fakultas' => 'FDIK', 'kode' => 'BKI', 'nama' => 'Bimbingan dan Konseling Islam'],
            ['fakultas' => 'FDIK', 'kode' => 'PMI', 'nama' => 'Pengembangan Masyarakat Islam'],
        ];

        $prodiModels = [];
        foreach ($prodiData as $pd) {
            $prodiModels[$pd['kode']] = ProgramStudi::firstOrCreate(
                ['kode_program_studi' => $pd['kode']],
                [
                    'fakultas_id' => $fakultasModels[$pd['fakultas']]->id,
                    'nama_program_studi' => $pd['nama'],
                    'status' => 'aktif',
                ]
            );
        }

        // =========================================================
        // 4. AKUN USER & OPERATOR PER FAKULTAS (PASSWORD: password)
        // =========================================================
        // Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@praktikum.ac.id'],
            [
                'name' => 'Dr. Ahmad Fauzi, M.T.',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->syncRoles(['super-admin']);

        // Operator Per Fakultas
        $operatorList = [
            [
                'email' => 'operator.if@praktikum.ac.id',
                'name' => 'Operator FTIK & Informatika',
                'fakultas' => 'FTIK',
                'prodi' => 'IF',
            ],
            [
                'email' => 'operator.febi@praktikum.ac.id',
                'name' => 'Operator FEBI (Ekonomi & Bisnis)',
                'fakultas' => 'FEBI',
                'prodi' => 'PBS',
            ],
            [
                'email' => 'operator.hukum@praktikum.ac.id',
                'name' => 'Operator FSH (Syariah & Hukum)',
                'fakultas' => 'FSH',
                'prodi' => 'HKI',
            ],
            [
                'email' => 'operator.fua@praktikum.ac.id',
                'name' => 'Operator FUA (Ushuluddin & Adab)',
                'fakultas' => 'FUA',
                'prodi' => 'TP',
            ],
            [
                'email' => 'operator.fdik@praktikum.ac.id',
                'name' => 'Operator FDIK (Dakwah & Komunikasi)',
                'fakultas' => 'FDIK',
                'prodi' => 'KPI',
            ],
        ];

        foreach ($operatorList as $op) {
            $userOp = User::firstOrCreate(
                ['email' => $op['email']],
                [
                    'name' => $op['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'fakultas_id' => $fakultasModels[$op['fakultas']]->id,
                    'program_studi_id' => $prodiModels[$op['prodi']]->id,
                ]
            );
            $userOp->syncRoles(['operator']);
        }

        // Fallback akun operator umum
        $operatorGeneric = User::firstOrCreate(
            ['email' => 'operator@praktikum.ac.id'],
            [
                'name' => 'Siti Nurhaliza, S.Kom.',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'fakultas_id' => $fakultasModels['FTIK']->id,
                'program_studi_id' => $prodiModels['IF']->id,
            ]
        );
        $operatorGeneric->syncRoles(['operator']);

        // =========================================================
        // 5. DOSEN PENGAMPU (Dosen Informatika & Fakultas Lain)
        // =========================================================
        $dosenList = [
            // Dosen Informatika (Diperbanyak)
            ['nidn' => '0501198501', 'nama' => 'Prof. Dr. Budi Santoso, M.Cs.', 'email' => 'budi.santoso@praktikum.ac.id'],
            ['nidn' => '0612199002', 'nama' => 'Dr. Ratna Dewi, M.Kom.', 'email' => 'ratna.dewi@praktikum.ac.id'],
            ['nidn' => '0723198803', 'nama' => 'Ir. Hendra Wijaya, M.T.', 'email' => 'hendra.wijaya@praktikum.ac.id'],
            ['nidn' => '1056199306', 'nama' => 'Dr. Fajar Rahman, M.Kom.', 'email' => 'fajar.rahman@praktikum.ac.id'],
            ['nidn' => '0945198705', 'nama' => 'Andi Prasetyo, S.T., M.Eng.', 'email' => 'andi.prasetyo@praktikum.ac.id'],
            ['nidn' => '1278199108', 'nama' => 'Dr. Rizki Amelia, S.Kom., M.T.', 'email' => 'rizki.amelia@praktikum.ac.id'],
            ['nidn' => '1389199409', 'nama' => 'Yusuf Alim, S.Kom., M.Kom.', 'email' => 'yusuf.alim@praktikum.ac.id'],
            ['nidn' => '1490199510', 'nama' => 'Annisa Rahmawati, S.Kom., M.T.', 'email' => 'annisa.rahmawati@praktikum.ac.id'],
            // Dosen Fakultas Lain
            ['nidn' => '0834199204', 'nama' => 'Dr. H. Muhsinin, M.Ag.', 'email' => 'muhsinin@praktikum.ac.id'], // FSH
            ['nidn' => '1167198607', 'nama' => 'Siti Aminah, S.E., M.Si.', 'email' => 'siti.aminah@praktikum.ac.id'], // FEBI
            ['nidn' => '1612199212', 'nama' => 'Nurul Hidayah, S.Sos., M.I.Kom.', 'email' => 'nurul.hidayah@praktikum.ac.id'], // FDIK
            // Dosen FUA (Sesuai Jadwal Resmi Laboratorium Psikoterapi)
            ['nidn' => '2101198501', 'nama' => 'Siti Asiyah, M.Ag', 'email' => 'siti.asiyah@praktikum.ac.id'],
            ['nidn' => '2102198602', 'nama' => 'Dr. Andri Azis Putra, M.Phil', 'email' => 'andri.azis@praktikum.ac.id'],
            ['nidn' => '2103198903', 'nama' => 'Muhammad Ikhsan Ghofur, M.A', 'email' => 'ikhsan.ghofur@praktikum.ac.id'],
            ['nidn' => '2104198404', 'nama' => 'Dr. Izzudin, M.A', 'email' => 'izzudin@praktikum.ac.id'],
        ];

        $dosenModels = [];
        foreach ($dosenList as $dl) {
            $userDosen = User::firstOrCreate(
                ['email' => $dl['email']],
                [
                    'name' => $dl['nama'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
            $userDosen->syncRoles(['dosen']);

            $dosenModels[$dl['nidn']] = Dosen::firstOrCreate(
                ['nidn' => $dl['nidn']],
                [
                    'nama' => $dl['nama'],
                    'email' => $dl['email'],
                    'status' => 'aktif',
                    'user_id' => $userDosen->id,
                ]
            );
        }

        // =========================================================
        // 6. KELAS TIAP FAKULTAS (Informatika Diperbanyak)
        // =========================================================
        $kelasData = [
            // Informatika (8 Kelas dari Semester 1 s/d 7)
            ['prodi' => 'IF', 'nama' => 'IF-1A', 'sem' => 1],
            ['prodi' => 'IF', 'nama' => 'IF-1B', 'sem' => 1],
            ['prodi' => 'IF', 'nama' => 'IF-3A', 'sem' => 3],
            ['prodi' => 'IF', 'nama' => 'IF-3B', 'sem' => 3],
            ['prodi' => 'IF', 'nama' => 'IF-5A', 'sem' => 5],
            ['prodi' => 'IF', 'nama' => 'IF-5B', 'sem' => 5],
            ['prodi' => 'IF', 'nama' => 'IF-7A', 'sem' => 7],
            ['prodi' => 'IF', 'nama' => 'IF-7B', 'sem' => 7],
            // Sistem Informasi
            ['prodi' => 'SI', 'nama' => 'SI-1A', 'sem' => 1],
            ['prodi' => 'SI', 'nama' => 'SI-3A', 'sem' => 3],
            ['prodi' => 'SI', 'nama' => 'SI-5A', 'sem' => 5],
            // Pendidikan Teknologi Informasi
            ['prodi' => 'PTI', 'nama' => 'PTI-1A', 'sem' => 1],
            ['prodi' => 'PTI', 'nama' => 'PTI-3A', 'sem' => 3],
            // PAI
            ['prodi' => 'PAI', 'nama' => 'PAI-1A', 'sem' => 1],
            // FEBI
            ['prodi' => 'PBS', 'nama' => 'PBS-1A', 'sem' => 1],
            ['prodi' => 'PBS', 'nama' => 'PBS-3A', 'sem' => 3],
            ['prodi' => 'AKS', 'nama' => 'AKS-1A', 'sem' => 1],
            ['prodi' => 'AKS', 'nama' => 'AKS-3A', 'sem' => 3],
            ['prodi' => 'ES',  'nama' => 'ES-1A',  'sem' => 1],
            ['prodi' => 'ES',  'nama' => 'ES-3A',  'sem' => 3],
            // FSH
            ['prodi' => 'HKI', 'nama' => 'HKI-1A', 'sem' => 1],
            ['prodi' => 'HKI', 'nama' => 'HKI-3A', 'sem' => 3],
            ['prodi' => 'HES', 'nama' => 'HES-1A', 'sem' => 1],
            ['prodi' => 'HTN', 'nama' => 'HTN-1A', 'sem' => 1],
            // FUA (Tasawuf dan Psikoterapi)
            ['prodi' => 'TP', 'nama' => 'TP/IV/A', 'sem' => 4],
            ['prodi' => 'TP', 'nama' => 'TP/VI/A', 'sem' => 6],
            // FDIK
            ['prodi' => 'KPI', 'nama' => 'KPI-1A', 'sem' => 1],
            ['prodi' => 'KPI', 'nama' => 'KPI-3A', 'sem' => 3],
            ['prodi' => 'BKI', 'nama' => 'BKI-1A', 'sem' => 1],
        ];

        $kelasModels = [];
        foreach ($kelasData as $kd) {
            $kelasModels[$kd['nama']] = Kelas::firstOrCreate(
                ['nama_kelas' => $kd['nama']],
                [
                    'program_studi_id' => $prodiModels[$kd['prodi']]->id,
                    'semester' => $kd['sem'],
                    'status' => 'aktif',
                ]
            );
        }

        // =========================================================
        // 7. MATA KULIAH PRAKTIKUM (Informatika Diperbanyak)
        // =========================================================
        $mkData = [
            // Informatika (14 Mata Kuliah Praktikum Lengkap)
            ['prodi' => 'IF', 'kode' => 'IF101', 'nama' => 'Algoritma dan Pemrograman Dasar', 'sks' => 3, 'sem' => 1],
            ['prodi' => 'IF', 'kode' => 'IF102', 'nama' => 'Pengantar Teknologi Informasi & Komputasi', 'sks' => 2, 'sem' => 1],
            ['prodi' => 'IF', 'kode' => 'IF103', 'nama' => 'Logika Pemrograman C++', 'sks' => 3, 'sem' => 1],
            ['prodi' => 'IF', 'kode' => 'IF201', 'nama' => 'Struktur Data & Algoritma Lanjut', 'sks' => 3, 'sem' => 3],
            ['prodi' => 'IF', 'kode' => 'IF202', 'nama' => 'Sistem Basis Data Relasional & SQL', 'sks' => 3, 'sem' => 3],
            ['prodi' => 'IF', 'kode' => 'IF203', 'nama' => 'Pemrograman Web & Modern Framework', 'sks' => 3, 'sem' => 3],
            ['prodi' => 'IF', 'kode' => 'IF204', 'nama' => 'Pemrograman Berorientasi Objek (OOP)', 'sks' => 3, 'sem' => 3],
            ['prodi' => 'IF', 'kode' => 'IF301', 'nama' => 'Jaringan Komputer & Routing Cisco', 'sks' => 3, 'sem' => 5],
            ['prodi' => 'IF', 'kode' => 'IF302', 'nama' => 'Rekayasa Perangkat Lunak Terapan', 'sks' => 3, 'sem' => 5],
            ['prodi' => 'IF', 'kode' => 'IF303', 'nama' => 'Kecerdasan Buatan & Machine Learning', 'sks' => 3, 'sem' => 5],
            ['prodi' => 'IF', 'kode' => 'IF304', 'nama' => 'Pengembangan Aplikasi Mobile (Android)', 'sks' => 3, 'sem' => 5],
            ['prodi' => 'IF', 'kode' => 'IF401', 'nama' => 'Cloud Computing & Docker Virtualization', 'sks' => 3, 'sem' => 7],
            ['prodi' => 'IF', 'kode' => 'IF402', 'nama' => 'Data Science & Big Data Analytics', 'sks' => 3, 'sem' => 7],
            ['prodi' => 'IF', 'kode' => 'IF403', 'nama' => 'Cyber Security & Ethical Hacking', 'sks' => 3, 'sem' => 7],
            // Sistem Informasi
            ['prodi' => 'SI', 'kode' => 'SI101', 'nama' => 'Analisis Proses Bisnis Berbasis TI', 'sks' => 3, 'sem' => 1],
            ['prodi' => 'SI', 'kode' => 'SI201', 'nama' => 'Desain Basis Data Enterprise', 'sks' => 3, 'sem' => 3],
            ['prodi' => 'SI', 'kode' => 'SI301', 'nama' => 'Enterprise Resource Planning (ERP)', 'sks' => 3, 'sem' => 5],
            // Pendidikan Teknologi Informasi
            ['prodi' => 'PTI', 'kode' => 'PTI101', 'nama' => 'Media Pembelajaran Digital Interaktif', 'sks' => 3, 'sem' => 1],
            ['prodi' => 'PTI', 'kode' => 'PTI201', 'nama' => 'Desain Grafis & Animasi Edukasi', 'sks' => 3, 'sem' => 3],
            // PAI
            ['prodi' => 'PAI', 'kode' => 'PAI101', 'nama' => 'Praktikum Microteaching PAI', 'sks' => 2, 'sem' => 1],
            // FEBI
            ['prodi' => 'PBS', 'kode' => 'PBS101', 'nama' => 'Praktikum Simulasi Bank Mini Syariah', 'sks' => 3, 'sem' => 1],
            ['prodi' => 'AKS', 'kode' => 'AKS201', 'nama' => 'Komputerisasi Akuntansi (Accurate & MYOB)', 'sks' => 3, 'sem' => 3],
            ['prodi' => 'ES',  'kode' => 'ES201',  'nama' => 'Simulasi Pasar Modal & FinTech Syariah', 'sks' => 3, 'sem' => 3],
            // FSH
            ['prodi' => 'HKI', 'kode' => 'HKI101', 'nama' => 'Praktikum Peradilan Semu Hukum Keluarga', 'sks' => 3, 'sem' => 1],
            ['prodi' => 'HES', 'kode' => 'HES201', 'nama' => 'Praktikum Peradilan Semu Bisnis Syariah', 'sks' => 3, 'sem' => 3],
            ['prodi' => 'HTN', 'kode' => 'HTN101', 'nama' => 'Praktikum Falak & Hisab Rukyat Astronomi', 'sks' => 2, 'sem' => 1],
            // FUA (Tasawuf dan Psikoterapi - Sesuai Data Resmi)
            ['prodi' => 'TP', 'kode' => 'TP201', 'nama' => 'SQ dan Pengukurannya', 'sks' => 3, 'sem' => 4],
            ['prodi' => 'TP', 'kode' => 'TP301', 'nama' => 'Penulisan Karya Ilmiah', 'sks' => 3, 'sem' => 6],
            ['prodi' => 'TP', 'kode' => 'TP202', 'nama' => 'Metode Penelitian Kualitatif', 'sks' => 3, 'sem' => 4],
            ['prodi' => 'TP', 'kode' => 'TP302', 'nama' => 'Terapi Matsnawi', 'sks' => 3, 'sem' => 6],
            ['prodi' => 'TP', 'kode' => 'TP303', 'nama' => 'Sufi Healing 2', 'sks' => 3, 'sem' => 6],
            // FDIK
            ['prodi' => 'KPI', 'kode' => 'KPI101', 'nama' => 'Praktikum Studio Broadcasting & Podcast', 'sks' => 3, 'sem' => 1],
            ['prodi' => 'KPI', 'kode' => 'KPI201', 'nama' => 'Produksi Berita TV & Live Streaming', 'sks' => 3, 'sem' => 3],
            ['prodi' => 'BKI', 'kode' => 'BKI101', 'nama' => 'Praktikum Konseling Mikro & Psikodiagnostik', 'sks' => 3, 'sem' => 1],
        ];

        $mkModels = [];
        foreach ($mkData as $mk) {
            $mkModels[$mk['kode']] = MataKuliah::firstOrCreate(
                ['kode_mk' => $mk['kode']],
                [
                    'program_studi_id' => $prodiModels[$mk['prodi']]->id,
                    'nama_mk' => $mk['nama'],
                    'sks' => $mk['sks'],
                    'semester' => $mk['sem'],
                    'status' => 'aktif',
                ]
            );
        }

        // =========================================================
        // 8. RUANG PRAKTIKUM & LABORATORIUM TIAP FAKULTAS
        // =========================================================
        $ruangData = [
            // Laboratorium FTIK / Informatika (6 Lab Komputer Spesifik)
            ['kode' => 'LAB-01', 'nama' => 'Lab Rekayasa Perangkat Lunak', 'lokasi' => 'Gedung A Lt. 1', 'kapasitas' => 40, 'tipe' => 'laboratorium', 'cakupan_akses' => 'bersama_fakultas', 'fakultas' => 'FTIK', 'ket' => 'Komputer core i7, dual monitor, IDE lengkap'],
            ['kode' => 'LAB-02', 'nama' => 'Lab Algoritma & Komputasi Dasar', 'lokasi' => 'Gedung A Lt. 1', 'kapasitas' => 40, 'tipe' => 'laboratorium', 'cakupan_akses' => 'bersama_fakultas', 'fakultas' => 'FTIK', 'ket' => 'Untuk praktikum dasar pemrograman dan struktur data'],
            ['kode' => 'LAB-03', 'nama' => 'Lab Jaringan Komputer & IoT', 'lokasi' => 'Gedung A Lt. 2', 'kapasitas' => 35, 'tipe' => 'laboratorium', 'cakupan_akses' => 'eksklusif', 'prodi' => 'IF', 'fakultas' => 'FTIK', 'ket' => 'Dilengkapi rack server, switch cisco, mikrotik, dan IoT kit khusus Informatika'],
            ['kode' => 'LAB-04', 'nama' => 'Lab Multimedia & Desain Grafis', 'lokasi' => 'Gedung B Lt. 1', 'kapasitas' => 30, 'tipe' => 'laboratorium', 'cakupan_akses' => 'bersama_fakultas', 'fakultas' => 'FTIK', 'ket' => 'PC grafis RTX, software editing, dan drawing tablet'],
            ['kode' => 'LAB-05', 'nama' => 'Lab Kecerdasan Buatan & Data Science', 'lokasi' => 'Gedung B Lt. 2', 'kapasitas' => 30, 'tipe' => 'laboratorium', 'cakupan_akses' => 'bersama_fakultas', 'fakultas' => 'FTIK', 'ket' => 'Komputasi GPU untuk AI & deep learning'],
            ['kode' => 'LAB-06', 'nama' => 'Lab Server & Cloud Computing', 'lokasi' => 'Gedung A Lt. 3', 'kapasitas' => 25, 'tipe' => 'laboratorium', 'cakupan_akses' => 'bersama_fakultas', 'fakultas' => 'FTIK', 'ket' => 'Server virtualisasi, Docker & Kubernetes cluster'],
            ['kode' => 'AUD-FTIK', 'nama' => 'Auditorium Tarbiyah & Sains', 'lokasi' => 'Gedung FTIK Lt. 3', 'kapasitas' => 250, 'tipe' => 'auditorium', 'cakupan_akses' => 'bersama_fakultas', 'fakultas' => 'FTIK', 'ket' => 'Auditorium seminar dan praktikum terpusat FTIK'],
            // FEBI
            ['kode' => 'LAB-FEBI-01', 'nama' => 'Lab Bank Mini Syariah & FinTech', 'lokasi' => 'Gedung FEBI Lt. 1', 'kapasitas' => 40, 'tipe' => 'laboratorium', 'cakupan_akses' => 'bersama_fakultas', 'fakultas' => 'FEBI', 'ket' => 'Simulasi teller, CS, dan core banking syariah'],
            ['kode' => 'LAB-FEBI-02', 'nama' => 'Lab Komputasi Akuntansi & Pajak', 'lokasi' => 'Gedung FEBI Lt. 2', 'kapasitas' => 35, 'tipe' => 'laboratorium', 'cakupan_akses' => 'bersama_fakultas', 'fakultas' => 'FEBI', 'ket' => 'Software Accurate, MYOB, dan e-Faktur'],
            // FSH
            ['kode' => 'LAB-FSH-01', 'nama' => 'Lab Peradilan Semu (Moot Court)', 'lokasi' => 'Gedung FSH Lt. 2', 'kapasitas' => 50, 'tipe' => 'laboratorium', 'cakupan_akses' => 'bersama_fakultas', 'fakultas' => 'FSH', 'ket' => 'Ruang sidang peradilan semu lengkap dengan jubah toga'],
            ['kode' => 'LAB-FSH-02', 'nama' => 'Lab Ilmu Falak & Observatorium', 'lokasi' => 'Gedung FSH Lt. 3', 'kapasitas' => 30, 'tipe' => 'laboratorium', 'cakupan_akses' => 'bersama_fakultas', 'fakultas' => 'FSH', 'ket' => 'Teleskop astronomi, theodolite, dan instrumen hisab rukyat'],
            // FUA (Laboratorium Resmi Psikoterapi)
            ['kode' => 'LAB-PSIKO', 'nama' => 'Laboratorium Psikoterapi', 'lokasi' => 'Gedung FUA Lt. 2', 'kapasitas' => 40, 'tipe' => 'laboratorium', 'cakupan_akses' => 'bersama_fakultas', 'fakultas' => 'FUA', 'ket' => 'Laboratorium Psikoterapi & Konseling Sufistik FUA'],
            // FDIK
            ['kode' => 'LAB-FDIK-01', 'nama' => 'Studio Broadcasting Radio & Podcast', 'lokasi' => 'Gedung FDIK Lt. 2', 'kapasitas' => 25, 'tipe' => 'laboratorium', 'cakupan_akses' => 'bersama_fakultas', 'fakultas' => 'FDIK', 'ket' => 'Peredam suara, mic condenser broadcast, mixer audio'],
            ['kode' => 'LAB-FDIK-02', 'nama' => 'Studio Televisi & Multi-Kamera', 'lokasi' => 'Gedung FDIK Lt. 3', 'kapasitas' => 35, 'tipe' => 'laboratorium', 'cakupan_akses' => 'bersama_fakultas', 'fakultas' => 'FDIK', 'ket' => 'Kamera broadcast, teleprompter, switcher video, green screen'],
            // Fasilitas Bersama Universitas (Auditorium Bersama)
            ['kode' => 'AUD-SIBER', 'nama' => 'Auditorium Utama Siber Syekh Nurjati', 'lokasi' => 'Gedung Siber Lt. 4', 'kapasitas' => 500, 'tipe' => 'auditorium', 'cakupan_akses' => 'umum_universitas', 'fakultas' => null, 'ket' => 'Fasilitas bersama universitas untuk kuliah akbar dan seminar'],
            ['kode' => 'AUD-ICC', 'nama' => 'Auditorium Islamic Cultural Center (ICC)', 'lokasi' => 'Kompleks ICC Kampus 2', 'kapasitas' => 800, 'tipe' => 'auditorium', 'cakupan_akses' => 'umum_universitas', 'fakultas' => null, 'ket' => 'Fasilitas akbar kampus untuk seremonial dan stadium generale'],
        ];

        $ruangModels = [];
        foreach ($ruangData as $rd) {
            $fakId = ! empty($rd['fakultas']) && isset($fakultasModels[$rd['fakultas']])
                ? $fakultasModels[$rd['fakultas']]->id
                : null;

            $prodiId = ! empty($rd['prodi']) && isset($prodiModels[$rd['prodi']])
                ? $prodiModels[$rd['prodi']]->id
                : null;

            $ruang = RuangPraktikum::firstOrCreate(
                ['kode_ruang' => $rd['kode']],
                [
                    'nama_ruang' => $rd['nama'],
                    'lokasi' => $rd['lokasi'],
                    'kapasitas' => $rd['kapasitas'],
                    'tipe_ruang' => $rd['tipe'],
                    'cakupan_akses' => $rd['cakupan_akses'] ?? 'bersama_fakultas',
                    'fakultas_id' => $fakId,
                    'program_studi_id' => $prodiId,
                    'status' => 'aktif',
                    'keterangan' => $rd['ket'],
                ]
            );

            // Jika multi-prodi, hubungkan ke tabel pivot
            if (! empty($rd['multi_prodi'])) {
                $pIds = [];
                foreach ($rd['multi_prodi'] as $pKode) {
                    if (isset($prodiModels[$pKode])) {
                        $pIds[] = $prodiModels[$pKode]->id;
                    }
                }
                $ruang->prodiPengguna()->sync($pIds);
            }

            $ruangModels[$rd['kode']] = $ruang;
        }

        // =========================================================
        // 9. TAHUN AKADEMIK (2026/2027 Ganjil Aktif)
        // =========================================================
        $taAktif = TahunAkademik::firstOrCreate(
            ['nama' => '2026/2027', 'semester' => 'Ganjil'],
            [
                'tahun_mulai' => 2026,
                'tahun_selesai' => 2027,
                'is_aktif' => true,
            ]
        );

        TahunAkademik::firstOrCreate(
            ['nama' => '2025/2026', 'semester' => 'Genap'],
            [
                'tahun_mulai' => 2025,
                'tahun_selesai' => 2026,
                'is_aktif' => false,
            ]
        );

        // =========================================================
        // 10. JADWAL PRAKTIKUM LENGKAP (Informatika & Lintas Fakultas)
        // =========================================================
        $jadwalData = [
            // SENIN
            // Informatika
            ['mk' => 'IF101', 'kelas' => 'IF-1A', 'dosen' => '0501198501', 'ruang' => 'LAB-01', 'hari' => 'Senin', 'mulai' => '08:00', 'selesai' => '10:30'],
            ['mk' => 'IF201', 'kelas' => 'IF-3A', 'dosen' => '0612199002', 'ruang' => 'LAB-02', 'hari' => 'Senin', 'mulai' => '08:00', 'selesai' => '10:30'],
            ['mk' => 'IF301', 'kelas' => 'IF-5A', 'dosen' => '0723198803', 'ruang' => 'LAB-03', 'hari' => 'Senin', 'mulai' => '08:00', 'selesai' => '10:30'],
            ['mk' => 'IF102', 'kelas' => 'IF-1B', 'dosen' => '0945198705', 'ruang' => 'LAB-01', 'hari' => 'Senin', 'mulai' => '10:30', 'selesai' => '13:00'],
            ['mk' => 'IF202', 'kelas' => 'IF-3B', 'dosen' => '1056199306', 'ruang' => 'LAB-02', 'hari' => 'Senin', 'mulai' => '10:30', 'selesai' => '13:00'],
            ['mk' => 'IF401', 'kelas' => 'IF-7A', 'dosen' => '1389199409', 'ruang' => 'LAB-06', 'hari' => 'Senin', 'mulai' => '13:00', 'selesai' => '15:30'],
            // Fakultas Lain Senin
            ['mk' => 'PBS101', 'kelas' => 'PBS-1A', 'dosen' => '1167198607', 'ruang' => 'LAB-FEBI-01', 'hari' => 'Senin', 'mulai' => '08:30', 'selesai' => '11:00'],
            ['mk' => 'HKI101', 'kelas' => 'HKI-1A', 'dosen' => '0834199204', 'ruang' => 'LAB-FSH-01',  'hari' => 'Senin', 'mulai' => '09:00', 'selesai' => '11:30'],
            ['mk' => 'KPI101', 'kelas' => 'KPI-1A', 'dosen' => '1612199212', 'ruang' => 'LAB-FDIK-01', 'hari' => 'Senin', 'mulai' => '13:00', 'selesai' => '15:30'],

            // SELASA
            // Informatika
            ['mk' => 'IF103', 'kelas' => 'IF-1B', 'dosen' => '1490199510', 'ruang' => 'LAB-01', 'hari' => 'Selasa', 'mulai' => '08:00', 'selesai' => '10:30'],
            ['mk' => 'IF203', 'kelas' => 'IF-3A', 'dosen' => '0612199002', 'ruang' => 'LAB-02', 'hari' => 'Selasa', 'mulai' => '08:00', 'selesai' => '10:30'],
            ['mk' => 'IF302', 'kelas' => 'IF-5B', 'dosen' => '0723198803', 'ruang' => 'LAB-04', 'hari' => 'Selasa', 'mulai' => '08:00', 'selesai' => '10:30'],
            ['mk' => 'IF303', 'kelas' => 'IF-5A', 'dosen' => '1278199108', 'ruang' => 'LAB-05', 'hari' => 'Selasa', 'mulai' => '10:30', 'selesai' => '13:00'],
            ['mk' => 'IF402', 'kelas' => 'IF-7B', 'dosen' => '0501198501', 'ruang' => 'LAB-05', 'hari' => 'Selasa', 'mulai' => '13:00', 'selesai' => '15:30'],
            ['mk' => 'SI101',  'kelas' => 'SI-1A',  'dosen' => '0945198705', 'ruang' => 'LAB-01', 'hari' => 'Selasa', 'mulai' => '13:00', 'selesai' => '15:30'],
            // Fakultas Lain Selasa
            ['mk' => 'AKS201', 'kelas' => 'AKS-1A', 'dosen' => '1167198607', 'ruang' => 'LAB-FEBI-02', 'hari' => 'Selasa', 'mulai' => '08:30', 'selesai' => '11:00'],

            // RABU
            // FUA - Laboratorium Psikoterapi (Sesuai Jadwal Resmi Gambar 4)
            ['mk' => 'TP201', 'kelas' => 'TP/IV/A', 'dosen' => '2101198501', 'ruang' => 'LAB-PSIKO', 'hari' => 'Rabu', 'mulai' => '07:30', 'selesai' => '09:45'],
            ['mk' => 'TP301', 'kelas' => 'TP/VI/A', 'dosen' => '2102198602', 'ruang' => 'LAB-PSIKO', 'hari' => 'Rabu', 'mulai' => '09:45', 'selesai' => '12:00'],
            ['mk' => 'TP202', 'kelas' => 'TP/IV/A', 'dosen' => '2103198903', 'ruang' => 'LAB-PSIKO', 'hari' => 'Rabu', 'mulai' => '13:00', 'selesai' => '15:15'],
            // Informatika
            ['mk' => 'IF101', 'kelas' => 'IF-1B', 'dosen' => '0501198501', 'ruang' => 'LAB-01', 'hari' => 'Rabu', 'mulai' => '08:00', 'selesai' => '10:30'],
            ['mk' => 'IF204', 'kelas' => 'IF-3B', 'dosen' => '1056199306', 'ruang' => 'LAB-02', 'hari' => 'Rabu', 'mulai' => '08:00', 'selesai' => '10:30'],
            ['mk' => 'IF304', 'kelas' => 'IF-5A', 'dosen' => '1389199409', 'ruang' => 'LAB-01', 'hari' => 'Rabu', 'mulai' => '10:30', 'selesai' => '13:00'],
            ['mk' => 'IF403', 'kelas' => 'IF-7A', 'dosen' => '0723198803', 'ruang' => 'LAB-03', 'hari' => 'Rabu', 'mulai' => '10:30', 'selesai' => '13:00'],
            ['mk' => 'PTI101', 'kelas' => 'PTI-1A', 'dosen' => '1490199510', 'ruang' => 'LAB-04', 'hari' => 'Rabu', 'mulai' => '13:00', 'selesai' => '15:30'],
            // Fakultas Lain Rabu
            ['mk' => 'HES201', 'kelas' => 'HES-1A', 'dosen' => '0834199204', 'ruang' => 'LAB-FSH-01',  'hari' => 'Rabu', 'mulai' => '09:00', 'selesai' => '11:30'],
            ['mk' => 'KPI201', 'kelas' => 'KPI-3A', 'dosen' => '1612199212', 'ruang' => 'LAB-FDIK-02', 'hari' => 'Rabu', 'mulai' => '13:00', 'selesai' => '15:30'],

            // KAMIS
            // FUA - Laboratorium Psikoterapi (Sesuai Jadwal Resmi Gambar 4)
            ['mk' => 'TP302', 'kelas' => 'TP/VI/A', 'dosen' => '2104198404', 'ruang' => 'LAB-PSIKO', 'hari' => 'Kamis', 'mulai' => '07:30', 'selesai' => '09:45'],
            ['mk' => 'TP303', 'kelas' => 'TP/VI/A', 'dosen' => '2103198903', 'ruang' => 'LAB-PSIKO', 'hari' => 'Kamis', 'mulai' => '13:00', 'selesai' => '15:15'],
            // Informatika
            ['mk' => 'IF201', 'kelas' => 'IF-3B', 'dosen' => '0612199002', 'ruang' => 'LAB-01', 'hari' => 'Kamis', 'mulai' => '08:00', 'selesai' => '10:30'],
            ['mk' => 'IF301', 'kelas' => 'IF-5B', 'dosen' => '0723198803', 'ruang' => 'LAB-03', 'hari' => 'Kamis', 'mulai' => '08:00', 'selesai' => '10:30'],
            ['mk' => 'IF203', 'kelas' => 'IF-3B', 'dosen' => '0612199002', 'ruang' => 'LAB-02', 'hari' => 'Kamis', 'mulai' => '10:30', 'selesai' => '13:00'],
            ['mk' => 'IF401', 'kelas' => 'IF-7B', 'dosen' => '1389199409', 'ruang' => 'LAB-06', 'hari' => 'Kamis', 'mulai' => '10:30', 'selesai' => '13:00'],
            ['mk' => 'SI201',  'kelas' => 'SI-3A',  'dosen' => '0945198705', 'ruang' => 'LAB-01', 'hari' => 'Kamis', 'mulai' => '13:00', 'selesai' => '15:30'],
            // Fakultas Lain Kamis
            ['mk' => 'ES201',  'kelas' => 'ES-1A',  'dosen' => '1167198607', 'ruang' => 'LAB-FEBI-01', 'hari' => 'Kamis', 'mulai' => '08:30', 'selesai' => '11:00'],
            ['mk' => 'HTN101', 'kelas' => 'HTN-1A', 'dosen' => '0834199204', 'ruang' => 'LAB-FSH-02',  'hari' => 'Kamis', 'mulai' => '13:00', 'selesai' => '15:00'],

            // JUMAT
            // Informatika
            ['mk' => 'IF103', 'kelas' => 'IF-1A', 'dosen' => '1490199510', 'ruang' => 'LAB-02', 'hari' => 'Jumat', 'mulai' => '08:00', 'selesai' => '10:30'],
            ['mk' => 'IF303', 'kelas' => 'IF-5B', 'dosen' => '1278199108', 'ruang' => 'LAB-05', 'hari' => 'Jumat', 'mulai' => '08:00', 'selesai' => '10:30'],
            ['mk' => 'IF403', 'kelas' => 'IF-7B', 'dosen' => '0723198803', 'ruang' => 'LAB-03', 'hari' => 'Jumat', 'mulai' => '13:30', 'selesai' => '16:00'],
            ['mk' => 'PTI201', 'kelas' => 'PTI-3A', 'dosen' => '1490199510', 'ruang' => 'LAB-04', 'hari' => 'Jumat', 'mulai' => '13:30', 'selesai' => '16:00'],
            // Fakultas Lain Jumat
            ['mk' => 'BKI101', 'kelas' => 'BKI-1A', 'dosen' => '1612199212', 'ruang' => 'LAB-FDIK-01', 'hari' => 'Jumat', 'mulai' => '13:30', 'selesai' => '15:30'],

            // Sesi Tambahan Hari Kerja (Senin–Jum'at)
            ['mk' => 'IF202', 'kelas' => 'IF-3A', 'dosen' => '1056199306', 'ruang' => 'LAB-01', 'hari' => 'Kamis', 'mulai' => '15:30', 'selesai' => '17:30'],
            ['mk' => 'IF304', 'kelas' => 'IF-5B', 'dosen' => '1389199409', 'ruang' => 'LAB-02', 'hari' => 'Rabu', 'mulai' => '15:30', 'selesai' => '17:30'],
            ['mk' => 'IF402', 'kelas' => 'IF-7A', 'dosen' => '0501198501', 'ruang' => 'LAB-05', 'hari' => 'Kamis', 'mulai' => '13:00', 'selesai' => '15:30'],
        ];

        foreach ($jadwalData as $jd) {
            if (isset($mkModels[$jd['mk']], $kelasModels[$jd['kelas']], $dosenModels[$jd['dosen']], $ruangModels[$jd['ruang']])) {
                JadwalPraktikum::create([
                    'mata_kuliah_id' => $mkModels[$jd['mk']]->id,
                    'kelas_id' => $kelasModels[$jd['kelas']]->id,
                    'dosen_id' => $dosenModels[$jd['dosen']]->id,
                    'ruang_praktikum_id' => $ruangModels[$jd['ruang']]->id,
                    'tahun_akademik_id' => $taAktif->id,
                    'hari' => $jd['hari'],
                    'jam_mulai' => $jd['mulai'],
                    'jam_selesai' => $jd['selesai'],
                    'status' => 'aktif',
                ]);
            }
        }
    }
}
