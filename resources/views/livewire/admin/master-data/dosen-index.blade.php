<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-zinc-100 tracking-tight">Dosen & Tutor Praktikum</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-800 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/50">
                    {{ number_format($dosen->total()) }} Pengampu
                </span>
            </div>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-zinc-400 mt-0.5">
                Direktori dosen pengampu & tutor lab, autentikasi NIDN (Dosen) & Gmail pribadi (Tutor non-NIDN), serta pemetaan fakultas.
            </p>
        </div>
        <button wire:click="openCreate" class="inline-flex items-center gap-2 bg-[#0F352A] hover:bg-[#134436] dark:bg-emerald-600 dark:hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow-xs self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Tambah Pengampu / Tutor</span>
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
            <div class="flex flex-wrap items-center gap-1.5">
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

        {{-- Kategori Filter Tabs --}}
        <div class="flex flex-wrap items-center gap-1.5">
            <button wire:click="$set('filterKategori', '')"
                    @class([
                        'px-3 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap shadow-2xs',
                        'bg-emerald-800 dark:bg-emerald-600 text-white' => $filterKategori === '',
                        'bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 text-gray-600 dark:text-zinc-300 hover:bg-gray-50 dark:hover:bg-zinc-800' => $filterKategori !== '',
                    ])>
                Semua Kategori
            </button>
            <button wire:click="$set('filterKategori', 'dosen')"
                    @class([
                        'px-3 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap shadow-2xs',
                        'bg-emerald-800 dark:bg-emerald-600 text-white' => $filterKategori === 'dosen',
                        'bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 text-gray-600 dark:text-zinc-300 hover:bg-gray-50 dark:hover:bg-zinc-800' => $filterKategori !== 'dosen',
                    ])>
                Dosen Pengampu (NIDN)
            </button>
            <button wire:click="$set('filterKategori', 'tutor')"
                    @class([
                        'px-3 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap shadow-2xs',
                        'bg-purple-700 dark:bg-purple-600 text-white' => $filterKategori === 'tutor',
                        'bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 text-gray-600 dark:text-zinc-300 hover:bg-gray-50 dark:hover:bg-zinc-800' => $filterKategori !== 'tutor',
                    ])>
                Tutor Lab (Gmail)
            </button>
            <button wire:click="$set('filterKategori', 'asisten')"
                    @class([
                        'px-3 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap shadow-2xs',
                        'bg-sky-700 dark:bg-sky-600 text-white' => $filterKategori === 'asisten',
                        'bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 text-gray-600 dark:text-zinc-300 hover:bg-gray-50 dark:hover:bg-zinc-800' => $filterKategori !== 'asisten',
                    ])>
                Asisten Lab
            </button>
        </div>

        {{-- Search & View Toggle --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
            <div class="relative flex-1 min-w-0 sm:max-w-md">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama, NIDN/NIM, atau email..."
                       class="w-full pl-10 pr-4 py-2 rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-2xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs text-gray-900 dark:text-zinc-100 placeholder:text-gray-400 transition">
            </div>

            <div class="flex items-center gap-1 self-end sm:self-auto bg-gray-100 dark:bg-zinc-800 p-1 rounded-xl shrink-0">
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
                            <div class="flex items-center gap-1.5 flex-wrap">
                                @if($d->kategori === 'tutor')
                                    <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold bg-purple-50 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 border border-purple-200/60 dark:border-purple-800/60">
                                        Tutor Lab
                                    </span>
                                @elseif($d->kategori === 'asisten')
                                    <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold bg-sky-50 dark:bg-sky-950/60 text-sky-700 dark:text-sky-300 border border-sky-200/60 dark:border-sky-800/60">
                                        Asisten Lab
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60">
                                        Dosen
                                    </span>
                                @endif

                                <span class="font-mono text-[11px] font-bold text-gray-700 dark:text-zinc-300 bg-gray-50 dark:bg-zinc-800 px-2 py-0.5 rounded-lg border border-gray-200/70 dark:border-zinc-700">
                                    {{ $d->nidn }}
                                </span>
                            </div>

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
                            <div @class([
                                'w-10 h-10 rounded-2xl text-white flex items-center justify-center font-black text-sm shrink-0 shadow-2xs',
                                'bg-gradient-to-br from-[#0F352A] to-emerald-700' => $d->kategori === 'dosen',
                                'bg-gradient-to-br from-purple-800 to-indigo-600' => $d->kategori === 'tutor',
                                'bg-gradient-to-br from-sky-700 to-cyan-600' => $d->kategori === 'asisten',
                            ])>
                                {{ strtoupper(substr($d->nama, 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="font-bold text-sm text-gray-900 dark:text-zinc-100 leading-snug line-clamp-2">{{ $d->nama }}</h3>
                                <p class="text-[11px] text-gray-400 dark:text-zinc-500 mt-0.5 flex items-center gap-1">
                                    <span>Auth:</span>
                                    @if($d->kategori === 'tutor' || $d->kategori === 'asisten')
                                        <span class="font-semibold text-purple-700 dark:text-purple-400">Login Gmail / Password</span>
                                    @else
                                        <span class="font-semibold text-emerald-700 dark:text-emerald-400">SSO PUSTIKOM (NIDN)</span>
                                    @endif
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
                            <th class="px-5 py-3.5 w-36">Identitas / NIDN</th>
                            <th class="px-5 py-3.5">Nama & Kategori</th>
                            <th class="px-5 py-3.5">Penugasan Fakultas</th>
                            <th class="px-5 py-3.5">Akun Login / Email</th>
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
                                <td class="px-5 py-3.5 font-mono font-bold text-gray-800 dark:text-zinc-200">
                                    <span class="px-2 py-0.5 rounded bg-gray-100 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700">{{ $d->nidn }}</span>
                                </td>
                                <td class="px-5 py-3.5 font-bold text-gray-900 dark:text-zinc-100">
                                    <div class="flex items-center gap-2.5">
                                        <div @class([
                                            'w-7 h-7 rounded-lg text-white flex items-center justify-center font-bold text-[10px] shrink-0',
                                            'bg-[#0F352A] dark:bg-emerald-700' => $d->kategori === 'dosen',
                                            'bg-purple-700 dark:bg-purple-600' => $d->kategori === 'tutor',
                                            'bg-sky-700 dark:bg-sky-600' => $d->kategori === 'asisten',
                                        ])>
                                            {{ strtoupper(substr($d->nama, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-900 dark:text-zinc-100">{{ $d->nama }}</div>
                                            <div class="mt-0.5">
                                                @if($d->kategori === 'tutor')
                                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-purple-50 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 border border-purple-200/60 dark:border-purple-800/60">
                                                        Tutor Lab
                                                    </span>
                                                @elseif($d->kategori === 'asisten')
                                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-sky-50 dark:bg-sky-950/60 text-sky-700 dark:text-sky-300 border border-sky-200/60 dark:border-sky-800/60">
                                                        Asisten Lab
                                                    </span>
                                                @else
                                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60">
                                                        Dosen Pengampu
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
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
                                <td class="px-5 py-3.5 text-gray-500 dark:text-zinc-400 text-[11px]">
                                    <span class="font-mono">{{ $d->email ?: "{$d->nidn}@uinssc.ac.id" }}</span>
                                    <div class="text-[10px] text-gray-400 dark:text-zinc-500 mt-0.5">
                                        @if($d->kategori === 'tutor' || $d->kategori === 'asisten')
                                            <span class="text-purple-600 dark:text-purple-400 font-semibold">Login Gmail / Password</span>
                                        @else
                                            <span class="text-emerald-600 dark:text-emerald-400 font-semibold">SSO PUSTIKOM (NIDN)</span>
                                        @endif
                                    </div>
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
                            <tr><td colspan="6" class="px-5 py-8 text-center text-gray-400">Belum ada data dosen/tutor.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{ $dosen->links() }}

    {{-- Create / Edit Modal --}}
    @if ($showModal)
        <div class="fixed inset-0 bg-black/60 z-50 overflow-y-auto p-4 sm:p-6 backdrop-blur-xs">
          <div class="flex min-h-full items-start sm:items-center justify-center py-4 sm:py-6">
            <div class="bg-white dark:bg-zinc-900 rounded-3xl w-full max-w-md p-6 border border-gray-200/80 dark:border-zinc-800 shadow-2xl space-y-4 animate-in fade-in zoom-in-95 duration-150" autocomplete="off">
                {{-- Hidden fields to prevent browser autofill --}}
                <input type="text" name="prevent_autofill_1" style="display:none" autocomplete="off">
                <input type="password" name="prevent_autofill_2" style="display:none" autocomplete="new-password">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-zinc-800">
                    <div>
                        <h2 class="text-base font-bold text-gray-900 dark:text-zinc-100">{{ $editId ? 'Edit Data Pengampu' : 'Tambah Pengampu / Tutor Baru' }}</h2>
                        <p class="text-xs text-gray-400">Kelola dosen NIDN atau akun Gmail tutor praktikum laboratorium.</p>
                    </div>
                    <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Pilihan Kategori --}}
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 mb-1.5">
                        Kategori Pengampu <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-3 gap-2">
                        <label @class([
                            'cursor-pointer border rounded-xl p-2.5 flex flex-col items-center text-center transition',
                            'border-emerald-600 bg-emerald-50/50 dark:bg-emerald-950/30 text-emerald-900 dark:text-emerald-200' => $kategori === 'dosen',
                            'border-gray-200 dark:border-zinc-800 bg-white dark:bg-zinc-800/50 text-gray-600 dark:text-zinc-400 hover:bg-gray-50' => $kategori !== 'dosen',
                        ])>
                            <input type="radio" wire:model.live="kategori" value="dosen" class="sr-only">
                            <span class="text-[11px] font-bold mt-1">Dosen</span>
                            <span class="text-[9px] text-gray-400">Login NIDN</span>
                        </label>
                        <label @class([
                            'cursor-pointer border rounded-xl p-2.5 flex flex-col items-center text-center transition',
                            'border-purple-600 bg-purple-50/50 dark:bg-purple-950/30 text-purple-900 dark:text-purple-200' => $kategori === 'tutor',
                            'border-gray-200 dark:border-zinc-800 bg-white dark:bg-zinc-800/50 text-gray-600 dark:text-zinc-400 hover:bg-gray-50' => $kategori !== 'tutor',
                        ])>
                            <input type="radio" wire:model.live="kategori" value="tutor" class="sr-only">
                            <span class="text-[11px] font-bold mt-1">Tutor Lab</span>
                            <span class="text-[9px] text-purple-600 dark:text-purple-400 font-semibold">Login Gmail</span>
                        </label>
                        <label @class([
                            'cursor-pointer border rounded-xl p-2.5 flex flex-col items-center text-center transition',
                            'border-sky-600 bg-sky-50/50 dark:bg-sky-950/30 text-sky-900 dark:text-sky-200' => $kategori === 'asisten',
                            'border-gray-200 dark:border-zinc-800 bg-white dark:bg-zinc-800/50 text-gray-600 dark:text-zinc-400 hover:bg-gray-50' => $kategori !== 'asisten',
                        ])>
                            <input type="radio" wire:model.live="kategori" value="asisten" class="sr-only">
                            <span class="text-[11px] font-bold mt-1">Asisten Lab</span>
                            <span class="text-[9px] text-sky-600 dark:text-sky-400 font-semibold">Login Gmail</span>
                        </label>
                    </div>
                </div>

                {{-- Nama Lengkap --}}
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 mb-1">
                        {{ $kategori === 'dosen' ? 'Nama Lengkap & Gelar Dosen' : 'Nama Lengkap Tutor / Asisten' }} <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" wire:model="nama" id="dosen-form-nama" name="dosen_nama_{{ $editId ?? 'new' }}" autocomplete="off" placeholder="{{ $kategori === 'dosen' ? 'mis. Dr. Ahmad Fauzi, M.Kom.' : 'mis. M. Rizki Pratama, S.Kom.' }}" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs px-3.5 py-2.5 text-gray-900 dark:text-zinc-100 transition">
                    @error('nama') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                @if($kategori === 'dosen')
                    {{-- NIDN untuk Dosen --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 mb-1">
                            NIDN (Nomor Induk Dosen Nasional) <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" wire:model="nidn" id="dosen-form-nidn" name="dosen_nidn_{{ $editId ?? 'new' }}" autocomplete="off" placeholder="mis. 0945198705" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs px-3.5 py-2.5 text-gray-900 dark:text-zinc-100 transition font-mono">
                        <p class="text-[11px] text-gray-400 mt-1">NIDN digunakan dosen sebagai akun login portal.</p>
                        @error('nidn') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Email Dosen (Opsional) --}}
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300">Email Kampus (Opsional)</label>
                            <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-semibold">Otomatis jika kosong</span>
                        </div>
                        <input type="email" wire:model="email" id="dosen-form-email-kampus" name="dosen_email_kampus_{{ $editId ?? 'new' }}" autocomplete="nope" placeholder="Email" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs px-3.5 py-2.5 text-gray-900 dark:text-zinc-100 transition">
                        @error('email') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                @else
                    {{-- Email Pribadi / Gmail untuk Tutor --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 mb-1">
                            Email Pribadi (Gmail) untuk Login <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" wire:model="email" id="tutor-form-email-gmail" name="tutor_email_gmail_{{ $editId ?? 'new' }}" autocomplete="nope" placeholder="mis. tutor.informatika@gmail.com" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs px-3.5 py-2.5 text-gray-900 dark:text-zinc-100 transition font-mono">
                        <p class="text-[11px] text-purple-600 dark:text-purple-400 mt-1">Daftarkan alamat Gmail tutor agar tutor dapat login dan memantau jadwal praktikumnya.</p>
                        @error('email') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Kata Sandi Tutor --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 mb-1">
                            Kata Sandi Akun {{ $editId ? '(Kosongkan jika tidak diubah)' : '(Default: password)' }}
                        </label>
                        <input type="password" wire:model="password" id="tutor-form-password" name="tutor_password_{{ $editId ?? 'new' }}" autocomplete="new-password" placeholder="{{ $editId ? 'Masukkan kata sandi baru jika ingin mengubah' : 'Default: password' }}" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs px-3.5 py-2.5 text-gray-900 dark:text-zinc-100 transition">
                        <p class="text-[11px] text-gray-400 mt-1">Tutor dapat masuk ke portal menggunakan email di atas dan kata sandi ini.</p>
                        @error('password') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- NIM / Kode Tutor (Opsional) --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 mb-1">
                            NIM / Kode Identitas Tutor (Opsional)
                        </label>
                        <input type="text" wire:model="nidn" id="tutor-form-nim-kode" name="tutor_nim_kode_{{ $editId ?? 'new' }}" autocomplete="nope" placeholder="Kosongkan jika otomatis (TUTOR-XXXX)" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs px-3.5 py-2.5 text-gray-900 dark:text-zinc-100 transition font-mono">
                        @error('nidn') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                @endif

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
                    <button type="button" wire:click="save" class="px-4 py-2 text-xs font-bold bg-[#0F352A] hover:bg-[#134436] dark:bg-emerald-600 dark:hover:bg-emerald-700 text-white rounded-xl shadow-xs transition">
                        {{ $editId ? 'Perbarui Pengampu' : 'Simpan Pengampu' }}
                    </button>
                </div>
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
