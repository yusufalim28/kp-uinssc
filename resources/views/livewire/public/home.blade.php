<div class="space-y-8 pb-12">

    {{-- Hero Section --}}
    <section class="relative bg-primary overflow-hidden rounded-3xl shadow-lg">
        <div class="absolute inset-0 bg-gradient-to-br from-primary to-primary-dark opacity-95"></div>
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-15"></div>

        <div class="relative p-7 sm:p-10 md:p-12 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="max-w-2xl text-white">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 border border-white/20 text-xs mb-3 backdrop-blur-xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                    <span>Tahun Akademik: <strong>{{ $taAktif?->nama ?? 'Aktif' }}</strong></span>
                </div>
                <h1 class="text-2xl sm:text-4xl md:text-5xl font-extrabold leading-tight tracking-tight">
                    Sistem Jadwal Praktikum
                </h1>
                <p class="mt-3 text-xs sm:text-sm text-white/90 leading-relaxed max-w-xl">
                    Layanan informasi jadwal praktikum terpadu, ketersediaan ruang laboratorium, dan kalender akademik di lingkungan <em>UIN Siber Syekh Nurjati Cirebon</em>.
                </p>
                <div class="mt-6 flex flex-wrap items-center gap-2.5">
                    <a data-keep-light href="{{ route('jadwal.index') }}"
                       class="bg-white text-primary font-bold px-4 py-2 rounded-xl hover:bg-gray-100 transition shadow-md flex items-center gap-1.5 text-xs sm:text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <span>Daftar Jadwal</span>
                    </a>
                    <a href="{{ route('jadwal.kalender') }}"
                       class="bg-white/15 border border-white/25 text-white font-semibold px-4 py-2 rounded-xl hover:bg-white/25 transition flex items-center gap-1.5 text-xs sm:text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Kalender</span>
                    </a>
                    <a href="{{ route('jadwal.ruang') }}"
                       class="bg-white/15 border border-white/25 text-white font-semibold px-4 py-2 rounded-xl hover:bg-white/25 transition flex items-center gap-1.5 text-xs sm:text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <span>Matriks Ruangan</span>
                    </a>
                </div>
            </div>

            {{-- Logo --}}
            <div data-keep-light class="hidden md:flex w-44 h-44 bg-white/80 rounded-full items-center justify-center backdrop-blur-xs border border-white/30 shadow-inner shrink-0">
                <img src="{{ asset('img/uinssc.png') }}" alt="UINSSC" class="w-32 mb-2">
            </div>
        </div>
    </section>

    {{-- WIDGET KOMPAK: STATUS KETERSEDIAAN RUANGAN (REAL-TIME LAB CHECKER) --}}
    <section class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl p-5 shadow-xs space-y-4">
        {{-- Baris Header & Ringkasan --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-gray-100 dark:border-zinc-800">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                    <h2 class="text-base sm:text-lg font-bold text-gray-900 dark:text-zinc-100">Ketersediaan Ruang Praktikum</h2>
                </div>
                <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">
                    Real-time ({{ $hariIni }}, pukul {{ $jamSekarang }} WIB)
                </p>
            </div>

            {{-- Ringkasan Angka Minimalis --}}
            <div class="flex items-center gap-3 text-xs">
                <span class="inline-flex items-center gap-1.5 text-gray-700 dark:text-zinc-300 font-semibold">
                    <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                    <span>{{ $totalTerpakai }} Terpakai</span>
                </span>
                <span class="inline-flex items-center gap-1.5 text-primary dark:text-emerald-400 font-semibold">
                    <span class="w-2 h-2 rounded-full bg-primary dark:bg-emerald-400"></span>
                    <span>{{ $totalTersedia }} Kosong</span>
                </span>
            </div>
        </div>

        {{-- Dropdown Pemilihan Tanpa Scroll Horizontal (Ramah Layar HP) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label class="block text-[11px] font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wider mb-1">
                    Fakultas
                </label>
                <select wire:model.live="filterFakultasRuang"
                        class="w-full text-xs rounded-xl border-gray-200 dark:border-zinc-700 bg-gray-50/80 dark:bg-zinc-800 text-gray-900 dark:text-zinc-100 py-2.5 px-3 focus:bg-white focus:border-primary">
                    <option value="semua">-- Semua Fakultas --</option>
                    @foreach ($fakultasList as $f)
                        <option value="{{ $f->id }}">{{ $f->nama_fakultas }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wider mb-1">
                    Pilih Ruang Lab untuk Cek Status
                </label>
                <select wire:model.live="selectedRuangId"
                        class="w-full text-xs rounded-xl border-gray-200 dark:border-zinc-700 bg-gray-50/80 dark:bg-zinc-800 text-gray-900 dark:text-zinc-100 py-2.5 px-3 focus:bg-white focus:border-primary">
                    <option value="">-- Pilih Laboratorium / Ruangan --</option>
                    @foreach ($ruangStatusList as $item)
                        @php $r = $item['ruang']; @endphp
                        <option value="{{ $r->id }}">
                            {{ $r->nama_ruang }} ({{ $item['label'] }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Panel Detail Ruangan Terpilih (Sangat Hemat Ruang) --}}
        @if ($selectedItem)
            @php
                $r = $selectedItem['ruang'];
                $st = $selectedItem['status'];
                $sesi = $selectedItem['sesi_aktif'];
                $next = $selectedItem['sesi_berikutnya'];
            @endphp
            <div class="p-3.5 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800/40 text-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="space-y-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="font-bold text-sm text-gray-900 dark:text-zinc-100">{{ $r->nama_ruang }}</span>
                        <span class="text-gray-400">• {{ $r->fakultas->nama_fakultas ?? 'Universitas' }} (Kapasitas {{ $r->kapasitas }})</span>
                    </div>

                    @if ($st === 'terpakai' && $sesi)
                        <div class="text-gray-700 dark:text-zinc-300">
                            <span class="font-bold text-gray-900 dark:text-zinc-100">Sedang Digunakan:</span>
                            <span>{{ $sesi->mataKuliah->nama_mk }} (Kelas {{ $sesi->kelas->nama_kelas }})</span>
                            <span class="text-gray-400">oleh {{ $sesi->dosen->nama }} ({{ \Illuminate\Support\Str::limit($sesi->jam_mulai, 5, '') }}–{{ \Illuminate\Support\Str::limit($sesi->jam_selesai, 5, '') }})</span>
                        </div>
                    @elseif ($st === 'tersedia')
                        <div class="text-primary dark:text-emerald-400 font-medium">
                            <span>Laboratorium kosong / siap digunakan.</span>
                            @if ($next)
                                <span class="text-gray-500 dark:text-zinc-400 font-normal ml-1">
                                    (Sesi berikutnya pukul {{ \Illuminate\Support\Str::limit($next->jam_mulai, 5, '') }}: {{ $next->mataKuliah->nama_mk }})
                                </span>
                            @endif
                        </div>
                    @elseif ($st === 'perbaikan')
                        <div class="text-gray-500 dark:text-zinc-400">
                            Laboratorium sedang dalam masa pemeliharaan sarana/perangkat.
                        </div>
                    @else
                        <div class="text-gray-500 dark:text-zinc-400">
                            Kampus libur akhir pekan.
                        </div>
                    @endif
                </div>

                <div class="shrink-0 flex items-center gap-2">
                    <a href="{{ route('jadwal.ruang', ['ruang' => $r->id]) }}"
                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 font-semibold text-xs text-primary dark:text-emerald-300 hover:bg-emerald-100 transition">
                        <span>Lihat Matriks Ruang</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        @else
            <div class="py-2 text-[11px] text-gray-400 dark:text-zinc-500 flex items-center justify-between">
                <span>Pilih ruangan pada dropdown di atas untuk mengecek status penggunaan laboratorium secara cepat.</span>
                <button wire:click="toggleShowAllRooms" type="button" class="text-primary dark:text-emerald-400 font-semibold hover:underline shrink-0">
                    {{ $showAllRooms ? 'Tutup Rekap Ruangan' : 'Lihat Rekap Seluruh Ruang' }}
                </button>
            </div>
        @endif

        {{-- Tabel Kompak Rekap Seluruh Ruangan (Collapsible / Tersembunyi Default) --}}
        @if ($showAllRooms)
            <div class="pt-2 border-t border-gray-100 dark:border-zinc-800">
                <div class="overflow-x-auto rounded-xl border border-gray-100 dark:border-zinc-800">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-gray-50 dark:bg-zinc-800 text-gray-600 dark:text-zinc-300 font-semibold">
                            <tr>
                                <th class="px-3.5 py-2">Nama Ruangan</th>
                                <th class="px-3.5 py-2">Fakultas</th>
                                <th class="px-3.5 py-2">Status Saat Ini</th>
                                <th class="px-3.5 py-2 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-zinc-800">
                            @foreach ($ruangStatusList as $item)
                                @php $r = $item['ruang']; @endphp
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-zinc-800/40">
                                    <td class="px-3.5 py-2 font-medium text-gray-900 dark:text-zinc-100">
                                        {{ $r->nama_ruang }}
                                    </td>
                                    <td class="px-3.5 py-2 text-gray-500 dark:text-zinc-400">
                                        {{ $r->fakultas->nama_fakultas ?? 'Universitas' }}
                                    </td>
                                    <td class="px-3.5 py-2">
                                        @if ($item['status'] === 'terpakai')
                                            <span class="text-gray-900 dark:text-zinc-100 font-medium">
                                                Terpakai: {{ $item['sesi_aktif']->mataKuliah->nama_mk ?? '-' }} ({{ \Illuminate\Support\Str::limit($item['sesi_aktif']->jam_selesai, 5, '') }})
                                            </span>
                                        @elseif ($item['status'] === 'tersedia')
                                            <span class="text-primary dark:text-emerald-400 font-medium">Kosong</span>
                                        @else
                                            <span class="text-gray-400">{{ $item['label'] }}</span>
                                        @endif
                                    </td>
                                    <td class="px-3.5 py-2 text-right">
                                        <a href="{{ route('jadwal.kalender', ['ruangPraktikumId' => $r->id]) }}"
                                           class="text-primary dark:text-emerald-400 hover:underline">
                                            Kalender
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </section>

    {{-- SECTION: JADWAL PRAKTIKUM HARI INI (MENJADI FOKUS UTAMA) --}}
    <section class="space-y-4">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-zinc-100">Jadwal Praktikum Hari Ini</h2>
                <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">
                    {{ $hariIni }}, {{ now()->translatedFormat('d F Y') }} (Total {{ $jadwalHariIni->count() }} sesi)
                </p>
            </div>
            <a href="{{ route('jadwal.index') }}" class="text-primary dark:text-emerald-400 font-semibold hover:underline flex items-center gap-1 text-xs sm:text-sm">
                Lihat Seluruh Jadwal Semester <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        @if ($jadwalHariIni->isEmpty())
            <div class="bg-white dark:bg-zinc-900 rounded-2xl p-8 text-center border border-gray-100 dark:border-zinc-800 shadow-xs">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-50 dark:bg-zinc-800 text-gray-400 dark:text-zinc-500 mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <p class="text-gray-700 dark:text-zinc-200 font-semibold text-sm">Tidak ada jadwal praktikum untuk hari {{ $hariIni }}.</p>
                <p class="text-gray-400 dark:text-zinc-500 text-xs mt-1">Gunakan halaman Daftar Jadwal atau Kalender untuk mengecek agenda di hari lain.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5">
                @foreach ($jadwalHariIni as $jadwal)
                    <a href="{{ route('jadwal.detail', $jadwal) }}"
                       class="group bg-white dark:bg-zinc-900 border border-gray-200/90 dark:border-zinc-800 shadow-xs rounded-2xl p-4 hover:border-primary dark:hover:border-emerald-500 hover:shadow-md transition flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="px-2 py-0.5 text-[11px] font-bold rounded-md bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300">
                                    {{ $jadwal->hari }}
                                </span>
                                <span class="text-xs font-semibold text-gray-600 dark:text-zinc-300">
                                    {{ \Illuminate\Support\Str::limit($jadwal->jam_mulai, 5, '') }}–{{ \Illuminate\Support\Str::limit($jadwal->jam_selesai, 5, '') }}
                                </span>
                            </div>
                            <h3 class="font-bold text-gray-900 dark:text-zinc-100 group-hover:text-primary dark:group-hover:text-emerald-400 transition line-clamp-1 text-sm sm:text-base">
                                {{ $jadwal->mataKuliah->nama_mk }}
                            </h3>
                            <div class="mt-2 space-y-1 text-xs text-gray-500 dark:text-zinc-400">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <span class="line-clamp-1">{{ $jadwal->dosen->nama }}</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    <span class="font-medium text-gray-700 dark:text-zinc-300">{{ $jadwal->ruangPraktikum->nama_ruang }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 pt-2 border-t border-gray-100 dark:border-zinc-800 text-[11px] text-gray-400 flex items-center justify-between">
                            <span>Kelas {{ $jadwal->kelas->nama_kelas }}</span>
                            <span class="text-primary dark:text-emerald-400 font-semibold group-hover:underline">Lihat Rincian →</span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </section>

</div>
