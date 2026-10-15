<div class="space-y-6">

    <div>
        <h1 class="text-xl font-bold text-gray-900">Sampah — Data Terhapus</h1>
        <p class="text-gray-500 text-sm">
            Data yang dihapus dari Master Data atau Jadwal tidak langsung hilang permanen.
            Pulihkan di sini kalau terhapus tidak sengaja.
        </p>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3">{{ session('success') }}</div>
    @endif

    {{-- Filter jenis --}}
    <div class="flex flex-wrap gap-2">
        <button wire:click="$set('filterJenis', '')"
                @class(['px-3 py-1.5 rounded-lg text-sm border', 'bg-gray-900 text-white border-gray-300' => $filterJenis === '', 'border-gray-300/20 text-gray-600 hover:bg-gray-900/5' => $filterJenis !== ''])>
            Semua
        </button>
        @foreach ($jenisMap as $key => $config)
            <button wire:click="$set('filterJenis', '{{ $key }}')"
                    @class(['px-3 py-1.5 rounded-lg text-sm border', 'bg-gray-900 text-white border-gray-300' => $filterJenis === $key, 'border-gray-300/20 text-gray-600 hover:bg-gray-900/5' => $filterJenis !== $key])>
                {{ $config['label'] }}
            </button>
        @endforeach
    </div>

    {{-- Desktop Table --}}
    <div class="hidden md:block bg-white border border-gray-300/10 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-primary text-white text-left">
                <tr>
                    <th class="px-4 py-3">Jenis</th>
                    <th class="px-4 py-3">Nama / Keterangan</th>
                    <th class="px-4 py-3">Dihapus Pada</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark/10">
                @forelse ($items as $item)
                    <tr class="hover:bg-primary/5">
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs bg-gray-900/10 text-gray-500">{{ $item['label_jenis'] }}</span>
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $item['nama'] }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $item['deleted_at']?->format('d M Y, H:i') }}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <button wire:click="restore('{{ $item['jenis'] }}', {{ $item['id'] }})"
                                    class="text-green-700 font-medium hover:underline">
                                Pulihkan
                            </button>
                            <button wire:click="forceDelete('{{ $item['jenis'] }}', {{ $item['id'] }})"
                                    wire:confirm="Hapus permanen? Data ini TIDAK BISA dikembalikan lagi setelah ini."
                                    class="text-red-600 font-medium hover:underline">
                                Hapus Permanen
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-gray-900/50">Tidak ada data di sampah.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Card View --}}
    <div class="md:hidden grid grid-cols-1 sm:grid-cols-2 gap-3">
        @forelse ($items as $item)
            <div class="bg-white border border-gray-100 rounded-xl p-3.5 shadow-xs hover:border-gray-200 transition flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between gap-2 mb-1.5">
                        <span class="inline-block px-2 py-0.5 rounded bg-gray-100 font-semibold text-[11px] text-gray-600">{{ $item['label_jenis'] }}</span>
                        <span class="text-gray-400 text-[10px]">{{ $item['deleted_at']?->format('d/m/Y H:i') }}</span>
                    </div>
                    <h3 class="font-bold text-gray-900 text-sm leading-snug">{{ $item['nama'] }}</h3>
                </div>

                <div class="pt-2.5 mt-3 border-t border-gray-100 flex items-center justify-end gap-2">
                    <button wire:click="restore('{{ $item['jenis'] }}', {{ $item['id'] }})"
                            class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 font-semibold hover:bg-emerald-100 transition text-xs">
                        Pulihkan
                    </button>
                    <button wire:click="forceDelete('{{ $item['jenis'] }}', {{ $item['id'] }})"
                            wire:confirm="Hapus permanen? Data ini TIDAK BISA dikembalikan lagi setelah ini."
                            class="px-2.5 py-1 rounded-lg bg-red-50 text-red-700 font-semibold hover:bg-red-100 transition text-xs">
                        Hapus
                    </button>
                </div>
            </div>
        @empty
            <div class="sm:col-span-2 bg-white rounded-xl p-8 text-center border border-gray-100 text-gray-500 text-sm">
                Tidak ada data di sampah.
            </div>
        @endforelse
    </div>

    {{ $items->links() }}

</div>
