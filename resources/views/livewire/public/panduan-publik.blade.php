<div class="space-y-6 max-w-5xl mx-auto pb-12">
    {{-- Header Banner Publik --}}
    <div class="bg-gradient-to-r from-primary to-primary-dark rounded-2xl p-6 sm:p-8 text-white shadow-md relative overflow-hidden">
        <div class="relative z-10 max-w-3xl space-y-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/15 text-white backdrop-blur-xs border border-white/20">
                Informasi Terbuka Mahasiswa & Sivitas Akademika
            </span>
            <h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold tracking-tight">Tata Tertib & Ketentuan Pelaksanaan Praktikum</h1>
            <p class="text-white/85 text-xs sm:text-sm leading-relaxed">
                Panduan tata tertib berpakaian, kedisiplinan kehadiran, etika pemanfaatan fasilitas laboratorium, serta panduan membaca jadwal praktikum di lingkungan universitas.
            </p>
        </div>
    </div>

    {{-- Accordion Publik (1 s/d 5) --}}
    <div class="space-y-3.5">

        {{-- 1. TATA TERTIB BERPAKAIAN --}}
        <div class="bg-white dark:bg-zinc-900 border border-gray-200/90 dark:border-zinc-800 rounded-2xl shadow-xs overflow-hidden transition">
            <button wire:click="toggleSection(1)"
                    type="button"
                    class="w-full p-4 sm:p-5 flex items-center justify-between text-left hover:bg-gray-50/60 dark:hover:bg-zinc-800/50 transition gap-3">
                <div class="flex items-center gap-3.5">
                    <span class="w-8 h-8 rounded-xl {{ $openSection === 1 ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300' }} flex items-center justify-center font-bold text-xs shrink-0">
                        1
                    </span>
                    <div>
                        <h2 class="font-bold text-sm sm:text-base text-gray-900 dark:text-zinc-100 leading-snug">Tata Tertib Berpakaian & Penampilan di Laboratorium</h2>
                        <p class="text-xs text-gray-500 dark:text-zinc-400 hidden sm:block">Ketentuan standar pakaian rapi, jas laboratorium, dan penggunaan sepatu tertutup.</p>
                    </div>
                </div>
                <div class="w-7 h-7 rounded-lg bg-gray-100 dark:bg-zinc-800 flex items-center justify-center shrink-0 transition {{ $openSection === 1 ? 'rotate-180 bg-primary/10 text-primary' : 'text-gray-400' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </button>

            @if ($openSection === 1)
                <div class="p-5 pt-0 border-t border-gray-100 dark:border-zinc-800 space-y-3 text-xs sm:text-sm text-gray-700 dark:text-zinc-300 leading-relaxed">
                    <p class="text-gray-500 dark:text-zinc-400 pt-3">
                        Seluruh mahasiswa wajib berpenampilan rapi, sopan, dan mencerminkan etika akademik saat memasuki ruang laboratorium:
                    </p>
                    <ul class="list-disc list-inside space-y-1.5 pl-1">
                        <li><strong>Pakaian:</strong> Mengenakan kemeja atau baju berkerah yang rapi dan sopan. Dilarang keras memakai kaus oblong tanpa kerah atau pakaian yang robek.</li>
                        <li><strong>Bawahan:</strong> Mengenakan celana panjang atau rok panjang berbahan sopan (bukan celana pendek atau kulot di atas mata kaki).</li>
                        <li><strong>Alas Kaki:</strong> Wajib menggunakan sepatu tertutup dan berkaus kaki. Dilarang masuk menggunakan sandal jepit, selop, atau alas kaki terbuka.</li>
                        <li><strong>Jas Laboratorium:</strong> Khusus praktikum sains atau sesi yang mensyaratkan alat pelindung diri (APD), mahasiswa wajib memakai jas laboratorium resmi berkancing rapi.</li>
                        <li><strong>Tanda Pengenal:</strong> Membawa dan mengenakan Kartu Tanda Mahasiswa (KTM) yang masih berlaku selama sesi praktikum berlangsung.</li>
                    </ul>
                </div>
            @endif
        </div>

        {{-- 2. KEDISIPLINAN KEHADIRAN --}}
        <div class="bg-white dark:bg-zinc-900 border border-gray-200/90 dark:border-zinc-800 rounded-2xl shadow-xs overflow-hidden transition">
            <button wire:click="toggleSection(2)"
                    type="button"
                    class="w-full p-4 sm:p-5 flex items-center justify-between text-left hover:bg-gray-50/60 dark:hover:bg-zinc-800/50 transition gap-3">
                <div class="flex items-center gap-3.5">
                    <span class="w-8 h-8 rounded-xl {{ $openSection === 2 ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300' }} flex items-center justify-center font-bold text-xs shrink-0">
                        2
                    </span>
                    <div>
                        <h2 class="font-bold text-sm sm:text-base text-gray-900 dark:text-zinc-100 leading-snug">Ketentuan Kehadiran & Kedisiplinan Waktu</h2>
                        <p class="text-xs text-gray-500 dark:text-zinc-400 hidden sm:block">Batas waktu keterlambatan, presensi sesi, dan ketentuan izin tidak hadir.</p>
                    </div>
                </div>
                <div class="w-7 h-7 rounded-lg bg-gray-100 dark:bg-zinc-800 flex items-center justify-center shrink-0 transition {{ $openSection === 2 ? 'rotate-180 bg-primary/10 text-primary' : 'text-gray-400' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </button>

            @if ($openSection === 2)
                <div class="p-5 pt-0 border-t border-gray-100 dark:border-zinc-800 space-y-3 text-xs sm:text-sm text-gray-700 dark:text-zinc-300 leading-relaxed">
                    <p class="text-gray-500 dark:text-zinc-400 pt-3">
                        Ketepatan waktu dan presensi kehadiran merupakan syarat mutlak kelulusan modul praktikum:
                    </p>
                    <ul class="list-disc list-inside space-y-1.5 pl-1">
                        <li><strong>Kehadiran Tepat Waktu:</strong> Mahasiswa wajib hadir di ruang laboratorium selambat-lambatnya 10 menit sebelum jam praktikum dimulai.</li>
                        <li><strong>Toleransi Keterlambatan:</strong> Keterlambatan maksimal adalah 15 menit dengan alasan yang dapat dipertanggungjawabkan kepada dosen pengampu / asisten lab. Keterlambatan melebihi batas tersebut tidak diizinkan mengikuti sesi pada hari itu.</li>
                        <li><strong>Persentase Kehadiran:</strong> Mahasiswa wajib memenuhi minimal 80% kehadiran sesi praktikum dalam satu semester untuk berhak mengikuti evaluasi akhir/responsipraktikum.</li>
                        <li><strong>Ketidakhadiran:</strong> Izin hanya berlaku apabila disertai surat keterangan dokter resmi (sakit) atau surat izin tertulis dari pimpinan fakultas/jurusan.</li>
                    </ul>
                </div>
            @endif
        </div>

        {{-- 3. ETIKA & KEBERSIHAN LABORATORIUM --}}
        <div class="bg-white dark:bg-zinc-900 border border-gray-200/90 dark:border-zinc-800 rounded-2xl shadow-xs overflow-hidden transition">
            <button wire:click="toggleSection(3)"
                    type="button"
                    class="w-full p-4 sm:p-5 flex items-center justify-between text-left hover:bg-gray-50/60 dark:hover:bg-zinc-800/50 transition gap-3">
                <div class="flex items-center gap-3.5">
                    <span class="w-8 h-8 rounded-xl {{ $openSection === 3 ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300' }} flex items-center justify-center font-bold text-xs shrink-0">
                        3
                    </span>
                    <div>
                        <h2 class="font-bold text-sm sm:text-base text-gray-900 dark:text-zinc-100 leading-snug">Etika, Kebersihan, & Pemeliharaan Fasilitas Lab</h2>
                        <p class="text-xs text-gray-500 dark:text-zinc-400 hidden sm:block">Larangan makan/minum, larangan mengubah kabel/konfigurasi, dan prosedur selesai praktikum.</p>
                    </div>
                </div>
                <div class="w-7 h-7 rounded-lg bg-gray-100 dark:bg-zinc-800 flex items-center justify-center shrink-0 transition {{ $openSection === 3 ? 'rotate-180 bg-primary/10 text-primary' : 'text-gray-400' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </button>

            @if ($openSection === 3)
                <div class="p-5 pt-0 border-t border-gray-100 dark:border-zinc-800 space-y-3 text-xs sm:text-sm text-gray-700 dark:text-zinc-300 leading-relaxed">
                    <p class="text-gray-500 dark:text-zinc-400 pt-3">
                        Guna merawat sarana dan prasarana komputer dan perlengkapan sains bersama:
                    </p>
                    <ul class="list-disc list-inside space-y-1.5 pl-1">
                        <li><strong>Larangan Makanan & Minuman:</strong> Dilarang keras membawa makanan, minuman terbuka, permen karet, atau merokok di dalam ruang laboratorium komputer.</li>
                        <li><strong>Integritas Perangkat Keras:</strong> Dilarang mencabut atau menukar kabel listrik, kabel VGA/HDMI, kabel LAN, mouse, atau keyboard antar workstation.</li>
                        <li><strong>Keamanan Sistem:</strong> Dilarang mengunduh berkas berbahaya, memasang software bajakan tanpa izin, atau mengubah setelan jaringan dan sandi komputer laboratorium.</li>
                        <li><strong>Prosedur Setelah Selesai:</strong> Mahasiswa wajib menutup semua aplikasi yang digunakan, mematikan (shut down) komputer, membuang sampah pada tempatnya, serta merapikan kembali kursi sebelum meninggalkan ruangan.</li>
                        <li><strong>Tanggung Jawab Kerusakan:</strong> Kerusakan perangkat yang disebabkan oleh kelalaian atau kesengajaan mahasiswa menjadi tanggung jawab yang bersangkutan.</li>
                    </ul>
                </div>
            @endif
        </div>

        {{-- 4. PANDUAN MEMBACA JADWAL --}}
        <div class="bg-white dark:bg-zinc-900 border border-gray-200/90 dark:border-zinc-800 rounded-2xl shadow-xs overflow-hidden transition">
            <button wire:click="toggleSection(4)"
                    type="button"
                    class="w-full p-4 sm:p-5 flex items-center justify-between text-left hover:bg-gray-50/60 dark:hover:bg-zinc-800/50 transition gap-3">
                <div class="flex items-center gap-3.5">
                    <span class="w-8 h-8 rounded-xl {{ $openSection === 4 ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300' }} flex items-center justify-center font-bold text-xs shrink-0">
                        4
                    </span>
                    <div>
                        <h2 class="font-bold text-sm sm:text-base text-gray-900 dark:text-zinc-100 leading-snug">Panduan Membaca Jadwal & Kalender Praktikum</h2>
                        <p class="text-xs text-gray-500 dark:text-zinc-400 hidden sm:block">Arti kode ruangan, penyaringan prodi, kalender sesi, dan unduh jadwal.</p>
                    </div>
                </div>
                <div class="w-7 h-7 rounded-lg bg-gray-100 dark:bg-zinc-800 flex items-center justify-center shrink-0 transition {{ $openSection === 4 ? 'rotate-180 bg-primary/10 text-primary' : 'text-gray-400' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </button>

            @if ($openSection === 4)
                <div class="p-5 pt-0 border-t border-gray-100 dark:border-zinc-800 space-y-3 text-xs sm:text-sm text-gray-700 dark:text-zinc-300 leading-relaxed">
                    <p class="text-gray-500 dark:text-zinc-400 pt-3">
                        Sistem informasi ini menyediakan portal terbuka tanpa login bagi seluruh mahasiswa:
                    </p>
                    <ul class="list-disc list-inside space-y-1.5 pl-1">
                        <li><strong>Daftar Jadwal:</strong> Menampilkan tabel dan kartu jadwal praktikum mingguan lengkap dengan mata kuliah, kelas, nama dosen pengampu, hari, jam, dan laboratorium.</li>
                        <li><strong>Filter Berjenjang:</strong> Mahasiswa dapat menyaring jadwal berdasarkan Fakultas, Program Studi, Hari, maupun pencarian teks langsung (nama dosen, kelas, atau mata kuliah).</li>
                        <li><strong>Kalender Interaktif:</strong> Menyajikan tampilan visual grid mingguan yang mempermudah memeriksa slot waktu kelas.</li>
                        <li><strong>Unduh Jadwal Resmi:</strong> Tombol <em>Unduh PDF</em> dan <em>Unduh Excel</em> di halaman Daftar Jadwal memungkinkan ketua kelas mengunduh jadwal resmi per program studi.</li>
                    </ul>
                </div>
            @endif
        </div>

        {{-- 5. SESI PENGGANTI & BANTUAN --}}
        <div class="bg-white dark:bg-zinc-900 border border-gray-200/90 dark:border-zinc-800 rounded-2xl shadow-xs overflow-hidden transition">
            <button wire:click="toggleSection(5)"
                    type="button"
                    class="w-full p-4 sm:p-5 flex items-center justify-between text-left hover:bg-gray-50/60 dark:hover:bg-zinc-800/50 transition gap-3">
                <div class="flex items-center gap-3.5">
                    <span class="w-8 h-8 rounded-xl {{ $openSection === 5 ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300' }} flex items-center justify-center font-bold text-xs shrink-0">
                        5
                    </span>
                    <div>
                        <h2 class="font-bold text-sm sm:text-base text-gray-900 dark:text-zinc-100 leading-snug">Prosedur Praktikum Pengganti & Pusat Bantuan</h2>
                        <p class="text-xs text-gray-500 dark:text-zinc-400 hidden sm:block">Alur koordinasi praktikum susulan dan saluran kontak Helpdesk Pustikom.</p>
                    </div>
                </div>
                <div class="w-7 h-7 rounded-lg bg-gray-100 dark:bg-zinc-800 flex items-center justify-center shrink-0 transition {{ $openSection === 5 ? 'rotate-180 bg-primary/10 text-primary' : 'text-gray-400' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </button>

            @if ($openSection === 5)
                <div class="p-5 pt-0 border-t border-gray-100 dark:border-zinc-800 space-y-3 text-xs sm:text-sm text-gray-700 dark:text-zinc-300 leading-relaxed">
                    <p class="text-gray-500 dark:text-zinc-400 pt-3">
                        Apabila terdapat sesi yang berhalangan atau perubahan waktu praktikum:
                    </p>
                    <ul class="list-disc list-inside space-y-1.5 pl-1">
                        <li><strong>Sesi Pengganti:</strong> Jadwal pengganti akan ditentukan oleh dosen pengampu dan langsung tercatat pada portal jadwal sistem secara otomatis. Mahasiswa dapat memeriksa jadwal terbaru secara berkala.</li>
                        <li><strong>Pertanyaan Jadwal:</strong> Apabila terdapat ketidaksesuaian jam kuliah atau ruangan, hubungi perwakilan ketua kelas atau laboran laboratorium bersangkutan.</li>
                    </ul>

                    {{-- Helpdesk Pustikom --}}
                    <div class="p-4 rounded-xl bg-primary/5 dark:bg-primary/10 border border-primary/20 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mt-3">
                        <div>
                            <strong class="font-bold text-gray-900 dark:text-zinc-100 block text-xs sm:text-sm">Pusat Bantuan & Layanan Informasi Pustikom</strong>
                            <p class="text-xs text-gray-500 dark:text-zinc-400">Pusat Teknologi Informasi dan Pangkalan Data (Pustikom) UIN Siber Syekh Nurjati Cirebon.</p>
                        </div>
                        <div class="flex items-center gap-2 flex-wrap text-xs">
                            <span class="px-3 py-1.5 rounded-lg bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 text-gray-700 dark:text-zinc-300 font-medium">Gedung Pustikom Lt. 2</span>
                            <span class="px-3 py-1.5 rounded-lg bg-primary text-white font-semibold">helpdesk@praktikum.ac.id</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>
