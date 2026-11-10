<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-zinc-100 tracking-tight">Tahun Akademik & Semester</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-800 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/50">
                    {{ number_format($tahun->total()) }} Periode
                </span>
            </div>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-zinc-400 mt-0.5">Kelola periode aktif perkuliahan praktikum laboratorium.</p>
        </div>
        <button wire:click="openCreate" class="inline-flex items-center gap-2 bg-[#0F352A] hover:bg-[#134436] dark:bg-emerald-600 dark:hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow-xs self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Tambah Tahun Akademik</span>
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

    {{-- Banner Tahun Akademik Aktif --}}
    @php
        $tahunAktif = \App\Models\TahunAkademik::where('is_aktif', true)->first();
    @endphp
    @if ($tahunAktif)
        <div class="bg-gradient-to-r from-emerald-50 via-white to-gray-50 dark:from-zinc-900 dark:via-zinc-900 dark:to-zinc-900 border border-emerald-200/80 dark:border-emerald-800/40 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-2xs">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 flex items-center justify-center font-bold text-lg shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <div class="text-[10px] font-bold text-emerald-800 dark:text-emerald-400 uppercase tracking-wider">Periode Aktif Sistem</div>
                    <div class="text-base font-extrabold text-gray-900 dark:text-zinc-100">{{ $tahunAktif->nama }} (Semester {{ $tahunAktif->semester }})</div>
                    <div class="text-xs text-gray-500 dark:text-zinc-400">Seluruh jadwal praktikum, plotting lab, dan kalender secara otomatis mengacu ke periode ini.</div>
                </div>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-600 text-white shrink-0 shadow-2xs">
                <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                Periode Aktif
            </span>
        </div>
    @endif

    {{-- Toolbar: Pencarian & Toggle View Mode --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="relative w-full sm:w-80">
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </span>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama periode..."
                   class="w-full pl-10 pr-4 py-2 rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-2xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs text-gray-900 dark:text-zinc-100 placeholder:text-gray-400 transition">
        </div>

        <div class="flex items-center gap-1.5 self-end sm:self-auto bg-gray-100 dark:bg-zinc-800 p-1 rounded-xl">
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
            @forelse ($tahun as $t)
                <div class="bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 rounded-2xl p-5 shadow-2xs hover:border-emerald-500/40 hover:shadow-xs transition flex flex-col justify-between">
                    <div>
                        {{-- Top Badge Row --}}
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="px-2.5 py-0.5 rounded-md text-[11px] font-bold {{ $t->semester === 'Ganjil' ? 'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border border-blue-200/60 dark:border-blue-800/40' : 'bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300 border border-purple-200/60 dark:border-purple-800/40' }}">
                                Semester {{ $t->semester }}
                            </span>

                            @if ($t->is_aktif)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Aktif
                                </span>
                            @else
                                <button wire:click="setAktif({{ $t->id }})" class="px-2 py-0.5 rounded-lg text-[10px] font-semibold bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-zinc-300 hover:bg-emerald-50 hover:text-emerald-700 border border-gray-200 dark:border-zinc-700 transition">
                                    Jadikan Aktif
                                </button>
                            @endif
                        </div>

                        {{-- Nama Periode --}}
                        <h3 class="font-bold text-base text-gray-900 dark:text-zinc-100 leading-snug">{{ $t->nama }}</h3>

                        {{-- Rentang Tahun & Stats --}}
                        <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1 font-mono">
                            Tahun: {{ $t->tahun_mulai }}{{ $t->tahun_selesai ? ' / ' . $t->tahun_selesai : '' }}
                        </p>

                        <div class="mt-3.5 pt-3 border-t border-gray-100 dark:border-zinc-800/80">
                            <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border border-blue-200/60 dark:border-blue-800/40">
                                {{ $t->jadwal_praktikum_count ?? 0 }} Jadwal Praktikum
                            </span>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="pt-3.5 mt-3.5 border-t border-gray-100 dark:border-zinc-800 flex items-center justify-end gap-1.5">
                        <button wire:click="openEdit({{ $t->id }})" class="p-1.5 text-emerald-700 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-zinc-800 rounded-lg transition" title="Edit Periode">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <button wire:click="confirmDelete({{ $t->id }})" class="p-1.5 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-zinc-800 rounded-lg transition" title="Hapus Periode">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white dark:bg-zinc-900 rounded-2xl p-10 text-center border border-gray-200/80 dark:border-zinc-800">
                    <p class="text-sm font-bold text-gray-700 dark:text-zinc-300">Belum ada data tahun akademik.</p>
                </div>
            @endforelse
        </div>
    @else
        {{-- Table Layout --}}
        <div class="bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 rounded-2xl overflow-hidden shadow-2xs">
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-gray-50/80 dark:bg-zinc-800/60 border-b border-gray-200/80 dark:border-zinc-700/80 text-[11px] font-bold text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                        <tr>
                            <th class="px-5 py-3.5">Nama Periode</th>
                            <th class="px-5 py-3.5 w-32">Semester</th>
                            <th class="px-5 py-3.5">Rentang Tahun</th>
                            <th class="px-5 py-3.5 w-36 text-center">Jadwal</th>
                            <th class="px-5 py-3.5 w-40">Status Sistem</th>
                            <th class="px-5 py-3.5 text-right w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-zinc-800 text-gray-800 dark:text-zinc-200">
                        @forelse ($tahun as $t)
                            <tr class="hover:bg-emerald-50/30 dark:hover:bg-zinc-800/40 transition">
                                <td class="px-5 py-3.5 font-bold text-gray-900 dark:text-zinc-100">{{ $t->nama }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="px-2.5 py-0.5 rounded-md text-[11px] font-bold {{ $t->semester === 'Ganjil' ? 'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border border-blue-200/60 dark:border-blue-800/40' : 'bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300 border border-purple-200/60 dark:border-purple-800/40' }}">
                                        Semester {{ $t->semester }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-gray-500 dark:text-zinc-400 font-mono">{{ $t->tahun_mulai }}{{ $t->tahun_selesai ? ' / ' . $t->tahun_selesai : '' }}</td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border border-blue-200/60 dark:border-blue-800/40">
                                        {{ $t->jadwal_praktikum_count ?? 0 }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    @if ($t->is_aktif)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Sedang Aktif
                                        </span>
                                    @else
                                        <button wire:click="setAktif({{ $t->id }})" class="px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-zinc-300 hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-200 border border-gray-200 dark:border-zinc-700 transition">
                                            Aktifkan
                                        </button>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-right space-x-2">
                                    <button wire:click="openEdit({{ $t->id }})" class="font-bold text-xs text-emerald-700 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 transition">Edit</button>
                                    <button wire:click="confirmDelete({{ $t->id }})" class="font-bold text-xs text-rose-600 dark:text-rose-400 hover:text-rose-700 dark:hover:text-rose-300 transition">Hapus</button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-5 py-8 text-center text-gray-400">Belum ada data tahun akademik.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{ $tahun->links() }}

    {{-- Modal Tambah / Edit --}}
    @if ($showModal)
        <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4 backdrop-blur-xs">
            <div class="bg-white dark:bg-zinc-900 rounded-3xl w-full max-w-md p-6 border border-gray-200/80 dark:border-zinc-800 shadow-2xl space-y-4 animate-in fade-in zoom-in-95 duration-150">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-zinc-800">
                    <h2 class="text-base font-bold text-gray-900 dark:text-zinc-100">{{ $editId ? 'Edit Tahun Akademik' : 'Tambah Tahun Akademik Baru' }}</h2>
                    <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 mb-1">Nama Periode</label>
                    <input type="text" wire:model="nama" placeholder="mis. 2026/2027 Ganjil" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs px-3.5 py-2.5 text-gray-900 dark:text-zinc-100 transition">
                    @error('nama') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 mb-1">Semester</label>
                    <select wire:model="semester" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs px-3.5 py-2.5 text-gray-900 dark:text-zinc-100 transition">
                        <option value="Ganjil">Semester Ganjil</option>
                        <option value="Genap">Semester Genap</option>
                    </select>
                    @error('semester') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 mb-1">Tahun Mulai</label>
                        <input type="number" wire:model="tahun_mulai" placeholder="2026" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs px-3.5 py-2.5 text-gray-900 dark:text-zinc-100 transition font-mono">
                        @error('tahun_mulai') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 mb-1">Tahun Selesai</label>
                        <input type="number" wire:model="tahun_selesai" placeholder="2027" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs px-3.5 py-2.5 text-gray-900 dark:text-zinc-100 transition font-mono">
                        @error('tahun_selesai') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="pt-2">
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" wire:model="status_aktif" class="rounded text-emerald-600 focus:ring-emerald-500 w-4 h-4">
                        <span class="text-xs font-bold text-gray-700 dark:text-zinc-300">Jadikan sebagai Periode Aktif Sistem Sekarang</span>
                    </label>
                    <p class="text-[11px] text-gray-400 mt-1">Mengaktifkan periode ini akan menonaktifkan status aktif pada periode tahun akademik lainnya secara otomatis.</p>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-gray-100 dark:border-zinc-800">
                    <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 text-xs font-semibold text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:hover:bg-zinc-800 rounded-xl transition">Batal</button>
                    <button type="button" wire:click="save" class="px-4 py-2 text-xs font-bold bg-[#0F352A] hover:bg-[#134436] dark:bg-emerald-600 dark:hover:bg-emerald-700 text-white rounded-xl shadow-xs transition">Simpan Periode</button>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Konfirmasi Hapus --}}
    @if ($confirmDeleteId)
        <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4 backdrop-blur-xs">
            <div class="bg-white dark:bg-zinc-900 rounded-3xl w-full max-w-sm p-6 border border-gray-200/80 dark:border-zinc-800 shadow-2xl space-y-4 animate-in fade-in zoom-in-95 duration-150">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center mx-auto border border-rose-100 dark:border-rose-900/40">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
                <div class="text-center">
                    <h2 class="text-base font-bold text-gray-900 dark:text-zinc-100">Hapus Tahun Akademik?</h2>
                    <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">Tahun Akademik yang masih dipakai pada Jadwal Praktikum tidak dapat dihapus.</p>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" wire:click="$set('confirmDeleteId', null)" class="w-1/2 px-4 py-2 text-xs font-semibold text-gray-500 hover:bg-gray-100 dark:hover:bg-zinc-800 rounded-xl transition">Batal</button>
                    <button type="button" wire:click="delete" class="w-1/2 px-4 py-2 text-xs font-bold bg-rose-600 hover:bg-rose-700 text-white rounded-xl shadow-xs transition">Ya, Hapus</button>
                </div>
            </div>
        </div>
    @endif

</div>
