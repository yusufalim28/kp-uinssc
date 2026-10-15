<div class="space-y-6">

    <div>
        <h1 class="text-xl font-bold text-gray-900">Riwayat Aktivitas</h1>
        <p class="text-gray-500 text-sm">Catatan siapa mengubah apa, kapan — untuk keperluan audit.</p>
    </div>

    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari berdasarkan aksi atau jenis data..."
           class="w-full md:w-96 rounded-xl border-gray-300 bg-white shadow-sm focus:border-primary focus:ring focus:ring-primary/20 text-sm px-4 py-2.5 transition">

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

</div>
