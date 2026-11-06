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
                'judul' => 'Panduan Membaca Jadwal & Kalender Praktikum',
                'subjudul' => 'Arti kode ruangan, penyaringan prodi, kalender sesi, dan unduh jadwal.',
                'konten' => '<p class="mb-3 text-gray-600 dark:text-zinc-400">Sistem informasi ini menyediakan portal terbuka tanpa login bagi seluruh mahasiswa:</p>
<ul class="list-disc list-inside space-y-1.5 pl-1">
    <li><strong>Daftar Jadwal:</strong> Menampilkan tabel dan kartu jadwal praktikum mingguan lengkap dengan mata kuliah, kelas, nama dosen pengampu, hari, jam, dan laboratorium.</li>
    <li><strong>Filter Berjenjang:</strong> Mahasiswa dapat menyaring jadwal berdasarkan Fakultas, Program Studi, Hari, maupun pencarian teks langsung (nama dosen, kelas, atau mata kuliah).</li>
    <li><strong>Kalender Interaktif:</strong> Menyajikan tampilan visual grid mingguan yang mempermudah memeriksa slot waktu kelas.</li>
    <li><strong>Unduh Jadwal Resmi:</strong> Tombol <em>Unduh PDF</em> dan <em>Unduh Excel</em> di halaman Daftar Jadwal memungkinkan ketua kelas mengunduh jadwal resmi per program studi.</li>
</ul>',
                'is_active' => true,
            ],
            [
                'kategori' => 'publik',
                'urutan' => 5,
                'judul' => 'Prosedur Praktikum Pengganti & Pusat Bantuan',
                'subjudul' => 'Alur koordinasi praktikum susulan dan saluran kontak Helpdesk Pustikom.',
                'konten' => '<p class="mb-3 text-gray-600 dark:text-zinc-400">Apabila terdapat sesi yang berhalangan atau perubahan waktu praktikum:</p>
<ul class="list-disc list-inside space-y-1.5 pl-1">
    <li><strong>Sesi Pengganti:</strong> Jadwal pengganti akan ditentukan oleh dosen pengampu dan langsung tercatat pada portal jadwal sistem secara otomatis. Mahasiswa dapat memeriksa jadwal terbaru secara berkala.</li>
    <li><strong>Pertanyaan Jadwal:</strong> Apabila terdapat ketidaksesuaian jam kuliah atau ruangan, hubungi perwakilan ketua kelas atau laboran laboratorium bersangkutan.</li>
</ul>
<div class="mt-4 p-4 rounded-xl bg-primary/5 dark:bg-primary/10 border border-primary/20 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
    <div>
        <strong class="font-bold text-gray-900 dark:text-zinc-100 block text-xs sm:text-sm">Pusat Bantuan & Layanan Informasi Pustikom</strong>
        <p class="text-xs text-gray-500 dark:text-zinc-400">Pusat Teknologi Informasi dan Pangkalan Data (Pustikom) UIN Siber Syekh Nurjati Cirebon.</p>
    </div>
    <div class="flex items-center gap-2 flex-wrap text-xs">
        <span class="px-3 py-1.5 rounded-lg bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 text-gray-700 dark:text-zinc-300 font-medium">Gedung Pustikom Lt. 2</span>
        <span class="px-3 py-1.5 rounded-lg bg-primary text-white font-semibold">helpdesk@praktikum.ac.id</span>
    </div>
</div>',
                'is_active' => true,
            ],
        ];

        $panduanInternal = [
            [
                'kategori' => 'internal',
                'urutan' => 1,
                'judul' => 'Standar Busana & Kode Etik Dosen/Pengelola Laboratorium',
                'subjudul' => 'Ketentuan pakaian formal, keteladanan akademik, dan kedisiplinan pembimbingan modul.',
                'konten' => '<p class="mb-3 text-gray-600 dark:text-zinc-400">Sebagai pembimbing dan penanggung jawab ruang praktikum, Dosen Pengampu dan Operator Laboratorium diwajibkan menjadi teladan moral dan akademik:</p>
<ul class="list-disc list-inside space-y-1.5 pl-1">
    <li><strong>Standar Pakaian Pria:</strong> Mengenakan kemeja formal berkerah rapi, kemeja batik resmi, atau jas almamater universitas, dipadukan dengan celana kain panjang serta sepatu pantofel/tertutup yang layak.</li>
    <li><strong>Standar Pakaian Wanita:</strong> Mengenakan pakaian formal berkerah/blus sopan non-transparan, rok/celana panjang bahan formal, serta bagi yang berhijab mengenakan jilbab rapi sesuai pedoman etika kampus islami.</li>
    <li><strong>Ketepatan Waktu Kehadiran:</strong> Dosen pengampu diharapkan tiba di laboratorium sekurang-kurangnya 10 menit sebelum jam praktikum dimulai untuk memastikan kesiapan perangkat lunak, modul, dan presensi mahasiswa.</li>
    <li><strong>Kepatuhan K3 & Ketertiban:</strong> Memastikan seluruh peserta praktikum menaati regulasi K3 laboratorium, tidak mengizinkan mahasiswa makan/minum di meja komputer, serta mematikan seluruh perangkat kelistrikan sebelum meninggalkan ruangan.</li>
</ul>',
                'is_active' => true,
            ],
            [
                'kategori' => 'internal',
                'urutan' => 2,
                'judul' => 'Prosedur Penginputan & Alokasi Ruangan oleh Operator',
                'subjudul' => 'Prinsip hak akses fakultas, validasi bentrok jadwal, dan manajemen kuota ruangan.',
                'konten' => '<p class="mb-3 text-gray-600 dark:text-zinc-400">Operator memiliki kewenangan mengelola jadwal dan sarana praktikum di bawah naungan fakultasnya:</p>
<ul class="list-disc list-inside space-y-1.5 pl-1">
    <li><strong>Batas Kewenangan Fakultas:</strong> Operator hanya mengelola data mata kuliah, kelas, dan ruangan milik fakultas yang ditugaskan untuk mencegah tumpang tindih data antar unit.</li>
    <li><strong>Deteksi Tabrakan Jadwal Otomatis:</strong> Sistem secara otomatis memblokir penyimpanan jika ada jadwal lain di ruangan dan rentang jam yang sama pada hari yang bersangkutan.</li>
    <li><strong>Pemeriksaan Kapasitas:</strong> Operator wajib memastikan jumlah mahasiswa dalam suatu kelas tidak melampaui kapasitas tempat duduk dan komputer yang tersedia di ruang lab.</li>
    <li><strong>Status Ruangan Aktif:</strong> Hanya ruangan berstatus <em>aktif</em> atau <em>tersedia</em> yang dapat dijadwalkan. Ruangan berstatus <em>perbaikan</em> tidak dapat dipilih hingga pemeliharaan tuntas.</li>
</ul>',
                'is_active' => true,
            ],
            [
                'kategori' => 'internal',
                'urutan' => 3,
                'judul' => 'Tata Cara Pengunggahan Jadwal Massal via File Excel',
                'subjudul' => 'Struktur kolom berkas, pencocokan otomatis nama prodi/kelas/dosen/ruang, dan validasi data.',
                'konten' => '<p class="mb-3 text-gray-600 dark:text-zinc-400">Untuk mempercepat alur kerja pada awal semester, operator dapat mengimpor puluhan jadwal praktikum sekaligus melalui berkas Microsoft Excel (.xlsx / .xls):</p>
<ul class="list-disc list-inside space-y-1.5 pl-1">
    <li><strong>Unduh Template Resmi:</strong> Klik tombol <em>Unduh Template Excel</em> di menu Kelola Jadwal agar urutan tajuk kolom sesuai standar sistem.</li>
    <li><strong>Format Waktu & Hari:</strong> Tuliskan nama hari dalam Bahasa Indonesia (Senin, Selasa, Rabu, Kamis, Jum\'at) dan format jam 24 jam (misal <code>08:00</code> sampai <code>10:30</code>). Kampus libur hari Sabtu.</li>
    <li><strong>Kesesuaian Kode / Nama Entitas:</strong> Pastikan Nama/Kode Mata Kuliah, Kelas, NIDN Dosen, dan Kode Ruangan sesuai dengan data master yang telah terdaftar pada sistem.</li>
    <li><strong>Laporan Hasil Impor:</strong> Sistem akan menampilkan rekapitulasi data yang sukses tersimpan serta rincian baris yang gagal akibat bentrok atau data tidak ditemukan.</li>
</ul>',
                'is_active' => true,
            ],
            [
                'kategori' => 'internal',
                'urutan' => 4,
                'judul' => 'Mekanisme Reschedule Mandiri Dosen vs Pengajuan Operator',
                'subjudul' => 'Perpindahan jadwal langsung saat ruangan kosong tanpa hambatan birokrasi, atau pengajuan persetujuan.',
                'konten' => '<p class="mb-3 text-gray-600 dark:text-zinc-400">Guna mengantisipasi kendala keterbatasan waktu penanggung jawab/operator yang tidak dapat bersiaga setiap saat, sistem menyediakan 2 alur pemindahan jadwal:</p>
<ul class="list-disc list-inside space-y-1.5 pl-1">
    <li><strong>Reschedule Mandiri Instan (Dosen):</strong> Apabila dosen berhalangan hadir pada jam reguler dan menemukan slot ruangan tujuan yang kosong pada hari/jam pengganti, dosen dapat melakukan perpindahan jadwal secara instan dari menu <em>Jadwal Mengajar Saya</em>. Sistem langsung memverifikasi ketiadaan bentrok dan memperbarui jadwal secara <em>real-time</em>.</li>
    <li><strong>Pengajuan Perubahan Formal (Melalui Operator):</strong> Apabila pemindahan memerlukan koordinasi khusus dengan pengelola prodi/fakultas lain, dosen dapat mengajukan tiket permohonan melalui menu <em>Pengajuan Perubahan</em> yang akan diverifikasi oleh Operator.</li>
    <li><strong>Pemberitahuan kepada Mahasiswa:</strong> Segera setelah perubahan jadwal disetujui atau dieksekusi, kalender dan daftar jadwal publik langsung terbarui otomatis sehingga mahasiswa dapat melihat agenda pengganti.</li>
</ul>',
                'is_active' => true,
            ],
            [
                'kategori' => 'internal',
                'urutan' => 5,
                'judul' => 'Pemeliharaan Sarana Laboratorium & Koordinasi PUSTIKOM',
                'subjudul' => 'Pengubahan status sarana, penanganan inventaris komputer, dan eskalasi teknis.',
                'konten' => '<p class="mb-3 text-gray-600 dark:text-zinc-400">Langkah penanganan kendala infrastruktur, konektivitas internet, atau perangkat keras komputer di laboratorium:</p>
<ul class="list-disc list-inside space-y-1.5 pl-1">
    <li><strong>Penyesuaian Status Ruangan:</strong> Operator wajib segera mengubah status ruangan menjadi <em>perbaikan</em> pada menu Ruang Praktikum jika laboratorium sedang dalam perawatan berkala atau perbaikan pendingin ruangan/jaringan.</li>
    <li><strong>Pencatatan Riwayat Aktivitas:</strong> Seluruh penambahan, pengubahan, atau penghapusan jadwal dan master data dicatat secara terperinci dalam <em>Riwayat Aktivitas</em> untuk menjamin akuntabilitas pengelola.</li>
    <li><strong>Dukungan Teknis PUSTIKOM:</strong> Kendala jaringan internet, server basis data, atau lisensi software dapat dilaporkan ke Helpdesk PUSTIKOM Gedung Rektorat Lt. 2 atau melalui layanan tiket universitas.</li>
</ul>',
                'is_active' => true,
            ],
        ];

        foreach (array_merge($panduanPublik, $panduanInternal) as $item) {
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
