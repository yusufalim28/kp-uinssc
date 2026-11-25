<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-zinc-100 tracking-tight">Mata Kuliah Praktikum</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-800 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/50">
                    {{ number_format($mataKuliah->total()) }} Mata Kuliah
                </span>
            </div>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-zinc-400 mt-0.5">Kelola kurikulum praktikum baik tingkat program studi maupun mata kuliah bersama fakultas.</p>
        </div>
        <button wire:click="openCreate" class="inline-flex items-center gap-2 bg-[#0B2E23] hover:bg-[#124334] dark:bg-emerald-600 dark:hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow-xs self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Tambah Mata Kuliah</span>
        </button>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 text-emerald-800 dark:text-emerald-300 text-xs rounded-xl px-4 py-3 shadow-2xs flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60 text-rose-800 dark:text-rose-300 text-xs rounded-xl px-4 py-3 shadow-2xs flex items-center gap-2">
            <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Toolbar: Pencarian, Filter Fakultas (Super Admin), Filter Cakupan, & Toggle View Mode --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-2.5">
        <div class="flex flex-col sm:flex-row sm:items-center gap-2.5 flex-1 min-w-0">
            <div class="relative flex-1 min-w-0 lg:max-w-sm">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama atau kode mata kuliah..."
                       class="w-full pl-10 pr-4 py-2 rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-2xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs text-gray-900 dark:text-zinc-100 placeholder:text-gray-400 transition">
            </div>

            @if ($isSuperAdmin)
                <select wire:model.live="filterFakultas" class="w-full sm:w-60 lg:w-64 shrink-0 truncate rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-xs px-3 py-2 text-gray-900 dark:text-zinc-100 shadow-2xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                    <option value="">Semua Fakultas (Universitas)</option>
                    @foreach ($fakultasList as $fak)
                        <option value="{{ $fak->id }}">[{{ $fak->kode_fakultas }}] {{ $fak->nama_fakultas }}</option>
                    @endforeach
                </select>
            @endif

            <select wire:model.live="filterCakupan" class="w-full sm:w-44 shrink-0 truncate rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-xs px-3 py-2 text-gray-900 dark:text-zinc-100 shadow-2xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                <option value="">Semua Cakupan</option>
                <option value="prodi">Khusus Prodi</option>
                <option value="bersama_fakultas">Bersama Fakultas</option>
                <option value="umum_universitas">Umum Universitas</option>
            </select>
        </div>

        <div class="flex items-center gap-1 self-end lg:self-auto bg-gray-100 dark:bg-zinc-800 p-1 rounded-xl shrink-0">
            <button wire:click="$set('viewMode', 'grid')" title="Tampilan Kartu (Grid)"
                    @class([
                        'p-1.5 rounded-lg transition',
                        'bg-white dark:bg-zinc-900 text-emerald-700 dark:text-emerald-400 shadow-2xs' => $viewMode === 'grid',
                        'text-gray-500 dark:text-zinc-400 hover:text-gray-700' => $viewMode !== 'grid',
                    ])>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            </button>
            <button wire:click="$set('viewMode', 'table')" title="Tampilan Tabel"
                    @class([
                        'p-1.5 rounded-lg transition',
                        'bg-white dark:bg-zinc-900 text-emerald-700 dark:text-emerald-400 shadow-2xs' => $viewMode === 'table',
                        'text-gray-500 dark:text-zinc-400 hover:text-gray-700' => $viewMode !== 'table',
                    ])>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            </button>
        </div>
    </div>

    {{-- Main View: Grid Card or Table --}}
    @if ($viewMode === 'grid')
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($mataKuliah as $mk)
                <div class="bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 rounded-2xl p-5 shadow-2xs hover:border-emerald-500/40 hover:shadow-xs transition flex flex-col justify-between">
                    <div>
                        {{-- Top Badge Row --}}
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="font-mono text-xs font-bold text-emerald-800 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/60 px-2.5 py-1 rounded-lg border border-emerald-200/60 dark:border-emerald-800/60 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                <span>{{ $mk->kode_mk }}</span>
                            </span>

                            @if($mk->status === 'aktif')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-medium bg-gray-100 dark:bg-zinc-800 text-gray-500">
                                    Nonaktif
                                </span>
                            @endif
                        </div>

                        {{-- Nama Mata Kuliah --}}
                        <h3 class="font-bold text-base text-gray-900 dark:text-zinc-100 leading-snug">{{ $mk->nama_mk }}</h3>

                        {{-- Program Studi & Fakultas / Cakupan --}}
                        <div class="mt-1 flex items-center gap-1.5 text-xs">
                            @if ($mk->cakupan_mk === 'bersama_fakultas')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300 border border-purple-200/60 dark:border-purple-800/40 shrink-0">
                                    <svg class="w-3 h-3 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    Bersama Fakultas
                                </span>
                                <span class="text-xs text-gray-500 dark:text-zinc-400 truncate">{{ $mk->nama_fakultas }}</span>
                            @elseif ($mk->cakupan_mk === 'umum_universitas')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200/60 dark:border-amber-800/40 shrink-0">
                                    🏛️ Umum Universitas (MKU)
                                </span>
                            @else
                                <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                <span class="text-xs text-gray-500 dark:text-zinc-400 truncate">{{ $mk->programStudi?->nama_program_studi ?? 'Prodi' }}</span>
                            @endif
                        </div>

                        {{-- SKS & Semester Stats --}}
                        <div class="mt-3.5 pt-3 border-t border-gray-100 dark:border-zinc-800/80 flex flex-wrap items-center gap-2">
                            <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40">
                                {{ $mk->sks }} SKS
                            </span>
                            <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200/60 dark:border-amber-800/40">
                                Semester {{ $mk->semester }}
                            </span>
                            <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border border-blue-200/60 dark:border-blue-800/40">
                                {{ $mk->jadwal_praktikum_count ?? 0 }} Jadwal
                            </span>
                            @if($mk->hasModul())
                                <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40 flex items-center gap-1">
                                    <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Modul Lab
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="pt-3.5 mt-3.5 border-t border-gray-100 dark:border-zinc-800 flex items-center justify-end gap-1.5">
                        <button wire:click="openEdit({{ $mk->id }})" class="p-1.5 text-emerald-700 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-zinc-800 rounded-lg transition" title="Edit Mata Kuliah">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <button wire:click="confirmDelete({{ $mk->id }})" class="p-1.5 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-zinc-800 rounded-lg transition" title="Hapus Mata Kuliah">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white dark:bg-zinc-900 rounded-2xl p-10 text-center border border-gray-200/80 dark:border-zinc-800">
                    <p class="text-sm font-bold text-gray-700 dark:text-zinc-300">Belum ada data mata kuliah.</p>
                </div>
            @endforelse
        </div>
    @else
        {{-- Table View --}}
        <div class="bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 rounded-2xl overflow-hidden shadow-2xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-zinc-800 bg-gray-50/60 dark:bg-zinc-800/40 text-gray-500 font-bold uppercase tracking-wider text-[10px]">
                            <th class="px-5 py-3.5">Kode</th>
                            <th class="px-5 py-3.5">Nama Mata Kuliah</th>
                            <th class="px-5 py-3.5">Cakupan / Program Studi</th>
                            <th class="px-5 py-3.5 text-center">SKS</th>
                            <th class="px-5 py-3.5 text-center">Semester</th>
                            <th class="px-5 py-3.5 text-center">Jadwal</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-zinc-800">
                        @forelse ($mataKuliah as $mk)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-zinc-800/50 transition">
                                <td class="px-5 py-3.5 font-mono font-bold text-emerald-800 dark:text-emerald-300">
                                    {{ $mk->kode_mk }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="font-bold text-gray-900 dark:text-zinc-100 text-sm">{{ $mk->nama_mk }}</div>
                                    @if($mk->hasModul())
                                        <span class="inline-flex items-center gap-1 text-[10px] text-emerald-700 dark:text-emerald-400 font-medium mt-0.5">
                                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            Modul Praktikum Tersedia
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5">
                                    @if ($mk->cakupan_mk === 'bersama_fakultas')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300 border border-purple-200/60 dark:border-purple-800/40">
                                            Bersama Fakultas
                                        </span>
                                        <span class="block text-[11px] font-semibold text-gray-700 dark:text-zinc-300 mt-0.5">{{ $mk->nama_fakultas }}</span>
                                    @elseif ($mk->cakupan_mk === 'umum_universitas')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200/60 dark:border-amber-800/40">
                                            Umum Universitas (MKU)
                                        </span>
                                    @else
                                        <span class="font-semibold text-gray-900 dark:text-zinc-100">{{ $mk->programStudi?->nama_program_studi }}</span>
                                        @if ($mk->nama_fakultas)
                                            <span class="block text-[10px] text-gray-400">{{ $mk->nama_fakultas }}</span>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-center font-bold text-gray-700 dark:text-zinc-300">{{ $mk->sks }}</td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="px-2 py-0.5 rounded-md font-mono font-bold text-amber-800 dark:text-amber-300 bg-amber-50 dark:bg-amber-950/50 border border-amber-200/50 dark:border-amber-800/40">
                                        Smt {{ $mk->semester }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border border-blue-200/60 dark:border-blue-800/40">
                                        {{ $mk->jadwal_praktikum_count ?? 0 }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    @if($mk->status === 'aktif')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-gray-100 dark:bg-zinc-800 text-gray-500">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-right space-x-2">
                                    <button wire:click="openEdit({{ $mk->id }})" class="font-bold text-xs text-emerald-700 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 transition">Edit</button>
                                    <button wire:click="confirmDelete({{ $mk->id }})" class="font-bold text-xs text-rose-600 dark:text-rose-400 hover:text-rose-700 dark:hover:text-rose-300 transition">Hapus</button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="px-5 py-8 text-center text-gray-400">Belum ada data mata kuliah.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{ $mataKuliah->links() }}

    {{-- Modal Tambah / Edit --}}
    @if ($showModal)
        <div class="fixed inset-0 bg-black/60 z-50 overflow-y-auto p-4 sm:p-6 backdrop-blur-xs">
            <div class="flex min-h-full items-start sm:items-center justify-center py-4 sm:py-6">
                <div class="bg-white dark:bg-zinc-900 rounded-3xl w-full max-w-lg p-6 border border-gray-200/80 dark:border-zinc-800 shadow-2xl space-y-4 my-auto animate-in fade-in zoom-in-95 duration-150">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-zinc-800">
                        <div>
                            <h2 class="text-base font-bold text-gray-900 dark:text-zinc-100">{{ $editId ? 'Edit Mata Kuliah' : 'Tambah Mata Kuliah Baru' }}</h2>
                            <p class="text-[11px] text-gray-500 dark:text-zinc-400">Atur kurikulum praktikum prodi atau mata kuliah lintas jurusan fakultas.</p>
                        </div>
                        <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    {{-- Cakupan Mata Kuliah --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 mb-1.5">Cakupan Mata Kuliah *</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label @class([
                                'flex items-center gap-2.5 p-3 rounded-xl border cursor-pointer transition text-xs',
                                'border-emerald-500 bg-emerald-50/50 dark:bg-emerald-950/30 text-emerald-900 dark:text-emerald-200 font-bold' => $cakupan_mk === 'prodi',
                                'border-gray-200 dark:border-zinc-700 hover:bg-gray-50 dark:hover:bg-zinc-800 text-gray-700 dark:text-zinc-300' => $cakupan_mk !== 'prodi'
                            ])>
                                <input type="radio" wire:model.live="cakupan_mk" value="prodi" class="text-emerald-600 focus:ring-emerald-500">
                                <div>
                                    <div class="font-bold">Khusus Prodi</div>
                                    <div class="text-[10px] opacity-75 font-normal">1 program studi tertentu</div>
                                </div>
                            </label>

                            <label @class([
                                'flex items-center gap-2.5 p-3 rounded-xl border cursor-pointer transition text-xs',
                                'border-purple-500 bg-purple-50/50 dark:bg-purple-950/30 text-purple-900 dark:text-purple-200 font-bold' => $cakupan_mk === 'bersama_fakultas',
                                'border-gray-200 dark:border-zinc-700 hover:bg-gray-50 dark:hover:bg-zinc-800 text-gray-700 dark:text-zinc-300' => $cakupan_mk !== 'bersama_fakultas'
                            ])>
                                <input type="radio" wire:model.live="cakupan_mk" value="bersama_fakultas" class="text-purple-600 focus:ring-purple-500">
                                <div>
                                    <div class="font-bold">Bersama Fakultas</div>
                                    <div class="text-[10px] opacity-75 font-normal">Lintas semua jurusan/prodi</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Fakultas Pemilik (Jika Super Admin) --}}
                    @if ($cakupan_mk !== 'umum_universitas')
                        @if ($isSuperAdmin)
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 mb-1">Fakultas *</label>
                                <select wire:model.live="fakultas_id" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs px-3.5 py-2.5 text-gray-900 dark:text-zinc-100 transition">
                                    <option value="">Pilih Fakultas...</option>
                                    @foreach ($fakultasList as $fak)
                                        <option value="{{ $fak->id }}">[{{ $fak->kode_fakultas }}] {{ $fak->nama_fakultas }}</option>
                                    @endforeach
                                </select>
                                @error('fakultas_id') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        @else
                            <div class="p-2.5 rounded-xl bg-gray-50 dark:bg-zinc-800/60 border border-gray-200 dark:border-zinc-700 text-xs text-gray-700 dark:text-zinc-300 flex items-center justify-between">
                                <span class="text-gray-500 dark:text-zinc-400">Fakultas:</span>
                                <span class="font-bold text-gray-900 dark:text-zinc-100">{{ $userFakultas?->nama_fakultas ?? 'Fakultas Terdaftar' }}</span>
                            </div>
                        @endif
                    @endif

                    {{-- Program Studi (Hanya jika cakupan = prodi) --}}
                    @if ($cakupan_mk === 'prodi')
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 mb-1">Program Studi *</label>
                            <select wire:model.live="program_studi_id" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs px-3.5 py-2.5 text-gray-900 dark:text-zinc-100 transition">
                                <option value="">Pilih Program Studi...</option>
                                @foreach ($programStudi as $ps)
                                    <option value="{{ $ps->id }}">{{ $ps->nama_program_studi }}</option>
                                @endforeach
                            </select>
                            @error('program_studi_id') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    @elseif ($cakupan_mk === 'bersama_fakultas')
                        <div class="p-3 rounded-xl bg-purple-50 dark:bg-purple-950/30 border border-purple-200/60 dark:border-purple-800/40 text-xs text-purple-900 dark:text-purple-200 flex items-start gap-2">
                            <svg class="w-4 h-4 text-purple-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Mata kuliah bersama ini dapat digunakan oleh <strong>semua kelas/jurusan</strong> di bawah fakultas ini (contoh: <em>Peradilan Agama, Hisab Falak,</em> dll).</span>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 mb-1">Kode Mata Kuliah *</label>
                            <input type="text" wire:model="kode_mk" placeholder="mis. PA501, FALAK01" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs px-3.5 py-2.5 text-gray-900 dark:text-zinc-100 transition font-mono uppercase">
                            @error('kode_mk') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 mb-1">Nama Mata Kuliah *</label>
                            <input type="text" wire:model="nama_mk" placeholder="mis. Peradilan Agama" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs px-3.5 py-2.5 text-gray-900 dark:text-zinc-100 transition">
                            @error('nama_mk') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 mb-1">Bobot SKS *</label>
                            <select wire:model="sks" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs px-3.5 py-2.5 text-gray-900 dark:text-zinc-100 transition">
                                <option value="">Pilih SKS...</option>
                                @for ($sk = 1; $sk <= 6; $sk++)
                                    <option value="{{ $sk }}">{{ $sk }} SKS</option>
                                @endfor
                            </select>
                            @error('sks') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 mb-1">Semester *</label>
                            <select wire:model="semester" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs px-3.5 py-2.5 text-gray-900 dark:text-zinc-100 transition">
                                <option value="">Pilih Semester...</option>
                                @for ($sem = 1; $sem <= 8; $sem++)
                                    <option value="{{ $sem }}">Semester {{ $sem }}</option>
                                @endfor
                            </select>
                            @error('semester') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 mb-1">Status Keaktifan</label>
                        <select wire:model="status" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs px-3.5 py-2.5 text-gray-900 dark:text-zinc-100 transition">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>

                    {{-- Modul Ajar Praktikum --}}
                    <div class="pt-3 border-t border-gray-100 dark:border-zinc-800 space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-bold text-gray-800 dark:text-zinc-200">
                                Modul Ajar Praktikum (Buku Petunjuk Lab)
                            </label>
                            <span class="text-[10px] text-gray-400 font-medium">Opsional</span>
                        </div>

                        {{-- Upload File Modul --}}
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-600 dark:text-zinc-400 mb-1">
                                Upload File Modul (PDF / DOC / DOCX / ZIP, Maks 20MB)
                            </label>
                            @if ($file_modul)
                                <div class="flex items-center justify-between p-2.5 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 rounded-xl mb-2 text-xs">
                                    <div class="flex items-center gap-2 truncate">
                                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span class="text-emerald-800 dark:text-emerald-300 truncate font-mono text-[11px]">{{ basename($file_modul) }}</span>
                                    </div>
                                    <button type="button" wire:click="hapusFileModul" class="text-rose-600 hover:text-rose-800 text-[11px] font-bold shrink-0 ml-2">Hapus</button>
                                </div>
                            @endif
                            <input type="file" wire:model="upload_modul" class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 dark:file:bg-emerald-950/50 file:text-emerald-800 dark:file:text-emerald-300 hover:file:bg-emerald-100 transition">
                            <div wire:loading wire:target="upload_modul" class="text-xs text-emerald-600 mt-1">Mengunggah file modul...</div>
                            @error('upload_modul') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Tautan Drive / Cloud Modul --}}
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-600 dark:text-zinc-400 mb-1">
                                Atau Tautan Modul Cloud (Google Drive / OneDrive / Dropbox)
                            </label>
                            <input type="url" wire:model="tautan_modul" placeholder="https://drive.google.com/file/d/..." class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs px-3.5 py-2 text-gray-900 dark:text-zinc-100 transition">
                            @error('tautan_modul') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Ringkasan / Deskripsi Modul --}}
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-600 dark:text-zinc-400 mb-1">
                                Catatan / Deskripsi Petunjuk Lab
                            </label>
                            <textarea wire:model="deskripsi_modul" rows="2" placeholder="mis. Modul ini wajib dicetak dan dibawa mahasiswa pada setiap pertemuan lab." class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs px-3.5 py-2 text-gray-900 dark:text-zinc-100 transition"></textarea>
                            @error('deskripsi_modul') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100 dark:border-zinc-800">
                        <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 text-xs font-bold text-gray-600 dark:text-zinc-400 hover:text-gray-800 rounded-xl transition">Batal</button>
                        <button type="button" wire:click="save" class="px-5 py-2 bg-[#0B2E23] hover:bg-[#124334] dark:bg-emerald-600 dark:hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition shadow-xs">
                            Simpan Mata Kuliah
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Konfirmasi Hapus --}}
    @if ($confirmDeleteId)
        <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4 backdrop-blur-xs">
            <div class="bg-white dark:bg-zinc-900 rounded-2xl max-w-sm w-full p-6 space-y-4 border border-gray-200/80 dark:border-zinc-800 shadow-2xl animate-in fade-in zoom-in-95 duration-150">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm text-gray-900 dark:text-zinc-100">Hapus Mata Kuliah?</h3>
                        <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">Tindakan ini tidak dapat dibatalkan jika mata kuliah belum terikat jadwal.</p>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-2 pt-2">
                    <button wire:click="$set('confirmDeleteId', null)" class="px-3.5 py-1.5 text-xs font-bold text-gray-600 dark:text-zinc-400 hover:text-gray-800 rounded-lg">Batal</button>
                    <button wire:click="delete" class="px-4 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-lg shadow-xs">Hapus</button>
                </div>
            </div>
        </div>
    @endif

</div>
