<div class="space-y-6">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-zinc-100 tracking-tight">Kelola Jadwal Praktikum</h1>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-zinc-400 mt-0.5">
                @if(auth()->user()->hasRole('operator') && auth()->user()->programStudi)
                    Akses Operator Prodi: <strong class="text-emerald-700 dark:text-emerald-400">{{ auth()->user()->programStudi->nama_program_studi }}</strong>
                @elseif(auth()->user()->hasRole('operator') && auth()->user()->fakultas)
                    Akses Operator Fakultas: <strong class="text-emerald-700 dark:text-emerald-400">{{ auth()->user()->fakultas->nama_fakultas }}</strong>
                @else
                    Akses Penuh Administrator Kampus
                @endif
                — Setiap plotting jadwal otomatis divalidasi bentrok ruang lab & dosen.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('jadwal.template.download') }}" class="inline-flex items-center gap-1.5 px-3 py-2 border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 hover:bg-gray-50 dark:hover:bg-zinc-800 text-gray-700 dark:text-zinc-300 text-xs font-semibold rounded-xl transition shadow-2xs">
                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Unduh Format Excel</span>
            </a>
            <button wire:click="openImportModal" class="inline-flex items-center gap-1.5 px-3 py-2 border border-emerald-200/80 dark:border-emerald-800/60 bg-emerald-50 dark:bg-emerald-950/40 hover:bg-emerald-100 text-emerald-800 dark:text-emerald-300 text-xs font-bold rounded-xl transition shadow-2xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                <span>Import Excel</span>
            </button>
            <button wire:click="openCreate" class="inline-flex items-center gap-1.5 bg-[#0F352A] hover:bg-[#164336] dark:bg-emerald-600 dark:hover:bg-emerald-500 text-white text-xs font-bold px-3.5 py-2 rounded-xl transition shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Jadwal</span>
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 text-emerald-800 dark:text-emerald-300 text-xs rounded-xl px-4 py-3 shadow-2xs">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60 text-rose-800 dark:text-rose-300 text-xs rounded-xl px-4 py-3 shadow-2xs">{{ session('error') }}</div>
    @endif

    {{-- Search & Filter Controls --}}
    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center justify-between">
        <div class="relative flex-1 max-w-md">
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </span>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari mata kuliah, dosen, kelas, atau ruang..."
                   class="w-full pl-10 pr-4 py-2 rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-2xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs text-gray-900 dark:text-zinc-100 placeholder:text-gray-400 transition">
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <select wire:model.live="filterTahunAkademik" class="rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-xs px-3 py-2 text-gray-900 dark:text-zinc-100 shadow-2xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                <option value="">Semua Semester</option>
                @foreach ($tahunList as $t)
                    <option value="{{ $t->id }}">{{ $t->nama }} {{ $t->is_aktif ? '(Aktif)' : '' }}</option>
                @endforeach
            </select>

            <select wire:model.live="filterHari" class="rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-xs px-3 py-2 text-gray-900 dark:text-zinc-100 shadow-2xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                <option value="">Semua Hari</option>
                @foreach ($hariList as $h)
                    <option value="{{ $h }}">{{ $h }}</option>
                @endforeach
            </select>

            <select wire:model.live="filterRuang" class="rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-xs px-3 py-2 text-gray-900 dark:text-zinc-100 shadow-2xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                <option value="">Semua Ruangan</option>
                @foreach ($ruangList as $r)
                    <option value="{{ $r->id }}">{{ $r->nama_ruang }}</option>
                @endforeach
            </select>

            @if($search || $filterHari || $filterRuang)
                <button wire:click="resetFilters" class="text-xs text-rose-600 hover:text-rose-700 font-bold px-2.5 py-1.5 hover:bg-rose-50 dark:hover:bg-rose-950/30 rounded-xl transition">
                    Reset Filter
                </button>
            @endif

            {{-- View Mode Toggle --}}
            <div class="flex items-center gap-1.5 self-end sm:self-auto bg-gray-100 dark:bg-zinc-800 p-1 rounded-xl">
                <button wire:click="$set('viewMode', 'grid')" title="Tampilan Kartu (Grid)"
                        @class([
                            'p-1.5 rounded-lg transition',
                            'bg-white dark:bg-zinc-900 text-emerald-700 dark:text-emerald-400 shadow-2xs' => $viewMode === 'grid',
                            'text-gray-500 dark:text-zinc-400 hover:text-gray-700 dark:hover:text-zinc-200' => $viewMode !== 'grid',
                        ])>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                </button>
                <button wire:click="$set('viewMode', 'table')" title="Tampilan Tabel"
                        @class([
                            'p-1.5 rounded-lg transition',
                            'bg-white dark:bg-zinc-900 text-emerald-700 dark:text-emerald-400 shadow-2xs' => $viewMode === 'table',
                            'text-gray-500 dark:text-zinc-400 hover:text-gray-700 dark:hover:text-zinc-200' => $viewMode !== 'table',
                        ])>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>
    </div>

    @if ($viewMode === 'grid')
        {{-- Modern Grid Cards Layout --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4.5">
            @forelse ($jadwal as $j)
                <div class="group bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 hover:border-emerald-500/40 dark:hover:border-emerald-500/40 rounded-2xl p-5 shadow-2xs hover:shadow-md transition duration-200 flex flex-col justify-between">
                    <div>
                        {{-- Top Badge Row --}}
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-bold rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40">
                                <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $j->hari }}, {{ \Illuminate\Support\Str::limit($j->jam_mulai, 5, '') }}–{{ \Illuminate\Support\Str::limit($j->jam_selesai, 5, '') }}
                            </span>
                            @if($j->status === 'aktif')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Aktif
                                </span>
                            @elseif($j->status === 'reschedule')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200/60 dark:border-amber-800/40">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Reschedule
                                </span>
                            @elseif($j->status === 'dibatalkan')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-200/60 dark:border-rose-800/40">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    Dibatalkan
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium bg-gray-100 dark:bg-zinc-800 text-gray-500">
                                    Nonaktif
                                </span>
                            @endif
                        </div>

                        {{-- Mata Kuliah Title --}}
                        <div class="mb-3">
                            <h3 class="font-bold text-sm sm:text-base text-gray-900 dark:text-zinc-100 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition leading-snug">
                                {{ $j->mataKuliah->nama_mk }}
                            </h3>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="font-mono text-[11px] text-gray-500 dark:text-zinc-400">{{ $j->mataKuliah->kode_mk }}</span>
                                @if($j->mataKuliah->programStudi)
                                    <span class="text-gray-300 dark:text-zinc-600">•</span>
                                    <span class="text-[11px] text-gray-500 dark:text-zinc-400 truncate">{{ $j->mataKuliah->programStudi->nama_prodi }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- Details List --}}
                        <div class="space-y-2 pt-2.5 border-t border-gray-100 dark:border-zinc-800/80 text-xs">
                            {{-- Ruang Lab --}}
                            <div class="flex items-center gap-2 text-gray-700 dark:text-zinc-300">
                                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                <span class="font-medium truncate">{{ $j->ruangPraktikum->nama_ruang }}</span>
                            </div>

                            {{-- Kelas --}}
                            <div class="flex items-center gap-2 text-gray-600 dark:text-zinc-300">
                                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                @if($j->kelas_id && $j->kelas && $j->kelas->nama_kelas !== '-')
                                    <span class="px-2 py-0.5 rounded font-mono font-bold text-emerald-800 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200/50 dark:border-emerald-800/40 text-[11px]">
                                        Kelas {{ $j->kelas->nama_kelas }}
                                    </span>
                                @else
                                    <span class="text-gray-400 dark:text-zinc-500 italic text-[11px]">Non-Kelas</span>
                                @endif
                            </div>

                            {{-- Dosen Pengampu --}}
                            <div class="flex items-center gap-2 text-gray-600 dark:text-zinc-300">
                                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span class="truncate font-medium">{{ $j->dosen->nama }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Bottom Action Buttons --}}
                    <div class="flex items-center justify-end gap-2 pt-3.5 mt-4 border-t border-gray-100 dark:border-zinc-800">
                        <button wire:click="openEdit({{ $j->id }})" class="px-3 py-1.5 text-xs font-semibold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 rounded-xl transition">
                            Edit
                        </button>
                        <button wire:click="confirmDelete({{ $j->id }})" class="px-3 py-1.5 text-xs font-semibold text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 dark:hover:bg-rose-900/40 rounded-xl transition">
                            Hapus
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white dark:bg-zinc-900 rounded-2xl p-12 text-center border border-gray-200/80 dark:border-zinc-800">
                    <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-zinc-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p class="text-sm font-semibold text-gray-700 dark:text-zinc-300">Belum ada jadwal praktikum</p>
                    <p class="text-xs text-gray-400 dark:text-zinc-500 mt-1">Gunakan tombol "Tambah Jadwal" atau sesuaikan filter pencarian.</p>
                </div>
            @endforelse
        </div>
    @else
        {{-- Desktop Table --}}
        <div class="hidden md:block bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 rounded-2xl overflow-hidden shadow-2xs">
            <table class="w-full text-xs text-left">
                <thead class="bg-gray-50/80 dark:bg-zinc-800/60 border-b border-gray-200/80 dark:border-zinc-700/80 text-[11px] font-bold text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">Mata Kuliah</th>
                        <th class="px-5 py-3.5 w-24">Kelas</th>
                        <th class="px-5 py-3.5">Dosen Pengampu</th>
                        <th class="px-5 py-3.5 w-44">Hari / Sesi Jam</th>
                        <th class="px-5 py-3.5">Ruang Lab</th>
                        <th class="px-5 py-3.5 w-32">Status</th>
                        <th class="px-5 py-3.5 text-right w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-zinc-800 text-gray-800 dark:text-zinc-200">
                    @forelse ($jadwal as $j)
                        <tr class="hover:bg-emerald-50/30 dark:hover:bg-zinc-800/40 transition">
                            <td class="px-5 py-3.5 font-bold text-gray-900 dark:text-zinc-100">
                                {{ $j->mataKuliah->nama_mk }}
                            </td>
                            <td class="px-5 py-3.5">
                                @if($j->kelas_id && $j->kelas && $j->kelas->nama_kelas !== '-')
                                    <span class="px-2 py-0.5 rounded font-mono font-bold text-emerald-800 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200/50 dark:border-emerald-800/40 text-[11px]">
                                        {{ $j->kelas->nama_kelas }}
                                    </span>
                                @else
                                    <span class="text-gray-400 dark:text-zinc-500 text-[11px] italic">Non-Kelas</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-gray-600 dark:text-zinc-300 font-medium">{{ $j->dosen->nama }}</td>
                            <td class="px-5 py-3.5 text-gray-600 dark:text-zinc-300">
                                <span class="font-bold text-gray-900 dark:text-zinc-100">{{ $j->hari }}</span>, 
                                <span class="font-mono text-[11px]">{{ \Illuminate\Support\Str::limit($j->jam_mulai, 5, '') }}–{{ \Illuminate\Support\Str::limit($j->jam_selesai, 5, '') }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-gray-600 dark:text-zinc-300 font-medium">{{ $j->ruangPraktikum->nama_ruang }}</td>
                            <td class="px-5 py-3.5">
                                @if($j->status === 'aktif')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Aktif
                                    </span>
                                @elseif($j->status === 'reschedule')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200/60 dark:border-amber-800/40">
                                        Reschedule
                                    </span>
                                @elseif($j->status === 'dibatalkan')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-200/60 dark:border-rose-800/40">
                                        Dibatalkan
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-gray-100 dark:bg-zinc-800 text-gray-500">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-right space-x-2">
                                <button wire:click="openEdit({{ $j->id }})" class="font-bold text-xs text-emerald-700 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 transition">Edit</button>
                                <button wire:click="confirmDelete({{ $j->id }})" class="font-bold text-xs text-rose-600 dark:text-rose-400 hover:text-rose-700 dark:hover:text-rose-300 transition">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-8 text-center text-gray-400 dark:text-zinc-500">Belum ada jadwal praktikum.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards Fallback when table view is chosen on small screens --}}
        <div class="md:hidden grid grid-cols-1 sm:grid-cols-2 gap-3.5">
            @forelse ($jadwal as $j)
                <div class="bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 rounded-2xl p-4 shadow-2xs flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40">
                                {{ $j->hari }}, {{ \Illuminate\Support\Str::limit($j->jam_mulai, 5, '') }}–{{ \Illuminate\Support\Str::limit($j->jam_selesai, 5, '') }}
                            </span>
                            <span @class([
                                'px-2 py-0.5 rounded-full text-[11px] font-semibold shrink-0',
                                'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60' => $j->status === 'aktif',
                                'bg-gray-100 dark:bg-zinc-800 text-gray-500' => $j->status === 'nonaktif',
                                'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-200/60' => $j->status === 'dibatalkan',
                                'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200/60' => $j->status === 'reschedule',
                            ])>
                                {{ ucfirst($j->status) }}
                            </span>
                        </div>
                        <h3 class="font-bold text-sm text-gray-900 dark:text-zinc-100 leading-snug">{{ $j->mataKuliah->nama_mk }}</h3>
                        <div class="flex flex-wrap items-center gap-1.5 mt-2 text-xs">
                            @if($j->kelas_id && $j->kelas && $j->kelas->nama_kelas !== '-')
                                <span class="px-2 py-0.5 rounded bg-gray-100 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 text-gray-700 dark:text-zinc-300 font-medium">Kelas {{ $j->kelas->nama_kelas }}</span>
                            @endif
                            <span class="px-2 py-0.5 rounded bg-gray-100 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 text-gray-700 dark:text-zinc-300 font-medium">{{ $j->ruangPraktikum->nama_ruang }}</span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-zinc-400 mt-2 truncate flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span class="truncate">{{ $j->dosen->nama }}</span>
                        </p>
                    </div>
                    <div class="flex items-center justify-end gap-2 pt-3 mt-3 border-t border-gray-100 dark:border-zinc-800">
                        <button wire:click="openEdit({{ $j->id }})" class="px-3 py-1.5 text-xs font-semibold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 hover:bg-emerald-100 rounded-xl transition">Edit</button>
                        <button wire:click="confirmDelete({{ $j->id }})" class="px-3 py-1.5 text-xs font-semibold text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 rounded-xl transition">Hapus</button>
                    </div>
                </div>
            @empty
                <div class="sm:col-span-2 bg-white dark:bg-zinc-900 rounded-2xl p-8 text-center border border-gray-200/80 dark:border-zinc-800">
                    <p class="text-gray-500 dark:text-zinc-400 text-sm">Belum ada jadwal.</p>
                </div>
            @endforelse
        </div>
    @endif

    {{ $jadwal->links() }}

    {{-- Modal Create/Edit --}}
    @if ($showModal)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs flex items-center justify-center z-50 p-4 sm:p-6 overflow-hidden">
            <div class="bg-white dark:bg-zinc-900 rounded-3xl w-full max-w-xl max-h-[90vh] flex flex-col shadow-2xl border border-gray-100 dark:border-zinc-800 overflow-hidden my-auto animate-in fade-in zoom-in-95 duration-150 text-gray-900 dark:text-zinc-100">
                <!-- Header (Sticky/Fixed) -->
                <div class="px-6 py-4 border-b border-gray-100 dark:border-zinc-800 flex items-center justify-between bg-white dark:bg-zinc-900 shrink-0">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-gray-900 dark:text-zinc-100">{{ $editId ? 'Edit Jadwal Praktikum' : 'Tambah Jadwal Praktikum' }}</h2>
                        <p class="text-xs text-gray-500 dark:text-zinc-400">Plotting sesi praktikum ruang lab & waktu perkuliahan.</p>
                    </div>
                    <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600 dark:hover:text-zinc-300 rounded-lg p-1 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Body (Scrollable) -->
                <div class="p-6 overflow-y-auto space-y-4 flex-1">
                    {{-- Pesan bentrok --}}
                    @if ($conflictMessage)
                        <div class="bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60 text-rose-800 dark:text-rose-300 text-xs rounded-2xl p-4 flex gap-3 items-start">
                            <svg class="w-5 h-5 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <div>
                                <strong class="font-bold">Jadwal bentrok!</strong>
                                <p class="mt-0.5 text-xs text-rose-700 dark:text-rose-300 leading-relaxed">{{ $conflictMessage }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div class="sm:col-span-2">
                            <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300">Mata Kuliah *</label>
                            <select wire:model="mata_kuliah_id" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100 transition">
                                <option value="">-- Pilih Mata Kuliah --</option>
                                @foreach ($mataKuliah as $mk)
                                    <option value="{{ $mk->id }}">{{ $mk->nama_mk }} ({{ $mk->sks }} SKS)</option>
                                @endforeach
                            </select>
                            @error('mata_kuliah_id') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300">Kelas (Opsional)</label>
                            <select wire:model="kelas_id" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100 transition">
                                <option value="">-- Tanpa Kelas / Rombel Tunggal --</option>
                                @foreach ($kelasList as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                            @error('kelas_id') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300">Dosen Pengampu *</label>
                            <select wire:model="dosen_id" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100 transition">
                                <option value="">-- Pilih Dosen --</option>
                                @foreach ($dosenList as $d)
                                    <option value="{{ $d->id }}">{{ $d->nama }}</option>
                                @endforeach
                            </select>
                            @error('dosen_id') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300">Ruang Praktikum *</label>
                            <select wire:model="ruang_praktikum_id" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100 transition">
                                <option value="">-- Pilih Ruang --</option>
                                @foreach ($ruangList as $r)
                                    <option value="{{ $r->id }}">
                                        {{ $r->nama_ruang }} (Kapasitas {{ $r->kapasitas }})
                                        @if($r->cakupan_akses === 'khusus_multi_prodi')
                                            [Multi-Prodi]
                                        @elseif($r->cakupan_akses === 'umum_universitas')
                                            [Umum Kampus]
                                        @elseif($r->cakupan_akses === 'eksklusif')
                                            [Eksklusif]
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('ruang_praktikum_id') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300">Tahun Akademik *</label>
                            <select wire:model="tahun_akademik_id" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100 transition">
                                <option value="">-- Pilih Tahun Akademik --</option>
                                @foreach ($tahunList as $t)
                                    <option value="{{ $t->id }}">{{ $t->nama }} ({{ $t->semester }})</option>
                                @endforeach
                            </select>
                            @error('tahun_akademik_id') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300">Hari Perkuliahan *</label>
                            <select wire:model="hari" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100 transition">
                                <option value="">-- Pilih Hari --</option>
                                @foreach ($hariList as $h)
                                    <option value="{{ $h }}">{{ $h }}</option>
                                @endforeach
                            </select>
                            @error('hari') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300">Jam Mulai *</label>
                            <input type="time" wire:model="jam_mulai" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100 transition">
                            @error('jam_mulai') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300">Jam Selesai *</label>
                            <input type="time" wire:model="jam_selesai" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100 transition">
                            @error('jam_selesai') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300">Status Operasional *</label>
                            <select wire:model="status" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100 transition">
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                                <option value="dibatalkan">Dibatalkan</option>
                                <option value="reschedule">Reschedule</option>
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300">Keterangan (Opsional)</label>
                            <textarea wire:model="keterangan" rows="2" placeholder="Catatan tambahan bila ada..." class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100 transition"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Footer (Sticky/Fixed) -->
                <div class="px-6 py-4 bg-gray-50 dark:bg-zinc-800/60 border-t border-gray-100 dark:border-zinc-800 flex justify-end gap-2 shrink-0">
                    <button wire:click="$set('showModal', false)" class="px-4 py-2 text-xs font-semibold text-gray-600 dark:text-zinc-400 hover:bg-gray-200/60 dark:hover:bg-zinc-700 rounded-xl transition">
                        Batal
                    </button>
                    <button wire:click="save" wire:loading.attr="disabled"
                            class="px-5 py-2 text-xs bg-[#0F352A] hover:bg-[#164336] dark:bg-emerald-600 dark:hover:bg-emerald-500 text-white font-bold rounded-xl transition shadow-xs disabled:opacity-50">
                        <span wire:loading.remove wire:target="save">Simpan Jadwal</span>
                        <span wire:loading wire:target="save">Mengecek bentrok...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal konfirmasi hapus --}}
    @if ($confirmDeleteId)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-zinc-900 rounded-3xl w-full max-w-sm p-6 space-y-4 shadow-2xl border border-gray-100 dark:border-zinc-800 text-gray-900 dark:text-zinc-100 animate-in fade-in zoom-in-95 duration-150">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center mx-auto border border-rose-200 dark:border-rose-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div class="text-center">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-zinc-100">Hapus Jadwal?</h2>
                    <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">Data yang dihapus akan dipindahkan ke Tempat Sampah dan dapat dipulihkan kapan saja.</p>
                </div>
                <div class="flex justify-center gap-2 pt-2">
                    <button wire:click="$set('confirmDeleteId', null)" class="px-4 py-2 text-xs text-gray-600 dark:text-zinc-400 hover:bg-gray-100 dark:hover:bg-zinc-800 font-semibold rounded-xl transition">Batal</button>
                    <button wire:click="delete" class="px-4 py-2 text-xs bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl shadow-xs transition">Ya, Hapus</button>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Import Excel --}}
    @if ($showImportModal)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-zinc-900 rounded-3xl w-full max-w-lg shadow-2xl p-6 space-y-4 border border-gray-100 dark:border-zinc-800 animate-in fade-in zoom-in-95 duration-150 text-gray-900 dark:text-zinc-100">
                <div class="flex items-center justify-between border-b border-gray-100 dark:border-zinc-800 pb-3">
                    <h2 class="text-base font-bold text-gray-900 dark:text-zinc-100 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        <span>Import Jadwal Praktikum (Excel)</span>
                    </h2>
                    <button wire:click="$set('showImportModal', false)" class="text-gray-400 hover:text-gray-600 dark:hover:text-zinc-300 rounded-lg p-1 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="text-xs text-gray-600 dark:text-zinc-300 leading-relaxed bg-emerald-50/70 dark:bg-emerald-950/40 border border-emerald-200/60 dark:border-emerald-800/40 rounded-2xl p-3.5 flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <strong class="text-emerald-900 dark:text-emerald-200">Smart Importer Aktif:</strong> Anda dapat mengunggah file Excel jurusan secara langsung. Sistem otomatis membaca kolom, mendaftarkan mata kuliah/kelas, dan mendeteksi bentrok jadwal.
                        <a href="{{ route('jadwal.template.download') }}" class="font-bold text-emerald-700 dark:text-emerald-400 underline block mt-1">Unduh Template Contoh (.xlsx)</a>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-zinc-300 mb-2">Pilih File Excel (.xlsx / .xls / .csv)</label>
                    <input type="file" wire:model="excelFile" accept=".xlsx,.xls,.csv" class="w-full text-xs text-gray-500 dark:text-zinc-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 dark:file:bg-emerald-950/50 file:text-emerald-700 dark:file:text-emerald-300 hover:file:bg-emerald-100 border border-gray-200 dark:border-zinc-700 rounded-xl p-1 bg-gray-50/50 dark:bg-zinc-800">
                    @error('excelFile') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>

                @if($importErrors)
                    <div class="max-h-40 overflow-y-auto bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60 rounded-xl p-3 space-y-1">
                        <span class="text-xs font-bold text-rose-800 dark:text-rose-300 block">Laporan Bentrok / Lewat ({{ count($importErrors) }} catatan):</span>
                        @foreach($importErrors as $err)
                            <p class="text-[11px] text-rose-600 dark:text-rose-400 leading-tight">• {{ $err }}</p>
                        @endforeach
                    </div>
                @endif

                @if($importSuccessCount > 0)
                    <div class="bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 rounded-xl p-3 text-xs text-emerald-800 dark:text-emerald-300 font-semibold">
                        ✓ Berhasil mengimpor <strong>{{ $importSuccessCount }}</strong> jadwal praktikum!
                    </div>
                @endif

                <div class="flex justify-end gap-2 pt-3 border-t border-gray-100 dark:border-zinc-800">
                    <button wire:click="$set('showImportModal', false)" class="px-4 py-2 text-xs font-semibold text-gray-600 dark:text-zinc-400 hover:bg-gray-100 dark:hover:bg-zinc-800 rounded-xl transition">
                        Tutup
                    </button>
                    <button wire:click="importExcel" wire:loading.attr="disabled" class="px-4 py-2 text-xs font-bold bg-[#0F352A] hover:bg-[#164336] dark:bg-emerald-600 dark:hover:bg-emerald-500 text-white rounded-xl transition shadow-xs disabled:opacity-50 flex items-center gap-1.5">
                        <span wire:loading.remove wire:target="importExcel">Proses Import</span>
                        <span wire:loading wire:target="importExcel">Mengimpor data...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
