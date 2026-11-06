<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-zinc-100 tracking-tight">Jadwal Mengajar Saya</h1>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-zinc-400 mt-0.5">
                Daftar agenda perkuliahan praktikum dan laboratorium yang Anda ampu pada semester aktif.
            </p>
        </div>

        {{-- Filter Tahun Akademik --}}
        <div class="flex items-center gap-2">
            <label class="text-xs font-semibold text-gray-500 dark:text-zinc-400 shrink-0">Periode:</label>
            <select wire:model.live="selectedTahunId" class="py-2 px-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-gray-900 dark:text-zinc-100 text-xs shadow-2xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition">
                <option value="">Semua Periode</option>
                @foreach ($tahunList as $t)
                    <option value="{{ $t->id }}">{{ $t->nama }} ({{ $t->semester }}) {{ $t->is_aktif ? '— Aktif' : '' }}</option>
                @endforeach
            </select>
        </div>
    </div>

    @if (! $dosen)
        <div class="bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/60 rounded-2xl p-5 text-amber-900 dark:text-amber-200 flex items-start gap-3 shadow-2xs">
            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <div class="text-xs sm:text-sm space-y-1">
                <strong class="font-bold">Profil Dosen Belum Terhubung</strong>
                <p class="text-amber-800 dark:text-amber-300">Akun login Anda belum terhubung dengan data Dosen resmi di sistem. Silakan hubungi Super Admin / Operator untuk menautkan akun Anda.</p>
            </div>
        </div>
    @else
        {{-- Profile Mini-Summary --}}
        <div class="bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 rounded-3xl p-5 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40 flex items-center justify-center font-bold text-base">
                    👨‍🏫
                </div>
                <div>
                    <h2 class="font-bold text-base text-gray-900 dark:text-zinc-100">{{ $dosen->nama }}</h2>
                    <p class="text-xs text-gray-500 dark:text-zinc-400 font-mono mt-0.5">NIDN: {{ $dosen->nidn ?? '-' }} • Email: {{ $dosen->email ?? '-' }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 text-xs">
                <div class="px-4 py-2.5 rounded-2xl bg-gray-50 dark:bg-zinc-800/60 border border-gray-200/80 dark:border-zinc-700 text-center">
                    <span class="text-gray-400 dark:text-zinc-500 block text-[10px] uppercase font-bold tracking-wider">Total Sesi</span>
                    <strong class="text-gray-900 dark:text-zinc-100 text-sm font-black">{{ $jadwalList->count() }}</strong>
                </div>
                <div class="px-4 py-2.5 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200/60 dark:border-emerald-800/40 text-center">
                    <span class="text-emerald-700 dark:text-emerald-400 block text-[10px] uppercase font-bold tracking-wider">Total Beban</span>
                    <strong class="text-emerald-800 dark:text-emerald-300 text-sm font-black">{{ $jadwalList->sum(fn($j) => $j->mataKuliah?->sks ?? 0) }} SKS</strong>
                </div>
            </div>
        </div>

        {{-- Schedule Cards / Grid --}}
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-900 dark:text-zinc-100 flex items-center gap-2">
                    <span>Sesi Praktikum Mingguan</span>
                </h3>
                <a href="{{ route('pengajuan.saya') }}" class="text-xs font-bold text-emerald-700 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 transition flex items-center gap-1">
                    <span>Riwayat Pengajuan</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            @if ($jadwalList->isEmpty())
                <div class="bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 rounded-3xl p-10 text-center space-y-2">
                    <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-zinc-800 text-gray-400 flex items-center justify-center mx-auto text-xl">📅</div>
                    <p class="text-sm font-bold text-gray-700 dark:text-zinc-200">Tidak Ada Jadwal Mengajar</p>
                    <p class="text-xs text-gray-400 dark:text-zinc-500 max-w-sm mx-auto">Belum ada kelas praktikum yang dijadwalkan untuk Anda pada periode ini. Hubungi operator fakultas/prodi untuk konfirmasi plotting.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($jadwalList as $j)
                        <div class="bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 rounded-3xl p-5 shadow-2xs hover:border-emerald-500/50 hover:shadow-md transition flex flex-col justify-between space-y-4">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="px-2.5 py-1 rounded-xl text-xs font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40">
                                        {{ $j->hari }}, {{ substr($j->jam_mulai, 0, 5) }}–{{ substr($j->jam_selesai, 0, 5) }}
                                    </span>
                                    <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300 font-mono border border-gray-200 dark:border-zinc-700">
                                        {{ $j->kelas?->nama_kelas }}
                                    </span>
                                </div>

                                <div>
                                    <h4 class="font-bold text-sm text-gray-900 dark:text-zinc-100 leading-snug">{{ $j->mataKuliah?->nama_mk }}</h4>
                                    <p class="text-xs text-gray-400 dark:text-zinc-500 font-mono mt-0.5">{{ $j->mataKuliah?->kode_mk }} • {{ $j->mataKuliah?->sks }} SKS</p>
                                </div>

                                <div class="text-xs text-gray-600 dark:text-zinc-300 bg-gray-50/80 dark:bg-zinc-800/60 rounded-2xl p-3 space-y-1 border border-gray-200/60 dark:border-zinc-700/60">
                                    <div class="flex items-center gap-1.5 font-bold text-gray-800 dark:text-zinc-200">
                                        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        <span>{{ $j->ruangPraktikum?->nama_ruang }}</span>
                                    </div>

                                    <p class="text-[11px] text-gray-500 dark:text-zinc-400 pl-5.5">
                                        Lokasi: {{ $j->ruangPraktikum?->lokasi ?? '-' }} • Kapasitas: {{ $j->ruangPraktikum?->kapasitas }} org
                                    </p>
                                </div>
                            </div>

                            <div class="pt-3 border-t border-gray-100 dark:border-zinc-800 flex items-center justify-between gap-2">
                                <span class="text-[11px] text-gray-400 dark:text-zinc-500">Semester {{ $j->tahunAkademik?->nama }}</span>
                                <a href="{{ route('pengajuan.saya') }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-50 dark:bg-emerald-950/40 hover:bg-emerald-100 dark:hover:bg-emerald-900/60 text-emerald-800 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/50 transition">
                                    <span>⚡ Pindah Jadwal</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif
</div>
