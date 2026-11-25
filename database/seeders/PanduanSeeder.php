<?php

namespace Database\Seeders;

use App\Models\Panduan;
use Illuminate\Database\Seeder;

class PanduanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $panduanPublik = [
            [
                'kategori' => 'publik',
                'urutan' => 1,
                'judul' => 'Tata Tertib Berpakaian & Penampilan di Laboratorium',
                'subjudul' => 'Ketentuan standar pakaian rapi, jas laboratorium, dan penggunaan sepatu tertutup.',
                'konten' => '<p class="mb-3 text-gray-600 dark:text-zinc-400">Seluruh mahasiswa wajib berpenampilan rapi, sopan, dan mencerminkan etika akademik saat memasuki ruang laboratorium:</p>
<ul class="list-disc list-inside space-y-1.5 pl-1">
    <li><strong>Pakaian:</strong> Mengenakan kemeja atau baju berkerah yang rapi dan sopan. Dilarang keras memakai kaus oblong tanpa kerah atau pakaian yang robek.</li>
    <li><strong>Bawahan:</strong> Mengenakan celana panjang atau rok panjang berbahan sopan (bukan celana pendek atau kulot di atas mata kaki).</li>
    <li><strong>Alas Kaki:</strong> Wajib menggunakan sepatu tertutup dan berkaus kaki. Dilarang masuk menggunakan sandal jepit, selop, atau alas kaki terbuka.</li>
    <li><strong>Jas Laboratorium:</strong> Khusus praktikum sains atau sesi yang mensyaratkan alat pelindung diri (APD), mahasiswa wajib memakai jas laboratorium resmi berkancing rapi.</li>
    <li><strong>Tanda Pengenal:</strong> Membawa dan mengenakan Kartu Tanda Mahasiswa (KTM) yang masih berlaku selama sesi praktikum berlangsung.</li>
</ul>',
                'is_active' => true,
            ],
            [
                'kategori' => 'publik',
                'urutan' => 2,
                'judul' => 'Ketentuan Kehadiran & Kedisiplinan Waktu',
                'subjudul' => 'Batas waktu keterlambatan, presensi sesi, dan ketentuan izin tidak hadir.',
                'konten' => '<p class="mb-3 text-gray-600 dark:text-zinc-400">Ketepatan waktu dan presensi kehadiran merupakan syarat mutlak kelulusan modul praktikum:</p>
<ul class="list-disc list-inside space-y-1.5 pl-1">
    <li><strong>Kehadiran Tepat Waktu:</strong> Mahasiswa wajib hadir di ruang laboratorium selambat-lambatnya 10 menit sebelum jam praktikum dimulai.</li>
    <li><strong>Toleransi Keterlambatan:</strong> Keterlambatan maksimal adalah 15 menit dengan alasan yang dapat dipertanggungjawabkan kepada dosen pengampu / asisten lab. Keterlambatan melebihi batas tersebut tidak diizinkan mengikuti sesi pada hari itu.</li>
    <li><strong>Persentase Kehadiran:</strong> Mahasiswa wajib memenuhi minimal 80% kehadiran sesi praktikum dalam satu semester untuk berhak mengikuti evaluasi akhir/responsipraktikum.</li>
    <li><strong>Ketidakhadiran:</strong> Izin hanya berlaku apabila disertai surat keterangan dokter resmi (sakit) atau surat izin tertulis dari pimpinan fakultas/jurusan.</li>
</ul>',
                'is_active' => true,
            ],
            [
                'kategori' => 'publik',
                'urutan' => 3,
                'judul' => 'Etika, Kebersihan, & Pemeliharaan Fasilitas Lab',
                'subjudul' => 'Larangan makan/minum, larangan mengubah kabel/konfigurasi, dan prosedur selesai praktikum.',
                'konten' => '<p class="mb-3 text-gray-600 dark:text-zinc-400">Guna merawat sarana dan prasarana komputer dan perlengkapan sains bersama:</p>
<ul class="list-disc list-inside space-y-1.5 pl-1">
    <li><strong>Larangan Makanan & Minuman:</strong> Dilarang keras membawa makanan, minuman terbuka, permen karet, atau merokok di dalam ruang laboratorium komputer.</li>
    <li><strong>Integritas Perangkat Keras:</strong> Dilarang mencabut atau menukar kabel listrik, kabel VGA/HDMI, kabel LAN, mouse, atau keyboard antar workstation.</li>
    <li><strong>Keamanan Sistem:</strong> Dilarang mengunduh berkas berbahaya, memasang software bajakan tanpa izin, atau mengubah setelan jaringan dan sandi komputer laboratorium.</li>
    <li><strong>Prosedur Setelah Selesai:</strong> Mahasiswa wajib menutup semua aplikasi yang digunakan, mematikan (shut down) komputer, membuang sampah pada tempatnya, serta merapikan kembali kursi sebelum meninggalkan ruangan.</li>
    <li><strong>Tanggung Jawab Kerusakan:</strong> Kerusakan perangkat yang disebabkan oleh kelalaian atau kesengajaan mahasiswa menjadi tanggung jawab yang bersangkutan.</li>
</ul>',
                'is_active' => true,
            ],
            [
                'kategori' => 'publik',
                'urutan' => 4,
                'judul' => 'Panduan Membaca Jadwal, Modul Praktikum, & Visual Ruang',
                'subjudul' => 'Pencarian jadwal prodi, unduh modul praktikum resmi PDF, dan periksa ketersediaan ruang.',
                'konten' => '<p class="mb-3 text-gray-600 dark:text-zinc-400">Sistem informasi ini menyediakan portal terbuka tanpa login bagi seluruh mahasiswa:</p>
<ul class="list-disc list-inside space-y-1.5 pl-1">
    <li><strong>Daftar Jadwal:</strong> Menampilkan tabel dan kartu jadwal praktikum mingguan lengkap dengan mata kuliah, kelas, nama dosen pengampu, hari, jam, dan laboratorium.</li>
    <li><strong>Unduh Modul Praktikum:</strong> Mahasiswa dapat mengklik jadwal untuk melihat detail sesi dan langsung mengunduh Buku Petunjuk/Modul Praktikum berformat PDF.</li>
    <li><strong>Visual Jadwal Ruangan:</strong> Menampilkan matriks penggunaan lab per sesi (12 sesi harian) sehingga mahasiswa dan pengajar dapat mengetahui kapan lab kosong atau dipakai.</li>
    <li><strong>Unduh Jadwal Resmi:</strong> Tombol <em>Unduh PDF</em> dan <em>Unduh Excel</em> di halaman Daftar Jadwal memungkinkan ketua kelas mengunduh jadwal resmi per program studi.</li>
</ul>',
                'is_active' => true,
            ],
        ];

        // 1. Panduan Khusus Operator Fakultas
        $panduanOperator = [
            [
                'kategori' => 'operator',
                'urutan' => 1,
                'judul' => 'Langkah 1: Verifikasi Data Awal (NIDN Dosen & Kode MK)',
                'subjudul' => 'Langkah pertama saat pertama kali login ke sistem sebelum mulai menginput jadwal.',
                'konten' => '<div class="space-y-3">
    <p class="text-gray-600 dark:text-zinc-400">Saat sistem diserahterimakan, sebagian data dosen diinput dengan NIDN sementara (dummy) karena data awal yang masuk belum lengkap. Oleh karena itu, lakukan langkah berikut:</p>
    <ol class="list-decimal list-inside space-y-2 pl-1 font-medium">
        <li>Buka menu <strong>Dosen Pengampu</strong> di sidebar kiri.</li>
        <li>Cari nama dosen fakultas Anda, klik tombol <strong>Edit</strong>, lalu masukkan NIDN resmi atau NIP dosen bersangkutan. NIDN ini digunakan dosen sebagai kredensial login akun dosen mereka.</li>
        <li>Buka menu <strong>Mata Kuliah</strong>, periksa apakah kode mata kuliah (mis. FAS101, FITK201) sudah sesuai kurikulum aktif.</li>
        <li>Untuk mata kuliah bersama fakultas (seperti Peradilan Agama di FASYA), pastikan cakupannya disetel ke <em>Bersama Fakultas</em> agar dapat dipakai semua jurusan di fakultas Anda.</li>
    </ol>
</div>',
                'is_active' => true,
            ],
            [
                'kategori' => 'operator',
                'urutan' => 2,
                'judul' => 'Langkah 2: Menyiapkan Master Data (Kelas, Dosen, MK, Ruangan)',
                'subjudul' => 'Cara mengelola entitas penunjang sebelum melakukan plotting jadwal praktikum.',
                'konten' => '<div class="space-y-3">
    <p class="text-gray-600 dark:text-zinc-400">Pastikan seluruh data master telah siap sebelum masuk ke menu Kelola Jadwal:</p>
    <ul class="list-disc list-inside space-y-2 pl-1">
        <li><strong>Master Kelas:</strong> Tambahkan rombongan belajar mahasiswa per prodi (contoh: IF-1A, HKI-3B).</li>
        <li><strong>Master Dosen & Tutor:</strong> Tambahkan pengajar praktikum. Sistem mendukung kategori Dosen maupun Tutor/Asisten Laboratorium.</li>
        <li><strong>Master Mata Kuliah:</strong> Masukkan nama MK, SKS, semester, dan Anda dapat mengunggah <em>File Modul Praktikum PDF</em> agar mahasiswa dapat mengunduhnya secara mandiri.</li>
        <li><strong>Master Ruang Praktikum:</strong> Pastikan laboratorium berstatus <em>aktif</em>. Anda dapat mengunggah foto ruangan (JPG/PNG/HEIC hingga 20MB) serta menentukan kapasitas normal dan fleksibel.</li>
    </ul>
</div>',
                'is_active' => true,
            ],
            [
                'kategori' => 'operator',
                'urutan' => 3,
                'judul' => 'Langkah 3: Plotting Jadwal Praktikum',
                'subjudul' => 'Prosedur penentuan sesi lab dengan validasi hak akses ruangan dan deteksi bentrok otomatis.',
                'konten' => '<div class="space-y-3">
    <p class="text-gray-600 dark:text-zinc-400">Untuk menambahkan jadwal praktikum ke laboratorium:</p>
    <ol class="list-decimal list-inside space-y-2 pl-1">
        <li>Buka menu <strong>Kelola Jadwal</strong> lalu klik tombol <em>+ Tambah Jadwal</em>.</li>
        <li>Pilih Mata Kuliah, Kelas (opsional untuk praktikum gabungan), Dosen Pengampu, Ruang Praktikum, Tahun Akademik, Hari, serta Jam Mulai dan Jam Selesai.</li>
        <li>Klik <strong>Simpan Jadwal</strong>. Sistem otomatis memvalidasi izin pemakaian ruangan untuk program studi terkait serta mengecek apakah ruangan atau dosen bentrok dengan jadwal lain.</li>
    </ol>
</div>',
                'is_active' => true,
            ],
            [
                'kategori' => 'operator',
                'urutan' => 4,
                'judul' => 'Langkah 4: Meninjau Pengajuan Perubahan Jadwal dari Dosen',
                'subjudul' => 'Alur verifikasi saat dosen mengajukan permohonan reschedule atau penggantian sesi lab.',
                'konten' => '<div class="space-y-3">
    <p class="text-gray-600 dark:text-zinc-400">Ketika dosen pengampu berhalangan dan mengajukan permohonan pergantian jadwal:</p>
    <ol class="list-decimal list-inside space-y-2 pl-1">
        <li>Periksa badge angka notifikasi pada menu <strong>Pengajuan Perubahan</strong> di sidebar.</li>
        <li>Buka menu tersebut untuk melihat daftar permohonan yang berstatus <em>Menunggu</em>.</li>
        <li>Periksa alasan permohonan, hari dan jam usulan baru. Sistem akan otomatis mengecek ketersediaan lab tujuan pada waktu tersebut.</li>
        <li>Klik tombol <strong>Setujui</strong> jika slot waktu dan lab tersedia, atau <strong>Tolak</strong> disertai alasan penolakan jika ruangan sedang terpakai agenda lain.</li>
        <li>Setelah disetujui, jadwal praktikum secara otomatis terbarui di seluruh portal publik dan dashboard mahasiswa.</li>
    </ol>
</div>',
                'is_active' => true,
            ],
        ];

        // 2. Panduan Khusus Ketua Laboratorium
        $panduanKetuaLab = [
            [
                'kategori' => 'ketua-lab',
                'urutan' => 1,
                'judul' => 'Alur Kerja Ketua Lab: Memantau Kondisi & Status Lab',
                'subjudul' => 'Memeriksa sesi yang sedang berlangsung, agenda harian, dan ketersediaan lab.',
                'konten' => '<div class="space-y-3">
    <p class="text-gray-600 dark:text-zinc-400">Ketua Laboratorium memiliki dashboard khusus untuk memantau operasional lab binaannya secara real-time:</p>
    <ul class="list-disc list-inside space-y-2 pl-1">
        <li><strong>Status Real-Time:</strong> Kartu pertama dashboard menunjukkan apakah saat ini laboratorium sedang aktif digunakan untuk praktikum (beserta nama MK, dosen, dan kelas) atau sedang kosong dan siap dipakai.</li>
        <li><strong>Agenda Hari Ini:</strong> Menampilkan timeline urutan sesi praktikum dari pagi hingga sore hari di laboratorium Anda.</li>
        <li><strong>Visual Jadwal Ruang:</strong> Tombol di pojok kanan atas memungkinkan Anda melihat matriks 12 sesi mingguan laboratorium untuk mengetahui slot jam yang masih kosong.</li>
    </ul>
</div>',
                'is_active' => true,
            ],
            [
                'kategori' => 'ketua-lab',
                'urutan' => 2,
                'judul' => 'Mengelola Profil, Kapasitas, & Foto Laboratorium',
                'subjudul' => 'Cara memperbarui spesifikasi laboratorium dan mengunggah foto berkualitas tinggi.',
                'konten' => '<div class="space-y-3">
    <p class="text-gray-600 dark:text-zinc-400">Ketua Lab berhak mengatur profil laboratorium yang dikepalainya:</p>
    <ul class="list-disc list-inside space-y-2 pl-1">
        <li>Buka menu <strong>Ruang Praktikum</strong> atau klik <em>Edit Profil & Foto Laboratorium</em> pada kartu dashboard.</li>
        <li><strong>Unggah Foto Lab:</strong> Anda dapat mengunggah foto ruangan berformat JPG, PNG, WebP, atau HEIC (hasil kamera HP iPhone/Android) hingga ukuran <strong>20MB</strong>. Foto ini akan tampil pada kartu jadwal mahasiswa dan profil lab.</li>
        <li><strong>Kapasitas:</strong> Tentukan kapasitas tetap (jumlah meja/PC) atau kapasitas fleksibel jika ruangan bisa menampung rombel lebih besar (misal Moot Court atau Lab Bahasa).</li>
        <li><strong>Hak Akses Pemakaian:</strong> Tentukan apakah lab bersifat bersama fakultas, khusus prodi tertentu, atau umum universitas.</li>
    </ul>
</div>',
                'is_active' => true,
            ],
            [
                'kategori' => 'ketua-lab',
                'urutan' => 3,
                'judul' => 'Hak Akses CRUD Lengkap Ketua Lab (Kelas, MK, Dosen, Jadwal)',
                'subjudul' => 'Wewenang penuh mandiri bagi fakultas yang menyerahkan operasional lab ke Ketua Lab.',
                'konten' => '<div class="space-y-3">
    <p class="text-gray-600 dark:text-zinc-400">Ketua Lab memiliki hak akses CRUD penuh untuk operasional praktikum di laboratoriumnya:</p>
    <ul class="list-disc list-inside space-y-2 pl-1">
        <li><strong>Master Kelas:</strong> Tambah dan kelola kelas yang menggunakan lab Anda.</li>
        <li><strong>Master Mata Kuliah:</strong> Tambah mata kuliah praktikum dan unggah file modul ajar PDF.</li>
        <li><strong>Master Dosen / Tutor:</strong> Tambah profil dosen pengampu dan asisten lab/tutor.</li>
        <li><strong>Kelola Jadwal:</strong> Menambah, mengedit, atau menghapus sesi praktikum di lab Anda tanpa harus menunggu operator fakultas.</li>
        <li><strong>Tinjau Pengajuan:</strong> Menyetujui atau menolak permohonan perubahan jam praktikum dari dosen pengampu di lab Anda.</li>
    </ul>
</div>',
                'is_active' => true,
            ],
        ];

        // 3. Panduan Khusus Dosen Pengampu / Tutor
        $panduanDosen = [
            [
                'kategori' => 'dosen',
                'urutan' => 1,
                'judul' => 'Login Dosen & Memeriksa Jadwal Mengajar',
                'subjudul' => 'Cara masuk ke akun dosen dan melihat agenda praktikum semester berjalan.',
                'konten' => '<div class="space-y-3">
    <p class="text-gray-600 dark:text-zinc-400">Akun dosen pengampu praktikum terdaftar di sistem:</p>
    <ul class="list-disc list-inside space-y-2 pl-1">
        <li><strong>Login:</strong> Masuk menggunakan alamat email atau NIDN yang telah didaftarkan oleh Operator Fakultas.</li>
        <li><strong>Dashboard Dosen:</strong> Menampilkan ringkasan total kelas yang diampu, sesi mengajar hari ini, dan riwayat pengajuan perubahan jadwal.</li>
        <li><strong>Jadwal Praktikum Saya:</strong> Menampilkan seluruh jadwal praktikum mingguan Anda lengkap dengan ruang lab, hari, jam pelaksanaan, dan kelas mahasiswa.</li>
        <li><strong>Unduh Modul:</strong> Anda dapat mengunduh berkas modul praktikum resmi yang telah diunggah untuk sesi Anda.</li>
    </ul>
</div>',
                'is_active' => true,
            ],
            [
                'kategori' => 'dosen',
                'urutan' => 2,
                'judul' => 'Reschedule Mandiri Instan vs Pengajuan Melalui Operator',
                'subjudul' => 'Dua cara mengganti jadwal praktikum saat berhalangan hadir pada jam reguler.',
                'konten' => '<div class="space-y-3">
    <p class="text-gray-600 dark:text-zinc-400">Sistem memberikan fleksibilitas tinggi bagi dosen pengampu saat berhalangan hadir:</p>
    <div class="space-y-3 my-2">
        <div class="p-3.5 rounded-xl bg-emerald-50/70 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800">
            <strong class="text-emerald-900 dark:text-emerald-300 block mb-1">1. Reschedule Mandiri Instan (Tanpa Birokrasi)</strong>
            <p class="text-xs text-gray-600 dark:text-zinc-400">Buka menu <em>Jadwal Mengajar Saya</em>, klik tombol <em>Pindah Jadwal Mandiri</em>. Pilih hari dan jam baru. Jika laboratorium tujuan kosong pada waktu tersebut, sistem langsung menyetujui dan memindahkan jadwal secara instan detik itu juga!</p>
        </div>
        <div class="p-3.5 rounded-xl bg-amber-50/70 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800">
            <strong class="text-amber-900 dark:text-amber-300 block mb-1">2. Pengajuan Perubahan Formal (Melalui Operator)</strong>
            <p class="text-xs text-gray-600 dark:text-zinc-400">Jika Anda memerlukan ruangan laboratorium lain atau waktu khusus yang membutuhkan konfirmasi pengelola fakultas, ajukan tiket melalui menu <em>Pengajuan Perubahan</em>. Operator akan memeriksa dan memberikan persetujuan.</p>
        </div>
    </div>
</div>',
                'is_active' => true,
            ],
        ];

        // 4. Panduan Khusus Super Admin
        $panduanSuperAdmin = [
            [
                'kategori' => 'super-admin',
                'urutan' => 1,
                'judul' => 'Manajemen Pengguna & Penugasan Operator / Ketua Lab',
                'subjudul' => 'Membuat akun pengelola fakultas dan mengikat wewenang unit.',
                'konten' => '<div class="space-y-3">
    <p class="text-gray-600 dark:text-zinc-400">Super Admin memegang wewenang tertinggi tata kelola akun:</p>
    <ul class="list-disc list-inside space-y-2 pl-1">
        <li>Buka menu <strong>Pengguna & Operator</strong> di sidebar.</li>
        <li>Klik <em>Tambah Pengguna</em> untuk mendaftarkan akun baru.</li>
        <li><strong>Role Operator:</strong> Wajib dipilihkan Fakultas terkait agar operator tersebut hanya dapat mengelola data di bawah naungan fakultasnya.</li>
        <li><strong>Role Ketua Lab:</strong> Pilihkan Ruangan Laboratorium yang dikepalai agar dashboard dan wewenang jadwalnya terkonsentrasi pada lab tersebut.</li>
        <li><strong>Role Super Admin:</strong> Memiliki akses universal lintas seluruh fakultas di universitas.</li>
    </ul>
</div>',
                'is_active' => true,
            ],
            [
                'kategori' => 'super-admin',
                'urutan' => 2,
                'judul' => 'Pengaturan Overlay Peringatan Verifikasi Data ke Operator',
                'subjudul' => 'Cara menampilkan ulang peringatan 1x NIDN Dosen & Kode MK saat serah terima sistem.',
                'konten' => '<div class="space-y-3">
    <p class="text-gray-600 dark:text-zinc-400">Super Admin dapat mengatur dan mereset pengumuman penting bagi Operator dan Ketua Lab:</p>
    <ol class="list-decimal list-inside space-y-2 pl-1">
        <li>Buka menu <strong>Pengguna & Operator</strong>.</li>
        <li>Klik tombol <strong>Peringatan Data (Overlay)</strong> di samping tombol Tambah Pengguna.</li>
        <li>Anda dapat mengedit judul dan isi pesan peringatan (misalnya imbauan pengecekan NIDN Dosen asli).</li>
        <li>Klik tombol <strong>Reset & Tampilkan Kembali ke Semua Operator/Ketua Lab</strong>. Sistem akan menaikkan versi pengumuman dan mereset flag, sehingga saat Operator dan Ketua Lab login berikutnya, modal overlay akan otomatis muncul 1x di layar mereka.</li>
    </ol>
</div>',
                'is_active' => true,
            ],
            [
                'kategori' => 'super-admin',
                'urutan' => 3,
                'judul' => 'Pengelolaan Sampah (Soft Deletes) & Riwayat Log Aktivitas',
                'subjudul' => 'Pemulihan data terhapus, audit trail Spatie, dan pembersihan otomatis 30 hari.',
                'konten' => '<div class="space-y-3">
    <p class="text-gray-600 dark:text-zinc-400">Pengamanan data dari ketidaksengajaan penghapusan:</p>
    <ul class="list-disc list-inside space-y-2 pl-1">
        <li><strong>Tempat Sampah:</strong> Data master (Fakultas, Prodi, Kelas, MK, Dosen, Ruang, Jadwal) yang dihapus oleh operator masuk ke Tempat Sampah dan bisa di-<em>Restore</em> kapan saja.</li>
        <li><strong>Riwayat Aktivitas:</strong> Setiap tindakan tambah, ubah, atau hapus dicatat otomatis oleh sistem beserta nama pelaku, waktu, dan rincian perubahan data.</li>
        <li><strong>Pembersihan Otomatis 1 Bulan (30 Hari):</strong> Sistem telah dilengkapi scheduler otomatis harian untuk membersihkan data sampah dan log aktivitas yang umurnya sudah melebihi 30 hari. Anda juga dapat membersihkannya secara manual sewaktu-waktu melalui tombol yang tersedia.</li>
    </ul>
</div>',
                'is_active' => true,
            ],
        ];

        // 5. Petunjuk Fitur Tiap Halaman
        $panduanFitur = [
            [
                'kategori' => 'fitur',
                'urutan' => 1,
                'judul' => 'Petunjuk Menu: Dashboard',
                'subjudul' => 'Statistik real-time, status pemakaian lab, agenda harian, dan ringkasan pengajuan.',
                'konten' => '<p class="text-gray-600 dark:text-zinc-400 leading-relaxed">Halaman Dashboard dirancang responsif dan berbeda untuk tiap role: Super Admin melihat rekap universitas, Operator melihat rekap fakultasnya, Ketua Lab melihat kondisi labnya, dan Dosen melihat jadwal mengajarnya. Dilengkapi indikator pulse hijau/kuning untuk sesi yang sedang berjalan detik ini.</p>',
                'is_active' => true,
            ],
            [
                'kategori' => 'fitur',
                'urutan' => 2,
                'judul' => 'Petunjuk Menu: Ruang Praktikum',
                'subjudul' => 'Upload foto hingga 20MB, kapasitas fleksibel vs tetap, dan cakupan hak pakai multi-prodi.',
                'konten' => '<p class="text-gray-600 dark:text-zinc-400 leading-relaxed">Menampilkan katalog ruang lab dan auditorium. Mendukung upload foto resolusi tinggi (JPG, PNG, HEIC hingga 20MB), penentuan kapasitas tetap (workstation) vs fleksibel (open space), serta pengaturan hak pakai: Bersama Fakultas, Khusus Multi-Prodi lintas fakultas, Eksklusif 1 prodi, atau Umum Universitas.</p>',
                'is_active' => true,
            ],
            [
                'kategori' => 'fitur',
                'urutan' => 3,
                'judul' => 'Petunjuk Menu: Mata Kuliah & Modul Praktikum',
                'subjudul' => 'Cakupan prodi vs bersama fakultas, SKS, semester, dan unggah modul ajar PDF.',
                'konten' => '<p class="text-gray-600 dark:text-zinc-400 leading-relaxed">Mata kuliah praktikum dapat disetel untuk 1 prodi atau <em>Bersama Fakultas</em> (mis. Peradilan Agama, Hisab Falak). Anda dapat mengunggah file Modul Ajar PDF resmi sehingga mahasiswa dapat mengunduhnya langsung dari portal jadwal publik.</p>',
                'is_active' => true,
            ],
            [
                'kategori' => 'fitur',
                'urutan' => 4,
                'judul' => 'Petunjuk Menu: Dosen Pengampu & Tutor',
                'subjudul' => 'Kategori dosen pengampu vs tutor lab, pengelolaan NIDN dan email.',
                'konten' => '<p class="text-gray-600 dark:text-zinc-400 leading-relaxed">Membedakan pengajar sebagai Dosen atau Tutor/Asisten Lab. NIDN bersifat opsional saat penginputan awal, namun wajib dilengkapi agar dosen bersangkutan dapat login ke sistem untuk melihat jadwal dan melakukan reschedule mandiri.</p>',
                'is_active' => true,
            ],
            [
                'kategori' => 'fitur',
                'urutan' => 5,
                'judul' => 'Petunjuk Menu: Kelola Jadwal Praktikum',
                'subjudul' => 'Filter multi-parameter, visual jadwal ruang, deteksi bentrok otomatis, dan ekspor PDF/Excel.',
                'konten' => '<p class="text-gray-600 dark:text-zinc-400 leading-relaxed">Pusat plotting jadwal universitas. Dilengkapi deteksi bentrok otomatis saat jam bertabrakan di ruang atau dosen yang sama, validasi izin pemakaian ruangan antar-prodi, serta tombol shortcut langsung ke Visual Jadwal Ruang (format matriks 12 sesi resmi).</p>',
                'is_active' => true,
            ],
            [
                'kategori' => 'fitur',
                'urutan' => 6,
                'judul' => 'Petunjuk Menu: Pengajuan Perubahan',
                'subjudul' => 'Tiket usulan pemindahan sesi, verifikasi bentrok, dan persetujuan operator.',
                'konten' => '<p class="text-gray-600 dark:text-zinc-400 leading-relaxed">Tempat Operator dan Ketua Lab memverifikasi permohonan pergantian hari/jam dari dosen. Disertai deteksi bentrok otomatis pada waktu usulan baru, tombol Setujui, dan tombol Tolak disertai catatan pengelola.</p>',
                'is_active' => true,
            ],
        ];

        $all = array_merge(
            $panduanPublik,
            $panduanOperator,
            $panduanKetuaLab,
            $panduanDosen,
            $panduanSuperAdmin,
            $panduanFitur
        );

        foreach ($all as $item) {
            Panduan::updateOrCreate(
                [
                    'kategori' => $item['kategori'],
                    'urutan' => $item['urutan'],
                ],
                $item
            );
        }
    }
}
