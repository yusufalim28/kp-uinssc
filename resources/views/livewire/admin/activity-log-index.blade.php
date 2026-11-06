<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-zinc-100 tracking-tight">Riwayat Aktivitas</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-800 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/50">
                    {{ number_format($totalLogs) }} Log
                </span>
            </div>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-zinc-400 mt-0.5">Catatan audit siapa mengubah apa, kapan — untuk transparansi sistem.</p>
        </div>

        <button wire:click="openCleanModal" class="inline-flex items-center justify-center gap-2 bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 hover:bg-rose-100 dark:hover:bg-rose-900/50 border border-rose-200 dark:border-rose-800 font-bold px-4 py-2.5 rounded-xl transition text-xs shadow-2xs self-start sm:self-auto">
            <svg class="w-4 h-4 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            <span>Bersihkan Log Lama</span>
        </button>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 text-emerald-800 dark:text-emerald-300 text-xs rounded-xl px-4 py-3 shadow-2xs flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Filter Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white dark:bg-zinc-900 p-3.5 rounded-2xl border border-gray-200/80 dark:border-zinc-800 shadow-2xs">
        <div class="flex flex-col sm:flex-row sm:items-center gap-2.5 w-full sm:w-auto">
            <label class="text-xs font-bold text-gray-700 dark:text-zinc-300 uppercase tracking-wider shrink-0 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Filter Jenis Aksi:
            </label>
            <select wire:model.live="filterAksi" class="w-full sm:w-auto rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-900 dark:text-zinc-100 text-xs px-3.5 py-2 shadow-2xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition">
                <option value="">Semua Aksi (Semua Riwayat)</option>
                <option value="created">Tambah (Created)</option>
                <option value="updated">Ubah (Updated)</option>
                <option value="deleted">Hapus (Deleted)</option>
                <option value="restored">Pulihkan (Restored)</option>
            </select>
        </div>

        @if($filterAksi)
            <button wire:click="$set('filterAksi', '')" class="text-xs text-rose-600 dark:text-rose-400 hover:text-rose-700 font-bold px-2 py-1 hover:bg-rose-50 dark:hover:bg-rose-950/30 rounded-lg transition self-end sm:self-auto">
                Reset Filter
            </button>
        @endif
    </div>

    {{-- Desktop Table --}}
    <div class="hidden md:block bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 rounded-2xl overflow-hidden shadow-2xs">
        <table class="w-full text-xs text-left">
            <thead class="bg-gray-50/80 dark:bg-zinc-800/60 border-b border-gray-200/80 dark:border-zinc-700/80 text-[11px] font-bold text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-3.5 w-36">Waktu</th>
                    <th class="px-5 py-3.5 w-44">Pengguna</th>
                    <th class="px-5 py-3.5 w-28">Aksi</th>
                    <th class="px-5 py-3.5 w-40">Jenis Data</th>
                    <th class="px-5 py-3.5">Detail Perubahan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-zinc-800 text-gray-800 dark:text-zinc-200">
                @forelse ($logs as $log)
                    <tr class="hover:bg-emerald-50/30 dark:hover:bg-zinc-800/40 align-top transition">
                        <td class="px-5 py-3.5 text-gray-500 dark:text-zinc-400 whitespace-nowrap font-mono text-[11px]">
                            {{ $log->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-5 py-3.5 font-bold text-gray-900 dark:text-zinc-100">
                            {{ $log->causer?->name ?? 'Sistem Otomatis' }}
                        </td>
                        <td class="px-5 py-3.5">
                            <span @class([
                                'px-2.5 py-0.5 rounded-full text-[11px] font-bold inline-flex items-center gap-1',
                                'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40' => $log->description === 'created',
                                'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800/40' => $log->description === 'updated',
                                'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800/40' => in_array($log->description, ['deleted', 'force deleted']),
                                'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800/40' => $log->description === 'restored',
                            ])>
                                {{ ucfirst($log->description) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="inline-block px-2 py-0.5 rounded bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300 font-mono text-[11px] font-bold border border-gray-200 dark:border-zinc-700">
                                {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-xs text-gray-600 dark:text-zinc-300">
                            @php
                                $old = $log->properties['old'] ?? [];
                                $new = $log->properties['attributes'] ?? [];
                                $changed = array_intersect_key($new, $old) ?: $new;
                            @endphp
                            @if ($changed)
                                <ul class="space-y-1">
                                    @foreach ($changed as $field => $value)
                                        @if (! isset($old[$field]) || $old[$field] != $value)
                                            <li>
                                                <span class="font-bold text-gray-700 dark:text-zinc-200">{{ $field }}</span>:
                                                @if (isset($old[$field]))
                                                    <span class="line-through text-gray-400 dark:text-zinc-500">{{ is_array($old[$field]) ? json_encode($old[$field]) : $old[$field] }}</span> →
                                                @endif
                                                <span class="font-medium text-emerald-700 dark:text-emerald-400">{{ is_array($value) ? json_encode($value) : $value }}</span>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-gray-400 dark:text-zinc-600">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-10 text-center text-gray-400 dark:text-zinc-500">Belum ada riwayat aktivitas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Card View --}}
    <div class="md:hidden grid grid-cols-1 sm:grid-cols-2 gap-3.5">
        @forelse ($logs as $log)
            <div class="bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 rounded-2xl p-4 shadow-2xs flex flex-col justify-between">
                <div>
                    <div class="flex items-start justify-between gap-2 mb-2">
                        <div>
                            <span class="inline-block px-2 py-0.5 rounded bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300 font-mono text-[10px] mb-1 font-bold border border-gray-200 dark:border-zinc-700">
                                {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}
                            </span>
                            <h3 class="font-bold text-gray-900 dark:text-zinc-100 text-sm leading-snug">{{ $log->causer?->name ?? 'Sistem Otomatis' }}</h3>
                            <p class="text-[10px] text-gray-400 dark:text-zinc-500 mt-0.5 font-mono">{{ $log->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <span @class([
                            'px-2 py-0.5 rounded-full text-[11px] font-bold shrink-0',
                            'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60' => $log->description === 'created',
                            'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border border-blue-200' => $log->description === 'updated',
                            'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-200' => in_array($log->description, ['deleted', 'force deleted']),
                            'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200' => $log->description === 'restored',
                        ])>
                            {{ ucfirst($log->description) }}
                        </span>
                    </div>

                    @php
                        $old = $log->properties['old'] ?? [];
                        $new = $log->properties['attributes'] ?? [];
                        $changed = array_intersect_key($new, $old) ?: $new;
                    @endphp
                    @if ($changed)
                        <div class="bg-gray-50/80 dark:bg-zinc-800/60 border border-gray-100 dark:border-zinc-700/80 rounded-xl p-2.5 text-xs space-y-1 mt-2">
                            <span class="text-[10px] font-bold text-gray-400 dark:text-zinc-400 uppercase tracking-wider block">Perubahan:</span>
                            @foreach ($changed as $field => $value)
                                @if (! isset($old[$field]) || $old[$field] != $value)
                                    <div class="text-gray-700 dark:text-zinc-300 text-xs">
                                        <span class="font-bold">{{ $field }}:</span>
                                        @if (isset($old[$field]))
                                            <span class="line-through text-gray-400 dark:text-zinc-500">{{ is_array($old[$field]) ? json_encode($old[$field]) : $old[$field] }}</span> →
                                        @endif
                                        <span class="font-semibold text-emerald-700 dark:text-emerald-400">{{ is_array($value) ? json_encode($value) : $value }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="sm:col-span-2 bg-white dark:bg-zinc-900 rounded-2xl p-8 text-center border border-gray-200/80 dark:border-zinc-800 text-gray-500 dark:text-zinc-400 text-sm">
                Belum ada riwayat aktivitas.
            </div>
        @endforelse
    </div>

    {{ $logs->links() }}

    {{-- Modal Bersihkan Log --}}
    @if ($showCleanModal)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-zinc-900 rounded-3xl w-full max-w-md p-6 space-y-4 shadow-2xl border border-gray-100 dark:border-zinc-800 text-gray-900 dark:text-zinc-100 animate-in fade-in zoom-in-95 duration-150">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0 border border-rose-200 dark:border-rose-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900 dark:text-zinc-100">Bersihkan Riwayat Log</h2>
                        <p class="text-xs text-gray-500 dark:text-zinc-400">Pilih kriteria data log audit lama yang ingin dihapus.</p>
                    </div>
                </div>

                <div class="space-y-2 pt-1">
                    <label class="text-xs font-bold text-gray-700 dark:text-zinc-300">Kriteria Penghapusan:</label>
                    <div class="space-y-2">
                        <label class="flex items-center gap-3 p-3 rounded-2xl border border-gray-200 dark:border-zinc-700 hover:border-emerald-500 cursor-pointer transition">
                            <input type="radio" wire:model="cleanDays" value="30" class="text-emerald-600 focus:ring-emerald-500">
                            <span class="text-xs text-gray-700 dark:text-zinc-300">Hapus log lebih lama dari <strong class="text-emerald-700 dark:text-emerald-400">30 hari</strong> (Direkomendasikan)</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 rounded-2xl border border-gray-200 dark:border-zinc-700 hover:border-emerald-500 cursor-pointer transition">
                            <input type="radio" wire:model="cleanDays" value="7" class="text-emerald-600 focus:ring-emerald-500">
                            <span class="text-xs text-gray-700 dark:text-zinc-300">Hapus log lebih lama dari <strong>7 hari</strong></span>
                        </label>
                        <label class="flex items-center gap-3 p-3 rounded-2xl border border-gray-200 dark:border-zinc-700 hover:border-emerald-500 cursor-pointer transition">
                            <input type="radio" wire:model="cleanDays" value="90" class="text-emerald-600 focus:ring-emerald-500">
                            <span class="text-xs text-gray-700 dark:text-zinc-300">Hapus log lebih lama dari <strong>90 hari</strong></span>
                        </label>
                        <label class="flex items-center gap-3 p-3 rounded-2xl border border-rose-200 dark:border-rose-800 bg-rose-50/30 dark:bg-rose-950/30 hover:border-rose-400 cursor-pointer transition">
                            <input type="radio" wire:model="cleanDays" value="0" class="text-rose-600 focus:ring-rose-500">
                            <span class="text-xs text-rose-700 dark:text-rose-300 font-semibold">Kosongkan seluruh riwayat log aktivitas (Semua data)</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-gray-100 dark:border-zinc-800">
                    <button wire:click="$set('showCleanModal', false)" type="button" class="px-4 py-2 text-xs font-semibold text-gray-600 dark:text-zinc-400 hover:bg-gray-100 dark:hover:bg-zinc-800 rounded-xl transition">
                        Batal
                    </button>
                    <button wire:click="cleanLogs" wire:loading.attr="disabled" type="button" class="px-5 py-2 text-xs font-bold bg-rose-600 hover:bg-rose-700 text-white rounded-xl transition shadow-xs">
                        <span wire:loading.remove wire:target="cleanLogs">Bersihkan Sekarang</span>
                        <span wire:loading wire:target="cleanLogs">Menghapus...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
