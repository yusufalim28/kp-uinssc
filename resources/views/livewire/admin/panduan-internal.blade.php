<div class="space-y-6 max-w-5xl mx-auto pb-12">
    {{-- Header Banner Pengelola --}}
    <div class="bg-gradient-to-r from-primary to-primary-dark rounded-2xl p-6 sm:p-8 text-white shadow-md relative overflow-hidden">
        <div class="relative z-10 max-w-3xl space-y-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/15 text-white backdrop-blur-xs border border-white/20">
                SOP Khusus Operator Fakultas & Dosen Pengampu
            </span>
            <h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold tracking-tight">Pedoman Tata Kelola & SOP Penjadwalan Praktikum</h1>
            <p class="text-white/85 text-xs sm:text-sm leading-relaxed">
                Panduan resmi standar etika berbusana pengajar/pengelola, tata kelola alokasi ruang lab, pengoperasian impor data Excel, serta mekanisme perubahan jadwal praktikum mandiri.
            </p>
        </div>
    </div>

    {{-- Accordion SOP Pengelola (1 s/d 5) --}}
    <div class="space-y-3.5">

        {{-- 1. ETIKA BERBUSANA & KODE ETIK PENGAJAR/OPERATOR --}}
        <div class="bg-white dark:bg-zinc-900 border border-gray-200/90 dark:border-zinc-800 rounded-2xl shadow-xs overflow-hidden transition">
            <button wire:click="toggleSection(1)"
                    type="button"
                    class="w-full p-4 sm:p-5 flex items-center justify-between text-left hover:bg-gray-50/60 dark:hover:bg-zinc-800/50 transition gap-3">
                <div class="flex items-center gap-3.5">
                    <span class="w-8 h-8 rounded-xl {{ $openSection === 1 ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300' }} flex items-center justify-center font-bold text-xs shrink-0">
                        1
                    </span>
                    <div>
                        <h2 class="font-bold text-sm sm:text-base text-gray-900 dark:text-zinc-100 leading-snug">Standar Busana & Kode Etik Dosen/Pengelola Laboratorium</h2>
                        <p class="text-xs text-gray-500 dark:text-zinc-400 hidden sm:block">Ketentuan pakaian formal, keteladanan akademik, dan kedisiplinan pembimbingan modul.</p>
                    </div>
                </div>
                <div class="w-7 h-7 rounded-lg bg-gray-100 dark:bg-zinc-800 flex items-center justify-center shrink-0 transition {{ $openSection === 1 ? 'rotate-180 bg-primary/10 text-primary' : 'text-gray-400' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </button>

            @if ($openSection === 1)
                <div class="p-5 pt-0 border-t border-gray-100 dark:border-zinc-800 space-y-3 text-xs sm:text-sm text-gray-700 dark:text-zinc-300 leading-relaxed">
                    <p class="text-gray-500 dark:text-zinc-400 pt-3">
                        Sebagai pembimbing dan penanggung jawab ruang praktikum, Dosen Pengampu dan Operator Laboratorium diwajibkan menjadi teladan moral dan akademik:
                    </p>
                    <ul class="list-disc list-inside space-y-1.5 pl-1">
                        <li><strong>Standar Pakaian Pria:</strong> Mengenakan kemeja formal berkerah rapi, kemeja batik resmi, atau jas almamater universitas, dipadukan dengan celana kain panjang serta sepatu pantofel/tertutup yang layak.</li>
                        <li><strong>Standar Pakaian Wanita:</strong> Mengenakan pakaian formal berkerah/blus sopan non-transparan, rok/celana panjang bahan formal, serta bagi yang berhijab mengenakan jilbab rapi sesuai pedoman etika kampus islami.</li>
                        <li><strong>Ketepatan Waktu Kehadiran:</strong> Dosen pengampu diharapkan tiba di laboratorium sekurang-kurangnya 10 menit sebelum jam praktikum dimulai untuk memastikan kesiapan perangkat lunak, modul, dan presensi mahasiswa.</li>
                        <li><strong>Kepatuhan K3 & Ketertiban:</strong> Memastikan seluruh peserta praktikum menaati regulasi K3 laboratorium, tidak mengizinkan mahasiswa makan/minum di meja komputer, serta mematikan seluruh perangkat kelistrikan sebelum meninggalkan ruangan.</li>
                    </ul>
                </div>
            @endif
        </div>

        {{-- 2. TATA KELOLA PENJADWALAN OLEH OPERATOR FAKULTAS --}}
        <div class="bg-white dark:bg-zinc-900 border border-gray-200/90 dark:border-zinc-800 rounded-2xl shadow-xs overflow-hidden transition">
            <button wire:click="toggleSection(2)"
                    type="button"
                    class="w-full p-4 sm:p-5 flex items-center justify-between text-left hover:bg-gray-50/60 dark:hover:bg-zinc-800/50 transition gap-3">
                <div class="flex items-center gap-3.5">
                    <span class="w-8 h-8 rounded-xl {{ $openSection === 2 ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300' }} flex items-center justify-center font-bold text-xs shrink-0">
                        2
                    </span>
                    <div>
                        <h2 class="font-bold text-sm sm:text-base text-gray-900 dark:text-zinc-100 leading-snug">Prosedur Penginputan & Alokasi Ruangan oleh Operator</h2>
                        <p class="text-xs text-gray-500 dark:text-zinc-400 hidden sm:block">Prinsip hak akses fakultas, validasi bentrok jadwal, dan manajemen kuota ruangan.</p>
                    </div>
                </div>
                <div class="w-7 h-7 rounded-lg bg-gray-100 dark:bg-zinc-800 flex items-center justify-center shrink-0 transition {{ $openSection === 2 ? 'rotate-180 bg-primary/10 text-primary' : 'text-gray-400' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </button>

            @if ($openSection === 2)
                <div class="p-5 pt-0 border-t border-gray-100 dark:border-zinc-800 space-y-3 text-xs sm:text-sm text-gray-700 dark:text-zinc-300 leading-relaxed">
                    <p class="text-gray-500 dark:text-zinc-400 pt-3">
                        Operator memiliki kewenangan mengelola jadwal dan sarana praktikum di bawah naungan fakultasnya:
                    </p>
                    <ul class="list-disc list-inside space-y-1.5 pl-1">
                        <li><strong>Batas Kewenangan Fakultas:</strong> Operator hanya mengelola data mata kuliah, kelas, dan ruangan milik fakultas yang ditugaskan untuk mencegah tumpang tindih data antar unit.</li>
                        <li><strong>Deteksi Tabrakan Jadwal Otomatis:</strong> Sistem secara otomatis memblokir penyimpanan jika ada jadwal lain di ruangan dan rentang jam yang sama pada hari yang bersangkutan.</li>
                        <li><strong>Pemeriksaan Kapasitas:</strong> Operator wajib memastikan jumlah mahasiswa dalam suatu kelas tidak melampaui kapasitas tempat duduk dan komputer yang tersedia di ruang lab.</li>
                        <li><strong>Status Ruangan Aktif:</strong> Hanya ruangan berstatus <em>aktif</em> atau <em>tersedia</em> yang dapat dijadwalkan. Ruangan berstatus <em>perbaikan</em> tidak dapat dipilih hingga pemeliharaan tuntas.</li>
                    </ul>
                </div>
            @endif
        </div>

        {{-- 3. PANDUAN UNGGAH EXCEL JADWAL PRAKTIKUM --}}
        <div class="bg-white dark:bg-zinc-900 border border-gray-200/90 dark:border-zinc-800 rounded-2xl shadow-xs overflow-hidden transition">
            <button wire:click="toggleSection(3)"
                    type="button"
                    class="w-full p-4 sm:p-5 flex items-center justify-between text-left hover:bg-gray-50/60 dark:hover:bg-zinc-800/50 transition gap-3">
                <div class="flex items-center gap-3.5">
                    <span class="w-8 h-8 rounded-xl {{ $openSection === 3 ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300' }} flex items-center justify-center font-bold text-xs shrink-0">
                        3
                    </span>
                    <div>
                        <h2 class="font-bold text-sm sm:text-base text-gray-900 dark:text-zinc-100 leading-snug">Tata Cara Pengunggahan Jadwal Massal via File Excel</h2>
                        <p class="text-xs text-gray-500 dark:text-zinc-400 hidden sm:block">Struktur kolom berkas, pencocokan otomatis nama prodi/kelas/dosen/ruang, dan validasi data.</p>
                    </div>
                </div>
                <div class="w-7 h-7 rounded-lg bg-gray-100 dark:bg-zinc-800 flex items-center justify-center shrink-0 transition {{ $openSection === 3 ? 'rotate-180 bg-primary/10 text-primary' : 'text-gray-400' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </button>

            @if ($openSection === 3)
                <div class="p-5 pt-0 border-t border-gray-100 dark:border-zinc-800 space-y-3 text-xs sm:text-sm text-gray-700 dark:text-zinc-300 leading-relaxed">
                    <p class="text-gray-500 dark:text-zinc-400 pt-3">
                        Untuk mempercepat alur kerja pada awal semester, operator dapat mengimpor puluhan jadwal praktikum sekaligus melalui berkas Microsoft Excel (.xlsx / .xls):
                    </p>
                    <ul class="list-disc list-inside space-y-1.5 pl-1">
                        <li><strong>Unduh Template Resmi:</strong> Klik tombol <em>Unduh Template Excel</em> di menu Kelola Jadwal agar urutan tajuk kolom sesuai standar sistem.</li>
                        <li><strong>Format Waktu & Hari:</strong> Tuliskan nama hari dalam Bahasa Indonesia (Senin, Selasa, Rabu, Kamis, Jumat, Sabtu) dan format jam 24 jam (misal <code>08:00</code> sampai <code>10:30</code>).</li>
                        <li><strong>Kesesuaian Kode / Nama Entitas:</strong> Pastikan Nama/Kode Mata Kuliah, Kelas, NIDN Dosen, dan Kode Ruangan sesuai dengan data master yang telah terdaftar pada sistem.</li>
                        <li><strong>Laporan Hasil Impor:</strong> Sistem akan menampilkan rekapitulasi data yang sukses tersimpan serta rincian baris yang gagal akibat bentrok atau data tidak ditemukan.</li>
                    </ul>
                </div>
            @endif
        </div>

        {{-- 4. PROSEDUR RESCHEDULE & PERPINDAHAN JADWAL --}}
        <div class="bg-white dark:bg-zinc-900 border border-gray-200/90 dark:border-zinc-800 rounded-2xl shadow-xs overflow-hidden transition">
            <button wire:click="toggleSection(4)"
                    type="button"
                    class="w-full p-4 sm:p-5 flex items-center justify-between text-left hover:bg-gray-50/60 dark:hover:bg-zinc-800/50 transition gap-3">
                <div class="flex items-center gap-3.5">
                    <span class="w-8 h-8 rounded-xl {{ $openSection === 4 ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300' }} flex items-center justify-center font-bold text-xs shrink-0">
                        4
                    </span>
                    <div>
                        <h2 class="font-bold text-sm sm:text-base text-gray-900 dark:text-zinc-100 leading-snug">Mekanisme Reschedule Mandiri Dosen vs Pengajuan Operator</h2>
                        <p class="text-xs text-gray-500 dark:text-zinc-400 hidden sm:block">Perpindahan jadwal langsung saat ruangan kosong tanpa hambatan birokrasi, atau pengajuan persetujuan.</p>
                    </div>
                </div>
                <div class="w-7 h-7 rounded-lg bg-gray-100 dark:bg-zinc-800 flex items-center justify-center shrink-0 transition {{ $openSection === 4 ? 'rotate-180 bg-primary/10 text-primary' : 'text-gray-400' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </button>

            @if ($openSection === 4)
                <div class="p-5 pt-0 border-t border-gray-100 dark:border-zinc-800 space-y-3 text-xs sm:text-sm text-gray-700 dark:text-zinc-300 leading-relaxed">
                    <p class="text-gray-500 dark:text-zinc-400 pt-3">
                        Guna mengantisipasi kendala keterbatasan waktu penanggung jawab/operator yang tidak dapat bersiaga setiap saat, sistem menyediakan 2 alur pemindahan jadwal:
                    </p>
                    <ul class="list-disc list-inside space-y-1.5 pl-1">
                        <li><strong>Reschedule Mandiri Instan (Dosen):</strong> Apabila dosen berhalangan hadir pada jam reguler dan menemukan slot ruangan tujuan yang kosong pada hari/jam pengganti, dosen dapat melakukan perpindahan jadwal secara instan dari menu <em>Jadwal Mengajar Saya</em>. Sistem langsung memverifikasi ketiadaan bentrok dan memperbarui jadwal secara <em>real-time</em>.</li>
                        <li><strong>Pengajuan Perubahan Formal (Melalui Operator):</strong> Apabila pemindahan memerlukan koordinasi khusus dengan pengelola prodi/fakultas lain, dosen dapat mengajukan tiket permohonan melalui menu <em>Pengajuan Perubahan</em> yang akan diverifikasi oleh Operator.</li>
                        <li><strong>Pemberitahuan kepada Mahasiswa:</strong> Segera setelah perubahan jadwal disetujui atau dieksekusi, kalender dan daftar jadwal publik langsung terbarui otomatis sehingga mahasiswa dapat melihat agenda pengganti.</li>
                    </ul>
                </div>
            @endif
        </div>

        {{-- 5. PEMELIHARAAN SARANA & PELAPORAN TEKNIS --}}
        <div class="bg-white dark:bg-zinc-900 border border-gray-200/90 dark:border-zinc-800 rounded-2xl shadow-xs overflow-hidden transition">
            <button wire:click="toggleSection(5)"
                    type="button"
                    class="w-full p-4 sm:p-5 flex items-center justify-between text-left hover:bg-gray-50/60 dark:hover:bg-zinc-800/50 transition gap-3">
                <div class="flex items-center gap-3.5">
                    <span class="w-8 h-8 rounded-xl {{ $openSection === 5 ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300' }} flex items-center justify-center font-bold text-xs shrink-0">
                        5
                    </span>
                    <div>
                        <h2 class="font-bold text-sm sm:text-base text-gray-900 dark:text-zinc-100 leading-snug">Pemeliharaan Sarana Laboratorium & Koordinasi PUSTIKOM</h2>
                        <p class="text-xs text-gray-500 dark:text-zinc-400 hidden sm:block">Pengubahan status sarana, penanganan inventaris komputer, dan eskalasi teknis.</p>
                    </div>
                </div>
                <div class="w-7 h-7 rounded-lg bg-gray-100 dark:bg-zinc-800 flex items-center justify-center shrink-0 transition {{ $openSection === 5 ? 'rotate-180 bg-primary/10 text-primary' : 'text-gray-400' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </button>

            @if ($openSection === 5)
                <div class="p-5 pt-0 border-t border-gray-100 dark:border-zinc-800 space-y-3 text-xs sm:text-sm text-gray-700 dark:text-zinc-300 leading-relaxed">
                    <p class="text-gray-500 dark:text-zinc-400 pt-3">
                        Langkah penanganan kendala infrastruktur, konektivitas internet, atau perangkat keras komputer di laboratorium:
                    </p>
                    <ul class="list-disc list-inside space-y-1.5 pl-1">
                        <li><strong>Penyesuaian Status Ruangan:</strong> Operator wajib segera mengubah status ruangan menjadi <em>perbaikan</em> pada menu Ruang Praktikum jika laboratorium sedang dalam perawatan berkala atau perbaikan pendingin ruangan/jaringan.</li>
                        <li><strong>Pencatatan Riwayat Aktivitas:</strong> Seluruh penambahan, pengubahan, atau penghapusan jadwal dan master data dicatat secara terperinci dalam <em>Riwayat Aktivitas</em> untuk menjamin akuntabilitas pengelola.</li>
                        <li><strong>Dukungan Teknis PUSTIKOM:</strong> Kendala jaringan internet, server basis data, atau lisensi software dapat dilaporkan ke Helpdesk PUSTIKOM Gedung Rektorat Lt. 2 atau melalui layanan tiket universitas.</li>
                    </ul>
                </div>
            @endif
        </div>

    </div>
</div>
