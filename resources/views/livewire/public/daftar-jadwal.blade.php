<div class="space-y-6 pb-12">

    {{-- Header Banner --}}
    <div class="bg-white dark:bg-zinc-900 p-6 sm:p-7 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 flex-wrap mb-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-primary/10 text-primary dark:bg-emerald-500/10 dark:text-emerald-400">
                    Portal Jadwal Terpadu UIN Siber Cirebon
                </span>
                @if ($selectedTahun)
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                        Semester: {{ $selectedTahun->nama }} {{ $selectedTahun->is_aktif ? '• Aktif' : '' }}
                    </span>
                @endif
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-zinc-100 tracking-tight">Daftar Jadwal Praktikum</h1>
            <p class="text-gray-500 dark:text-zinc-400 text-xs sm:text-sm mt-1">Pilih Program Studi atau Ruangan Praktikum untuk melihat sesi praktikum yang sedang aktif.</p>
        </div>

        @if ($hasActiveFilter && $jadwal && $jadwal->total() > 0)
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('jadwal.export.pdf', array_filter([
                        'tahun_akademik_id'  => $tahunAkademikId,
                        'fakultas_id'        => $fakultasId,
                        'program_studi_id'   => $programStudiId,
                        'ruang_praktikum_id' => $ruangPraktikumId,
                        'mata_kuliah_id'     => $mataKuliahId,
                        'dosen_id'           => $dosenId,
                        'hari'               => $hari,
                    ])) }}"
                   target="_blank"
                   class="inline-flex items-center gap-1.5 px-4 py-2.5 text-xs sm:text-sm font-semibold border border-red-200 dark:border-red-900/60 rounded-xl text-red-700 dark:text-red-400 bg-red-50/60 dark:bg-red-950/30 hover:bg-red-100 dark:hover:bg-red-900/50 transition shadow-xs">
                    <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Unduh PDF</span>
                </a>
                <a href="{{ route('jadwal.export.excel', array_filter([
                        'tahun_akademik_id'  => $tahunAkademikId,
                        'fakultas_id'        => $fakultasId,
                        'program_studi_id'   => $programStudiId,
                        'ruang_praktikum_id' => $ruangPraktikumId,
                        'mata_kuliah_id'     => $mataKuliahId,
                        'dosen_id'           => $dosenId,
                        'hari'               => $hari,
                    ])) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2.5 text-xs sm:text-sm font-semibold border border-emerald-200 dark:border-emerald-900/60 rounded-xl text-emerald-700 dark:text-emerald-400 bg-emerald-50/60 dark:bg-emerald-950/30 hover:bg-emerald-100 dark:hover:bg-emerald-900/50 transition shadow-xs">
                    <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Unduh Excel</span>
                </a>
            </div>
        @endif
    </div>

    {{-- Filter Panel Berjenjang --}}
    <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 shadow-sm rounded-2xl p-5 space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5">
            {{-- Filter Tahun Akademik --}}
            <div>
                <label class="block text-xs font-bold text-gray-600 dark:text-zinc-400 uppercase tracking-wider mb-1.5">
                    Tahun Akademik
                </label>
                <select wire:model.live="tahunAkademikId" class="w-full rounded-xl border-gray-200 dark:border-zinc-700 bg-gray-50/90 dark:bg-zinc-800 text-gray-900 dark:text-zinc-100 focus:bg-white text-xs sm:text-sm py-2.5 transition">
                    @foreach ($tahunAkademikList as $ta)
                        <option value="{{ $ta->id }}">
                            {{ $ta->nama }} {{ $ta->is_aktif ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Fakultas --}}
            <div>
                <label class="block text-xs font-bold text-gray-600 dark:text-zinc-400 uppercase tracking-wider mb-1.5">
                    Fakultas
                </label>
                <select wire:model.live="fakultasId" class="w-full rounded-xl border-gray-200 dark:border-zinc-700 bg-gray-50/90 dark:bg-zinc-800 text-gray-900 dark:text-zinc-100 focus:bg-white text-xs sm:text-sm py-2.5 transition">
                    <option value="">-- Semua Fakultas --</option>
                    @foreach ($fakultasList as $f)
                        <option value="{{ $f->id }}">{{ $f->nama_fakultas }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Program Studi --}}
            <div>
                <label class="block text-xs font-bold text-gray-600 dark:text-zinc-400 uppercase tracking-wider mb-1.5">
                    Program Studi
                </label>
                <select wire:model.live="programStudiId" class="w-full rounded-xl border-gray-200 dark:border-zinc-700 bg-gray-50/90 dark:bg-zinc-800 text-gray-900 dark:text-zinc-100 focus:bg-white text-xs sm:text-sm py-2.5 transition">
                    <option value="">-- Semua Prodi --</option>
                    @foreach ($programStudiList as $ps)
                        <option value="{{ $ps->id }}">{{ $ps->nama_program_studi }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Ruangan Praktikum --}}
            <div>
                <label class="block text-xs font-bold text-gray-600 dark:text-zinc-400 uppercase tracking-wider mb-1.5">
                    Ruang Praktikum
                </label>
                <select wire:model.live="ruangPraktikumId" class="w-full rounded-xl border-gray-200 dark:border-zinc-700 bg-gray-50/90 dark:bg-zinc-800 text-gray-900 dark:text-zinc-100 focus:bg-white text-xs sm:text-sm py-2.5 transition">
                    <option value="">-- Semua Ruang --</option>
                    @foreach ($ruangList as $r)
                        <option value="{{ $r->id }}">
                            {{ $r->nama_ruang }}{{ $r->cakupan_akses === 'khusus_multi_prodi' ? ' [Multi-Prodi]' : ($r->cakupan_akses === 'umum_universitas' ? ' [Umum Kampus]' : ($r->cakupan_akses === 'eksklusif' ? ' [Eksklusif]' : '')) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Hari (Senin - Jumat) --}}
            <div>
                <label class="block text-xs font-bold text-gray-600 dark:text-zinc-400 uppercase tracking-wider mb-1.5">
                    Hari Perkuliahan
                </label>
                <select wire:model.live="hari" class="w-full rounded-xl border-gray-200 dark:border-zinc-700 bg-gray-50/90 dark:bg-zinc-800 text-gray-900 dark:text-zinc-100 focus:bg-white text-xs sm:text-sm py-2.5 transition">
                    <option value="">-- Semua Hari --</option>
                    @foreach ($hariList as $h)
                        <option value="{{ $h }}">{{ $h }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Baris Tambahan: Pencarian Teks & Tombol Aksi --}}
        <div class="pt-3 border-t border-gray-100 dark:border-zinc-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="relative w-full sm:w-80">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama mata kuliah, kelas, atau dosen..."
                    class="w-full pl-9 pr-3 text-xs sm:text-sm rounded-xl border-gray-200 dark:border-zinc-700 bg-gray-50/90 dark:bg-zinc-800 text-gray-900 dark:text-zinc-100 focus:bg-white py-2">
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                @if ($hasActiveFilter)
                    <button wire:click="resetFilter"
                        class="px-4 py-2 bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-zinc-300 hover:bg-gray-200 dark:hover:bg-zinc-700 rounded-xl font-medium text-xs sm:text-sm transition flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span>Reset Filter</span>
                    </button>
                @endif

                <button wire:click="lihatSemuaJadwal"
                    class="px-4 py-2 bg-primary/10 text-primary dark:bg-emerald-500/10 dark:text-emerald-400 hover:bg-primary/20 rounded-xl font-semibold text-xs sm:text-sm transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    <span>Tampilkan Seluruh Jadwal</span>
                </button>

                <div wire:loading class="text-xs text-primary dark:text-emerald-400 font-medium flex items-center gap-1.5 ml-2">
                    <svg class="animate-spin w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span>Menyaring...</span>
                </div>
            </div>
        </div>
    </div>

    {{-- KONTEN UTAMA: INITIAL STATE vs DAFTAR JADWAL --}}
    @if (! $hasActiveFilter)
        {{-- Empty / Initial State yang Elegan Sebelum Memilih Filter --}}
        <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-2xl p-8 sm:p-12 text-center shadow-xs">
            <div class="w-16 h-16 rounded-2xl bg-primary/10 text-primary dark:bg-emerald-500/15 dark:text-emerald-400 mx-auto flex items-center justify-center mb-4 shadow-xs">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-zinc-100">Silakan Tentukan Filter untuk Menampilkan Jadwal</h2>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-zinc-400 max-w-lg mx-auto mt-2 leading-relaxed">
                Pilih <strong>Fakultas</strong>, <strong>Program Studi</strong>, atau <strong>Ruang Praktikum</strong> pada panel di atas untuk menampilkan jadwal yang relevan, atau gunakan pintasan cepat di bawah ini.
            </p>

            {{-- Pintasan Cepat Populer --}}
            <div class="mt-8 max-w-3xl mx-auto">
                <p class="text-[11px] font-bold text-gray-400 dark:text-zinc-500 uppercase tracking-wider mb-3">Pintasan Cepat Ruangan Laboratorium:</p>
                <div class="flex flex-wrap items-center justify-center gap-2">
                    @foreach ($ruangList->take(6) as $r)
                        <button wire:click="pilihRuang({{ $r->id }})"
                            class="px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 text-gray-700 dark:text-zinc-300 hover:border-primary hover:text-primary dark:hover:border-emerald-400 dark:hover:text-emerald-300 transition">
                            {{ $r->nama_ruang }}
                        </button>
                    @endforeach
                </div>

                <div class="mt-5 pt-5 border-t border-gray-100 dark:border-zinc-800 flex justify-center">
                    <button wire:click="lihatSemuaJadwal"
                        class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold bg-primary text-white hover:bg-primary-dark transition shadow-sm inline-flex items-center gap-2">
                        <span>Tampilkan Seluruh Jadwal Praktikum Kampus</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </div>
        </div>
    @else
        {{-- Jadwal Praktikum Tampil Sesuai Filter --}}
        <div class="space-y-4">
            {{-- Info Bar Hasil Pencarian --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 px-1 text-xs text-gray-500 dark:text-zinc-400">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="font-bold text-gray-900 dark:text-zinc-100">Hasil Penjadwalan:</span>
                    @if ($selectedRuang)
                        <span class="px-2.5 py-0.5 rounded-full bg-primary/10 text-primary dark:bg-emerald-500/10 dark:text-emerald-300 font-semibold">
                            Ruang: {{ $selectedRuang->nama_ruang }}
                        </span>
                    @endif
                    @if ($selectedProdi)
                        <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300 font-semibold border border-blue-200 dark:border-blue-800">
                            Prodi: {{ $selectedProdi->nama_program_studi }}
                        </span>
                    @endif
                    @if ($hari)
                        <span class="px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 font-semibold border border-amber-200 dark:border-amber-800">
                            Hari: {{ $hari }}
                        </span>
                    @endif
                    <span>(Ditemukan {{ $jadwal->total() }} sesi aktif)</span>
                </div>
                <span>Halaman {{ $jadwal->currentPage() }} dari {{ $jadwal->lastPage() }}</span>
            </div>

            {{-- Desktop Table --}}
            <div class="hidden md:block bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 shadow-sm rounded-2xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-primary text-white text-left">
                        <tr>
                            <th class="px-5 py-3.5 font-semibold">Mata Kuliah</th>
                            <th class="px-4 py-3.5 font-semibold">Kelas & Prodi</th>
                            <th class="px-4 py-3.5 font-semibold">Hari & Jam</th>
                            <th class="px-5 py-3.5 font-semibold">Ruang Praktikum</th>
                            <th class="px-5 py-3.5 font-semibold">Dosen Pengampu</th>
                            <th class="px-4 py-3.5 font-semibold text-right">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-zinc-800">
                        @forelse ($jadwal as $j)
                            <tr class="hover:bg-primary/5 dark:hover:bg-zinc-800/40 transition">
                                <td class="px-5 py-3.5">
                                    <span class="font-bold text-gray-900 dark:text-zinc-100 block">{{ $j->mataKuliah->nama_mk }}</span>
                                    <span class="text-xs text-gray-400 font-mono">{{ $j->mataKuliah->kode_mk }} • {{ $j->mataKuliah->sks }} SKS</span>
                                </td>
                                <td class="px-4 py-3.5">
                                    <span class="font-semibold text-gray-800 dark:text-zinc-200 block text-xs">{{ $j->kelas->nama_kelas }}</span>
                                    <span class="text-[11px] text-gray-400 block truncate max-w-[140px]">{{ $j->mataKuliah->programStudi->nama_program_studi ?? '-' }}</span>
                                </td>
                                <td class="px-4 py-3.5">
                                    <span class="font-bold text-gray-900 dark:text-zinc-100 block text-xs">{{ $j->hari }}</span>
                                    <span class="text-xs text-primary dark:text-emerald-400 font-semibold">{{ \Illuminate\Support\Str::limit($j->jam_mulai, 5, '') }} – {{ \Illuminate\Support\Str::limit($j->jam_selesai, 5, '') }}</span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex flex-col">
                                        <span class="font-semibold text-gray-900 dark:text-zinc-100 text-xs">{{ $j->ruangPraktikum->nama_ruang ?? '-' }}</span>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            @if ($j->ruangPraktikum?->cakupan_akses === 'khusus_multi_prodi')
                                                <span class="px-1.5 py-0.2 rounded text-[10px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300">Multi-Prodi</span>
                                            @elseif ($j->ruangPraktikum?->cakupan_akses === 'umum_universitas')
                                                <span class="px-1.5 py-0.2 rounded text-[10px] font-bold bg-purple-100 text-purple-800 dark:bg-purple-950/60 dark:text-purple-300">Umum</span>
                                            @endif
                                            <span class="text-[11px] text-gray-400">{{ $j->ruangPraktikum->lokasi ?? 'Kampus' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="font-medium text-gray-800 dark:text-zinc-200 block text-xs">{{ $j->dosen->nama }}</span>
                                    <span class="text-[11px] text-gray-400 font-mono">{{ $j->dosen->nidn ?? 'NIDN -' }}</span>
                                </td>
                                <td class="px-4 py-3.5 text-right">
                                    <a href="{{ route('jadwal.detail', $j->id) }}"
                                       class="inline-flex items-center gap-1 text-xs font-semibold text-primary dark:text-emerald-400 hover:underline">
                                        <span>Rincian</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-400 dark:text-zinc-500 text-sm">
                                    Tidak ada jadwal praktikum yang cocok dengan filter yang dipilih.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile Cards --}}
            <div class="md:hidden grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                @forelse ($jadwal as $j)
                    <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-2xl p-4 shadow-xs space-y-3">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <h3 class="font-bold text-sm text-gray-900 dark:text-zinc-100 leading-snug">{{ $j->mataKuliah->nama_mk }}</h3>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $j->mataKuliah->kode_mk }} • Kelas {{ $j->kelas->nama_kelas }}</p>
                            </div>
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-primary/10 text-primary dark:bg-emerald-500/10 dark:text-emerald-400 shrink-0">
                                {{ $j->hari }}
                            </span>
                        </div>

                        <div class="p-3 bg-gray-50 dark:bg-zinc-800/60 rounded-xl space-y-1.5 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Waktu:</span>
                                <span class="font-bold text-gray-800 dark:text-zinc-200">{{ \Illuminate\Support\Str::limit($j->jam_mulai, 5, '') }} – {{ \Illuminate\Support\Str::limit($j->jam_selesai, 5, '') }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Ruangan:</span>
                                <span class="font-semibold text-primary dark:text-emerald-400">{{ $j->ruangPraktikum->nama_ruang }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Dosen:</span>
                                <span class="font-medium text-gray-800 dark:text-zinc-200 truncate max-w-[150px]">{{ $j->dosen->nama }}</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-end pt-1">
                            <a href="{{ route('jadwal.detail', $j->id) }}" class="text-xs font-semibold text-primary dark:text-emerald-400 hover:underline inline-flex items-center gap-1">
                                <span>Lihat Informasi Lengkap</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="sm:col-span-2 bg-white dark:bg-zinc-900 rounded-2xl p-8 text-center text-gray-400 dark:text-zinc-500 text-sm border border-gray-100 dark:border-zinc-800">
                        Tidak ada jadwal praktikum yang cocok dengan filter yang dipilih.
                    </div>
                @endforelse
            </div>

            @if ($jadwal->hasPages())
                <div class="pt-2">
                    {{ $jadwal->links() }}
                </div>
            @endif
        </div>
    @endif

</div>
