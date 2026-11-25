<div class="space-y-6 max-w-5xl mx-auto pb-12">
    {{-- Header Banner Pengelola --}}
    <div class="bg-gradient-to-r from-[#0B2E23] to-[#124334] rounded-3xl p-6 sm:p-8 text-white shadow-md relative overflow-hidden">
        <div class="relative z-10 max-w-3xl space-y-2.5">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/10 text-emerald-200 backdrop-blur-xs border border-white/15">
                    Buku Pedoman Resmi Pengelola Sistem
                </span>
                @role('super-admin')
                    <a href="{{ route('panduan.manage') }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-400 text-[#0B2E23] hover:bg-emerald-300 transition shadow-xs">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        <span>Kelola Pedoman (Super Admin)</span>
                    </a>
                @endrole
            </div>
            <h1 class="text-xl sm:text-2xl md:text-3xl font-black tracking-tight text-white">Pedoman Alur Kerja & Petunjuk Penggunaan Sistem</h1>
            <p class="text-emerald-100/85 text-xs sm:text-sm leading-relaxed">
                Panduan menyeluruh mulai dari alur kerja saat pertama kali login, verifikasi NIDN & mata kuliah, input master data, plotting jadwal lab, hingga petunjuk fitur per halaman.
            </p>
        </div>
    </div>

    {{-- Role Tabs Selector --}}
    <div class="bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 rounded-2xl p-2 shadow-2xs">
        <div class="flex items-center gap-1.5 overflow-x-auto [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">
            {{-- Tab Khusus Role yang Sedang Login --}}
            <button wire:click="setTab('{{ $userRoleKey }}')"
                    type="button"
                    class="px-4 py-2.5 rounded-xl text-xs font-bold transition shrink-0 flex items-center gap-2 {{ $activeTab === $userRoleKey ? 'bg-[#0B2E23] dark:bg-emerald-600 text-white shadow-xs' : 'text-gray-600 dark:text-zinc-400 hover:bg-gray-100 dark:hover:bg-zinc-800' }}">
                @if ($userRoleKey === 'operator')
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                @elseif ($userRoleKey === 'ketua-lab')
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                @elseif ($userRoleKey === 'dosen')
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                @else
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                @endif
                <span>{{ $userRoleLabel }}</span>
            </button>

            {{-- Tab Petunjuk Tiap Halaman --}}
            <button wire:click="setTab('fitur')"
                    type="button"
                    class="px-4 py-2.5 rounded-xl text-xs font-bold transition shrink-0 flex items-center gap-2 {{ $activeTab === 'fitur' ? 'bg-[#0B2E23] dark:bg-emerald-600 text-white shadow-xs' : 'text-gray-600 dark:text-zinc-400 hover:bg-gray-100 dark:hover:bg-zinc-800' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                <span>Petunjuk Tiap Halaman</span>
            </button>
        </div>
    </div>

    {{-- Ringkasan Informasi Tab Aktif --}}
    <div class="px-1 text-xs text-gray-500 dark:text-zinc-400 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <span class="font-bold text-gray-700 dark:text-zinc-200">Panduan Ditampilkan:</span>
            <span class="px-2.5 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-300 font-bold uppercase tracking-wider text-[11px]">
                {{ $activeTab === 'fitur' ? 'Petunjuk Tiap Halaman' : $userRoleLabel }}
            </span>
            <span>•</span>
            <span>{{ $panduan->count() }} modul panduan tersedia</span>
        </div>
    </div>

    {{-- Accordion Dinamis Pedoman --}}
    <div class="space-y-3.5">
        @forelse ($panduan as $index => $item)
            @php $sectionNum = $index + 1; @endphp
            <div class="bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 rounded-2xl shadow-2xs overflow-hidden transition">
                <button wire:click="toggleSection({{ $sectionNum }})"
                        type="button"
                        class="w-full p-4 sm:p-5 flex items-center justify-between text-left hover:bg-gray-50/60 dark:hover:bg-zinc-800/50 transition gap-3">
                    <div class="flex items-center gap-3.5">
                        <span class="w-8 h-8 rounded-xl {{ $openSection === $sectionNum ? 'bg-[#0B2E23] dark:bg-emerald-600 text-white' : 'bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300' }} flex items-center justify-center font-bold text-xs shrink-0 font-mono">
                            {{ $item->urutan }}
                        </span>
                        <div>
                            <h2 class="font-bold text-sm sm:text-base text-gray-900 dark:text-zinc-100 leading-snug">{{ $item->judul }}</h2>
                            @if ($item->subjudul)
                                <p class="text-xs text-gray-500 dark:text-zinc-400 hidden sm:block mt-0.5">{{ $item->subjudul }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="w-7 h-7 rounded-lg bg-gray-100 dark:bg-zinc-800 flex items-center justify-center shrink-0 transition {{ $openSection === $sectionNum ? 'rotate-180 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300' : 'text-gray-400' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </button>

                @if ($openSection === $sectionNum)
                    <div class="p-5 pt-0 border-t border-gray-100 dark:border-zinc-800 text-xs sm:text-sm text-gray-700 dark:text-zinc-300 leading-relaxed pt-3">
                        {!! $item->konten !!}
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white dark:bg-zinc-900 rounded-2xl p-8 text-center text-gray-400 dark:text-zinc-500 border border-gray-200/80 dark:border-zinc-800">
                Belum ada dokumen panduan pada kategori ini.
            </div>
        @endforelse
    </div>
</div>
