<div class="space-y-6">

    <div>
        <h1 class="text-xl font-bold text-dark">Sampah — Data Terhapus</h1>
        <p class="text-dark/60 text-sm">
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
                @class(['px-3 py-1.5 rounded-lg text-sm border', 'bg-dark text-white border-dark' => $filterJenis === '', 'border-dark/20 text-dark/70 hover:bg-dark/5' => $filterJenis !== ''])>
            Semua
        </button>
        @foreach ($jenisMap as $key => $config)
            <button wire:click="$set('filterJenis', '{{ $key }}')"
                    @class(['px-3 py-1.5 rounded-lg text-sm border', 'bg-dark text-white border-dark' => $filterJenis === $key, 'border-dark/20 text-dark/70 hover:bg-dark/5' => $filterJenis !== $key])>
                {{ $config['label'] }}
            </button>
        @endforeach
    </div>

    <div class="bg-white border border-dark/10 rounded-xl overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-dark text-white text-left">
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
                            <span class="px-2 py-1 rounded-full text-xs bg-dark/10 text-dark/60">{{ $item['label_jenis'] }}</span>
                        </td>
                        <td class="px-4 py-3 font-medium text-dark">{{ $item['nama'] }}</td>
                        <td class="px-4 py-3 text-dark/60">{{ $item['deleted_at']?->format('d M Y, H:i') }}</td>
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
                    <tr><td colspan="4" class="px-4 py-8 text-center text-dark/50">Tidak ada data di sampah.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $items->links() }}

</div>
