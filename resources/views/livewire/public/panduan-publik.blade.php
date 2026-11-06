<div class="space-y-6 max-w-5xl mx-auto pb-12">
    {{-- Header Banner Publik --}}
    <div class="bg-gradient-to-r from-primary to-primary-dark rounded-2xl p-6 sm:p-8 text-white shadow-md relative overflow-hidden">
        <div class="relative z-10 max-w-3xl space-y-2">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/15 text-white backdrop-blur-xs border border-white/20">
                    Informasi Terbuka Mahasiswa & Sivitas Akademika
                </span>
                @auth
                    @role('super-admin')
                        <a href="{{ route('panduan.manage') }}"
                           class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-amber-400 text-gray-900 hover:bg-amber-300 transition shadow-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            <span>Edit Isi Panduan Ini</span>
                        </a>
                    @endrole
                @endauth
            </div>
            <h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold tracking-tight">Tata Tertib & Ketentuan Pelaksanaan Praktikum</h1>
            <p class="text-white/85 text-xs sm:text-sm leading-relaxed">
                Panduan tata tertib berpakaian, kedisiplinan kehadiran, etika pemanfaatan fasilitas laboratorium, serta panduan membaca jadwal praktikum di lingkungan universitas.
            </p>
        </div>
    </div>

    {{-- Accordion Dinamis Publik --}}
    <div class="space-y-3.5">
        @forelse ($panduan as $index => $item)
            @php $sectionNum = $index + 1; @endphp
            <div class="bg-white dark:bg-zinc-900 border border-gray-200/90 dark:border-zinc-800 rounded-2xl shadow-xs overflow-hidden transition">
                <button wire:click="toggleSection({{ $sectionNum }})"
                        type="button"
                        class="w-full p-4 sm:p-5 flex items-center justify-between text-left hover:bg-gray-50/60 dark:hover:bg-zinc-800/50 transition gap-3">
                    <div class="flex items-center gap-3.5">
                        <span class="w-8 h-8 rounded-xl {{ $openSection === $sectionNum ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300' }} flex items-center justify-center font-bold text-xs shrink-0">
                            {{ $item->urutan }}
                        </span>
                        <div>
                            <h2 class="font-bold text-sm sm:text-base text-gray-900 dark:text-zinc-100 leading-snug">{{ $item->judul }}</h2>
                            @if ($item->subjudul)
                                <p class="text-xs text-gray-500 dark:text-zinc-400 hidden sm:block mt-0.5">{{ $item->subjudul }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="w-7 h-7 rounded-lg bg-gray-100 dark:bg-zinc-800 flex items-center justify-center shrink-0 transition {{ $openSection === $sectionNum ? 'rotate-180 bg-primary/10 text-primary' : 'text-gray-400' }}">
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
            <div class="bg-white dark:bg-zinc-900 rounded-2xl p-8 text-center text-gray-400 dark:text-zinc-500 border border-gray-100 dark:border-zinc-800">
                Belum ada butir panduan yang diterbitkan.
            </div>
        @endforelse
    </div>
</div>
