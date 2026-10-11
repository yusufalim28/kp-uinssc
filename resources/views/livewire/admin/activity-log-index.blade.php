<div class="space-y-6">

    <div>
        <h1 class="text-xl font-bold text-gray-900">Riwayat Aktivitas</h1>
        <p class="text-gray-500 text-sm">Catatan siapa mengubah apa, kapan — untuk keperluan audit.</p>
    </div>

    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari berdasarkan aksi atau jenis data..."
           class="w-full md:w-96 rounded-lg border-gray-300/20 text-sm">

    <div class="bg-white border border-gray-300/10 rounded-xl overflow-x-auto">
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
                        <td class="px-4 py-3 text-gray-600">
                            {{ $log->causer?->name ?? 'Sistem' }}
                        </td>
                        <td class="px-4 py-3">
                            <span @class([
                                'px-2 py-1 rounded-full text-xs',
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
                                                <span class="font-medium">{{ $field }}</span>:
                                                @if (isset($old[$field]))
                                                    <span class="line-through text-gray-900/40">{{ $old[$field] }}</span> →
                                                @endif
                                                <span>{{ $value }}</span>
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

    {{ $logs->links() }}

</div>
