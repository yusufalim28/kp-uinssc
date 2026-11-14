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

class DemoMultiFakultasSeeder extends Seeder
{
    public function run(): void
    {
        $tahun = TahunAkademik::where('is_aktif', 1)->first() ?? TahunAkademik::first();
        if (! $tahun) {
            $this->command->error('Tahun Akademik tidak ditemukan.');

            return;
        }

        // ==========================================
        // 1. FAKULTAS ILMU TARBIYAH DAN KEGURUAN (FITK)
        // ==========================================
        $fitk = Fakultas::where('kode_fakultas', 'FITK')->first();
        $jadwalFitkUntukPengajuan = null;

        if ($fitk) {
            $labFitk1 = RuangPraktikum::updateOrCreate(
                ['kode_ruang' => 'LAB-FITK-01'],
                [
                    'fakultas_id' => $fitk->id,
                    'program_studi_id' => null,
                    'nama_ruang' => 'Laboratorium Komputer & Multimedia FITK',
                    'lokasi' => 'Gedung FITK Lt. 3 Sayap Barat',
                    'kapasitas' => 45,
                    'tipe_kapasitas' => 'tetap',
                    'kapasitas_maksimal' => null,
                    'tipe_ruang' => 'laboratorium',
                    'cakupan_akses' => 'bersama_fakultas',
                    'status' => 'aktif',
                    'keterangan' => 'Laboratorium komputer untuk praktikum pemrograman, multimedia pembelajaran, dan CBT.',
                ]
            );

            $labFitk2 = RuangPraktikum::updateOrCreate(
                ['kode_ruang' => 'LAB-FITK-02'],
                [
                    'fakultas_id' => $fitk->id,
                    'program_studi_id' => null,
                    'nama_ruang' => 'Laboratorium Microteaching & Bahasa FITK',
                    'lokasi' => 'Gedung FITK Lt. 2 Ruang 204',
                    'kapasitas' => 35,
                    'tipe_kapasitas' => 'fleksibel',
                    'kapasitas_maksimal' => 45,
                    'tipe_ruang' => 'laboratorium',
                    'cakupan_akses' => 'bersama_fakultas',
                    'status' => 'aktif',
                    'keterangan' => 'Dilengkapi kamera perekam simulasi mengajar dan audio laboratorium bahasa interaktif.',
                ]
            );

            $dosenFitk1 = $this->createOrGetDosen('FITK-D01', 'Dr. Farhan Hidayat, M.Pd.', 'farhan.hidayat@uinssc.ac.id', 'dosen', $fitk->id);
            $dosenFitk2 = $this->createOrGetDosen('FITK-D02', 'Nisaul Mardhiyyah, M.Kom.', 'nisaul.m@uinssc.ac.id', 'dosen', $fitk->id);
            $tutorFitk = $this->createOrGetDosen('TUTOR-FITK-01', 'M. Rizky Ramadhan, S.Pd.', 'rizky.tutorfitk@gmail.com', 'tutor', $fitk->id);

            $prodiIf = ProgramStudi::where('fakultas_id', $fitk->id)->where('kode_program_studi', 'IF')->first();
            $prodiPti = ProgramStudi::where('fakultas_id', $fitk->id)->where('kode_program_studi', 'PTI')->first();
            $prodiPai = ProgramStudi::where('fakultas_id', $fitk->id)->where('kode_program_studi', 'PAI')->first();

            if ($prodiIf) {
                $mkIf1 = MataKuliah::updateOrCreate(
                    ['kode_mk' => 'IF-301'],
                    [
                        'program_studi_id' => $prodiIf->id,
                        'nama_mk' => 'Praktikum Struktur Data & Algoritma',
                        'sks' => 2,
                        'semester' => 3,
                        'status' => 'aktif',
                        'tautan_modul' => 'https://drive.google.com/drive/folders/modul-if301-struktur-data',
                        'deskripsi_modul' => 'Mahasiswa wajib membaca Modul 1–3 mengenai Linked List, Stack, dan Queue serta membawa laptop/akun Git sebelum sesi lab dimulai.',
                    ]
                );
                $mkIf2 = MataKuliah::updateOrCreate(
                    ['kode_mk' => 'IF-502'],
                    [
                        'program_studi_id' => $prodiIf->id,
                        'nama_mk' => 'Praktikum Jaringan Komputer & Cloud',
                        'sks' => 2,
                        'semester' => 5,
                        'status' => 'aktif',
                        'tautan_modul' => 'https://drive.google.com/drive/folders/modul-if502-jarkom',
                        'deskripsi_modul' => 'Pastikan Cisco Packet Tracer & Wireshark sudah terpasang di PC laboratorium atau perangkat pribadi.',
                    ]
                );

                $klsIfA = Kelas::firstOrCreate(['program_studi_id' => $prodiIf->id, 'nama_kelas' => 'IF-3A'], ['semester' => 3, 'status' => 'aktif']);
                $klsIfB = Kelas::firstOrCreate(['program_studi_id' => $prodiIf->id, 'nama_kelas' => 'IF-3B'], ['semester' => 3, 'status' => 'aktif']);
                $klsIfC = Kelas::firstOrCreate(['program_studi_id' => $prodiIf->id, 'nama_kelas' => 'IF-5A'], ['semester' => 5, 'status' => 'aktif']);

                $this->createJadwal($mkIf1, $klsIfA, $dosenFitk2, $labFitk1, $tahun, 'Senin', '08:00:00', '09:40:00');
                $this->createJadwal($mkIf1, $klsIfB, $tutorFitk, $labFitk1, $tahun, 'Senin', '10:00:00', '11:40:00');
                $this->createJadwal($mkIf2, $klsIfC, $dosenFitk2, $labFitk1, $tahun, 'Rabu', '13:00:00', '15:30:00');
            }

            if ($prodiPti) {
                $mkPti = MataKuliah::updateOrCreate(
                    ['kode_mk' => 'PTI-301'],
                    [
                        'program_studi_id' => $prodiPti->id,
                        'nama_mk' => 'Praktikum Media Pembelajaran Interaktif',
                        'sks' => 2,
                        'semester' => 3,
                        'status' => 'aktif',
                    ]
                );
                $klsPtiA = Kelas::firstOrCreate(['program_studi_id' => $prodiPti->id, 'nama_kelas' => 'PTI-3A'], ['semester' => 3, 'status' => 'aktif']);

                $this->createJadwal($mkPti, $klsPtiA, $tutorFitk, $labFitk1, $tahun, "Jum'at", '08:00:00', '10:30:00');
            }

            if ($prodiPai) {
                $mkPai = MataKuliah::updateOrCreate(
                    ['kode_mk' => 'PAI-503'],
                    [
                        'program_studi_id' => $prodiPai->id,
                        'nama_mk' => 'Praktikum Microteaching PAI',
                        'sks' => 2,
                        'semester' => 5,
                        'status' => 'aktif',
                        'deskripsi_modul' => 'Mahasiswa menyiapkan RPP dan bahan ajar microteaching berdurasi 15 menit sesuai daftar urutan tampil.',
                    ]
                );
                $klsPaiA = Kelas::firstOrCreate(['program_studi_id' => $prodiPai->id, 'nama_kelas' => 'PAI-5A'], ['semester' => 5, 'status' => 'aktif']);
                $klsPaiB = Kelas::firstOrCreate(['program_studi_id' => $prodiPai->id, 'nama_kelas' => 'PAI-5B'], ['semester' => 5, 'status' => 'aktif']);

                $this->createJadwal($mkPai, $klsPaiA, $dosenFitk1, $labFitk2, $tahun, 'Selasa', '08:00:00', '10:30:00');
                $jadwalFitkUntukPengajuan = $this->createJadwal($mkPai, $klsPaiB, $dosenFitk1, $labFitk2, $tahun, 'Kamis', '08:00:00', '10:30:00');
            }
        }

        // ==========================================
        // 2. FAKULTAS SYARIAH (FASYA)
        // ==========================================
        $fasya = Fakultas::where('kode_fakultas', 'FASYA')->first();
        $jadwalFasyaUntukPengajuan = null;

        if ($fasya) {
            $labFasya1 = RuangPraktikum::updateOrCreate(
                ['kode_ruang' => 'LAB-FASYA-01'],
                [
                    'fakultas_id' => $fasya->id,
                    'program_studi_id' => null,
                    'nama_ruang' => 'Laboratorium Peradilan Semu (Moot Court) FASYA',
                    'lokasi' => 'Gedung FASYA Lt. 2 Ruang Sidang',
                    'kapasitas' => 50,
                    'tipe_kapasitas' => 'fleksibel',
                    'kapasitas_maksimal' => 60,
                    'tipe_ruang' => 'auditorium',
                    'cakupan_akses' => 'bersama_fakultas',
                    'status' => 'aktif',
                    'keterangan' => 'Dilengkapi toga hakim, mikrofon persidangan, podium saksi dan tata ruang persidangan pengadilan agama/negeri.',
                ]
            );

            $labFasya2 = RuangPraktikum::updateOrCreate(
                ['kode_ruang' => 'LAB-FASYA-02'],
                [
                    'fakultas_id' => $fasya->id,
                    'program_studi_id' => null,
                    'nama_ruang' => 'Laboratorium Ilmu Falak & Observatorium FASYA',
                    'lokasi' => 'Rooftop Gedung FASYA Lt. 4',
                    'kapasitas' => 35,
                    'tipe_kapasitas' => 'tetap',
                    'kapasitas_maksimal' => null,
                    'tipe_ruang' => 'laboratorium',
                    'cakupan_akses' => 'bersama_fakultas',
                    'status' => 'aktif',
                    'keterangan' => 'Peralatan teleskop rukyatul hilal, theodolit digital, kompas kiblat, dan software hisab astronomi.',
                ]
            );

            $dosenFasya1 = $this->createOrGetDosen('FASYA-D01', 'Dr. H. Mahrus Ali, M.H.I.', 'mahrus.ali@uinssc.ac.id', 'dosen', $fasya->id);
            $dosenFasya2 = $this->createOrGetDosen('FASYA-D02', 'Ahmad Zaki Mubarak, M.H.', 'zaki.mubarak@uinssc.ac.id', 'dosen', $fasya->id);
            $tutorFasya = $this->createOrGetDosen('TUTOR-FASYA-01', 'H. Syarif Hidayatullah, S.H.', 'syarif.falak@gmail.com', 'tutor', $fasya->id);

            $prodiHki = ProgramStudi::where('fakultas_id', $fasya->id)->where('kode_program_studi', 'HKI')->first();
            $prodiHes = ProgramStudi::where('fakultas_id', $fasya->id)->where('kode_program_studi', 'HES')->first();
            $prodiHtn = ProgramStudi::where('fakultas_id', $fasya->id)->where('kode_program_studi', 'HTN')->first();

            if ($prodiHki) {
                $mkHki1 = MataKuliah::updateOrCreate(
                    ['kode_mk' => 'HKI-501'],
                    [
                        'program_studi_id' => $prodiHki->id,
                        'nama_mk' => 'Praktikum Kemahiran Hukum & Peradilan Semu',
                        'sks' => 2,
                        'semester' => 5,
                        'status' => 'aktif',
                        'tautan_modul' => 'https://drive.google.com/drive/folders/modul-hki501-moot-court',
                        'deskripsi_modul' => 'Mahasiswa membawa berkas gugatan, jawaban, replik-duplik, dan mengenakan pakaian sidang sesuai peran majelis hakim / advokat.',
                    ]
                );
                $mkHki2 = MataKuliah::updateOrCreate(
                    ['kode_mk' => 'HKI-502'],
                    [
                        'program_studi_id' => $prodiHki->id,
                        'nama_mk' => 'Praktikum Hisab Rukyat & Penentuan Arah Kiblat',
                        'sks' => 2,
                        'semester' => 5,
                        'status' => 'aktif',
                    ]
                );

                $klsHkiA = Kelas::firstOrCreate(['program_studi_id' => $prodiHki->id, 'nama_kelas' => 'HKI-5A'], ['semester' => 5, 'status' => 'aktif']);
                $klsHkiB = Kelas::firstOrCreate(['program_studi_id' => $prodiHki->id, 'nama_kelas' => 'HKI-5B'], ['semester' => 5, 'status' => 'aktif']);

                $this->createJadwal($mkHki1, $klsHkiA, $dosenFasya1, $labFasya1, $tahun, 'Senin', '08:00:00', '10:30:00');
                $this->createJadwal($mkHki1, $klsHkiB, $dosenFasya1, $labFasya1, $tahun, 'Rabu', '08:00:00', '10:30:00');
                $this->createJadwal($mkHki2, $klsHkiA, $tutorFasya, $labFasya2, $tahun, 'Kamis', '13:00:00', '15:30:00');
            }

            if ($prodiHes) {
                $mkHes = MataKuliah::updateOrCreate(
                    ['kode_mk' => 'HES-501'],
                    [
                        'program_studi_id' => $prodiHes->id,
                        'nama_mk' => 'Praktikum Penyelesaian Sengketa Ekonomi Syariah',
                        'sks' => 2,
                        'semester' => 5,
                        'status' => 'aktif',
                    ]
                );
                $klsHesA = Kelas::firstOrCreate(['program_studi_id' => $prodiHes->id, 'nama_kelas' => 'HES-5A'], ['semester' => 5, 'status' => 'aktif']);

                $this->createJadwal($mkHes, $klsHesA, $dosenFasya2, $labFasya1, $tahun, "Jum'at", '08:00:00', '10:30:00');
            }

            if ($prodiHtn) {
                $mkHtn = MataKuliah::updateOrCreate(
                    ['kode_mk' => 'HTN-501'],
                    [
                        'program_studi_id' => $prodiHtn->id,
                        'nama_mk' => 'Praktikum Legal Drafting & Uji Materiil MK',
                        'sks' => 2,
                        'semester' => 5,
                        'status' => 'aktif',
                    ]
                );
                $klsHtnA = Kelas::firstOrCreate(['program_studi_id' => $prodiHtn->id, 'nama_kelas' => 'HTN-5A'], ['semester' => 5, 'status' => 'aktif']);

                $jadwalFasyaUntukPengajuan = $this->createJadwal($mkHtn, $klsHtnA, $dosenFasya2, $labFasya1, $tahun, 'Selasa', '13:00:00', '15:30:00');
            }
        }

        // ==========================================
        // 3. FAKULTAS DAKWAH DAN KOMUNIKASI ISLAM (FDKI)
        // ==========================================
        $fdki = Fakultas::where('kode_fakultas', 'FDKI')->first();
        $jadwalFdkiMenunggu = null;
        $jadwalFdkiDisetujui = null;

        if ($fdki) {
            $labFdki1 = RuangPraktikum::updateOrCreate(
                ['kode_ruang' => 'LAB-FDKI-01'],
                [
                    'fakultas_id' => $fdki->id,
                    'program_studi_id' => null,
                    'nama_ruang' => 'Laboratorium Studio Broadcasting & Podcast FDKI',
                    'lokasi' => 'Gedung FDKI Lt. 1 Ruang Studio A',
                    'kapasitas' => 30,
                    'tipe_kapasitas' => 'tetap',
                    'kapasitas_maksimal' => null,
                    'tipe_ruang' => 'studio',
                    'cakupan_akses' => 'bersama_fakultas',
                    'status' => 'aktif',
                    'keterangan' => 'Studio kedap suara dilengkapi mixer audio, kamera broadcast 4K, lighting pro, dan green screen.',
                ]
            );

            $labFdki2 = RuangPraktikum::updateOrCreate(
                ['kode_ruang' => 'LAB-FDKI-02'],
                [
                    'fakultas_id' => $fdki->id,
                    'program_studi_id' => null,
                    'nama_ruang' => 'Laboratorium Konseling & Terapi Psikososial FDKI',
                    'lokasi' => 'Gedung FDKI Lt. 2 Ruang Konseling B201',
                    'kapasitas' => 25,
                    'tipe_kapasitas' => 'tetap',
                    'kapasitas_maksimal' => null,
                    'tipe_ruang' => 'laboratorium',
                    'cakupan_akses' => 'bersama_fakultas',
                    'status' => 'aktif',
                    'keterangan' => 'Dilengkapi ruang one-way mirror untuk observasi sesi konseling individual dan kelompok.',
                ]
            );

            $dosenFdki1 = $this->createOrGetDosen('FDKI-D01', 'Dr. Rahmat Subarkah, M.Si.', 'rahmat.subarkah@uinssc.ac.id', 'dosen', $fdki->id);
            $dosenFdki2 = $this->createOrGetDosen('FDKI-D02', 'Dewi Anggraeni, M.Sos.', 'dewi.anggraeni@uinssc.ac.id', 'dosen', $fdki->id);
            $tutorFdki = $this->createOrGetDosen('TUTOR-FDKI-01', 'Bagas Satria, S.I.Kom.', 'bagas.broadcast@gmail.com', 'tutor', $fdki->id);

            $prodiKpi = ProgramStudi::where('fakultas_id', $fdki->id)->where('kode_program_studi', 'KPI')->first();
            $prodiBki = ProgramStudi::where('fakultas_id', $fdki->id)->where('kode_program_studi', 'BKI')->first();
            $prodiPmi = ProgramStudi::where('fakultas_id', $fdki->id)->where('kode_program_studi', 'PMI')->first();

            if ($prodiKpi) {
                $mkKpi1 = MataKuliah::updateOrCreate(
                    ['kode_mk' => 'KPI-301'],
                    [
                        'program_studi_id' => $prodiKpi->id,
                        'nama_mk' => 'Praktikum Produksi Siaran Radio & Podcast',
                        'sks' => 2,
                        'semester' => 3,
                        'status' => 'aktif',
                        'tautan_modul' => 'https://drive.google.com/drive/folders/modul-kpi301-broadcast',
                        'deskripsi_modul' => 'Setiap kelompok menyiapkan naskah rundown program audio berdurasi 10 menit sebelum masuk ruang rekaman studio.',
                    ]
                );
                $mkKpi2 = MataKuliah::updateOrCreate(
                    ['kode_mk' => 'KPI-502'],
                    [
                        'program_studi_id' => $prodiKpi->id,
                        'nama_mk' => 'Praktikum Jurnalistik Televisi & Sinematografi Dakwah',
                        'sks' => 2,
                        'semester' => 5,
                        'status' => 'aktif',
                    ]
                );

                $klsKpiA = Kelas::firstOrCreate(['program_studi_id' => $prodiKpi->id, 'nama_kelas' => 'KPI-3A'], ['semester' => 3, 'status' => 'aktif']);
                $klsKpiB = Kelas::firstOrCreate(['program_studi_id' => $prodiKpi->id, 'nama_kelas' => 'KPI-5A'], ['semester' => 5, 'status' => 'aktif']);

                $jadwalFdkiMenunggu = $this->createJadwal($mkKpi1, $klsKpiA, $tutorFdki, $labFdki1, $tahun, 'Senin', '13:00:00', '15:30:00');
                $jadwalFdkiDisetujui = $this->createJadwal($mkKpi2, $klsKpiB, $dosenFdki1, $labFdki1, $tahun, 'Kamis', '08:00:00', '11:00:00');
            }

            if ($prodiPmi) {
                $mkPmi = MataKuliah::updateOrCreate(
                    ['kode_mk' => 'PMI-301'],
                    [
                        'program_studi_id' => $prodiPmi->id,
                        'nama_mk' => 'Praktikum Pemetaan Sosial & Pemberdayaan Komunitas',
                        'sks' => 2,
                        'semester' => 3,
                        'status' => 'aktif',
                    ]
                );
                $klsPmiA = Kelas::firstOrCreate(['program_studi_id' => $prodiPmi->id, 'nama_kelas' => 'PMI-3A'], ['semester' => 3, 'status' => 'aktif']);

                $this->createJadwal($mkPmi, $klsPmiA, $tutorFdki, $labFdki1, $tahun, 'Selasa', '09:00:00', '11:30:00');
            }

            if ($prodiBki) {
                $mkBki = MataKuliah::updateOrCreate(
                    ['kode_mk' => 'BKI-501'],
                    [
                        'program_studi_id' => $prodiBki->id,
                        'nama_mk' => 'Praktikum Teknik Konseling Individual & Kelompok',
                        'sks' => 2,
                        'semester' => 5,
                        'status' => 'aktif',
                    ]
                );
                $klsBkiA = Kelas::firstOrCreate(['program_studi_id' => $prodiBki->id, 'nama_kelas' => 'BKI-5A'], ['semester' => 5, 'status' => 'aktif']);
                $klsBkiB = Kelas::firstOrCreate(['program_studi_id' => $prodiBki->id, 'nama_kelas' => 'BKI-5B'], ['semester' => 5, 'status' => 'aktif']);

                $this->createJadwal($mkBki, $klsBkiA, $dosenFdki2, $labFdki2, $tahun, 'Rabu', '08:00:00', '10:30:00');
                $this->createJadwal($mkBki, $klsBkiB, $dosenFdki2, $labFdki2, $tahun, "Jum'at", '08:00:00', '10:30:00');
            }
        }

        // ==========================================
        // 4. DATA PENGAJUAN PERUBAHAN JADWAL DEMO
        // Menggunakan kolom aktual tabel pengajuan_perubahan:
        // jadwal_praktikum_id, dosen_id, jenis_perubahan, perubahan, alasan, status, catatan_penolakan
        // ==========================================
        if ($jadwalFitkUntukPengajuan) {
            PengajuanPerubahan::updateOrCreate(
                [
                    'jadwal_praktikum_id' => $jadwalFitkUntukPengajuan->id,
                    'dosen_id' => $jadwalFitkUntukPengajuan->dosen_id,
                    'status' => 'menunggu',
                ],
                [
                    'jenis_perubahan' => 'ganti_jadwal',
                    'perubahan' => 'Pindah ke Hari Selasa (13:00 - 15:30)',
                    'alasan' => 'Menghadiri rapat koordinasi kurikulum nasional FITK di waktu yang bersamaan.',
                    'catatan_penolakan' => null,
                ]
            );
        }

        if ($jadwalFasyaUntukPengajuan) {
            PengajuanPerubahan::updateOrCreate(
                [
                    'jadwal_praktikum_id' => $jadwalFasyaUntukPengajuan->id,
                    'dosen_id' => $jadwalFasyaUntukPengajuan->dosen_id,
                    'status' => 'menunggu',
                ],
                [
                    'jenis_perubahan' => 'ganti_jadwal_ruang',
                    'perubahan' => 'Pindah ke Hari Kamis (08:00 - 10:30) di Laboratorium Ilmu Falak & Observatorium FASYA',
                    'alasan' => 'Permohonan penyesuaian ruangan sementara karena ruang peradilan semu dipakai gladi bersih lomba debat.',
                    'catatan_penolakan' => null,
                ]
            );
        }

        if ($jadwalFdkiMenunggu) {
            PengajuanPerubahan::updateOrCreate(
                [
                    'jadwal_praktikum_id' => $jadwalFdkiMenunggu->id,
                    'dosen_id' => $jadwalFdkiMenunggu->dosen_id,
                    'status' => 'menunggu',
                ],
                [
                    'jenis_perubahan' => 'reschedule',
                    'perubahan' => "Kuliah Pengganti pada Jum'at (13:00 - 15:30)",
                    'alasan' => 'Peralatan mixer audio studio digunakan sementara untuk siaran langsung Dies Natalis FDKI.',
                    'catatan_penolakan' => null,
                ]
            );
        }

        if ($jadwalFdkiDisetujui) {
            PengajuanPerubahan::updateOrCreate(
                [
                    'jadwal_praktikum_id' => $jadwalFdkiDisetujui->id,
                    'dosen_id' => $jadwalFdkiDisetujui->dosen_id,
                    'status' => 'disetujui',
                ],
                [
                    'jenis_perubahan' => 'ganti_ruang',
                    'perubahan' => 'Pindah ke Studio Broadcasting & Podcast FDKI',
                    'alasan' => 'Praktikum pengambilan gambar studio membutuhkan pencahayaan profesional.',
                    'catatan_penolakan' => 'Disetujui otomatis oleh sistem (Ruangan & Jadwal bebas bentrok)',
                ]
            );
        }

        $this->command->info('Data Dummy Demo untuk FITK, FASYA, dan FDKI berhasil dilengkapi!');
    }

    private function createOrGetDosen(string $nidn, string $nama, string $email, string $kategori, ?int $fakultasId = null): Dosen
    {
        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $nama,
                'fakultas_id' => $fakultasId,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        if (! $user->hasRole('dosen')) {
            $user->assignRole('dosen');
        }

        return Dosen::updateOrCreate(
            ['nidn' => $nidn],
            [
                'user_id' => $user->id,
                'nama' => $nama,
                'kategori' => $kategori,
                'email' => $email,
                'status' => 'aktif',
            ]
        );
    }

    private function createJadwal(
        MataKuliah $mk,
        Kelas $kls,
        Dosen $dosen,
        RuangPraktikum $ruang,
        TahunAkademik $tahun,
        string $hari,
        string $jamMulai,
        string $jamSelesai
    ): JadwalPraktikum {
        return JadwalPraktikum::firstOrCreate(
            [
                'mata_kuliah_id' => $mk->id,
                'kelas_id' => $kls->id,
                'tahun_akademik_id' => $tahun->id,
            ],
            [
                'dosen_id' => $dosen->id,
                'ruang_praktikum_id' => $ruang->id,
                'hari' => $hari,
                'jam_mulai' => $jamMulai,
                'jam_selesai' => $jamSelesai,
                'status' => 'aktif',
                'keterangan' => 'Sesi Praktikum Reguler Semester '.$tahun->semester.' T.A. '.$tahun->nama,
            ]
        );
    }
}
