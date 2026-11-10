<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-zinc-100 tracking-tight">Tempat Sampah — Data Terhapus</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 dark:bg-rose-950/50 text-rose-700 dark:text-rose-300 border border-rose-200/60 dark:border-rose-800/50">
                    {{ number_format($totalSampahCount) }} Data
                </span>
            </div>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-zinc-400 mt-0.5">
                Data yang dihapus dari Master Data atau Jadwal tersimpan aman di sini. Anda dapat memulihkan atau menghapusnya secara permanen.
            </p>
        </div>

        {{-- Bulk Actions (Super Admin / Authorized) --}}
        @if ($totalSampahCount > 0)
            <div class="flex items-center gap-2 self-start sm:self-auto">
                <button wire:click="restoreAll"
                        wire:confirm="Pulihkan semua data di tempat sampah yang sedang ditampilkan?"
                        class="inline-flex items-center gap-1.5 bg-emerald-50 dark:bg-emerald-950/50 hover:bg-emerald-100 dark:hover:bg-emerald-900/50 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 text-xs font-bold px-3.5 py-2.5 rounded-xl transition shadow-2xs">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span>Pulihkan Semua</span>
                </button>
                <button wire:click="forceDeleteAll"
                        wire:confirm="PERINGATAN: Kosongkan tempat sampah? Seluruh data yang dihapus permanen TIDAK BISA dikembalikan lagi!"
                        class="inline-flex items-center gap-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold px-3.5 py-2.5 rounded-xl transition shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    <span>Kosongkan Sampah</span>
                </button>
            </div>
        @endif
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 text-emerald-800 dark:text-emerald-300 text-xs rounded-xl px-4 py-3 shadow-2xs flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Filter jenis --}}
    <div class="flex flex-wrap items-center gap-2">
        <button wire:click="$set('filterJenis', '')"
                @class([
                    'px-3.5 py-1.5 rounded-xl text-xs font-bold transition shadow-2xs',
                    'bg-[#0F352A] dark:bg-emerald-600 text-white' => $filterJenis === '',
                    'bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 text-gray-600 dark:text-zinc-300 hover:bg-gray-50 dark:hover:bg-zinc-800' => $filterJenis !== '',
                ])>
            Semua Data
        </button>
        @foreach ($jenisMap as $key => $config)
            <button wire:click="$set('filterJenis', '{{ $key }}')"
                    @class([
                        'px-3.5 py-1.5 rounded-xl text-xs font-bold transition shadow-2xs',
                        'bg-[#0F352A] dark:bg-emerald-600 text-white' => $filterJenis === $key,
                        'bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 text-gray-600 dark:text-zinc-300 hover:bg-gray-50 dark:hover:bg-zinc-800' => $filterJenis !== $key,
                    ])>
                {{ $config['label'] }}
            </button>
        @endforeach
    </div>

    {{-- Desktop Table --}}
    <div class="hidden md:block bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 rounded-2xl overflow-hidden shadow-2xs">
        <table class="w-full text-xs text-left">
            <thead class="bg-gray-50/80 dark:bg-zinc-800/60 border-b border-gray-200/80 dark:border-zinc-700/80 text-[11px] font-bold text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-3.5 w-40">Jenis Data</th>
                    <th class="px-5 py-3.5">Nama / Keterangan</th>
                    <th class="px-5 py-3.5 w-48">Dihapus Pada</th>
                    <th class="px-5 py-3.5 text-right w-48">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-zinc-800 text-gray-800 dark:text-zinc-200">
                @forelse ($items as $item)
                    <tr class="hover:bg-emerald-50/30 dark:hover:bg-zinc-800/40 transition">
                        <td class="px-5 py-3.5">
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300 border border-gray-200 dark:border-zinc-700">{{ $item['label_jenis'] }}</span>
                        </td>
                        <td class="px-5 py-3.5 font-bold text-gray-900 dark:text-zinc-100">{{ $item['nama'] }}</td>
                        <td class="px-5 py-3.5 text-gray-500 dark:text-zinc-400 font-mono text-[11px]">{{ $item['deleted_at']?->format('d M Y, H:i') }}</td>
                        <td class="px-5 py-3.5 text-right space-x-2">
                            <button wire:click="restore('{{ $item['jenis'] }}', {{ $item['id'] }})"
                                    class="font-bold text-xs text-emerald-700 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 transition">
                                Pulihkan
                            </button>
                            <button wire:click="forceDelete('{{ $item['jenis'] }}', {{ $item['id'] }})"
                                    wire:confirm="Hapus permanen? Data ini TIDAK BISA dikembalikan lagi setelah ini."
                                    class="font-bold text-xs text-rose-600 dark:text-rose-400 hover:text-rose-700 dark:hover:text-rose-300 transition">
                                Hapus Permanen
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-5 py-10 text-center text-gray-400 dark:text-zinc-500 text-sm">Tidak ada data di tempat sampah.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Card View --}}
    <div class="md:hidden grid grid-cols-1 sm:grid-cols-2 gap-3.5">
        @forelse ($items as $item)
            <div class="bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 rounded-2xl p-4 shadow-2xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between gap-2 mb-2">
                        <span class="inline-block px-2 py-0.5 rounded bg-gray-100 dark:bg-zinc-800 font-bold text-[10px] text-gray-700 dark:text-zinc-300 border border-gray-200 dark:border-zinc-700">{{ $item['label_jenis'] }}</span>
                        <span class="text-gray-400 dark:text-zinc-500 text-[10px] font-mono">{{ $item['deleted_at']?->format('d/m/Y H:i') }}</span>
                    </div>
                    <h3 class="font-bold text-gray-900 dark:text-zinc-100 text-sm leading-snug">{{ $item['nama'] }}</h3>
                </div>

                <div class="pt-3 mt-3 border-t border-gray-100 dark:border-zinc-800 flex items-center justify-end gap-2">
                    <button wire:click="restore('{{ $item['jenis'] }}', {{ $item['id'] }})"
                            class="px-3 py-1.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-300 font-bold hover:bg-emerald-100 transition text-xs">
                        Pulihkan
                    </button>
                    <button wire:click="forceDelete('{{ $item['jenis'] }}', {{ $item['id'] }})"
                            wire:confirm="Hapus permanen? Data ini TIDAK BISA dikembalikan lagi setelah ini."
                            class="px-3 py-1.5 rounded-xl bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 font-bold hover:bg-rose-100 transition text-xs">
                        Hapus Permanen
                    </button>
                </div>
            </div>
        @empty
            <div class="sm:col-span-2 bg-white dark:bg-zinc-900 rounded-2xl p-8 text-center border border-gray-200/80 dark:border-zinc-800 text-gray-500 dark:text-zinc-400 text-sm">
                Tidak ada data di tempat sampah.
            </div>
        @endforelse
    </div>

    {{ $items->links() }}

</div>
