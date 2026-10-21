<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-xl font-bold text-gray-900">Riwayat Aktivitas</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                    {{ number_format($totalLogs) }} Log
                </span>
            </div>
            <p class="text-gray-500 text-sm">Catatan audit siapa mengubah apa, kapan — untuk transparansi sistem.</p>
        </div>

        <button wire:click="openCleanModal" class="inline-flex items-center justify-center gap-2 bg-red-50 text-red-700 hover:bg-red-100 border border-red-200 font-semibold px-4 py-2.5 rounded-xl transition text-xs shadow-xs">
            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            Bersihkan Log Lama
        </button>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl px-4 py-3 flex items-center gap-2">
            <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Filter Bar --}}
    <div class="flex items-center justify-between gap-3 bg-white p-3.5 rounded-2xl border border-gray-200/80 shadow-xs">
        <div class="flex items-center gap-2.5">
            <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider shrink-0 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Filter Jenis Aksi:
            </label>
            <select wire:model.live="filterAksi" class="rounded-xl border border-gray-300 bg-gray-50 focus:bg-white text-xs px-3.5 py-2 shadow-xs focus:border-primary focus:ring-2 focus:ring-primary/20 transition">
                <option value="">Semua Aksi (Semua Riwayat)</option>
                <option value="created">Tambah (Created)</option>
                <option value="updated">Ubah (Updated)</option>
                <option value="deleted">Hapus (Deleted)</option>
                <option value="restored">Pulihkan (Restored)</option>
            </select>
        </div>

        @if($filterAksi)
            <button wire:click="$set('filterAksi', '')" class="text-xs text-red-600 hover:text-red-700 font-semibold px-2 py-1 hover:bg-red-50 rounded-lg transition">
                Reset Filter
            </button>
        @endif
    </div>

    {{-- Desktop Table --}}
    <div class="hidden md:block bg-white border border-gray-300/10 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-primary text-white text-left">
                <tr>
                    <th class="px-4 py-3">Waktu</th>
                    <th class="px-4 py-3">Pengguna</th>
                    <th class="px-4 py-3">Aksi</th>
                    <th class="px-4 py-3">Jenis Data</th>
                    <th class="px-4 py-3">Detail Perubahan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark/10">
                @forelse ($logs as $log)
                    <tr class="hover:bg-primary/5 align-top">
                        <td class="px-4 py-3 text-gray-500 whitespace-nowrap">
                            {{ $log->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-4 py-3 text-gray-600 font-medium">
                            {{ $log->causer?->name ?? 'Sistem' }}
                        </td>
                        <td class="px-4 py-3">
                            <span @class([
                                'px-2 py-1 rounded-full text-xs font-semibold',
                                'bg-green-100 text-green-700' => $log->description === 'created',
                                'bg-blue-100 text-blue-700' => $log->description === 'updated',
                                'bg-red-100 text-red-700' => in_array($log->description, ['deleted', 'force deleted']),
                                'bg-yellow-100 text-yellow-700' => $log->description === 'restored',
                            ])>
                                {{ ucfirst($log->description) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">
                            {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500">
                            @php
                                $old = $log->properties['old'] ?? [];
                                $new = $log->properties['attributes'] ?? [];
                                $changed = array_intersect_key($new, $old) ?: $new;
                            @endphp
                            @if ($changed)
                                <ul class="space-y-0.5">
                                    @foreach ($changed as $field => $value)
                                        @if (! isset($old[$field]) || $old[$field] != $value)
                                            <li>
                                                <span class="font-medium text-gray-700">{{ $field }}</span>:
                                                @if (isset($old[$field]))
                                                    <span class="line-through text-gray-400">{{ is_array($old[$field]) ? json_encode($old[$field]) : $old[$field] }}</span> →
                                                @endif
                                                <span>{{ is_array($value) ? json_encode($value) : $value }}</span>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-gray-900/30">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-gray-900/50">Belum ada riwayat aktivitas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Card View --}}
    <div class="md:hidden grid grid-cols-1 sm:grid-cols-2 gap-3.5">
        @forelse ($logs as $log)
            <div class="bg-white border border-gray-100 rounded-xl p-3.5 shadow-xs hover:border-gray-200 transition flex flex-col justify-between">
                <div>
                    <div class="flex items-start justify-between gap-2 mb-1.5">
                        <div>
                            <span class="inline-block px-2 py-0.5 rounded bg-gray-100 text-gray-600 font-mono text-[10px] mb-1 font-semibold">
                                {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}
                            </span>
                            <h3 class="font-bold text-gray-900 text-sm leading-snug">{{ $log->causer?->name ?? 'Sistem' }}</h3>
                            <p class="text-[10px] text-gray-400 mt-0.5">{{ $log->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <span @class([
                            'px-2 py-0.5 rounded-full text-[11px] font-medium shrink-0',
                            'bg-green-100 text-green-700' => $log->description === 'created',
                            'bg-blue-100 text-blue-700' => $log->description === 'updated',
                            'bg-red-100 text-red-700' => in_array($log->description, ['deleted', 'force deleted']),
                            'bg-yellow-100 text-yellow-700' => $log->description === 'restored',
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
                        <div class="bg-gray-50 border border-gray-100 rounded-lg p-2.5 text-xs space-y-1 mt-2">
                            <span class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider block">Perubahan:</span>
                            @foreach ($changed as $field => $value)
                                @if (! isset($old[$field]) || $old[$field] != $value)
                                    <div class="text-gray-700 text-xs">
                                        <span class="font-semibold">{{ $field }}:</span>
                                        @if (isset($old[$field]))
                                            <span class="line-through text-gray-400">{{ is_array($old[$field]) ? json_encode($old[$field]) : $old[$field] }}</span> →
                                        @endif
                                        <span class="font-medium text-emerald-800">{{ is_array($value) ? json_encode($value) : $value }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="sm:col-span-2 bg-white rounded-xl p-8 text-center border border-gray-100 text-gray-500 text-sm">
                Belum ada riwayat aktivitas.
            </div>
        @endforelse
    </div>

    {{ $logs->links() }}

    {{-- Modal Bersihkan Log --}}
    @if ($showCleanModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-xs flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl w-full max-w-md p-6 space-y-4 shadow-xl border border-gray-100 animate-in fade-in zoom-in-95 duration-150">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Bersihkan Riwayat Log</h2>
                        <p class="text-xs text-gray-500">Pilih kriteria data log lama yang ingin dihapus.</p>
                    </div>
                </div>

                <div class="space-y-2 pt-1">
                    <label class="text-xs font-semibold text-gray-700">Kriteria Penghapusan:</label>
                    <div class="space-y-2">
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 hover:border-primary/50 cursor-pointer transition">
                            <input type="radio" wire:model="cleanDays" value="30" class="text-primary focus:ring-primary">
                            <span class="text-xs text-gray-700">Hapus log yang lebih lama dari <strong>30 hari</strong> (Direkomendasikan)</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 hover:border-primary/50 cursor-pointer transition">
                            <input type="radio" wire:model="cleanDays" value="7" class="text-primary focus:ring-primary">
                            <span class="text-xs text-gray-700">Hapus log yang lebih lama dari <strong>7 hari</strong></span>
                        </label>
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 hover:border-primary/50 cursor-pointer transition">
                            <input type="radio" wire:model="cleanDays" value="90" class="text-primary focus:ring-primary">
                            <span class="text-xs text-gray-700">Hapus log yang lebih lama dari <strong>90 hari</strong></span>
                        </label>
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-red-200 bg-red-50/30 hover:border-red-400 cursor-pointer transition">
                            <input type="radio" wire:model="cleanDays" value="0" class="text-red-600 focus:ring-red-500">
                            <span class="text-xs text-red-700 font-medium">Kosongkan seluruh riwayat log aktivitas (Semua data)</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-gray-100">
                    <button wire:click="$set('showCleanModal', false)" type="button" class="px-4 py-2 text-xs font-medium text-gray-600 hover:bg-gray-100 rounded-xl transition">
                        Batal
                    </button>
                    <button wire:click="cleanLogs" wire:loading.attr="disabled" type="button" class="px-5 py-2 text-xs font-semibold bg-red-600 hover:bg-red-700 text-white rounded-xl transition shadow-xs">
                        <span wire:loading.remove wire:target="cleanLogs">Bersihkan Sekarang</span>
                        <span wire:loading wire:target="cleanLogs">Menghapus...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
