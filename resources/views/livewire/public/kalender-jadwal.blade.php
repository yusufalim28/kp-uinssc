<div class="space-y-6 pb-12">

    {{-- Header Banner Kalender --}}
    <div class="bg-white dark:bg-zinc-900 p-6 sm:p-7 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 flex-wrap mb-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-primary/10 text-primary dark:bg-emerald-500/10 dark:text-emerald-400">
                    Kalender Mingguan (Senin – Jum'at)
                </span>
                @if ($selectedTahun)
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                        Semester: {{ $selectedTahun->nama }} {{ $selectedTahun->is_aktif ? '• Aktif' : '' }}
                    </span>
                @endif
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-zinc-100 tracking-tight">Kalender Praktikum</h1>
            <p class="text-gray-500 dark:text-zinc-400 text-xs sm:text-sm mt-1">
                Visualisasi jadwal per hari kerja. Pilih ruangan lab atau program studi di bawah untuk menampilkan kalender yang tertata rapi.
            </p>
        </div>

        @if ($hasActiveFilter)
            <div class="flex items-center gap-2 text-xs shrink-0">
                <span class="px-3.5 py-2 rounded-xl bg-primary text-white font-bold shadow-xs">
                    {{ $totalJadwal }} Sesi Mingguan
                </span>
            </div>
        @endif
    </div>

    {{-- Filter Bar Khusus Kalender --}}
    <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 shadow-sm rounded-2xl p-4 sm:p-5 space-y-3.5">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-end">
            {{-- Global Filter: Tahun Akademik --}}
            <div>
                <label class="block text-[11px] font-bold text-gray-500 dark:text-zinc-400 uppercase tracking-wider mb-1.5">
                    Tahun Akademik
                </label>
                <select wire:model.live="tahunAkademikId" class="w-full rounded-xl border-gray-200 dark:border-zinc-700 bg-gray-50/90 dark:bg-zinc-800 text-gray-900 dark:text-zinc-100 text-xs sm:text-sm py-2.5 transition">
                    @foreach ($tahunAkademikList as $ta)
                        <option value="{{ $ta->id }}">
                            {{ $ta->nama }} {{ $ta->is_aktif ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Fakultas --}}
            <div>
                <label class="block text-[11px] font-bold text-gray-500 dark:text-zinc-400 uppercase tracking-wider mb-1.5">
                    Fakultas
                </label>
                <select wire:model.live="fakultasId" class="w-full rounded-xl border-gray-200 dark:border-zinc-700 bg-gray-50/90 dark:bg-zinc-800 text-gray-900 dark:text-zinc-100 text-xs sm:text-sm py-2.5 transition">
                    <option value="">-- Semua Fakultas --</option>
                    @foreach ($fakultasList as $f)
                        <option value="{{ $f->id }}">{{ $f->nama_fakultas }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Program Studi --}}
            <div>
                <label class="block text-[11px] font-bold text-gray-500 dark:text-zinc-400 uppercase tracking-wider mb-1.5">
                    Program Studi
                </label>
                <select wire:model.live="programStudiId" class="w-full rounded-xl border-gray-200 dark:border-zinc-700 bg-gray-50/90 dark:bg-zinc-800 text-gray-900 dark:text-zinc-100 text-xs sm:text-sm py-2.5 transition">
                    <option value="">-- Semua Program Studi --</option>
                    @foreach ($programStudiList as $ps)
                        <option value="{{ $ps->id }}">{{ $ps->nama_program_studi }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Ruang Praktikum --}}
            <div>
                <label class="block text-[11px] font-bold text-primary dark:text-emerald-400 uppercase tracking-wider mb-1.5">
                    Ruang Praktikum (Dianjurkan)
                </label>
                <select wire:model.live="ruangPraktikumId" class="w-full rounded-xl border-primary/40 dark:border-emerald-600 bg-primary/5 dark:bg-emerald-950/20 text-gray-900 dark:text-zinc-100 text-xs sm:text-sm py-2.5 font-medium transition focus:ring-primary/20">
                    <option value="">-- Pilih Ruang Praktikum --</option>
                    @foreach ($ruangList as $r)
                        <option value="{{ $r->id }}">
                            {{ $r->nama_ruang }}{{ $r->cakupan_akses === 'khusus_multi_prodi' ? ' [Multi-Prodi]' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Baris Status Filter & Tombol Aksi --}}
        <div class="pt-2 border-t border-gray-100 dark:border-zinc-800 flex items-center justify-between flex-wrap gap-2 text-xs">
            <div class="flex items-center gap-2 flex-wrap">
                @if ($selectedRuang)
                    <span class="px-2.5 py-0.5 rounded-full bg-primary text-white font-bold text-[11px]">
                        Ruang: {{ $selectedRuang->nama_ruang }}
                    </span>
                @endif
                @if ($selectedProdi)
                    <span class="px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300 font-bold text-[11px]">
                        Prodi: {{ $selectedProdi->nama_program_studi }}
                    </span>
                @endif
            </div>

            <div class="flex items-center gap-2">
                @if ($hasActiveFilter)
                    <button wire:click="resetFilter" class="text-red-500 hover:text-red-700 text-xs font-semibold flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span>Reset Filter</span>
                    </button>
                @endif
                <button wire:click="lihatSemuaKalender"
                    class="px-3.5 py-1.5 bg-gray-100 dark:bg-zinc-800 hover:bg-gray-200 dark:hover:bg-zinc-700 text-gray-700 dark:text-zinc-300 rounded-xl font-semibold text-xs transition">
                    Tampilkan Seluruh Kalender
                </button>
            </div>
        </div>
    </div>

    {{-- KONTEN: INITIAL STATE vs GRID MINGGUAN --}}
    @if (! $hasActiveFilter)
        {{-- State Awal: Panduan Filter Sebelum Kalender Tampil --}}
        <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-2xl p-8 sm:p-12 text-center shadow-xs">
            <div class="w-16 h-16 rounded-2xl bg-primary/10 text-primary dark:bg-emerald-500/15 dark:text-emerald-400 mx-auto flex items-center justify-center mb-4 shadow-xs">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-zinc-100">Silakan Tentukan Ruangan atau Program Studi</h2>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-zinc-400 max-w-lg mx-auto mt-2 leading-relaxed">
                Pilih <strong>Ruang Praktikum</strong> pada filter di atas untuk melihat kalender penggunaan lab tersebut tanpa tumpukan jadwal lain, atau klik salah satu pintasan ruangan di bawah ini.
            </p>

            {{-- Pintasan Cepat Ruangan Populer --}}
            <div class="mt-8 max-w-3xl mx-auto">
                <p class="text-[11px] font-bold text-gray-400 dark:text-zinc-500 uppercase tracking-wider mb-3">Pintasan Cepat Kalender Laboratorium:</p>
                <div class="flex flex-wrap items-center justify-center gap-2">
                    @foreach ($ruangList->take(6) as $r)
                        <button wire:click="setRuang({{ $r->id }})"
                            class="px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 text-gray-700 dark:text-zinc-300 hover:border-primary hover:text-primary dark:hover:border-emerald-400 dark:hover:text-emerald-300 transition">
                            {{ $r->nama_ruang }}
                        </button>
                    @endforeach
                </div>

                <div class="mt-5 pt-5 border-t border-gray-100 dark:border-zinc-800 flex justify-center">
                    <button wire:click="lihatSemuaKalender"
                        class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold bg-primary text-white hover:bg-primary-dark transition shadow-sm inline-flex items-center gap-2">
                        <span>Tampilkan Seluruh Kalender Praktikum</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </div>
        </div>
    @else
        {{-- Grid Mingguan 5 Kolom: Senin s/d Jum'at --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-start">
            @foreach ($hariList as $hari)
                @php
                    $jadwalHari = $jadwalPerHari->get($hari, collect());
                @endphp
                <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-2xl shadow-xs overflow-hidden flex flex-col">
                    {{-- Header Hari --}}
                    <div class="bg-primary text-white text-xs font-extrabold uppercase tracking-wider px-4 py-3 flex items-center justify-between shadow-xs">
                        <span>{{ $hari }}</span>
                        <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-white/20 text-white">
                            {{ $jadwalHari->count() }}
                        </span>
                    </div>

                    {{-- Sesi Kartu Hari Ini --}}
                    <div class="p-3 space-y-2.5 min-h-[220px] bg-gray-50/40 dark:bg-zinc-950/40 flex-1">
                        @forelse ($jadwalHari as $j)
                            <div class="bg-white dark:bg-zinc-800/95 border border-gray-200/80 dark:border-zinc-700/80 rounded-xl p-3 shadow-xs hover:border-primary dark:hover:border-emerald-500 hover:shadow-md transition relative overflow-hidden group">
                                {{-- Accent stripe --}}
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary/40 dark:bg-emerald-500/50 group-hover:bg-primary dark:group-hover:bg-emerald-400 transition"></div>

                                {{-- Time Badge --}}
                                <div class="flex items-center justify-between gap-1.5 mb-1.5">
                                    <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-primary/10 text-primary dark:bg-emerald-500/15 dark:text-emerald-300">
                                        {{ \Illuminate\Support\Str::limit($j->jam_mulai, 5, '') }}–{{ \Illuminate\Support\Str::limit($j->jam_selesai, 5, '') }}
                                    </span>
                                    @if($j->kelas_id && $j->kelas && $j->kelas->nama_kelas !== '-')
                                        <span class="text-[10px] font-semibold text-gray-500 dark:text-zinc-400 bg-gray-100 dark:bg-zinc-700 px-1.5 py-0.5 rounded">
                                            {{ $j->kelas->nama_kelas }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Mata Kuliah --}}
                                <a href="{{ route('jadwal.detail', $j->id) }}" class="font-bold text-gray-900 dark:text-zinc-100 hover:text-primary dark:hover:text-emerald-400 text-xs block line-clamp-2 leading-snug">
                                    {{ $j->mataKuliah->nama_mk }}
                                </a>

                                {{-- Dosen & Ruangan --}}
                                <div class="mt-2 pt-2 border-t border-gray-100 dark:border-zinc-700/60 space-y-1 text-[11px]">
                                    {{-- Ruang Lab Badge --}}
                                    <div class="flex items-center gap-1 text-gray-600 dark:text-zinc-300">
                                        <svg class="w-3 h-3 text-primary dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        <span class="font-semibold text-gray-900 dark:text-zinc-200 truncate">{{ $j->ruangPraktikum->nama_ruang }}</span>
                                    </div>

                                    {{-- Dosen Pengampu --}}
                                    <div class="flex items-center gap-1 text-gray-500 dark:text-zinc-400">
                                        <svg class="w-3 h-3 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        <span class="truncate">{{ $j->dosen->nama }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="h-full flex flex-col items-center justify-center py-10 text-center opacity-60">
                                <svg class="w-7 h-7 text-gray-300 dark:text-zinc-600 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <p class="text-[11px] text-gray-400 dark:text-zinc-500 font-medium">Tidak ada sesi praktikum</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
