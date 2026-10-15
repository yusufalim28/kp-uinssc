<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Master Data — Tahun Akademik</h1>
            <p class="text-gray-500 text-sm">Hanya 1 tahun akademik yang boleh berstatus aktif.</p>
        </div>
        <button wire:click="openCreate" class="bg-primary text-white font-medium px-4 py-2 rounded-lg hover:bg-primary-dark transition">
            + Tambah Tahun Akademik
        </button>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3">{{ session('error') }}</div>
    @endif

    {{-- Desktop Table --}}
    <div class="hidden md:block bg-white border border-gray-300/10 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-primary text-white text-left">
                <tr>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Semester</th>
                    <th class="px-4 py-3">Periode</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark/10">
                @forelse ($tahun as $t)
                    <tr class="hover:bg-primary/5">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $t->nama }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2.5 py-1 rounded-md text-xs font-semibold {{ $t->semester === 'Ganjil' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-purple-50 text-purple-700 border border-purple-200' }}">
                                {{ $t->semester }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $t->tahun_mulai }}{{ $t->tahun_selesai ? ' / ' . $t->tahun_selesai : '' }}</td>
                        <td class="px-4 py-3">
                            @if ($t->is_aktif)
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Aktif</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-xs bg-gray-900/10 text-gray-900/50">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <button wire:click="openEdit({{ $t->id }})" class="text-primary-dark font-medium hover:underline">Edit</button>
                            <button wire:click="confirmDelete({{ $t->id }})" class="text-red-600 font-medium hover:underline">Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-gray-900/50">Belum ada data tahun akademik.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Card View --}}
    <div class="md:hidden grid grid-cols-1 sm:grid-cols-2 gap-3">
        @forelse ($tahun as $t)
            <div class="bg-white border border-gray-100 rounded-xl p-3.5 shadow-xs hover:border-gray-200 transition flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between gap-2 mb-1.5">
                        <span class="px-2 py-0.5 rounded-md font-semibold text-[11px] {{ $t->semester === 'Ganjil' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-purple-50 text-purple-700 border border-purple-200' }}">
                            Semester {{ $t->semester }}
                        </span>
                        @if ($t->is_aktif)
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-medium bg-emerald-100 text-emerald-800 shrink-0">Aktif</span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-medium bg-gray-100 text-gray-500 shrink-0">Nonaktif</span>
                        @endif
                    </div>
                    <h3 class="font-bold text-sm text-gray-900 leading-snug">{{ $t->nama }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Periode: {{ $t->tahun_mulai }}{{ $t->tahun_selesai ? ' / ' . $t->tahun_selesai : '' }}</p>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2.5 mt-3 border-t border-gray-100">
                    <button wire:click="openEdit({{ $t->id }})" class="px-2.5 py-1 text-xs font-semibold text-primary bg-primary/5 hover:bg-primary/15 rounded-lg transition">Edit</button>
                    <button wire:click="confirmDelete({{ $t->id }})" class="px-2.5 py-1 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition">Hapus</button>
                </div>
            </div>
        @empty
            <div class="sm:col-span-2 bg-white rounded-xl p-8 text-center border border-gray-100 text-gray-500 text-sm">
                Belum ada data tahun akademik.
            </div>
        @endforelse
    </div>

    {{ $tahun->links() }}

    @if ($showModal)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 px-4">
            <div class="bg-white rounded-xl w-full max-w-md p-6 space-y-4">
                <h2 class="text-lg font-semibold text-gray-900">{{ $editId ? 'Edit Tahun Akademik' : 'Tambah Tahun Akademik' }}</h2>

                <div>
                    <label class="text-sm text-gray-600">Nama</label>
                    <input type="text" wire:model="nama" placeholder="mis. 2026/2027 Ganjil" class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 text-sm py-2 mt-1 transition">
                    @error('nama') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-sm text-gray-600">Semester</label>
                        <select wire:model="semester" class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 text-sm py-2 mt-1 transition">
                            <option value="Ganjil">Ganjil</option>
                            <option value="Genap">Genap</option>
                        </select>
                        @error('semester') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">Tahun Mulai</label>
                        <input type="number" wire:model="tahun_mulai" min="2000" max="2100" placeholder="2026" class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 text-sm py-2 mt-1 transition">
                        @error('tahun_mulai') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="text-sm text-gray-600">Tahun Selesai (opsional)</label>
                    <input type="number" wire:model="tahun_selesai" min="2000" max="2100" placeholder="Otomatis Tahun Mulai + 1" class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 text-sm py-2 mt-1 transition">
                    @error('tahun_selesai') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input type="checkbox" wire:model="is_aktif" class="rounded border-gray-300/20 text-primary focus:ring-primary">
                    Jadikan tahun akademik aktif (tahun aktif lain otomatis dinonaktifkan)
                </label>

                <div class="flex justify-end gap-2 pt-2">
                    <button wire:click="$set('showModal', false)" class="px-4 py-2 text-sm text-gray-500 hover:bg-gray-900/5 rounded-lg">Batal</button>
                    <button wire:click="save" class="px-4 py-2 text-sm bg-primary text-white font-medium rounded-lg hover:bg-primary-dark">Simpan</button>
                </div>
            </div>
        </div>
    @endif

    @if ($confirmDeleteId)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 px-4">
            <div class="bg-white rounded-xl w-full max-w-sm p-6 space-y-4">
                <h2 class="text-lg font-semibold text-gray-900">Hapus Tahun Akademik?</h2>
                <p class="text-sm text-gray-500">Data yang sudah dihapus tidak bisa dikembalikan.</p>
                <div class="flex justify-end gap-2">
                    <button wire:click="$set('confirmDeleteId', null)" class="px-4 py-2 text-sm text-gray-500 hover:bg-gray-900/5 rounded-lg">Batal</button>
                    <button wire:click="delete" class="px-4 py-2 text-sm bg-red-600 text-white font-medium rounded-lg hover:bg-red-700">Ya, Hapus</button>
                </div>
            </div>
        </div>
    @endif

</div>
