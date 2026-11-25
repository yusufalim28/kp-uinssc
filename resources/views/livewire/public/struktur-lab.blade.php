<div class="space-y-10 pb-12">
    {{-- Header Section (Terinspirasi Desain Tim PUSTIKOM) --}}
    <div class="text-center max-w-2xl mx-auto space-y-3">
        <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-md text-[11px] font-extrabold uppercase tracking-wider bg-[#0B2E23] dark:bg-emerald-600 text-white shadow-2xs">
            Struktur Organisasi Lab
        </span>
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-black text-gray-900 dark:text-zinc-100 tracking-tight">
            Penanggung Jawab Laboratorium
        </h1>
        <p class="text-xs sm:text-sm text-gray-600 dark:text-zinc-400 leading-relaxed">
            Daftar struktur pengelola, ketua laboratorium, pranata, dan teknisi penanggung jawab pada tiap ruang laboratorium di lingkungan UIN Siber Syekh Nurjati Cirebon.
        </p>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white dark:bg-zinc-900 rounded-2xl p-4 sm:p-5 border border-gray-200/80 dark:border-zinc-800 shadow-2xs">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div>
                <label class="block text-[11px] font-bold text-gray-500 dark:text-zinc-400 uppercase tracking-wider mb-1.5">Fakultas</label>
                <select wire:model.live="fakultasId"
                        class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 text-xs sm:text-sm px-3.5 py-2.5 text-gray-900 dark:text-zinc-100 focus:border-primary focus:ring-2 focus:ring-primary/20 transition">
                    <option value="">-- Semua Fakultas --</option>
                    @foreach ($fakultasList as $fak)
                        <option value="{{ $fak->id }}" wire:key="fak-opt-{{ $fak->id }}">{{ $fak->nama_fakultas }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-gray-500 dark:text-zinc-400 uppercase tracking-wider mb-1.5">Ruang Laboratorium</label>
                <select wire:model.live="ruangPraktikumId"
                        class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 text-xs sm:text-sm px-3.5 py-2.5 text-gray-900 dark:text-zinc-100 focus:border-primary focus:ring-2 focus:ring-primary/20 transition">
                    <option value="">-- Semua Ruang Laboratorium --</option>
                    @foreach ($ruangOptions as $r)
                        <option value="{{ $r->id }}" wire:key="ruang-opt-{{ $r->id }}">{{ $r->nama_ruang }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-gray-500 dark:text-zinc-400 uppercase tracking-wider mb-1.5">Cari Nama / Jabatan</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Ketik nama, jabatan, atau NIP..."
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 text-xs sm:text-sm text-gray-900 dark:text-zinc-100 placeholder:text-gray-400 focus:border-primary focus:ring-2 focus:ring-primary/20 transition">
                </div>
            </div>
        </div>

        @if ($fakultasId || $ruangPraktikumId || $search !== '')
            <div class="mt-3 pt-3 border-t border-gray-100 dark:border-zinc-800 flex justify-end">
                <button wire:click="resetFilters" type="button"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 dark:hover:bg-rose-900/50 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span>Reset Filter</span>
                </button>
            </div>
        @endif
    </div>

    {{-- Daftar Struktur per Ruang Laboratorium --}}
    <div class="space-y-14">
        @forelse ($ruangGroups as $ruang)
            @php
                $pimpinan = $ruang->penanggungJawab->where('tingkat', 'pimpinan');
                $anggota = $ruang->penanggungJawab->where('tingkat', '!=', 'pimpinan');
            @endphp

            <section wire:key="ruang-section-{{ $ruang->id }}" class="space-y-7">
                {{-- Header Ruang Lab --}}
                <div class="text-center space-y-2">
                    <div class="flex items-center justify-center gap-2 flex-wrap">
                        @if ($ruang->fakultas)
                            <span class="px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-800 dark:text-emerald-300 border border-emerald-200/70 dark:border-emerald-800/60">
                                {{ $ruang->fakultas->nama_fakultas }}
                            </span>
                        @endif
                        @if ($ruang->kode_ruang && $ruang->kode_ruang !== 'TBD')
                            <span class="px-2.5 py-0.5 rounded-md text-[11px] font-mono font-bold bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-zinc-300">
                                {{ $ruang->kode_ruang }}
                            </span>
                        @endif
                    </div>

                    <h2 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-zinc-100 tracking-tight">
                        {{ $ruang->nama_ruang }}
                    </h2>

                    <div class="flex items-center justify-center gap-3 flex-wrap text-xs text-gray-500 dark:text-zinc-400">
                        @if ($ruang->lokasi && $ruang->lokasi !== 'TBD')
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $ruang->lokasi }}
                            </span>
                            <span>•</span>
                        @endif
                        <a href="{{ route('jadwal.ruang', ['ruang' => $ruang->id]) }}"
                           class="inline-flex items-center gap-1 font-semibold text-primary dark:text-emerald-400 hover:underline">
                            <span>Lihat Jadwal Ruangan</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Bagan Struktur Organisasi Laboratorium --}}
                <div class="bg-white/70 dark:bg-zinc-900/50 border border-gray-200/70 dark:border-zinc-800/80 rounded-3xl p-4 sm:p-6 md:p-8 shadow-2xs">
                    {{-- Baris Pimpinan (Level Atas — Terpusat) --}}
                    @if ($pimpinan->isNotEmpty())
                        <div class="flex flex-wrap justify-center gap-3 sm:gap-5">
                            @foreach ($pimpinan as $person)
                                <article wire:key="pj-{{ $person->id }}"
                                         class="group w-[calc(50%-0.375rem)] max-w-[180px] sm:w-48 bg-white dark:bg-zinc-900 rounded-2xl border border-gray-200/80 dark:border-zinc-800 border-t-4 border-t-[#0B2E23] dark:border-t-emerald-500 shadow-xs hover:shadow-md transition duration-200 p-3 sm:p-4 flex flex-col items-center text-center justify-between gap-2.5">
                                    <div class="flex flex-col items-center gap-2.5 w-full">
                                        <div class="w-16 h-20 sm:w-20 sm:h-24 rounded-xl bg-gray-100 dark:bg-zinc-800 border border-gray-200/70 dark:border-zinc-700 overflow-hidden flex items-center justify-center shrink-0 shadow-2xs">
                                            @if ($person->foto_url)
                                                <img src="{{ $person->foto_url }}" alt="{{ $person->nama }}"
                                                     class="w-full h-full object-cover object-top group-hover:scale-105 transition duration-300">
                                            @else
                                                <span class="text-base sm:text-lg font-black text-[#0B2E23] dark:text-emerald-300">
                                                    {{ $person->inisial }}
                                                </span>
                                            @endif
                                        </div>

                                        <div class="space-y-0.5 w-full">
                                            <h3 class="font-extrabold text-xs sm:text-sm text-gray-900 dark:text-zinc-100 leading-snug line-clamp-2">
                                                {{ $person->nama }}
                                            </h3>
                                            <p class="text-[11px] sm:text-xs font-semibold text-emerald-700 dark:text-emerald-400 leading-tight">
                                                {{ $person->jabatan }}
                                            </p>
                                            @if ($person->nip_nidn)
                                                <p class="text-[10px] font-mono text-gray-400 dark:text-zinc-500 truncate">
                                                    {{ $person->nip_nidn }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    @if ($person->email || $person->whatsapp_url || $person->link_instagram || $person->link_linkedin)
                                        <div class="flex items-center justify-center gap-2.5 pt-2 border-t border-gray-100 dark:border-zinc-800 w-full text-gray-500 dark:text-zinc-400">
                                            @if ($person->email)
                                                <a href="mailto:{{ $person->email }}" title="Email: {{ $person->email }}" class="hover:text-primary dark:hover:text-emerald-400 transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                                </a>
                                            @endif
                                            @if ($person->whatsapp_url)
                                                <a href="{{ $person->whatsapp_url }}" target="_blank" rel="noopener noreferrer" title="WhatsApp: {{ $person->no_telepon }}" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                                </a>
                                            @endif
                                            @if ($person->link_instagram)
                                                <a href="{{ $person->link_instagram }}" target="_blank" rel="noopener noreferrer" title="Instagram" class="hover:text-pink-600 dark:hover:text-pink-400 transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5" stroke-width="2" stroke-linecap="round"/></svg>
                                                </a>
                                            @endif
                                            @if ($person->link_linkedin)
                                                <a href="{{ $person->link_linkedin }}" target="_blank" rel="noopener noreferrer" title="LinkedIn" class="hover:text-blue-600 dark:hover:text-blue-400 transition">
                                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h14m-.5 15.5v-5.3a3.26 3.26 0 00-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 011.4 1.4v4.93h2.75M6.88 8.56a1.68 1.68 0 001.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 00-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/></svg>
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </article>
                            @endforeach
                        </div>
                    @endif

                    {{-- Garis Penghubung Hierarki Organisasi (Pimpinan ke Anggota) --}}
                    @if ($pimpinan->isNotEmpty() && $anggota->isNotEmpty())
                        <div class="flex flex-col items-center my-2 sm:my-3" aria-hidden="true">
                            <div class="w-0.5 h-5 sm:h-6 bg-gray-300 dark:bg-zinc-700"></div>
                            <div class="w-3/4 sm:w-2/3 max-w-2xl h-0.5 bg-gray-300 dark:bg-zinc-700 relative">
                                <span class="absolute left-0 top-0 w-0.5 h-3 bg-gray-300 dark:bg-zinc-700"></span>
                                <span class="absolute left-1/2 -translate-x-1/2 top-0 w-0.5 h-3 bg-gray-300 dark:bg-zinc-700"></span>
                                <span class="absolute right-0 top-0 w-0.5 h-3 bg-gray-300 dark:bg-zinc-700"></span>
                            </div>
                            <div class="h-3"></div>
                        </div>
                    @endif

                    {{-- Baris Anggota / Pranata / Teknisi (Kompak: 2 kolom di HP, terpusat rapi di Desktop) --}}
                    @if ($anggota->isNotEmpty())
                        <div class="flex flex-wrap justify-center gap-3 sm:gap-4">
                            @foreach ($anggota as $person)
                                <article wire:key="pj-{{ $person->id }}"
                                         class="group w-[calc(50%-0.375rem)] max-w-[175px] sm:w-44 bg-white dark:bg-zinc-900 rounded-2xl border border-gray-200/80 dark:border-zinc-800 shadow-2xs hover:shadow-md hover:border-emerald-400/50 transition duration-200 p-3 sm:p-3.5 flex flex-col items-center text-center justify-between gap-2">
                                    <div class="flex flex-col items-center gap-2 w-full">
                                        <div class="w-14 h-18 sm:w-18 sm:h-22 rounded-xl bg-gray-100 dark:bg-zinc-800 border border-gray-200/70 dark:border-zinc-700 overflow-hidden flex items-center justify-center shrink-0">
                                            @if ($person->foto_url)
                                                <img src="{{ $person->foto_url }}" alt="{{ $person->nama }}"
                                                     class="w-full h-full object-cover object-top group-hover:scale-105 transition duration-300">
                                            @else
                                                <span class="text-sm sm:text-base font-black text-[#0B2E23] dark:text-emerald-300">
                                                    {{ $person->inisial }}
                                                </span>
                                            @endif
                                        </div>

                                        <div class="space-y-0.5 w-full">
                                            <h3 class="font-bold text-xs sm:text-[13px] text-gray-900 dark:text-zinc-100 leading-snug line-clamp-2">
                                                {{ $person->nama }}
                                            </h3>
                                            <p class="text-[11px] font-medium text-gray-500 dark:text-zinc-400 leading-tight">
                                                {{ $person->jabatan }}
                                            </p>
                                            @if ($person->nip_nidn)
                                                <p class="text-[10px] font-mono text-gray-400 dark:text-zinc-500 truncate">
                                                    {{ $person->nip_nidn }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    @if ($person->email || $person->whatsapp_url || $person->link_instagram || $person->link_linkedin)
                                        <div class="flex items-center justify-center gap-2.5 pt-1.5 border-t border-gray-100 dark:border-zinc-800 w-full text-gray-500 dark:text-zinc-400">
                                            @if ($person->email)
                                                <a href="mailto:{{ $person->email }}" title="Email: {{ $person->email }}" class="hover:text-primary dark:hover:text-emerald-400 transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                                </a>
                                            @endif
                                            @if ($person->whatsapp_url)
                                                <a href="{{ $person->whatsapp_url }}" target="_blank" rel="noopener noreferrer" title="WhatsApp: {{ $person->no_telepon }}" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                                </a>
                                            @endif
                                            @if ($person->link_instagram)
                                                <a href="{{ $person->link_instagram }}" target="_blank" rel="noopener noreferrer" title="Instagram" class="hover:text-pink-600 dark:hover:text-pink-400 transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5" stroke-width="2" stroke-linecap="round"/></svg>
                                                </a>
                                            @endif
                                            @if ($person->link_linkedin)
                                                <a href="{{ $person->link_linkedin }}" target="_blank" rel="noopener noreferrer" title="LinkedIn" class="hover:text-blue-600 dark:hover:text-blue-400 transition">
                                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h14m-.5 15.5v-5.3a3.26 3.26 0 00-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 011.4 1.4v4.93h2.75M6.88 8.56a1.68 1.68 0 001.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 00-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/></svg>
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </article>
                            @endforeach
                        </div>
                    @endif
                </div>
            </section>

            @if (! $loop->last)
                <hr class="border-gray-200/70 dark:border-zinc-800">
            @endif
        @empty
            <div class="bg-white dark:bg-zinc-900 rounded-3xl p-12 text-center border border-gray-200/80 dark:border-zinc-800 shadow-2xs space-y-3">
                <div class="w-16 h-16 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-primary dark:text-emerald-400 flex items-center justify-center mx-auto border border-emerald-100 dark:border-emerald-800">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-zinc-100">Belum Ada Data Penanggung Jawab</h3>
                <p class="text-xs sm:text-sm text-gray-500 dark:text-zinc-400 max-w-md mx-auto">
                    Data struktur penanggung jawab ruang laboratorium belum tersedia untuk filter yang dipilih.
                </p>
            </div>
        @endforelse
    </div>
</div>
