<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-zinc-100 tracking-tight">Dosen Pengampu Praktikum</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-800 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/50">
                    {{ number_format($dosen->total()) }} Dosen
                </span>
            </div>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-zinc-400 mt-0.5">
                Direktori dosen pengampu laboratorium universitas, integrasi NIDN API Bridge PUSTIKOM, dan pemetaan fakultas.
            </p>
        </div>
        <button wire:click="openCreate" class="inline-flex items-center gap-2 bg-[#0F352A] hover:bg-[#134436] dark:bg-emerald-600 dark:hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow-xs self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Tambah Dosen</span>
        </button>
    </div>

    {{-- Feedback Alerts --}}
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

    {{-- Control Toolbar: Tabs & Filters --}}
    <div class="space-y-3">
        {{-- Super Admin: Faculty Filter Tabs --}}
        @if ($isSuperAdmin)
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 scrollbar-none">
                <button wire:click="$set('filterFakultas', '')"
                        @class([
                            'px-3.5 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap shadow-2xs',
                            'bg-[#0F352A] dark:bg-emerald-600 text-white' => $filterFakultas === '',
                            'bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 text-gray-600 dark:text-zinc-300 hover:bg-gray-50 dark:hover:bg-zinc-800' => $filterFakultas !== '',
                        ])>
                    Semua Fakultas
                </button>
                @foreach ($fakultasList as $fak)
                    <button wire:click="$set('filterFakultas', '{{ $fak->id }}')"
                            @class([
                                'px-3.5 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap shadow-2xs',
                                'bg-[#0F352A] dark:bg-emerald-600 text-white' => $filterFakultas === (string) $fak->id,
                                'bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 text-gray-600 dark:text-zinc-300 hover:bg-gray-50 dark:hover:bg-zinc-800' => $filterFakultas !== (string) $fak->id,
                            ])>
                        {{ $fak->nama_fakultas }}
                    </button>
                @endforeach
            </div>
        @endif

        {{-- Operator: Scope Filter & Directory Info --}}
        @if ($isOperator)
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white dark:bg-zinc-900 p-3 rounded-2xl border border-gray-200/80 dark:border-zinc-800 shadow-2xs">
                <div class="flex items-center gap-2">
                    <button wire:click="$set('viewScope', 'fakultas')"
                            @class([
                                'px-3 py-1.5 rounded-xl text-xs font-bold transition shadow-2xs',
                                'bg-[#0F352A] dark:bg-emerald-600 text-white' => $viewScope === 'fakultas',
                                'bg-gray-50 dark:bg-zinc-800 text-gray-600 dark:text-zinc-300 hover:bg-gray-100' => $viewScope !== 'fakultas',
                            ])>
                        Dosen {{ $userFakultas ? ($userFakultas->kode_fakultas ?: (str_starts_with($userFakultas->nama_fakultas, 'Fakultas') ? $userFakultas->nama_fakultas : 'Fakultas ' . $userFakultas->nama_fakultas)) : 'Fakultas Saya' }}
                    </button>
                    <button wire:click="$set('viewScope', 'semua')"
                            @class([
                                'px-3 py-1.5 rounded-xl text-xs font-bold transition shadow-2xs',
                                'bg-[#0F352A] dark:bg-emerald-600 text-white' => $viewScope === 'semua',
                                'bg-gray-50 dark:bg-zinc-800 text-gray-600 dark:text-zinc-300 hover:bg-gray-100' => $viewScope !== 'semua',
                            ])>
                        Seluruh Direktori Kampus
                    </button>
                </div>
                <div class="text-[11px] text-gray-500 dark:text-zinc-400 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @if ($viewScope === 'fakultas')
                        <span>Hanya menampilkan dosen yang mengajar di {{ $userFakultas?->nama_fakultas ?? 'fakultas Anda' }}.</span>
                    @else
                        <span>Melihat seluruh dosen universitas (agar tidak input ganda jika dosen lintas fakultas).</span>
                    @endif
                </div>
            </div>
        @endif

        {{-- Search & View Toggle --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="relative w-full sm:w-80">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama, NIDN, atau email..."
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
    </div>

    {{-- Main Content: Grid or Table --}}
    @if ($viewMode === 'grid')
        {{-- Card Grid Layout --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($dosen as $d)
                @php
                    $faculties = collect();
                    foreach ($d->jadwalPraktikum as $j) {
                        if ($j->mataKuliah?->programStudi?->fakultas) {
                            $faculties->push($j->mataKuliah->programStudi->fakultas->nama_fakultas);
                        } elseif ($j->ruangPraktikum?->fakultas) {
                            $faculties->push($j->ruangPraktikum->fakultas->nama_fakultas);
                        }
                    }
                    if ($d->user?->fakultas) {
                        $faculties->push($d->user->fakultas->nama_fakultas);
                    }
                    $faculties = $faculties->unique();
                    $totalJadwal = $d->jadwalPraktikum->count();
                @endphp
                <div class="bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 rounded-2xl p-5 shadow-2xs hover:border-emerald-500/40 hover:shadow-xs transition flex flex-col justify-between">
                    <div>
                        {{-- Top Badge Row --}}
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="font-mono text-xs font-bold text-emerald-800 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/60 px-2.5 py-1 rounded-lg border border-emerald-200/60 dark:border-emerald-800/60 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                                <span>{{ $d->nidn }}</span>
                            </span>

                            @if($d->status === 'aktif')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Aktif
                                </span>
                            @elseif($d->status === 'cuti')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200/60 dark:border-amber-800/40">
                                    Cuti
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-medium bg-gray-100 dark:bg-zinc-800 text-gray-500">
                                    {{ ucfirst($d->status) }}
                                </span>
                            @endif
                        </div>

                        {{-- Dosen Identity --}}
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-[#0F352A] to-emerald-700 text-white flex items-center justify-center font-black text-sm shrink-0 shadow-2xs">
                                {{ strtoupper(substr($d->nama, 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="font-bold text-sm text-gray-900 dark:text-zinc-100 leading-snug line-clamp-2">{{ $d->nama }}</h3>
                                <p class="text-[11px] text-gray-400 dark:text-zinc-500 mt-0.5 flex items-center gap-1">
                                    <span>Auth:</span>
                                    <span class="font-semibold text-emerald-700 dark:text-emerald-400">SSO PUSTIKOM (NIDN)</span>
                                </p>
                            </div>
                        </div>

                        {{-- Faculty Badges & Schedule Stats --}}
                        <div class="mt-3.5 pt-3 border-t border-gray-100 dark:border-zinc-800/80">
                            <span class="text-[10px] font-bold text-gray-400 dark:text-zinc-500 uppercase tracking-wider block mb-1.5">Penugasan Fakultas:</span>
                            <div class="flex flex-wrap items-center gap-1.5">
                                @forelse ($faculties as $fac)
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border border-blue-200/60 dark:border-blue-800/40">
                                        {{ $fac }}
                                    </span>
                                @empty
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-medium bg-gray-100 dark:bg-zinc-800 text-gray-500">
                                        Belum Terplot Jadwal
                                    </span>
                                @endforelse

                                @if ($totalJadwal > 0)
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40">
                                        {{ $totalJadwal }} Jadwal Praktikum
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Actions & Email row --}}
                    <div class="pt-3.5 mt-3.5 border-t border-gray-100 dark:border-zinc-800 flex items-center justify-between gap-2">
                        <span class="text-[11px] text-gray-400 dark:text-zinc-500 truncate font-mono" title="{{ $d->email }}">
                            {{ $d->email ?: "{$d->nidn}@uinssc.ac.id" }}
                        </span>

                        <div class="flex items-center gap-1 shrink-0">
                            <button wire:click="openEdit({{ $d->id }})" class="p-1.5 text-emerald-700 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-zinc-800 rounded-lg transition" title="Edit Data Dosen">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <button wire:click="confirmDelete({{ $d->id }})" class="p-1.5 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-zinc-800 rounded-lg transition" title="Hapus Dosen">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white dark:bg-zinc-900 rounded-2xl p-10 text-center border border-gray-200/80 dark:border-zinc-800">
                    <div class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-zinc-800 flex items-center justify-center mx-auto text-gray-400 mb-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                    </div>
                    <p class="text-sm font-bold text-gray-700 dark:text-zinc-300">Tidak ada data dosen yang sesuai.</p>
                    <p class="text-xs text-gray-400 mt-0.5">Silakan sesuaikan filter pencarian atau tambah dosen baru.</p>
                </div>
            @endforelse
        </div>
    @else
        {{-- Table Layout --}}
        <div class="bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 rounded-2xl shadow-2xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-gray-50/80 dark:bg-zinc-800/60 border-b border-gray-200/80 dark:border-zinc-700/80 text-[11px] font-bold text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                        <tr>
                            <th class="px-5 py-3.5 w-36">NIDN</th>
                            <th class="px-5 py-3.5">Nama Dosen & Gelar</th>
                            <th class="px-5 py-3.5">Penugasan Fakultas</th>
                            <th class="px-5 py-3.5">Akun SSO / Email</th>
                            <th class="px-5 py-3.5 w-28">Status</th>
                            <th class="px-5 py-3.5 text-right w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-zinc-800 text-gray-800 dark:text-zinc-200">
                        @forelse ($dosen as $d)
                            @php
                                $faculties = collect();
                                foreach ($d->jadwalPraktikum as $j) {
                                    if ($j->mataKuliah?->programStudi?->fakultas) {
                                        $faculties->push($j->mataKuliah->programStudi->fakultas->nama_fakultas);
                                    } elseif ($j->ruangPraktikum?->fakultas) {
                                        $faculties->push($j->ruangPraktikum->fakultas->nama_fakultas);
                                    }
                                }
                                if ($d->user?->fakultas) {
                                    $faculties->push($d->user->fakultas->nama_fakultas);
                                }
                                $faculties = $faculties->unique();
                            @endphp
                            <tr class="hover:bg-emerald-50/30 dark:hover:bg-zinc-800/40 transition">
                                <td class="px-5 py-3.5 font-mono font-bold text-emerald-800 dark:text-emerald-300">
                                    <span class="px-2 py-0.5 rounded bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200/50 dark:border-emerald-800/40">{{ $d->nidn }}</span>
                                </td>
                                <td class="px-5 py-3.5 font-bold text-gray-900 dark:text-zinc-100">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-lg bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 flex items-center justify-center font-bold text-[10px] shrink-0">
                                            {{ strtoupper(substr($d->nama, 0, 1)) }}
                                        </div>
                                        <span>{{ $d->nama }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex flex-wrap items-center gap-1">
                                        @forelse ($faculties as $fac)
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border border-blue-200/60 dark:border-blue-800/40">
                                                {{ $fac }}
                                            </span>
                                        @empty
                                            <span class="text-gray-400 text-[11px]">—</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-gray-500 dark:text-zinc-400 font-mono text-[11px]">
                                    {{ $d->email ?: "{$d->nidn}@uinssc.ac.id" }}
                                </td>
                                <td class="px-5 py-3.5">
                                    @if($d->status === 'aktif')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Aktif
                                        </span>
                                    @elseif($d->status === 'cuti')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200/60 dark:border-amber-800/40">
                                            Cuti
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-gray-100 dark:bg-zinc-800 text-gray-500">
                                            {{ ucfirst($d->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-right space-x-2">
                                    <button wire:click="openEdit({{ $d->id }})" class="font-bold text-xs text-emerald-700 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 transition">Edit</button>
                                    <button wire:click="confirmDelete({{ $d->id }})" class="font-bold text-xs text-rose-600 dark:text-rose-400 hover:text-rose-700 dark:hover:text-rose-300 transition">Hapus</button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-5 py-8 text-center text-gray-400">Belum ada data dosen.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{ $dosen->links() }}

    {{-- Create / Edit Modal --}}
    @if ($showModal)
        <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4 backdrop-blur-xs">
            <div class="bg-white dark:bg-zinc-900 rounded-3xl w-full max-w-md p-6 border border-gray-200/80 dark:border-zinc-800 shadow-2xl space-y-4 animate-in fade-in zoom-in-95 duration-150">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-zinc-800">
                    <div>
                        <h2 class="text-base font-bold text-gray-900 dark:text-zinc-100">{{ $editId ? 'Edit Data Dosen' : 'Tambah Dosen Baru' }}</h2>
                        <p class="text-xs text-gray-400">Sinkronisasi data pengampu praktikum laboratorium.</p>
                    </div>
                    <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 mb-1">
                        NIDN (Nomor Induk Dosen Nasional) <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" wire:model="nidn" placeholder="mis. 0945198705" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs px-3.5 py-2.5 text-gray-900 dark:text-zinc-100 transition font-mono">
                    <p class="text-[11px] text-gray-400 mt-1">NIDN digunakan dosen sebagai akun login portal/SSO via API Bridge PUSTIKOM.</p>
                    @error('nidn') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 mb-1">
                        Nama Lengkap & Gelar <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" wire:model="nama" placeholder="mis. Dr. Ahmad Fauzi, M.Kom." class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs px-3.5 py-2.5 text-gray-900 dark:text-zinc-100 transition">
                    @error('nama') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300">Email (Opsional)</label>
                        <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-semibold">Otomatis jika kosong</span>
                    </div>
                    <input type="email" wire:model="email" placeholder="Otomatis {{ $nidn ?: '{nidn}' }}@uinssc.ac.id" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs px-3.5 py-2.5 text-gray-900 dark:text-zinc-100 transition">
                    <p class="text-[11px] text-gray-400 mt-1">Operator tidak perlu menanyakan Gmail pribadi dosen; login autentikasi dosen menggunakan NIDN.</p>
                    @error('email') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 mb-1">Status Keaktifan</label>
                    <select wire:model="status" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs px-3.5 py-2.5 text-gray-900 dark:text-zinc-100 transition">
                        <option value="aktif">Aktif Mengajar</option>
                        <option value="cuti">Cuti</option>
                        <option value="nonaktif">Nonaktif</option>
                        <option value="keluar">Keluar / Pindah</option>
                    </select>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-gray-100 dark:border-zinc-800">
                    <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 text-xs font-semibold text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:hover:bg-zinc-800 rounded-xl transition">Batal</button>
                    <button type="button" wire:click="save" class="px-4 py-2 text-xs font-bold bg-[#0F352A] hover:bg-[#134436] dark:bg-emerald-600 dark:hover:bg-emerald-700 text-white rounded-xl shadow-xs transition">Simpan Dosen</button>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    @if ($confirmDeleteId)
        <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4 backdrop-blur-xs">
            <div class="bg-white dark:bg-zinc-900 rounded-3xl w-full max-w-sm p-6 border border-gray-200/80 dark:border-zinc-800 shadow-2xl space-y-4 animate-in fade-in zoom-in-95 duration-150">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center mx-auto border border-rose-100 dark:border-rose-900/40">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
                <div class="text-center">
                    <h2 class="text-base font-bold text-gray-900 dark:text-zinc-100">Hapus Dosen?</h2>
                    <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">Dosen yang masih memiliki jadwal aktif tidak dapat dihapus demi integritas data.</p>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" wire:click="$set('confirmDeleteId', null)" class="w-1/2 px-4 py-2 text-xs font-semibold text-gray-500 hover:bg-gray-100 dark:hover:bg-zinc-800 rounded-xl transition">Batal</button>
                    <button type="button" wire:click="delete" class="w-1/2 px-4 py-2 text-xs font-bold bg-rose-600 hover:bg-rose-700 text-white rounded-xl shadow-xs transition">Ya, Hapus</button>
                </div>
            </div>
        </div>
    @endif

</div>
