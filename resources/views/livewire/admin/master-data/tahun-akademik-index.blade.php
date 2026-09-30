<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-dark">Master Data — Tahun Akademik</h1>
            <p class="text-dark/60 text-sm">Hanya 1 tahun akademik yang boleh berstatus aktif.</p>
        </div>
        <button wire:click="openCreate" class="bg-primary text-dark font-medium px-4 py-2 rounded-lg hover:bg-primary-dark transition">
            + Tambah Tahun Akademik
        </button>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3">{{ session('error') }}</div>
    @endif

    <div class="bg-white border border-dark/10 rounded-xl overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-dark text-white text-left">
                <tr>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Tahun Mulai</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark/10">
                @forelse ($tahun as $t)
                    <tr class="hover:bg-primary/5">
                        <td class="px-4 py-3 font-medium text-dark">{{ $t->nama }}</td>
                        <td class="px-4 py-3 text-dark/70">{{ $t->tahun_mulai }}</td>
                        <td class="px-4 py-3">
                            @if ($t->status_aktif)
                                <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">Aktif</span>
                            @else
                                <span class="px-2 py-1 rounded-full text-xs bg-dark/10 text-dark/50">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <button wire:click="openEdit({{ $t->id }})" class="text-primary-dark font-medium hover:underline">Edit</button>
                            <button wire:click="confirmDelete({{ $t->id }})" class="text-red-600 font-medium hover:underline">Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-dark/50">Belum ada data tahun akademik.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $tahun->links() }}

    @if ($showModal)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 px-4">
            <div class="bg-white rounded-xl w-full max-w-md p-6 space-y-4">
                <h2 class="text-lg font-semibold text-dark">{{ $editId ? 'Edit Tahun Akademik' : 'Tambah Tahun Akademik' }}</h2>

                <div>
                    <label class="text-sm text-dark/70">Nama</label>
                    <input type="text" wire:model="nama" placeholder="mis. 2026/2027 Ganjil" class="w-full rounded-lg border-dark/20 text-sm mt-1">
                    @error('nama') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm text-dark/70">Tahun Mulai</label>
                    <input type="number" wire:model="tahun_mulai" min="2000" max="2100" class="w-full rounded-lg border-dark/20 text-sm mt-1">
                    @error('tahun_mulai') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <label class="flex items-center gap-2 text-sm text-dark/70">
                    <input type="checkbox" wire:model="status_aktif" class="rounded border-dark/20">
                    Jadikan tahun akademik aktif (tahun aktif lain otomatis dinonaktifkan)
                </label>

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
                <h2 class="text-lg font-semibold text-dark">Hapus Tahun Akademik?</h2>
                <p class="text-sm text-dark/60">Data yang sudah dihapus tidak bisa dikembalikan.</p>
                <div class="flex justify-end gap-2">
                    <button wire:click="$set('confirmDeleteId', null)" class="px-4 py-2 text-sm text-dark/60 hover:bg-dark/5 rounded-lg">Batal</button>
                    <button wire:click="delete" class="px-4 py-2 text-sm bg-red-600 text-white font-medium rounded-lg hover:bg-red-700">Ya, Hapus</button>
                </div>
            </div>
        </div>
    @endif

</div>
