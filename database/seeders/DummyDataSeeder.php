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
use App\Models\TahunAkademik;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds for rich dummy data demonstration.
     */
    public function run(): void
    {
        // Pastikan role dosen ada
        Role::firstOrCreate(['name' => 'dosen', 'guard_name' => 'web']);

        // 1. Tahun Akademik
        $tahunAkademik = TahunAkademik::where('is_aktif', true)->first()
            ?? TahunAkademik::firstOrCreate(
                ['nama' => '2026/2027', 'semester' => 'Ganjil'],
                ['is_aktif' => true, 'tahun_mulai' => 2026, 'tahun_selesai' => 2027]
            );

        // 2. Mapping Fakultas & Prodi
        $fitk = Fakultas::where('kode_fakultas', 'FITK')->first();
        $febi = Fakultas::where('kode_fakultas', 'FEBI')->first();
        $fasya = Fakultas::where('kode_fakultas', 'FASYA')->first();
        $fua = Fakultas::where('kode_fakultas', 'FUA')->first();
        $fdki = Fakultas::where('kode_fakultas', 'FDKI')->first();

        $prodiIF = ProgramStudi::where('kode_program_studi', 'IF')->first();
        $prodiSI = ProgramStudi::where('kode_program_studi', 'SI')->first();
        $prodiPTI = ProgramStudi::where('kode_program_studi', 'PTI')->first();
        $prodiPAI = ProgramStudi::where('kode_program_studi', 'PAI')->first();
        $prodiPBA = ProgramStudi::where('kode_program_studi', 'PBA')->first();
        $prodiPBS = ProgramStudi::where('kode_program_studi', 'PBS')->first();
        $prodiAKS = ProgramStudi::where('kode_program_studi', 'AKS')->first();
        $prodiES = ProgramStudi::where('kode_program_studi', 'ES')->first();
        $prodiHKI = ProgramStudi::where('kode_program_studi', 'HKI')->first();
        $prodiHES = ProgramStudi::where('kode_program_studi', 'HES')->first();
        $prodiHTN = ProgramStudi::where('kode_program_studi', 'HTN')->first();
        $prodiTP = ProgramStudi::where('kode_program_studi', 'TP')->first();
        $prodiKPI = ProgramStudi::where('kode_program_studi', 'KPI')->first();
        $prodiBKI = ProgramStudi::where('kode_program_studi', 'BKI')->first();

        // =========================================================================
        // 3. SEED DOSEN (18 Dosen Realistis)
        // =========================================================================
        $dosenList = [
            // FITK Dosen
            ['nidn' => '0945198705', 'nama' => 'Andi Prasetyo, S.T., M.Eng.', 'email' => 'andi.prasetyo@praktikum.ac.id'],
            ['nidn' => '0412087501', 'nama' => 'Dr. Ir. Budi Santoso, M.Kom.', 'email' => 'budi.santoso@praktikum.ac.id'],
            ['nidn' => '0725048802', 'nama' => 'Siti Rahmawati, S.Kom., M.Cs.', 'email' => 'siti.rahmawati@praktikum.ac.id'],
            ['nidn' => '0019039103', 'nama' => 'Fajar Nugroho, M.T.', 'email' => 'fajar.nugroho@praktikum.ac.id'],
            ['nidn' => '0011096501', 'nama' => 'Prof. Dr. Hj. Nurul Hidayah, M.Pd.', 'email' => 'nurul.hidayah@praktikum.ac.id'],
            ['nidn' => '0814028304', 'nama' => 'Ahmad Zaki, M.Pd.I.', 'email' => 'ahmad.zaki@praktikum.ac.id'],
            // FEBI Dosen
            ['nidn' => '0615108001', 'nama' => 'Dr. Muhammad Ridwan, S.E., M.E.', 'email' => 'm.ridwan@praktikum.ac.id'],
            ['nidn' => '0718068903', 'nama' => 'Annisa Fitriani, S.E., M.Ak.', 'email' => 'annisa.fitriani@praktikum.ac.id'],
            ['nidn' => '0022078602', 'nama' => 'Hendra Kurniawan, M.E.I.', 'email' => 'hendra.kurniawan@praktikum.ac.id'],
            ['nidn' => '0514119001', 'nama' => 'Maya Lestari, S.E., M.Si.', 'email' => 'maya.lestari@praktikum.ac.id'],
            // FASYA Dosen
            ['nidn' => '0110037802', 'nama' => 'Dr. H. Faisal Basri, S.H., M.H.', 'email' => 'faisal.basri@praktikum.ac.id'],
            ['nidn' => '0420088501', 'nama' => 'Dewi Sartika, S.H.I., M.Sy.', 'email' => 'dewi.sartika@praktikum.ac.id'],
            ['nidn' => '0915129201', 'nama' => 'M. Rifqi Hakim, S.H., M.H.', 'email' => 'rifqi.hakim@praktikum.ac.id'],
            // FUA Dosen
            ['nidn' => '0205067201', 'nama' => 'Dr. K.H. Luqman Hakim, M.Ag.', 'email' => 'luqman.hakim@praktikum.ac.id'],
            ['nidn' => '0709018702', 'nama' => 'Rina Indrawati, S.Psi., M.Psi.', 'email' => 'rina.indrawati@praktikum.ac.id'],
            // FDKI Dosen
            ['nidn' => '0317058601', 'nama' => 'Bagus Setiawan, S.Sos., M.I.Kom.', 'email' => 'bagus.setiawan@praktikum.ac.id'],
            ['nidn' => '0628098902', 'nama' => 'Dian Permatasari, M.Sos.', 'email' => 'dian.permatasari@praktikum.ac.id'],
            ['nidn' => '0012049104', 'nama' => 'Wahyu Pratama, S.Kom.I., M.A.', 'email' => 'wahyu.pratama@praktikum.ac.id'],
        ];

        $dosenModels = [];
        foreach ($dosenList as $d) {
            $user = User::firstOrCreate(
                ['email' => $d['email']],
                [
                    'name' => $d['nama'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
            $user->syncRoles(['dosen']);

            $dosen = Dosen::withTrashed()
                ->where(function ($q) use ($d) {
                    $q->where('nidn', $d['nidn'])
                        ->orWhere('email', $d['email']);
                })
                ->first();

            if ($dosen) {
                if ($dosen->trashed()) {
                    $dosen->restore();
                }
                $dosen->update([
                    'user_id' => $user->id,
                    'nidn' => $d['nidn'],
                    'nama' => $d['nama'],
                    'email' => $d['email'],
                    'status' => 'aktif',
                ]);
            } else {
                $dosen = Dosen::create([
                    'user_id' => $user->id,
                    'nidn' => $d['nidn'],
                    'nama' => $d['nama'],
                    'email' => $d['email'],
                    'status' => 'aktif',
                ]);
            }

            $dosenModels[$d['nidn']] = $dosen;
        }

        // =========================================================================
        // 4. SEED RUANG PRAKTIKUM (14 Ruang Lab & Studio)
        // =========================================================================
        $ruangData = [
            // FITK
            [
                'kode' => 'LAB-KOM-A',
                'nama' => 'Laboratorium Komputer A (Software & Algoritma)',
                'lokasi' => 'Gedung FITK Lt. 2 R.201',
                'kapasitas' => 40,
                'fakultas_id' => $fitk?->id,
                'program_studi_id' => $prodiIF?->id,
                'tipe_ruang' => 'laboratorium',
                'cakupan_akses' => 'bersama_fakultas',
            ],
            [
                'kode' => 'LAB-KOM-B',
                'nama' => 'Laboratorium Komputer B (Jaringan & Cloud)',
                'lokasi' => 'Gedung FITK Lt. 2 R.203',
                'kapasitas' => 36,
                'fakultas_id' => $fitk?->id,
                'program_studi_id' => $prodiSI?->id,
                'tipe_ruang' => 'laboratorium',
                'cakupan_akses' => 'bersama_fakultas',
            ],
            [
                'kode' => 'LAB-MM',
                'nama' => 'Laboratorium Multimedia & Desain UI/UX',
                'lokasi' => 'Gedung FITK Lt. 3 R.305',
                'kapasitas' => 32,
                'fakultas_id' => $fitk?->id,
                'program_studi_id' => $prodiPTI?->id,
                'tipe_ruang' => 'studio',
                'cakupan_akses' => 'khusus_multi_prodi',
                'multi_prodis' => [$prodiIF?->id, $prodiSI?->id, $prodiPTI?->id],
            ],
            [
                'kode' => 'LAB-MICRO',
                'nama' => 'Laboratorium Microteaching & Pedagogi',
                'lokasi' => 'Gedung Tarbiyah Lt. 1 R.102',
                'kapasitas' => 30,
                'fakultas_id' => $fitk?->id,
                'program_studi_id' => $prodiPAI?->id,
                'tipe_ruang' => 'laboratorium',
                'cakupan_akses' => 'bersama_fakultas',
            ],

            // FEBI
            [
                'kode' => 'LAB-BANK',
                'nama' => 'Laboratorium Bank Mini Syariah',
                'lokasi' => 'Gedung FEBI Lt. 1 R.104',
                'kapasitas' => 35,
                'fakultas_id' => $febi?->id,
                'program_studi_id' => $prodiPBS?->id,
                'tipe_ruang' => 'laboratorium',
                'cakupan_akses' => 'bersama_fakultas',
            ],
            [
                'kode' => 'LAB-BEI',
                'nama' => 'Galeri Investasi & Bursa Efek Syariah',
                'lokasi' => 'Gedung FEBI Lt. 2 R.205',
                'kapasitas' => 30,
                'fakultas_id' => $febi?->id,
                'program_studi_id' => $prodiES?->id,
                'tipe_ruang' => 'laboratorium',
                'cakupan_akses' => 'bersama_fakultas',
            ],
            [
                'kode' => 'LAB-ACC',
                'nama' => 'Laboratorium Komputasi Akuntansi & Pajak',
                'lokasi' => 'Gedung FEBI Lt. 3 R.302',
                'kapasitas' => 35,
                'fakultas_id' => $febi?->id,
                'program_studi_id' => $prodiAKS?->id,
                'tipe_ruang' => 'laboratorium',
                'cakupan_akses' => 'bersama_fakultas',
            ],

            // FASYA
            [
                'kode' => 'LAB-MOOT',
                'nama' => 'Laboratorium Peradilan Semu (Moot Court)',
                'lokasi' => 'Gedung FASYA Lt. 2 R.201',
                'kapasitas' => 45,
                'fakultas_id' => $fasya?->id,
                'program_studi_id' => $prodiHKI?->id,
                'tipe_ruang' => 'laboratorium',
                'cakupan_akses' => 'bersama_fakultas',
            ],
            [
                'kode' => 'LAB-FALAK',
                'nama' => 'Laboratorium Ilmu Falak & Hisab Rukyat',
                'lokasi' => 'Gedung FASYA Lt. 4 Rooftop',
                'kapasitas' => 25,
                'fakultas_id' => $fasya?->id,
                'program_studi_id' => $prodiHES?->id,
                'tipe_ruang' => 'laboratorium',
                'cakupan_akses' => 'eksklusif',
            ],

            // FUA
            [
                'kode' => 'LAB-KONSEL',
                'nama' => 'Laboratorium Konseling & Psikoterapi Islam',
                'lokasi' => 'Gedung Ushuluddin Lt. 2 R.203',
                'kapasitas' => 25,
                'fakultas_id' => $fua?->id,
                'program_studi_id' => $prodiTP?->id,
                'tipe_ruang' => 'laboratorium',
                'cakupan_akses' => 'bersama_fakultas',
            ],
            [
                'kode' => 'LAB-FILOLOGI',
                'nama' => 'Laboratorium Bahasa & Kajian Manuskrip Islam',
                'lokasi' => 'Gedung Ushuluddin Lt. 1 R.105',
                'kapasitas' => 30,
                'fakultas_id' => $fua?->id,
                'program_studi_id' => $prodiTP?->id,
                'tipe_ruang' => 'laboratorium',
                'cakupan_akses' => 'bersama_fakultas',
            ],

            // FDKI
            [
                'kode' => 'LAB-BROADCAST',
                'nama' => 'Studio Broadcast TV & Radio Kampus',
                'lokasi' => 'Gedung Dakwah Lt. 3 R.301',
                'kapasitas' => 30,
                'fakultas_id' => $fdki?->id,
                'program_studi_id' => $prodiKPI?->id,
                'tipe_ruang' => 'studio',
                'cakupan_akses' => 'bersama_fakultas',
            ],
            [
                'kode' => 'LAB-FOTO',
                'nama' => 'Studio Fotografi & Jurnalistik Multimedia',
                'lokasi' => 'Gedung Dakwah Lt. 2 R.204',
                'kapasitas' => 28,
                'fakultas_id' => $fdki?->id,
                'program_studi_id' => $prodiBKI?->id,
                'tipe_ruang' => 'studio',
                'cakupan_akses' => 'bersama_fakultas',
            ],

            // UMUM UNIVERSITAS
            [
                'kode' => 'LAB-CBT-PUSAT',
                'nama' => 'Integrated Computer Based Test (CBT) Center',
                'lokasi' => 'Gedung PUSTIKOM Lt. 2',
                'kapasitas' => 80,
                'fakultas_id' => null,
                'program_studi_id' => null,
                'tipe_ruang' => 'laboratorium',
                'cakupan_akses' => 'umum_universitas',
            ],
        ];

        $ruangModels = [];
        foreach ($ruangData as $r) {
            $multiProdis = $r['multi_prodis'] ?? [];

            $ruang = RuangPraktikum::updateOrCreate(
                ['kode_ruang' => $r['kode']],
                [
                    'nama_ruang' => $r['nama'],
                    'lokasi' => $r['lokasi'],
                    'kapasitas' => $r['kapasitas'],
                    'fakultas_id' => $r['fakultas_id'],
                    'program_studi_id' => $r['program_studi_id'],
                    'tipe_ruang' => $r['tipe_ruang'],
                    'cakupan_akses' => $r['cakupan_akses'],
                    'status' => 'aktif',
                ]
            );

            if (! empty($multiProdis)) {
                $ruang->prodiPengguna()->sync(array_filter($multiProdis));
            }

            $ruangModels[$r['kode']] = $ruang;
        }

        // =========================================================================
        // 5. SEED MATA KULIAH PRAKTIKUM (22 Mata Kuliah)
        // =========================================================================
        $mkData = [
            // IF
            ['kode' => 'IF-101', 'nama' => 'Praktikum Algoritma & Pemrograman', 'prodi' => $prodiIF?->id, 'sks' => 3, 'smt' => 1],
            ['kode' => 'IF-201', 'nama' => 'Praktikum Basis Data Terdistribusi', 'prodi' => $prodiIF?->id, 'sks' => 3, 'smt' => 3],
            ['kode' => 'IF-202', 'nama' => 'Praktikum Pemrograman Web Fullstack', 'prodi' => $prodiIF?->id, 'sks' => 3, 'smt' => 3],
            ['kode' => 'IF-301', 'nama' => 'Praktikum Jaringan Komputer & Cyber Security', 'prodi' => $prodiIF?->id, 'sks' => 3, 'smt' => 5],
            ['kode' => 'IF-302', 'nama' => 'Praktikum Machine Learning & Kecerdasan Buatan', 'prodi' => $prodiIF?->id, 'sks' => 3, 'smt' => 5],

            // SI
            ['kode' => 'SI-201', 'nama' => 'Praktikum Analisis & Desain Sistem', 'prodi' => $prodiSI?->id, 'sks' => 3, 'smt' => 3],
            ['kode' => 'SI-301', 'nama' => 'Praktikum Sistem Informasi Enterprise (ERP)', 'prodi' => $prodiSI?->id, 'sks' => 3, 'smt' => 5],
            ['kode' => 'SI-302', 'nama' => 'Praktikum UI/UX & Interaksi Manusia Komputer', 'prodi' => $prodiSI?->id, 'sks' => 2, 'smt' => 5],

            // PTI
            ['kode' => 'PTI-201', 'nama' => 'Praktikum Media Pembelajaran Digital', 'prodi' => $prodiPTI?->id, 'sks' => 3, 'smt' => 3],
            ['kode' => 'PTI-301', 'nama' => 'Praktikum Animasi & Desain Edukasi', 'prodi' => $prodiPTI?->id, 'sks' => 3, 'smt' => 5],

            // PAI & PBA
            ['kode' => 'PAI-301', 'nama' => 'Praktikum Microteaching PAI', 'prodi' => $prodiPAI?->id, 'sks' => 2, 'smt' => 5],
            ['kode' => 'PBA-201', 'nama' => 'Praktikum Lab Bahasa Arab Multimedia', 'prodi' => $prodiPBA?->id, 'sks' => 2, 'smt' => 3],

            // FEBI (PBS, AKS, ES)
            ['kode' => 'PBS-201', 'nama' => 'Praktikum Operasional Core Banking Syariah', 'prodi' => $prodiPBS?->id, 'sks' => 3, 'smt' => 3],
            ['kode' => 'PBS-301', 'nama' => 'Praktikum Analisis Pembiayaan & Mitigasi Risiko', 'prodi' => $prodiPBS?->id, 'sks' => 3, 'smt' => 5],
            ['kode' => 'AKS-201', 'nama' => 'Praktikum Komputer Akuntansi (Accurate & MYOB)', 'prodi' => $prodiAKS?->id, 'sks' => 3, 'smt' => 3],
            ['kode' => 'AKS-301', 'nama' => 'Praktikum Audit Sistem Informasi Keuangan', 'prodi' => $prodiAKS?->id, 'sks' => 3, 'smt' => 5],
            ['kode' => 'ES-301',  'nama' => 'Praktikum Analisis Ekonometrika & SPSS', 'prodi' => $prodiES?->id, 'sks' => 3, 'smt' => 5],

            // FASYA (HKI, HES, HTN)
            ['kode' => 'HKI-301', 'nama' => 'Praktikum Peradilan Semu & Hukum Acara', 'prodi' => $prodiHKI?->id, 'sks' => 3, 'smt' => 5],
            ['kode' => 'HES-301', 'nama' => 'Praktikum Legal Drafting & Kontrak Syariah', 'prodi' => $prodiHES?->id, 'sks' => 3, 'smt' => 5],
            ['kode' => 'HTN-201', 'nama' => 'Praktikum Simulasi Sidang Mahkamah Konstitusi', 'prodi' => $prodiHTN?->id, 'sks' => 2, 'smt' => 3],

            // FUA (TP)
            ['kode' => 'TP-301',  'nama' => 'Praktikum Konseling Sufistik & Relaksasi Diri', 'prodi' => $prodiTP?->id, 'sks' => 3, 'smt' => 5],

            // FDKI (KPI, BKI)
            ['kode' => 'KPI-201', 'nama' => 'Praktikum Produksi Siaran TV & Podcast', 'prodi' => $prodiKPI?->id, 'sks' => 3, 'smt' => 3],
            ['kode' => 'BKI-301', 'nama' => 'Praktikum Konseling Interpersonal & Asesmen', 'prodi' => $prodiBKI?->id, 'sks' => 3, 'smt' => 5],
        ];

        $mkModels = [];
        foreach ($mkData as $mk) {
            if (! $mk['prodi']) {
                continue;
            }
            $mkModels[$mk['kode']] = MataKuliah::updateOrCreate(
                ['kode_mk' => $mk['kode']],
                [
                    'nama_mk' => $mk['nama'],
                    'program_studi_id' => $mk['prodi'],
                    'sks' => $mk['sks'],
                    'semester' => $mk['smt'],
                    'status' => 'aktif',
                ]
            );
        }

        // =========================================================================
        // 6. SEED KELAS (22 Kelas)
        // =========================================================================
        $kelasData = [
            ['nama' => 'IF-1A',  'prodi' => $prodiIF?->id,  'smt' => 1],
            ['nama' => 'IF-3A',  'prodi' => $prodiIF?->id,  'smt' => 3],
            ['nama' => 'IF-3B',  'prodi' => $prodiIF?->id,  'smt' => 3],
            ['nama' => 'IF-5A',  'prodi' => $prodiIF?->id,  'smt' => 5],
            ['nama' => 'IF-5B',  'prodi' => $prodiIF?->id,  'smt' => 5],
            ['nama' => 'SI-3A',  'prodi' => $prodiSI?->id,  'smt' => 3],
            ['nama' => 'SI-5A',  'prodi' => $prodiSI?->id,  'smt' => 5],
            ['nama' => 'PTI-3A', 'prodi' => $prodiPTI?->id, 'smt' => 3],
            ['nama' => 'PTI-5A', 'prodi' => $prodiPTI?->id, 'smt' => 5],
            ['nama' => 'PAI-5A', 'prodi' => $prodiPAI?->id, 'smt' => 5],
            ['nama' => 'PBA-3A', 'prodi' => $prodiPBA?->id, 'smt' => 3],
            ['nama' => 'PBS-3A', 'prodi' => $prodiPBS?->id, 'smt' => 3],
            ['nama' => 'PBS-5A', 'prodi' => $prodiPBS?->id, 'smt' => 5],
            ['nama' => 'AKS-3A', 'prodi' => $prodiAKS?->id, 'smt' => 3],
            ['nama' => 'AKS-5A', 'prodi' => $prodiAKS?->id, 'smt' => 5],
            ['nama' => 'ES-5A',  'prodi' => $prodiES?->id,  'smt' => 5],
            ['nama' => 'HKI-5A', 'prodi' => $prodiHKI?->id, 'smt' => 5],
            ['nama' => 'HES-5A', 'prodi' => $prodiHES?->id, 'smt' => 5],
            ['nama' => 'HTN-3A', 'prodi' => $prodiHTN?->id, 'smt' => 3],
            ['nama' => 'TP-5A',  'prodi' => $prodiTP?->id,  'smt' => 5],
            ['nama' => 'KPI-3A', 'prodi' => $prodiKPI?->id, 'smt' => 3],
            ['nama' => 'BKI-5A', 'prodi' => $prodiBKI?->id, 'smt' => 5],
        ];

        $kelasModels = [];
        foreach ($kelasData as $k) {
            if (! $k['prodi']) {
                continue;
            }
            $kelasModels[$k['nama']] = Kelas::updateOrCreate(
                ['nama_kelas' => $k['nama'], 'program_studi_id' => $k['prodi']],
                [
                    'semester' => $k['smt'],
                    'status' => 'aktif',
                ]
            );
        }

        // =========================================================================
        // 7. SEED JADWAL PRAKTIKUM (32 Sesi Terjadwal Rapi & Bebas Bentrok)
        // =========================================================================
        $jadwalData = [
            // Senin Pagi (08:00 - 10:30)
            [
                'mk' => 'IF-101', 'kls' => 'IF-1A', 'dsn' => '0945198705', 'rng' => 'LAB-KOM-A',
                'hari' => 'Senin', 'mulai' => '08:00:00', 'selesai' => '10:30:00', 'status' => 'aktif',
            ],
            [
                'mk' => 'SI-201', 'kls' => 'SI-3A', 'dsn' => '0725048802', 'rng' => 'LAB-KOM-B',
                'hari' => 'Senin', 'mulai' => '08:00:00', 'selesai' => '10:30:00', 'status' => 'aktif',
            ],
            [
                'mk' => 'PBS-201', 'kls' => 'PBS-3A', 'dsn' => '0615108001', 'rng' => 'LAB-BANK',
                'hari' => 'Senin', 'mulai' => '08:00:00', 'selesai' => '10:30:00', 'status' => 'aktif',
            ],
            [
                'mk' => 'HKI-301', 'kls' => 'HKI-5A', 'dsn' => '0110037802', 'rng' => 'LAB-MOOT',
                'hari' => 'Senin', 'mulai' => '08:00:00', 'selesai' => '10:30:00', 'status' => 'aktif',
            ],
            [
                'mk' => 'KPI-201', 'kls' => 'KPI-3A', 'dsn' => '0317058601', 'rng' => 'LAB-BROADCAST',
                'hari' => 'Senin', 'mulai' => '08:00:00', 'selesai' => '10:30:00', 'status' => 'aktif',
            ],

            // Senin Siang (10:30 - 13:00)
            [
                'mk' => 'IF-201', 'kls' => 'IF-3A', 'dsn' => '0412087501', 'rng' => 'LAB-KOM-A',
                'hari' => 'Senin', 'mulai' => '10:30:00', 'selesai' => '13:00:00', 'status' => 'aktif',
            ],
            [
                'mk' => 'PTI-201', 'kls' => 'PTI-3A', 'dsn' => '0019039103', 'rng' => 'LAB-MM',
                'hari' => 'Senin', 'mulai' => '10:30:00', 'selesai' => '13:00:00', 'status' => 'aktif',
            ],
            [
                'mk' => 'AKS-201', 'kls' => 'AKS-3A', 'dsn' => '0718068903', 'rng' => 'LAB-ACC',
                'hari' => 'Senin', 'mulai' => '10:30:00', 'selesai' => '13:00:00', 'status' => 'aktif',
            ],
            [
                'mk' => 'TP-301', 'kls' => 'TP-5A', 'dsn' => '0709018702', 'rng' => 'LAB-KONSEL',
                'hari' => 'Senin', 'mulai' => '10:30:00', 'selesai' => '13:00:00', 'status' => 'aktif',
            ],

            // Senin Sore (13:30 - 16:00)
            [
                'mk' => 'IF-301', 'kls' => 'IF-5A', 'dsn' => '0019039103', 'rng' => 'LAB-KOM-B',
                'hari' => 'Senin', 'mulai' => '13:30:00', 'selesai' => '16:00:00', 'status' => 'aktif',
            ],
            [
                'mk' => 'ES-301', 'kls' => 'ES-5A', 'dsn' => '0514119001', 'rng' => 'LAB-BEI',
                'hari' => 'Senin', 'mulai' => '13:30:00', 'selesai' => '16:00:00', 'status' => 'aktif',
            ],

            // Selasa Pagi (08:00 - 10:30)
            [
                'mk' => 'IF-202', 'kls' => 'IF-3B', 'dsn' => '0945198705', 'rng' => 'LAB-KOM-A',
                'hari' => 'Selasa', 'mulai' => '08:00:00', 'selesai' => '10:30:00', 'status' => 'aktif',
            ],
            [
                'mk' => 'SI-302', 'kls' => 'SI-5A', 'dsn' => '0725048802', 'rng' => 'LAB-MM',
                'hari' => 'Selasa', 'mulai' => '08:00:00', 'selesai' => '10:30:00', 'status' => 'aktif',
            ],
            [
                'mk' => 'PBS-301', 'kls' => 'PBS-5A', 'dsn' => '0022078602', 'rng' => 'LAB-BANK',
                'hari' => 'Selasa', 'mulai' => '08:00:00', 'selesai' => '10:30:00', 'status' => 'aktif',
            ],
            [
                'mk' => 'HES-301', 'kls' => 'HES-5A', 'dsn' => '0420088501', 'rng' => 'LAB-MOOT',
                'hari' => 'Selasa', 'mulai' => '08:00:00', 'selesai' => '10:30:00', 'status' => 'aktif',
            ],

            // Selasa Siang (10:30 - 13:00)
            [
                'mk' => 'IF-302', 'kls' => 'IF-5A', 'dsn' => '0412087501', 'rng' => 'LAB-KOM-A',
                'hari' => 'Selasa', 'mulai' => '10:30:00', 'selesai' => '13:00:00', 'status' => 'aktif',
            ],
            [
                'mk' => 'AKS-301', 'kls' => 'AKS-5A', 'dsn' => '0718068903', 'rng' => 'LAB-ACC',
                'hari' => 'Selasa', 'mulai' => '10:30:00', 'selesai' => '13:00:00', 'status' => 'reschedule',
            ],
            [
                'mk' => 'PAI-301', 'kls' => 'PAI-5A', 'dsn' => '0011096501', 'rng' => 'LAB-MICRO',
                'hari' => 'Selasa', 'mulai' => '10:30:00', 'selesai' => '13:00:00', 'status' => 'aktif',
            ],

            // Rabu Pagi (08:00 - 10:30)
            [
                'mk' => 'SI-301', 'kls' => 'SI-5A', 'dsn' => '0725048802', 'rng' => 'LAB-KOM-B',
                'hari' => 'Rabu', 'mulai' => '08:00:00', 'selesai' => '10:30:00', 'status' => 'aktif',
            ],
            [
                'mk' => 'PTI-301', 'kls' => 'PTI-5A', 'dsn' => '0019039103', 'rng' => 'LAB-MM',
                'hari' => 'Rabu', 'mulai' => '08:00:00', 'selesai' => '10:30:00', 'status' => 'aktif',
            ],
            [
                'mk' => 'PBA-201', 'kls' => 'PBA-3A', 'dsn' => '0814028304', 'rng' => 'LAB-FILOLOGI',
                'hari' => 'Rabu', 'mulai' => '08:00:00', 'selesai' => '10:30:00', 'status' => 'aktif',
            ],
            [
                'mk' => 'BKI-301', 'kls' => 'BKI-5A', 'dsn' => '0628098902', 'rng' => 'LAB-FOTO',
                'hari' => 'Rabu', 'mulai' => '08:00:00', 'selesai' => '10:30:00', 'status' => 'aktif',
            ],

            // Rabu Siang (10:30 - 13:00)
            [
                'mk' => 'IF-301', 'kls' => 'IF-5B', 'dsn' => '0019039103', 'rng' => 'LAB-KOM-B',
                'hari' => 'Rabu', 'mulai' => '10:30:00', 'selesai' => '13:00:00', 'status' => 'aktif',
            ],
            [
                'mk' => 'HTN-201', 'kls' => 'HTN-3A', 'dsn' => '0915129201', 'rng' => 'LAB-MOOT',
                'hari' => 'Rabu', 'mulai' => '10:30:00', 'selesai' => '13:00:00', 'status' => 'aktif',
            ],

            // Kamis Pagi (08:00 - 10:30)
            [
                'mk' => 'IF-202', 'kls' => 'IF-3A', 'dsn' => '0945198705', 'rng' => 'LAB-KOM-A',
                'hari' => 'Kamis', 'mulai' => '08:00:00', 'selesai' => '10:30:00', 'status' => 'aktif',
            ],
            [
                'mk' => 'PBS-201', 'kls' => 'PBS-3A', 'dsn' => '0615108001', 'rng' => 'LAB-BANK',
                'hari' => 'Kamis', 'mulai' => '08:00:00', 'selesai' => '10:30:00', 'status' => 'aktif',
            ],
            [
                'mk' => 'TP-301', 'kls' => 'TP-5A', 'dsn' => '0205067201', 'rng' => 'LAB-KONSEL',
                'hari' => 'Kamis', 'mulai' => '08:00:00', 'selesai' => '10:30:00', 'status' => 'aktif',
            ],

            // Kamis Siang (10:30 - 13:00)
            [
                'mk' => 'IF-302', 'kls' => 'IF-5B', 'dsn' => '0412087501', 'rng' => 'LAB-KOM-A',
                'hari' => 'Kamis', 'mulai' => '10:30:00', 'selesai' => '13:00:00', 'status' => 'aktif',
            ],
            [
                'mk' => 'KPI-201', 'kls' => 'KPI-3A', 'dsn' => '0012049104', 'rng' => 'LAB-BROADCAST',
                'hari' => 'Kamis', 'mulai' => '10:30:00', 'selesai' => '13:00:00', 'status' => 'dibatalkan',
            ],

            // Jum'at Pagi (08:00 - 10:30)
            [
                'mk' => 'IF-201', 'kls' => 'IF-3B', 'dsn' => '0412087501', 'rng' => 'LAB-KOM-A',
                'hari' => "Jum'at", 'mulai' => '08:00:00', 'selesai' => '10:30:00', 'status' => 'aktif',
            ],
            [
                'mk' => 'AKS-201', 'kls' => 'AKS-3A', 'dsn' => '0718068903', 'rng' => 'LAB-ACC',
                'hari' => "Jum'at", 'mulai' => '08:00:00', 'selesai' => '10:30:00', 'status' => 'aktif',
            ],
            [
                'mk' => 'SI-201', 'kls' => 'SI-3A', 'dsn' => '0725048802', 'rng' => 'LAB-KOM-B',
                'hari' => "Jum'at", 'mulai' => '08:00:00', 'selesai' => '10:30:00', 'status' => 'aktif',
            ],
        ];

        $jadwalModels = [];
        foreach ($jadwalData as $j) {
            $mkModel = $mkModels[$j['mk']] ?? null;
            $klsModel = $kelasModels[$j['kls']] ?? null;
            $dsnModel = $dosenModels[$j['dsn']] ?? null;
            $rngModel = $ruangModels[$j['rng']] ?? null;

            if (! $mkModel || ! $dsnModel || ! $rngModel) {
                continue;
            }

            $jadwal = JadwalPraktikum::updateOrCreate(
                [
                    'mata_kuliah_id' => $mkModel->id,
                    'kelas_id' => $klsModel?->id,
                    'dosen_id' => $dsnModel->id,
                    'ruang_praktikum_id' => $rngModel->id,
                    'tahun_akademik_id' => $tahunAkademik->id,
                    'hari' => $j['hari'],
                    'jam_mulai' => $j['mulai'],
                ],
                [
                    'jam_selesai' => $j['selesai'],
                    'status' => $j['status'],
                    'keterangan' => 'Sesi praktikum reguler semester ganjil.',
                ]
            );

            $jadwalModels[] = $jadwal;
        }

        // =========================================================================
        // 8. SEED PENGAJUAN PERUBAHAN JADWAL (6 Contoh Kasus Nyata)
        // =========================================================================
        if (count($jadwalModels) >= 4) {
            $pengajuanData = [
                [
                    'jadwal_idx' => 0,
                    'jenis' => 'ganti_jadwal',
                    'perubahan' => 'Pindah ke hari Rabu pukul 10:30 - 13:00',
                    'alasan' => 'Dosen bertugas sebagai penguji sidang tesis di universitas mitra pada hari Senin pagi.',
                    'status' => 'menunggu',
                    'catatan' => null,
                ],
                [
                    'jadwal_idx' => 1,
                    'jenis' => 'ganti_ruang',
                    'perubahan' => 'Pindah ke Lab Multimedia (LAB-MM)',
                    'alasan' => 'Mata kuliah memerlukan spesifikasi GPU rendering yang lebih tinggi untuk materi Figma & Blender.',
                    'status' => 'disetujui',
                    'catatan' => null,
                ],
                [
                    'jadwal_idx' => 2,
                    'jenis' => 'reschedule',
                    'perubahan' => 'Reschedule sesi pertemuan ke-4 pada Sabtu, 21 November 2026 jam 09:00',
                    'alasan' => 'Dosen menghadiri Konferensi Nasional Perbankan Syariah di Jakarta.',
                    'status' => 'menunggu',
                    'catatan' => null,
                ],
                [
                    'jadwal_idx' => 3,
                    'jenis' => 'ganti_jadwal_ruang',
                    'perubahan' => 'Pindah ke hari Kamis jam 13:30 di Ruang Moot Court Lt. 2',
                    'alasan' => 'Waktu bentrok dengan agenda pendampingan LBH Mahasiswa.',
                    'status' => 'ditolak',
                    'catatan' => 'Ruang Moot Court pada hari Kamis jam 13:30 sudah terisi penuh oleh praktikum HKI-5B.',
                ],
                [
                    'jadwal_idx' => 4,
                    'jenis' => 'ganti_jadwal',
                    'perubahan' => 'Pindah ke Jum\'at jam 08:30 - 11:00',
                    'alasan' => 'Jadwal bertabrakan dengan siaran langsung TVRI regional.',
                    'status' => 'menunggu',
                    'catatan' => null,
                ],
                [
                    'jadwal_idx' => 5,
                    'jenis' => 'reschedule',
                    'perubahan' => 'Kuliah pengganti pada tanggal 18 November 2026 jam 13:30',
                    'alasan' => 'Perbaikan instalasi jaringan kabel LAN di Lab Komputer A pada jam perkuliahan reguler.',
                    'status' => 'disetujui',
                    'catatan' => null,
                ],
            ];

            foreach ($pengajuanData as $p) {
                if (! isset($jadwalModels[$p['jadwal_idx']])) {
                    continue;
                }
                $targetJadwal = $jadwalModels[$p['jadwal_idx']];

                PengajuanPerubahan::updateOrCreate(
                    [
                        'jadwal_praktikum_id' => $targetJadwal->id,
                        'dosen_id' => $targetJadwal->dosen_id,
                        'perubahan' => $p['perubahan'],
                    ],
                    [
                        'jenis_perubahan' => $p['jenis'],
                        'alasan' => $p['alasan'],
                        'status' => $p['status'],
                        'catatan_penolakan' => $p['catatan'],
                    ]
                );
            }
        }
    }
}
