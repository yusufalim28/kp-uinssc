<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-dark">Master Data — Ruang Praktikum</h1>
            <p class="text-dark/60 text-sm">Kelola data ruang praktikum.</p>
        </div>
        <button wire:click="openCreate" class="bg-primary text-dark font-medium px-4 py-2 rounded-lg hover:bg-primary-dark transition">
            + Tambah Ruang
        </button>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3">{{ session('error') }}</div>
    @endif

    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama atau kode ruang..."
           class="w-full md:w-80 rounded-lg border-dark/20 text-sm">

    <div class="bg-white border border-dark/10 rounded-xl overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-dark text-white text-left">
                <tr>
                    <th class="px-4 py-3">Kode</th>
                    <th class="px-4 py-3">Nama Ruang</th>
                    <th class="px-4 py-3">Lokasi</th>
                    <th class="px-4 py-3">Kapasitas</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark/10">
                @forelse ($ruang as $r)
                    <tr class="hover:bg-primary/5">
                        <td class="px-4 py-3 font-mono text-dark/70">{{ $r->kode_ruang }}</td>
                        <td class="px-4 py-3 font-medium text-dark">{{ $r->nama_ruang }}</td>
                        <td class="px-4 py-3 text-dark/70">{{ $r->lokasi }}</td>
                        <td class="px-4 py-3 text-dark/70">{{ $r->kapasitas }} orang</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs {{ $r->status === 'aktif' ? 'bg-green-100 text-green-700' : 'bg-dark/10 text-dark/50' }}">
                                {{ ucfirst($r->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <button wire:click="openEdit({{ $r->id }})" class="text-primary-dark font-medium hover:underline">Edit</button>
                            <button wire:click="confirmDelete({{ $r->id }})" class="text-red-600 font-medium hover:underline">Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-dark/50">Belum ada data ruang praktikum.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $ruang->links() }}

    @if ($showModal)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 px-4">
            <div class="bg-white rounded-xl w-full max-w-md p-6 space-y-4">
                <h2 class="text-lg font-semibold text-dark">{{ $editId ? 'Edit Ruang Praktikum' : 'Tambah Ruang Praktikum' }}</h2>

                <div>
                    <label class="text-sm text-dark/70">Kode Ruang</label>
                    <input type="text" wire:model="kode_ruang" class="w-full rounded-lg border-dark/20 text-sm mt-1">
                    @error('kode_ruang') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm text-dark/70">Nama Ruang</label>
                    <input type="text" wire:model="nama_ruang" class="w-full rounded-lg border-dark/20 text-sm mt-1">
                    @error('nama_ruang') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm text-dark/70">Lokasi</label>
                    <input type="text" wire:model="lokasi" placeholder="mis. Gedung A Lantai 2" class="w-full rounded-lg border-dark/20 text-sm mt-1">
                    @error('lokasi') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm text-dark/70">Kapasitas</label>
                    <input type="number" wire:model="kapasitas" min="1" max="200" class="w-full rounded-lg border-dark/20 text-sm mt-1">
                    @error('kapasitas') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm text-dark/70">Status</label>
                    <select wire:model="status" class="w-full rounded-lg border-dark/20 text-sm mt-1">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button wire:click="$set('showModal', false)" class="px-4 py-2 text-sm text-dark/60 hover:bg-dark/5 rounded-lg">Batal</button>
                    <button wire:click="save" class="px-4 py-2 text-sm bg-primary text-dark font-medium rounded-lg hover:bg-primary-dark">Simpan</button>
                </div>
            </div>
        </div>
    @endif

    @if ($confirmDeleteId)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 px-4">
            <div class="bg-white rounded-xl w-full max-w-sm p-6 space-y-4">
                <h2 class="text-lg font-semibold text-dark">Hapus Ruang Praktikum?</h2>
                <p class="text-sm text-dark/60">Data yang sudah dihapus tidak bisa dikembalikan.</p>
                <div class="flex justify-end gap-2">
                    <button wire:click="$set('confirmDeleteId', null)" class="px-4 py-2 text-sm text-dark/60 hover:bg-dark/5 rounded-lg">Batal</button>
                    <button wire:click="delete" class="px-4 py-2 text-sm bg-red-600 text-white font-medium rounded-lg hover:bg-red-700">Ya, Hapus</button>
                </div>
            </div>
        </div>
    @endif

</div>
