<div class="space-y-6 max-w-5xl mx-auto pb-12">
    {{-- Header Banner --}}
    <div class="bg-gradient-to-r from-primary to-primary-dark rounded-2xl p-6 sm:p-8 text-white shadow-md relative overflow-hidden">
        <div class="relative z-10 max-w-3xl space-y-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/15 text-white backdrop-blur-xs border border-white/20">
                Dokumen Resmi Pustikom
            </span>
            <h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold tracking-tight">Pedoman Operasional Standar (SOP) & Tata Tertib Praktikum</h1>
            <p class="text-white/85 text-xs sm:text-sm leading-relaxed">
                Panduan tata laksana penjadwalan laboratorium, batas kewenangan pengguna, alur kerja dosen dan operator fakultas/program studi, serta tata tertib fasilitas praktikum di lingkungan universitas.
            </p>
        </div>
    </div>

    {{-- Accordion List (1, 2, 3, 4, 5) --}}
    <div class="space-y-3.5">

        {{-- SECTION 1 --}}
        <div class="bg-white border border-gray-200/90 rounded-2xl shadow-xs overflow-hidden transition">
            <button wire:click="toggleSection(1)"
                    type="button"
                    class="w-full p-4 sm:p-5 flex items-center justify-between text-left hover:bg-gray-50/60 transition gap-3">
                <div class="flex items-center gap-3.5">
                    <span class="w-8 h-8 rounded-xl {{ $openSection === 1 ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700' }} flex items-center justify-center font-bold text-xs shrink-0">
                        1
                    </span>
                    <div>
                        <h2 class="font-bold text-sm sm:text-base text-gray-900 leading-snug">Pembagian Tugas dan Wewenang Pengguna</h2>
                        <p class="text-xs text-gray-500 hidden sm:block">Peran dan batas tanggung jawab Super Admin, Operator, Dosen, dan Mahasiswa.</p>
                    </div>
                </div>
                <div class="w-7 h-7 rounded-lg bg-gray-100 flex items-center justify-center shrink-0 transition {{ $openSection === 1 ? 'rotate-180 bg-primary/10 text-primary' : 'text-gray-400' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </button>

            @if ($openSection === 1)
                <div class="p-5 pt-0 border-t border-gray-100 space-y-4 text-xs sm:text-sm text-gray-700 leading-relaxed">
                    <p class="text-gray-500 pt-3">
                        Guna menjamin kepastian jadwal, menghindari tumpang tindih penggunaan fasilitas, dan menjaga integritas data lintas unit kerja, sistem menerapkan pembagian wewenang berjenjang:
                    </p>

                    <div class="space-y-3">
                        {{-- Super Admin --}}
                        <div class="p-4 rounded-xl border border-purple-100 bg-purple-50/30 space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-purple-950 text-xs sm:text-sm">A. Super Administrator (Pustikom / Sarana Prasarana Universitas)</span>
                            </div>
                            <ul class="list-disc list-inside space-y-1 text-gray-600 pl-1 text-xs sm:text-sm">
                                <li><strong>Pengelolaan Pengguna:</strong> Mendaftarkan, mengedit, dan menetapkan penugasan akun Operator tiap fakultas/program studi melalui menu <em>Pengguna & Operator</em>.</li>
                                <li><strong>Otoritas Master Ruang:</strong> Satu-satunya pengelola yang berwenang mendaftarkan, memperbarui, atau menghapus data fisik laboratorium dan fasilitas universitas.</li>
                                <li><strong>Periode Akademik:</strong> Menetapkan tahun akademik dan semester yang berstatus aktif di lingkungan universitas.</li>
                                <li><strong>Pengawasan Sistem:</strong> Memantau log riwayat aktivitas seluruh pengelola dan mengelola arsip data terhapus (sampah).</li>
                            </ul>
                        </div>

                        {{-- Operator --}}
                        <div class="p-4 rounded-xl border border-blue-100 bg-blue-50/30 space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-blue-950 text-xs sm:text-sm">B. Operator (Fakultas / Program Studi)</span>
                            </div>
                            <ul class="list-disc list-inside space-y-1 text-gray-600 pl-1 text-xs sm:text-sm">
                                <li><strong>Penyusunan Jadwal:</strong> Mengatur jadwal praktikum untuk mata kuliah dan kelas yang berada di bawah lingkup fakultas/prodinya.</li>
                                <li><strong>Impor Lembar Kerja Excel:</strong> Mengunggah berkas jadwal perkuliahan secara kolektif dengan sistem pencocokan otomatis.</li>
                                <li><strong>Katalog Ruang Resmi:</strong> Memilih ruangan laboratorium resmi yang telah diverifikasi (tidak dapat menambah ruang fiktif).</li>
                                <li><strong>Verifikasi Pengajuan:</strong> Meninjau dan menyetujui permohonan perpindahan jadwal dari dosen pengampu yang memerlukan koordinasi khusus.</li>
                            </ul>
                        </div>

                        {{-- Dosen --}}
                        <div class="p-4 rounded-xl border border-emerald-100 bg-emerald-50/30 space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-emerald-950 text-xs sm:text-sm">C. Dosen Pengampu Praktikum</span>
                            </div>
                            <ul class="list-disc list-inside space-y-1 text-gray-600 pl-1 text-xs sm:text-sm">
                                <li><strong>Jadwal Mengajar Saya:</strong> Memeriksa agenda mengajar praktikum per semester melalui menu khusus dosen.</li>
                                <li><strong>Reschedule Mandiri Instan:</strong> Menggeser waktu atau ruangan praktikum secara langsung detik itu juga apabila slot tujuan terbukti kosong.</li>
                                <li><strong>Pengajuan Perubahan:</strong> Mengajukan permohonan ke operator fakultas apabila diperlukan penyesuaian khusus di luar slot kosong.</li>
                            </ul>
                        </div>

                        {{-- Mahasiswa --}}
                        <div class="p-4 rounded-xl border border-amber-100 bg-amber-50/30 space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-amber-950 text-xs sm:text-sm">D. Mahasiswa dan Sivitas Akademika (Akses Publik)</span>
                            </div>
                            <ul class="list-disc list-inside space-y-1 text-gray-600 pl-1 text-xs sm:text-sm">
                                <li><strong>Akses Tanpa Login:</strong> Mahasiswa dapat langsung melihat jadwal praktikum tanpa perlu membuat akun atau login ke sistem.</li>
                                <li><strong>Pencarian & Filter:</strong> Menyaring jadwal berdasarkan program studi, hari, ruangan, atau nama dosen pengampu.</li>
                                <li><strong>Unduh Dokumen:</strong> Mengunduh jadwal dalam format cetak (PDF) atau lembar kerja untuk arsip angkatan/kelas.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- SECTION 2 --}}
        <div class="bg-white border border-gray-200/90 rounded-2xl shadow-xs overflow-hidden transition">
            <button wire:click="toggleSection(2)"
                    type="button"
                    class="w-full p-4 sm:p-5 flex items-center justify-between text-left hover:bg-gray-50/60 transition gap-3">
                <div class="flex items-center gap-3.5">
                    <span class="w-8 h-8 rounded-xl {{ $openSection === 2 ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700' }} flex items-center justify-center font-bold text-xs shrink-0">
                        2
                    </span>
                    <div>
                        <h2 class="font-bold text-sm sm:text-base text-gray-900 leading-snug">Panduan Pengelolaan Jadwal & Prosedur Unggah Excel</h2>
                        <p class="text-xs text-gray-500 hidden sm:block">Ketentuan penyusunan jadwal, format berkas, dan sistem pencocokan kolom cerdas.</p>
                    </div>
                </div>
                <div class="w-7 h-7 rounded-lg bg-gray-100 flex items-center justify-center shrink-0 transition {{ $openSection === 2 ? 'rotate-180 bg-primary/10 text-primary' : 'text-gray-400' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </button>

            @if ($openSection === 2)
                <div class="p-5 pt-0 border-t border-gray-100 space-y-4 text-xs sm:text-sm text-gray-700 leading-relaxed">
                    <p class="text-gray-500 pt-3">
                        Operator program studi dapat mengelola jadwal melalui form input manual atau melakukan pengunggahan berkas lembar kerja (Excel):
                    </p>

                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 space-y-2">
                        <strong class="font-bold text-emerald-950 flex items-center gap-1.5">
                            <span>✓</span> Fleksibilitas Fitur Unggah Excel (Smart Importer):
                        </strong>
                        <ol class="list-decimal list-inside space-y-1.5 text-emerald-900">
                            <li><strong>Deteksi Header Otomatis:</strong> Sistem secara mandiri mendeteksi baris judul kolom meskipun dokumen Anda memuat kop surat atau baris judul di bagian atas.</li>
                            <li><strong>Nama Kolom Fleksibel:</strong> Anda dapat menggunakan judul kolom umum seperti <em>Mata Kuliah</em>, <em>MK</em>, <em>Kelas</em>, <em>Dosen</em>, <em>Ruang</em>, <em>Hari</em>, dan <em>Jam</em>.</li>
                            <li><strong>Format Waktu Adaptif:</strong> Sistem mendukung format terpisah (Jam Mulai & Jam Selesai) maupun format gabungan seperti <code>08:00 - 10:00</code> atau <code>08.00-10.30</code>.</li>
                            <li><strong>Registrasi Master Otomatis:</strong> Apabila nama mata kuliah, kelas, atau dosen baru belum terdaftar di sistem, sistem akan mendaftarkannya secara otomatis di bawah unit kerja Anda.</li>
                            <li><strong>Deteksi Bentrok Terperinci:</strong> Baris yang tidak bentrok akan langsung tersimpan, sedangkan baris yang bentrok akan dilaporkan dengan alasan spesifik.</li>
                        </ol>
                    </div>

                    <div class="space-y-2">
                        <strong class="font-bold text-gray-900">Tata Cara Pengunggahan:</strong>
                        <ol class="list-decimal list-inside space-y-1 text-gray-600 pl-1">
                            <li>Buka menu <strong>Kelola Jadwal</strong> pada navigasi utama.</li>
                            <li>Klik tombol <strong>Unggah Excel</strong> pada sudut kanan atas.</li>
                            <li>Pilih berkas Excel (.xlsx, .xls, atau .csv) dari perangkat Anda, lalu klik <strong>Proses Import</strong>.</li>
                            <li>Periksa ringkasan hasil: jumlah jadwal yang berhasil masuk serta daftar catatan revisi (jika ditemukan bentrok waktu/ruang).</li>
                        </ol>
                    </div>
                </div>
            @endif
        </div>

        {{-- SECTION 3 --}}
        <div class="bg-white border border-gray-200/90 rounded-2xl shadow-xs overflow-hidden transition">
            <button wire:click="toggleSection(3)"
                    type="button"
                    class="w-full p-4 sm:p-5 flex items-center justify-between text-left hover:bg-gray-50/60 transition gap-3">
                <div class="flex items-center gap-3.5">
                    <span class="w-8 h-8 rounded-xl {{ $openSection === 3 ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700' }} flex items-center justify-center font-bold text-xs shrink-0">
                        3
                    </span>
                    <div>
                        <h2 class="font-bold text-sm sm:text-base text-gray-900 leading-snug">Panduan Dosen: Jadwal Mengajar & Mekanisme Reschedule</h2>
                        <p class="text-xs text-gray-500 hidden sm:block">Perbedaan Reschedule Mandiri Instan dengan Pengajuan Perubahan Jadwal.</p>
                    </div>
                </div>
                <div class="w-7 h-7 rounded-lg bg-gray-100 flex items-center justify-center shrink-0 transition {{ $openSection === 3 ? 'rotate-180 bg-primary/10 text-primary' : 'text-gray-400' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </button>

            @if ($openSection === 3)
                <div class="p-5 pt-0 border-t border-gray-100 space-y-4 text-xs sm:text-sm text-gray-700 leading-relaxed">
                    <p class="text-gray-500 pt-3">
                        Setiap dosen yang telah terhubung ke sistem dapat melihat seluruh kelas praktikum yang diampu dan melakukan penyesuaian jadwal mengajar:
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                        <div class="p-4 rounded-xl border border-emerald-200 bg-emerald-50/40 space-y-2">
                            <div class="font-bold text-emerald-950 flex items-center gap-1.5">
                                <span>⚡</span> Reschedule Mandiri Instan
                            </div>
                            <p class="text-emerald-900 text-xs sm:text-sm">
                                Berlaku apabila ruangan, hari, dan jam pengganti yang dipilih <strong>sedang kosong</strong> dan tidak digunakan kelas lain. Jadwal akan berpindah <strong>seketika itu juga</strong> tanpa perlu menunggu persetujuan dari operator, dan mahasiswa langsung melihat jadwal terkini.
                            </p>
                        </div>

                        <div class="p-4 rounded-xl border border-amber-200 bg-amber-50/40 space-y-2">
                            <div class="font-bold text-amber-950 flex items-center gap-1.5">
                                <span>📝</span> Pengajuan Perubahan Jadwal
                            </div>
                            <p class="text-amber-900 text-xs sm:text-sm">
                                Digunakan apabila slot yang diinginkan memerlukan koordinasi khusus, pertukaran jadwal dengan dosen rekanan, atau permohonan dispensasi ruang. Permohonan akan masuk ke antrean operator fakultas untuk diverifikasi dan disetujui.
                            </p>
                        </div>
                    </div>

                    <div class="space-y-1.5 text-gray-600">
                        <strong class="font-bold text-gray-900 block">Langkah Pelaksanaan bagi Dosen:</strong>
                        <p>1. Masuk ke menu <strong>Jadwal Mengajar Saya</strong> untuk memeriksa agenda kelas.</p>
                        <p>2. Klik tombol <strong>Pindah Jadwal</strong> pada kelas yang ingin disesuaikan.</p>
                        <p>3. Tentukan hari, jam, dan ruangan pengganti. Sistem secara langsung mengonfirmasi ketersediaan ruangan sebelum Anda menyimpan perubahan.</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- SECTION 4 --}}
        <div class="bg-white border border-gray-200/90 rounded-2xl shadow-xs overflow-hidden transition">
            <button wire:click="toggleSection(4)"
                    type="button"
                    class="w-full p-4 sm:p-5 flex items-center justify-between text-left hover:bg-gray-50/60 transition gap-3">
                <div class="flex items-center gap-3.5">
                    <span class="w-8 h-8 rounded-xl {{ $openSection === 4 ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700' }} flex items-center justify-center font-bold text-xs shrink-0">
                        4
                    </span>
                    <div>
                        <h2 class="font-bold text-sm sm:text-base text-gray-900 leading-snug">Ketentuan Penggunaan Fasilitas Ruang & Pencegahan Bentrok</h2>
                        <p class="text-xs text-gray-500 hidden sm:block">Perlindungan hak akses ruang fisik guna mencegah duplikasi atau manipulasi data.</p>
                    </div>
                </div>
                <div class="w-7 h-7 rounded-lg bg-gray-100 flex items-center justify-center shrink-0 transition {{ $openSection === 4 ? 'rotate-180 bg-primary/10 text-primary' : 'text-gray-400' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </button>

            @if ($openSection === 4)
                <div class="p-5 pt-0 border-t border-gray-100 space-y-4 text-xs sm:text-sm text-gray-700 leading-relaxed">
                    <p class="text-gray-500 pt-3">
                        Guna mengantisipasi potensi rekayasa nama ruangan (seperti membuat nama ruang baru yang mirip untuk menghindari bentrok), sistem menerapkan standarisasi keamanan berikut:
                    </p>

                    <div class="space-y-3">
                        <div class="p-3.5 bg-gray-50 rounded-xl border border-gray-200/80">
                            <strong class="font-bold text-gray-900 block mb-1">1. Master Ruang Terpusat pada Super Admin</strong>
                            <p class="text-gray-600 text-xs sm:text-sm">
                                Operator fakultas/prodi tidak memiliki hak akses untuk menambah, menyunting, atau menghapus data fisik laboratorium. Seluruh ruangan fisik resmi didaftarkan langsung oleh Super Administrator / Bagian Sarana Prasarana.
                            </p>
                        </div>

                        <div class="p-3.5 bg-gray-50 rounded-xl border border-gray-200/80">
                            <strong class="font-bold text-gray-900 block mb-1">2. Validasi Kode Ruangan Unik</strong>
                            <p class="text-gray-600 text-xs sm:text-sm">
                                Setiap laboratorium memiliki identitas kode resmi (contoh: <code>LAB-01</code>, <code>LAB-02</code>). Setiap sesi praktikum mengunci ID ruangan tersebut, sehingga tidak dapat disamarkan dengan penamaan ganda.
                            </p>
                        </div>

                        <div class="p-3.5 bg-gray-50 rounded-xl border border-gray-200/80">
                            <strong class="font-bold text-gray-900 block mb-1">3. Ruang Khusus vs Fasilitas Bersama</strong>
                            <p class="text-gray-600 text-xs sm:text-sm">
                                Laboratorium milik suatu fakultas hanya dapat dijadwalkan oleh operator fakultas bersangkutan. Ruang auditorium bersama berstatus fasilitas umum dan penggunaannya dikendalikan oleh kebijakan pusat.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- SECTION 5 --}}
        <div class="bg-white border border-gray-200/90 rounded-2xl shadow-xs overflow-hidden transition">
            <button wire:click="toggleSection(5)"
                    type="button"
                    class="w-full p-4 sm:p-5 flex items-center justify-between text-left hover:bg-gray-50/60 transition gap-3">
                <div class="flex items-center gap-3.5">
                    <span class="w-8 h-8 rounded-xl {{ $openSection === 5 ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700' }} flex items-center justify-center font-bold text-xs shrink-0">
                        5
                    </span>
                    <div>
                        <h2 class="font-bold text-sm sm:text-base text-gray-900 leading-snug">Tata Tertib Laboratorium Praktikum & Pusat Bantuan</h2>
                        <p class="text-xs text-gray-500 hidden sm:block">Peraturan umum penggunaan ruang dan saluran bantuan teknis Pustikom.</p>
                    </div>
                </div>
                <div class="w-7 h-7 rounded-lg bg-gray-100 flex items-center justify-center shrink-0 transition {{ $openSection === 5 ? 'rotate-180 bg-primary/10 text-primary' : 'text-gray-400' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </button>

            @if ($openSection === 5)
                <div class="p-5 pt-0 border-t border-gray-100 space-y-4 text-xs sm:text-sm text-gray-700 leading-relaxed">
                    <p class="text-gray-500 pt-3">
                        Seluruh sivitas akademika wajib mematuhi tata tertib pemanfaatan ruang laboratorium praktikum:
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                        <div class="p-4 rounded-xl border border-gray-200 bg-gray-50/50 space-y-2">
                            <strong class="font-bold text-gray-900 flex items-center gap-1.5">
                                <span class="text-primary font-bold">●</span> Ketertiban Waktu dan Penggunaan
                            </strong>
                            <ul class="list-disc list-inside space-y-1 text-gray-600 text-xs sm:text-sm">
                                <li>Kegiatan praktikum wajib dimulai dan diakhiri sesuai durasi jam yang tercantum pada sistem.</li>
                                <li>Dosen dan laboran diharapkan hadir minimal 10 menit sebelum sesi dimulai.</li>
                                <li>Penggunaan ruang di luar jadwal terdaftar wajib berkoordinasi terlebih dahulu dengan pengelola laboratorium.</li>
                            </ul>
                        </div>

                        <div class="p-4 rounded-xl border border-gray-200 bg-gray-50/50 space-y-2">
                            <strong class="font-bold text-gray-900 flex items-center gap-1.5">
                                <span class="text-primary font-bold">●</span> Pemeliharaan Fasilitas & Kebersihan
                            </strong>
                            <ul class="list-disc list-inside space-y-1 text-gray-600 text-xs sm:text-sm">
                                <li>Dilarang membawa makanan dan minuman terbuka ke dalam ruang laboratorium komputer.</li>
                                <li>Wajib mematikan perangkat komputer, proyektor, dan penyejuk udara (AC) setelah sesi selesai.</li>
                                <li>Dilarang memindahkan piranti keras atau mengubah konfigurasi jaringan tanpa persetujuan laboran.</li>
                            </ul>
                        </div>
                    </div>

                    {{-- Helpdesk Pustikom --}}
                    <div class="p-4 rounded-xl bg-primary/5 border border-primary/20 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mt-2">
                        <div>
                            <strong class="font-bold text-gray-900 block text-xs sm:text-sm">Layanan Bantuan Teknis Pustikom</strong>
                            <p class="text-xs text-gray-500">Pusat Teknologi Informasi dan Pangkalan Data (Pustikom) Universitas.</p>
                        </div>
                        <div class="flex items-center gap-2 flex-wrap text-xs">
                            <span class="px-3 py-1.5 rounded-lg bg-white border border-gray-200 text-gray-700 font-medium">Gedung Pustikom Lt. 2</span>
                            <span class="px-3 py-1.5 rounded-lg bg-primary text-white font-semibold">helpdesk@praktikum.ac.id</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>
