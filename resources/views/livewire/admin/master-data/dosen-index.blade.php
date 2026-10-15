<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Master Data — Dosen</h1>
            <p class="text-gray-500 text-sm">Kelola data dosen.</p>
        </div>
        <button wire:click="openCreate" class="bg-primary text-white font-medium px-4 py-2 rounded-lg hover:bg-primary-dark transition">
            + Tambah Dosen
        </button>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3">{{ session('error') }}</div>
    @endif

    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama atau NIDN dosen..."
           class="w-full md:w-96 rounded-xl border-gray-300 bg-white shadow-sm focus:border-primary focus:ring focus:ring-primary/20 text-sm px-4 py-2.5 transition">

    {{-- Desktop Table --}}
    <div class="hidden md:block bg-white border border-gray-300/10 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-primary text-white text-left">
                <tr>
                    <th class="px-4 py-3">NIDN</th>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark/10">
                @forelse ($dosen as $d)
                    <tr class="hover:bg-primary/5">
                        <td class="px-4 py-3 font-mono text-gray-600 font-medium">{{ $d->nidn }}</td>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $d->nama }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $d->email }}</td>
                        <td class="px-4 py-3">
                            <span @class([
                                'px-2.5 py-1 rounded-full text-xs font-medium',
                                'bg-green-100 text-green-700' => $d->status === 'aktif',
                                'bg-yellow-100 text-yellow-700' => $d->status === 'cuti',
                                'bg-red-100 text-red-700' => $d->status === 'keluar',
                                'bg-gray-900/10 text-gray-900/50' => $d->status === 'nonaktif' || !in_array($d->status, ['aktif', 'cuti', 'keluar']),
                            ])>
                                {{ ucfirst($d->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <button wire:click="openEdit({{ $d->id }})" class="text-primary-dark font-medium hover:underline">Edit</button>
                            <button wire:click="confirmDelete({{ $d->id }})" class="text-red-600 font-medium hover:underline">Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-gray-900/50">Belum ada data dosen.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Cards Grid --}}
    <div class="md:hidden grid grid-cols-1 sm:grid-cols-2 gap-3">
        @forelse ($dosen as $d)
            <div class="bg-white border border-gray-100 rounded-xl p-3.5 shadow-xs hover:border-gray-200 transition flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between gap-2 mb-1.5">
                        <span class="font-mono text-xs font-semibold text-primary bg-primary/10 px-2 py-0.5 rounded-md">{{ $d->nidn }}</span>
                        <span @class([
                            'px-2 py-0.5 rounded-full text-[11px] font-medium',
                            'bg-green-100 text-green-700' => $d->status === 'aktif',
                            'bg-yellow-100 text-yellow-700' => $d->status === 'cuti',
                            'bg-red-100 text-red-700' => $d->status === 'keluar',
                            'bg-gray-100 text-gray-500' => $d->status === 'nonaktif' || !in_array($d->status, ['aktif', 'cuti', 'keluar']),
                        ])>
                            {{ ucfirst($d->status) }}
                        </span>
                    </div>
                    <h3 class="font-bold text-sm text-gray-900 leading-snug">{{ $d->nama }}</h3>
                    <p class="text-xs text-gray-500 mt-1 flex items-center gap-1 truncate">
                        <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span class="truncate">{{ $d->email }}</span>
                    </p>
                </div>
                <div class="flex items-center justify-end gap-2 pt-2.5 mt-3 border-t border-gray-100">
                    <button wire:click="openEdit({{ $d->id }})" class="px-2.5 py-1 text-xs font-semibold text-primary bg-primary/5 hover:bg-primary/15 rounded-lg transition">Edit</button>
                    <button wire:click="confirmDelete({{ $d->id }})" class="px-2.5 py-1 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition">Hapus</button>
                </div>
            </div>
        @empty
            <div class="sm:col-span-2 bg-white rounded-xl p-8 text-center border border-gray-100">
                <p class="text-gray-500 text-sm">Belum ada data dosen.</p>
            </div>
        @endforelse
    </div>

    {{ $dosen->links() }}

    @if ($showModal)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 px-4">
            <div class="bg-white rounded-xl w-full max-w-md p-6 space-y-4">
                <h2 class="text-lg font-semibold text-gray-900">{{ $editId ? 'Edit Dosen' : 'Tambah Dosen' }}</h2>

                <div>
                    <label class="text-sm text-gray-600">NIDN (Nomor Induk Dosen)</label>
                    <input type="text" wire:model="nidn" placeholder="mis. 0501198501" class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 text-sm py-2 mt-1 transition">
                    @error('nidn') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm text-gray-600">Nama Lengkap & Gelar</label>
                    <input type="text" wire:model="nama" placeholder="mis. Dr. Ahmad Fauzi, M.Kom." class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 text-sm py-2 mt-1 transition">
                    @error('nama') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm text-gray-600">Email</label>
                    <input type="email" wire:model="email" placeholder="mis. ahmad@kampus.ac.id" class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 text-sm py-2 mt-1 transition">
                    @error('email') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm text-gray-600">Status</label>
                    <select wire:model="status" class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 text-sm py-2 mt-1 transition">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                        <option value="cuti">Cuti</option>
                        <option value="keluar">Keluar</option>
                    </select>
                </div>

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
                <h2 class="text-lg font-semibold text-gray-900">Hapus Dosen?</h2>
                <p class="text-sm text-gray-500">Data yang sudah dihapus tidak bisa dikembalikan.</p>
                <div class="flex justify-end gap-2">
                    <button wire:click="$set('confirmDeleteId', null)" class="px-4 py-2 text-sm text-gray-500 hover:bg-gray-900/5 rounded-lg">Batal</button>
                    <button wire:click="delete" class="px-4 py-2 text-sm bg-red-600 text-white font-medium rounded-lg hover:bg-red-700">Ya, Hapus</button>
                </div>
            </div>
        </div>
    @endif

</div>
