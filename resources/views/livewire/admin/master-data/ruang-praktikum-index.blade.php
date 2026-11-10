<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-zinc-100 tracking-tight">Ruang Praktikum & Laboratorium</h1>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-zinc-400 mt-0.5">Kelola data laboratorium, studio, dan kapasitas ruangan kampus.</p>
        </div>
        <button wire:click="openCreate" class="inline-flex items-center gap-2 bg-[#0F352A] hover:bg-[#164336] dark:bg-emerald-600 dark:hover:bg-emerald-500 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow-xs self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Tambah Ruangan</span>
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

    {{-- Filter & Search Bar --}}
    <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center justify-between">
        <div class="relative flex-1 max-w-md">
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </span>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama, kode ruang, atau lokasi..."
                   class="w-full pl-10 pr-4 py-2 rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-2xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs text-gray-900 dark:text-zinc-100 placeholder:text-gray-400 transition">
        </div>

        <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
            <select wire:model.live="filterCakupan" class="rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-xs px-3 py-2 text-gray-900 dark:text-zinc-100 shadow-2xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                <option value="">Semua Hak Pakai</option>
                <option value="bersama_fakultas">Bersama Fakultas</option>
                <option value="eksklusif">Eksklusif 1 Prodi</option>
                <option value="khusus_multi_prodi">Khusus Multi-Prodi</option>
                <option value="umum_universitas">Umum / Universitas</option>
            </select>

            <select wire:model.live="filterTipe" class="rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-xs px-3 py-2 text-gray-900 dark:text-zinc-100 shadow-2xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                <option value="">Semua Tipe Ruang</option>
                <option value="laboratorium">Laboratorium</option>
                <option value="auditorium">Auditorium / Gedung Besar</option>
                <option value="studio">Studio</option>
                <option value="kelas">Kelas Praktikum</option>
            </select>

            <div class="flex items-center gap-1 bg-gray-100 dark:bg-zinc-800 p-1 rounded-xl">
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
    </div>

    @if ($viewMode === 'grid')
        {{-- Grid Card View --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($ruang as $r)
                <div class="bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 rounded-2xl p-5 shadow-2xs hover:border-emerald-500/40 hover:shadow-xs transition flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="font-mono text-xs font-bold text-emerald-800 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/60 px-2.5 py-1 rounded-lg border border-emerald-200/60 dark:border-emerald-800/60 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                <span>{{ $r->kode_ruang }}</span>
                            </span>

                            <span @class([
                                'px-2.5 py-0.5 rounded-full text-[10px] font-bold inline-flex items-center gap-1',
                                'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60' => in_array($r->status, ['aktif', 'tersedia']),
                                'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200/60' => $r->status === 'perbaikan',
                                'bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-zinc-400' => !in_array($r->status, ['aktif', 'tersedia', 'perbaikan']),
                            ])>
                                <span class="w-1.5 h-1.5 rounded-full {{ in_array($r->status, ['aktif', 'tersedia']) ? 'bg-emerald-500' : ($r->status === 'perbaikan' ? 'bg-amber-500' : 'bg-gray-400') }}"></span>
                                {{ $r->status === 'perbaikan' ? 'Perbaikan' : ucfirst($r->status) }}
                            </span>
                        </div>

                        <h3 class="font-bold text-base text-gray-900 dark:text-zinc-100 leading-snug">{{ $r->nama_ruang }}</h3>

                        <div class="flex items-center gap-1.5 flex-wrap mt-2">
                            @if($r->tipe_ruang === 'auditorium')
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300 border border-purple-200/60">Auditorium</span>
                            @else
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-medium bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60">{{ ucfirst($r->tipe_ruang) }}</span>
                            @endif

                            @if($r->cakupan_akses === 'khusus_multi_prodi')
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-teal-50 dark:bg-teal-950/40 text-teal-700 dark:text-teal-300 border border-teal-200/60">Multi-Prodi</span>
                            @elseif($r->cakupan_akses === 'eksklusif')
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200/60">Eksklusif</span>
                            @elseif($r->cakupan_akses === 'umum_universitas')
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300 border border-purple-200/60">Umum Kampus</span>
                            @else
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-medium bg-slate-100 dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 border border-slate-200 dark:border-zinc-700">Bersama Fak</span>
                            @endif
                        </div>

                        <p class="text-xs text-gray-500 dark:text-zinc-400 mt-2 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="truncate">{{ $r->lokasi }}</span>
                        </p>

                        <div class="mt-3 pt-3 border-t border-gray-100 dark:border-zinc-800/80 flex items-center justify-between text-xs">
                            <span class="text-gray-500 dark:text-zinc-400">Kapasitas: <strong class="text-gray-900 dark:text-zinc-100">{{ $r->kapasitas }} org</strong></span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border border-blue-200/60">
                                {{ $r->jadwal_praktikum_count ?? 0 }} Jadwal
                            </span>
                        </div>
                    </div>

                    <div class="pt-3.5 mt-3.5 border-t border-gray-100 dark:border-zinc-800 flex items-center justify-end gap-1.5">
                        @if(auth()->user()->hasRole('super-admin') || (auth()->user()->fakultas_id && auth()->user()->fakultas_id === $r->fakultas_id))
                            <button wire:click="openEdit({{ $r->id }})" class="p-1.5 text-emerald-700 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-zinc-800 rounded-lg transition" title="Edit Ruangan">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <button wire:click="confirmDelete({{ $r->id }})" class="p-1.5 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-zinc-800 rounded-lg transition" title="Hapus Ruangan">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white dark:bg-zinc-900 rounded-2xl p-10 text-center border border-gray-200/80 dark:border-zinc-800">
                    <p class="text-sm font-bold text-gray-700 dark:text-zinc-300">Tidak ada data ruangan ditemukan.</p>
                </div>
            @endforelse
        </div>
    @else
        {{-- Desktop Table --}}
    <div class="hidden md:block bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 rounded-2xl overflow-hidden shadow-2xs">
        <table class="w-full text-xs text-left">
            <thead class="bg-gray-50/80 dark:bg-zinc-800/60 border-b border-gray-200/80 dark:border-zinc-700/80 text-[11px] font-bold text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-3.5 w-32">Kode</th>
                    <th class="px-5 py-3.5">Nama Ruang</th>
                    <th class="px-5 py-3.5">Tipe & Kepemilikan</th>
                    <th class="px-5 py-3.5">Lokasi</th>
                    <th class="px-5 py-3.5 w-24 text-center">Kapasitas</th>
                    <th class="px-5 py-3.5 w-28">Status</th>
                    <th class="px-5 py-3.5 text-right w-36">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-zinc-800 text-gray-800 dark:text-zinc-200">
                @forelse ($ruang as $r)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-zinc-800/40 transition">
                        <td class="px-5 py-3.5 font-mono text-xs font-bold text-gray-700 dark:text-zinc-300">
                            <span class="px-2 py-1 bg-gray-100 dark:bg-zinc-800 rounded-md border border-gray-200/60 dark:border-zinc-700">{{ $r->kode_ruang }}</span>
                        </td>
                        <td class="px-5 py-3.5 font-semibold text-gray-900 dark:text-zinc-100">
                            {{ $r->nama_ruang }}
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex flex-col gap-1 items-start">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    @if($r->tipe_ruang === 'auditorium')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800/50">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                            Auditorium
                                        </span>
                                    @elseif($r->tipe_ruang === 'studio')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800/50">
                                            Studio
                                        </span>
                                    @elseif($r->tipe_ruang === 'kelas')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800/50">
                                            Kelas
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/50">
                                            Laboratorium
                                        </span>
                                    @endif

                                    {{-- Badge Cakupan Hak Pakai --}}
                                    @if($r->cakupan_akses === 'khusus_multi_prodi')
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-teal-50 dark:bg-teal-950/40 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-800/50">
                                            Khusus Multi-Prodi
                                        </span>
                                    @elseif($r->cakupan_akses === 'eksklusif')
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800/50">
                                            Eksklusif Prodi
                                        </span>
                                    @elseif($r->cakupan_akses === 'umum_universitas')
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800/50">
                                            Umum Kampus
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-medium bg-slate-100 dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 border border-slate-200 dark:border-zinc-700">
                                            Bersama Fakultas
                                        </span>
                                    @endif
                                </div>

                                @if($r->fakultas)
                                    <span class="text-[11px] text-gray-500 dark:text-zinc-400 font-medium">
                                        🏛️ {{ $r->fakultas->nama_fakultas }}
                                        @if($r->cakupan_akses === 'eksklusif' && $r->programStudi)
                                            <span class="text-gray-400">/</span> <strong class="text-gray-700 dark:text-zinc-200">{{ $r->programStudi->nama_program_studi }}</strong>
                                        @endif
                                    </span>
                                @else
                                    <span class="text-[11px] text-purple-600 dark:text-purple-400 font-semibold flex items-center gap-1">
                                        🌐 Fasilitas Bersama Universitas
                                    </span>
                                @endif

                                @if($r->cakupan_akses === 'khusus_multi_prodi' && $r->prodiPengguna->isNotEmpty())
                                    <div class="flex flex-wrap gap-1 mt-0.5">
                                        @foreach($r->prodiPengguna as $pu)
                                            <span class="px-1.5 py-0.5 rounded bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300 font-semibold text-[10px] border border-gray-200 dark:border-zinc-700" title="{{ $pu->fakultas?->nama_fakultas }}">
                                                {{ $pu->nama_program_studi }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-gray-600 dark:text-zinc-400">{{ $r->lokasi }}</td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-zinc-300 font-medium text-center">
                            {{ $r->kapasitas }} <span class="text-xs text-gray-400 font-normal">kursi</span>
                        </td>
                        <td class="px-5 py-3.5">
                            <span @class([
                                'px-2.5 py-1 rounded-full text-xs font-semibold inline-flex items-center gap-1.5',
                                'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40' => in_array($r->status, ['aktif', 'tersedia']),
                                'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200/60 dark:border-amber-800/40' => $r->status === 'perbaikan',
                                'bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-zinc-400' => !in_array($r->status, ['aktif', 'tersedia', 'perbaikan']),
                            ])>
                                <span class="w-1.5 h-1.5 rounded-full {{ in_array($r->status, ['aktif', 'tersedia']) ? 'bg-emerald-500' : ($r->status === 'perbaikan' ? 'bg-amber-500' : 'bg-gray-400') }}"></span>
                                {{ $r->status === 'perbaikan' ? 'Perbaikan' : ucfirst($r->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right space-x-2">
                            @if(auth()->user()->hasRole('super-admin') || (auth()->user()->fakultas_id && auth()->user()->fakultas_id === $r->fakultas_id))
                                <button wire:click="openEdit({{ $r->id }})" class="font-bold text-xs text-emerald-700 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 transition">Edit</button>
                                <button wire:click="confirmDelete({{ $r->id }})" class="font-bold text-xs text-rose-600 dark:text-rose-400 hover:text-rose-700 dark:hover:text-rose-300 transition">Hapus</button>
                            @else
                                <span class="text-xs text-gray-400 italic">Read-only</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-10 text-center text-gray-400 dark:text-zinc-500 text-sm">
                            Tidak ada data ruangan ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Card View --}}
    <div class="md:hidden grid grid-cols-1 sm:grid-cols-2 gap-3.5">
        @forelse ($ruang as $r)
            <div class="bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 rounded-2xl p-4 shadow-2xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between gap-2 mb-2">
                        <span class="inline-block px-2 py-0.5 rounded bg-gray-100 dark:bg-zinc-800 font-mono text-xs text-gray-700 dark:text-zinc-300 font-bold border border-gray-200 dark:border-zinc-700">{{ $r->kode_ruang }}</span>
                        <span @class([
                            'px-2 py-0.5 rounded-full text-[11px] font-semibold shrink-0',
                            'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40' => in_array($r->status, ['aktif', 'tersedia']),
                            'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200/60 dark:border-amber-800/40' => $r->status === 'perbaikan',
                            'bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-zinc-400' => !in_array($r->status, ['aktif', 'tersedia', 'perbaikan']),
                        ])>
                            {{ $r->status === 'perbaikan' ? 'Perbaikan' : ucfirst($r->status) }}
                        </span>
                    </div>

                    <h3 class="font-bold text-sm text-gray-900 dark:text-zinc-100 leading-snug">{{ $r->nama_ruang }}</h3>

                    <div class="mt-2 space-y-1.5">
                        <div class="flex items-center gap-1.5 flex-wrap">
                            @if($r->tipe_ruang === 'auditorium')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300 border border-purple-200">Auditorium</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60">{{ ucfirst($r->tipe_ruang) }}</span>
                            @endif

                            @if($r->cakupan_akses === 'khusus_multi_prodi')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-teal-50 dark:bg-teal-950/40 text-teal-700 dark:text-teal-300 border border-teal-200">Multi-Prodi</span>
                            @elseif($r->cakupan_akses === 'eksklusif')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200">Eksklusif</span>
                            @elseif($r->cakupan_akses === 'umum_universitas')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300 border border-purple-200">Umum Kampus</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 border border-slate-200 dark:border-zinc-700">Bersama Fak</span>
                            @endif

                            @if($r->fakultas)
                                <span class="text-[11px] text-gray-500 dark:text-zinc-400">{{ $r->fakultas->nama_fakultas }}</span>
                            @else
                                <span class="text-[11px] text-purple-600 dark:text-purple-400 font-semibold">Fasilitas Bersama</span>
                            @endif
                        </div>

                        @if($r->cakupan_akses === 'khusus_multi_prodi' && $r->prodiPengguna->isNotEmpty())
                            <div class="flex flex-wrap gap-1 pt-0.5">
                                @foreach($r->prodiPengguna as $pu)
                                    <span class="px-1.5 py-0.5 rounded bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300 font-semibold text-[10px] border border-gray-200 dark:border-zinc-700">
                                        {{ $pu->nama_program_studi }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-zinc-400 pt-1">
                            <span>Kapasitas: <strong class="text-gray-800 dark:text-zinc-200">{{ $r->kapasitas }} org</strong></span>
                            @if($r->lokasi)
                                <span>• {{ $r->lokasi }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                @if(auth()->user()->hasRole('super-admin') || (auth()->user()->fakultas_id && auth()->user()->fakultas_id === $r->fakultas_id))
                    <div class="flex items-center justify-end gap-2 pt-3 mt-3 border-t border-gray-100 dark:border-zinc-800">
                        <button wire:click="openEdit({{ $r->id }})" class="px-3 py-1.5 text-xs font-semibold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 hover:bg-emerald-100 dark:hover:bg-emerald-900/60 rounded-xl transition">Edit</button>
                        <button wire:click="confirmDelete({{ $r->id }})" class="px-3 py-1.5 text-xs font-semibold text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 dark:hover:bg-rose-900/60 rounded-xl transition">Hapus</button>
                    </div>
                @endif
            </div>
        @empty
            <div class="sm:col-span-2 bg-white dark:bg-zinc-900 rounded-2xl p-8 text-center border border-gray-200/80 dark:border-zinc-800 text-gray-500 dark:text-zinc-400 text-sm">
                Belum ada data ruang praktikum atau auditorium.
            </div>
        @endforelse
    </div>
    @endif

    {{ $ruang->links() }}

    {{-- Create / Edit Modal --}}
    @if ($showModal)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs z-50 overflow-y-auto p-4 sm:p-6">
            <div class="flex min-h-full items-start sm:items-center justify-center py-4 sm:py-6">
                <div class="bg-white dark:bg-zinc-900 rounded-3xl w-full max-w-lg p-6 space-y-4 shadow-2xl border border-gray-100 dark:border-zinc-800 my-auto text-gray-900 dark:text-zinc-100 animate-in fade-in zoom-in-95 duration-150">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-zinc-800">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 dark:text-zinc-100">{{ $editId ? 'Edit Ruangan' : 'Tambah Ruang / Auditorium' }}</h2>
                            <p class="text-xs text-gray-500 dark:text-zinc-400">Konfigurasi ruangan praktikum, laboratorium, atau auditorium bersama.</p>
                        </div>
                        <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600 dark:hover:text-zinc-300 p-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300">Kode Ruang *</label>
                            <input type="text" wire:model="kode_ruang" placeholder="mis. LAB-KOM-01" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100 transition">
                            @error('kode_ruang') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300">Tipe Ruangan *</label>
                            <select wire:model="tipe_ruang" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100 transition">
                                <option value="laboratorium">Laboratorium</option>
                                <option value="auditorium">Auditorium / Gedung Besar</option>
                                <option value="studio">Studio</option>
                                <option value="kelas">Kelas Praktikum</option>
                            </select>
                            @error('tipe_ruang') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300">Nama Ruang / Auditorium *</label>
                        <input type="text" wire:model="nama_ruang" placeholder="mis. Lab Jaringan Komputer / Lab Bahasa Terpadu / Auditorium" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100 transition">
                        @error('nama_ruang') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Pengaturan Cakupan Hak Pakai --}}
                    <div class="p-3.5 bg-gray-50/80 dark:bg-zinc-800/60 border border-gray-200/80 dark:border-zinc-700/80 rounded-2xl space-y-3">
                        <div>
                            <label class="text-xs font-bold text-gray-800 dark:text-zinc-200">Cakupan Hak Pakai Ruangan *</label>
                            <select wire:model.live="cakupan_akses" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100 font-medium transition">
                                <option value="bersama_fakultas">️Bersama Fakultas (Dapat dipakai seluruh prodi dalam fakultas)</option>
                                <option value="eksklusif">Eksklusif Prodi (Hanya 1 prodi yang boleh memakai)</option>
                                <option value="khusus_multi_prodi">Khusus Multi-Prodi (Dipakai bersama 2+ prodi tertentu, bisa lintas fakultas)</option>
                                <option value="umum_universitas">Fasilitas Umum Universitas</option>
                            </select>
                            @error('cakupan_akses') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Jika Eksklusif Prodi: pilih 1 prodi --}}
                        @if($cakupan_akses === 'eksklusif')
                            <div>
                                <label class="text-xs font-semibold text-amber-900 dark:text-amber-300">Program Studi Pemilik Ruangan *</label>
                                <select wire:model="program_studi_id" class="w-full rounded-xl border border-amber-300 dark:border-amber-700 bg-white dark:bg-zinc-900 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100 transition">
                                    <option value="">-- Pilih Program Studi Pemilik --</option>
                                    @foreach ($prodiList as $prd)
                                        <option value="{{ $prd->id }}">{{ $prd->nama_program_studi }} ({{ $prd->fakultas?->nama_fakultas }})</option>
                                    @endforeach
                                </select>
                                <p class="text-[11px] text-amber-700 dark:text-amber-400 mt-1">Hanya mata kuliah dari program studi ini yang dapat dijadwalkan di ruangan ini.</p>
                                @error('program_studi_id') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        @endif

                        {{-- Jika Khusus Multi-Prodi: Checkbox Prodi Lintas Fakultas --}}
                        @if($cakupan_akses === 'khusus_multi_prodi')
                            <div class="space-y-2 pt-1 border-t border-gray-200/80 dark:border-zinc-700">
                                <div class="flex items-center justify-between">
                                    <label class="text-xs font-bold text-teal-900 dark:text-teal-300">Pilih Program Studi yang Berhak Menggunakan Ruangan Ini:</label>
                                    <span class="text-[11px] text-teal-700 dark:text-teal-400 font-semibold">Tersedia lintas fakultas</span>
                                </div>
                                <div class="max-h-48 overflow-y-auto space-y-2.5 pr-1 text-xs">
                                    @foreach ($allProdiGrouped as $fakNama => $prodis)
                                        <div class="border border-gray-200/90 dark:border-zinc-700 rounded-xl p-2.5 bg-white dark:bg-zinc-900">
                                            <div class="font-bold text-[#0F352A] dark:text-emerald-400 mb-1 pb-1 border-b border-gray-100 dark:border-zinc-800 flex items-center gap-1.5">
                                                <span>🏛️</span> <span>{{ $fakNama }}</span>
                                            </div>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-1.5 pt-1">
                                                @foreach ($prodis as $prd)
                                                    <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 dark:hover:bg-zinc-800 p-1.5 rounded-lg transition select-none">
                                                        <input type="checkbox" wire:model="selected_prodi_ids" value="{{ $prd->id }}" class="rounded text-emerald-600 focus:ring-emerald-500/20">
                                                        <span class="text-gray-700 dark:text-zinc-300 font-medium">{{ $prd->nama_program_studi }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('selected_prodi_ids') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                            </div>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300">Fakultas Penanggung Jawab / Lokasi Fisik</label>
                            <select wire:model="fakultas_id" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100 transition">
                                <option value="">-- Fasilitas Bersama Universitas --</option>
                                @foreach ($fakultasList as $fak)
                                    <option value="{{ $fak->id }}">{{ $fak->nama_fakultas }}</option>
                                @endforeach
                            </select>
                            <p class="text-[11px] text-gray-400 dark:text-zinc-500 mt-1">Gedung / fakultas tempat aset ruangan ini berada.</p>
                            @error('fakultas_id') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300">Status Operasional *</label>
                            <select wire:model="status" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100 transition">
                                <option value="aktif">Aktif / Siap Pakai</option>
                                <option value="perbaikan">Dalam Perbaikan (Maintenance)</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                            @error('status') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300">Lokasi / Gedung *</label>
                            <input type="text" wire:model="lokasi" placeholder="mis. Gedung Siber Lt. 3" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100 transition">
                            @error('lokasi') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300">Kapasitas (Kursi/Orang) *</label>
                            <input type="number" wire:model="kapasitas" min="1" max="1000" placeholder="mis. 40" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100 transition">
                            @error('kapasitas') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-2.5 pt-3 border-t border-gray-100 dark:border-zinc-800">
                        <button wire:click="$set('showModal', false)" type="button" class="px-4 py-2 text-xs text-gray-600 dark:text-zinc-400 hover:bg-gray-100 dark:hover:bg-zinc-800 font-semibold rounded-xl transition">Batal</button>
                        <button wire:click="save" type="button" class="px-5 py-2 text-xs bg-[#0F352A] hover:bg-[#164336] dark:bg-emerald-600 dark:hover:bg-emerald-500 text-white font-bold rounded-xl shadow-xs transition">Simpan Ruangan</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    @if ($confirmDeleteId)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-zinc-900 rounded-3xl w-full max-w-sm p-6 space-y-4 shadow-2xl border border-gray-100 dark:border-zinc-800 text-gray-900 dark:text-zinc-100 animate-in fade-in zoom-in-95 duration-150">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center mx-auto border border-rose-200 dark:border-rose-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div class="text-center">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-zinc-100">Hapus Ruang / Auditorium?</h2>
                    <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">Data yang dihapus tidak bisa dikembalikan. Ruang yang masih memiliki jadwal tidak dapat dihapus.</p>
                </div>
                <div class="flex justify-center gap-2 pt-2">
                    <button wire:click="$set('confirmDeleteId', null)" class="px-4 py-2 text-xs text-gray-600 dark:text-zinc-400 hover:bg-gray-100 dark:hover:bg-zinc-800 font-semibold rounded-xl transition">Batal</button>
                    <button wire:click="delete" class="px-4 py-2 text-xs bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl shadow-xs transition">Ya, Hapus</button>
                </div>
            </div>
        </div>
    @endif

</div>
