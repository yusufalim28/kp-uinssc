<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-zinc-100 tracking-tight">
                Notifikasi Saya
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-zinc-400 mt-0.5">
                {{ $jumlahBelumDibaca }} notifikasi perubahan jadwal belum dibaca.
            </p>
        </div>

        @if ($jumlahBelumDibaca > 0)
            <button wire:click="markAllAsRead"
                class="text-xs text-emerald-700 dark:text-emerald-400 font-bold hover:underline self-start sm:self-auto">
                Tandai semua sudah dibaca
            </button>
        @endif
    </div>

    {{-- Daftar Notifikasi --}}
    <div class="space-y-3">
        @forelse ($notifikasi as $n)
            <div wire:click="markAsRead('{{ $n->id }}')"
                @class([
                    'border rounded-3xl p-5 cursor-pointer transition shadow-2xs',
                    'bg-white dark:bg-zinc-900 border-emerald-500/40 dark:border-emerald-500/50 hover:border-emerald-500' => is_null($n->read_at),
                    'bg-white/70 dark:bg-zinc-900/60 border-gray-200/80 dark:border-zinc-800 hover:border-gray-300 dark:hover:border-zinc-700' => ! is_null($n->read_at),
                ])>

                <div class="flex items-start justify-between gap-3">
                    <div class="space-y-1">
                        @if ($n->data['status'] === 'disetujui')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Pengajuan Disetujui
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800/40">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                Pengajuan Ditolak
                            </span>
                        @endif

                        <h3 class="text-sm font-bold text-gray-900 dark:text-zinc-100 pt-1">
                            Mata kuliah: {{ $n->data['nama_mk'] }}
                        </h3>

                        <p class="text-xs text-gray-600 dark:text-zinc-300">
                            {{ $n->data['perubahan'] }}
                        </p>

                        @if ($n->data['status'] === 'ditolak' && $n->data['catatan_penolakan'])
                            <p class="text-xs text-rose-600 dark:text-rose-400 italic pt-1">
                                "{{ $n->data['catatan_penolakan'] }}"
                            </p>
                        @endif
                    </div>

                    {{-- Indikator Belum Dibaca --}}
                    @if (is_null($n->read_at))
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 dark:bg-emerald-400 shrink-0 mt-1.5" title="Belum dibaca"></span>
                    @endif
                </div>

                {{-- Waktu Notifikasi --}}
                <p class="text-[11px] text-gray-400 dark:text-zinc-500 mt-3 font-mono">
                    {{ $n->created_at->diffForHumans() }}
                </p>
            </div>
        @empty
            {{-- Kondisi Kosong --}}
            <div class="bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 rounded-3xl p-10 text-center text-gray-400 dark:text-zinc-500">
                Belum ada notifikasi aktivitas.
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    {{ $notifikasi->links() }}

</div>
