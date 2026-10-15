<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Master Data — Mata Kuliah</h1>
            <p class="text-gray-500 text-sm">Kelola data mata kuliah.</p>
        </div>
        <button wire:click="openCreate" class="bg-primary text-white font-medium px-4 py-2 rounded-lg hover:bg-primary-dark transition">
            + Tambah Mata Kuliah
        </button>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3">{{ session('error') }}</div>
    @endif

    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama atau kode MK..."
           class="w-full md:w-96 rounded-xl border-gray-300 bg-white shadow-sm focus:border-primary focus:ring focus:ring-primary/20 text-sm px-4 py-2.5 transition">

    {{-- Desktop Table --}}
    <div class="hidden md:block bg-white border border-gray-300/10 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-primary text-white text-left">
                <tr>
                    <th class="px-4 py-3">Kode</th>
                    <th class="px-4 py-3">Nama Mata Kuliah</th>
                    <th class="px-4 py-3">Prodi</th>
                    <th class="px-4 py-3">SKS</th>
                    <th class="px-4 py-3">Semester</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark/10">
                @forelse ($mataKuliah as $mk)
                    <tr class="hover:bg-primary/5">
                        <td class="px-4 py-3 font-mono text-gray-600">{{ $mk->kode_mk }}</td>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $mk->nama_mk }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $mk->programStudi->nama_program_studi }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $mk->sks }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $mk->semester }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs {{ $mk->status === 'aktif' ? 'bg-green-100 text-green-700' : 'bg-gray-900/10 text-gray-900/50' }}">
                                {{ ucfirst($mk->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <button wire:click="openEdit({{ $mk->id }})" class="text-primary-dark font-medium hover:underline">Edit</button>
                            <button wire:click="confirmDelete({{ $mk->id }})" class="text-red-600 font-medium hover:underline">Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-8 text-center text-gray-900/50">Belum ada data mata kuliah.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Cards Grid --}}
    <div class="md:hidden grid grid-cols-1 sm:grid-cols-2 gap-3">
        @forelse ($mataKuliah as $mk)
            <div class="bg-white border border-gray-100 rounded-xl p-3.5 shadow-xs hover:border-gray-200 transition flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between gap-2 mb-1.5">
                        <span class="font-mono text-xs font-semibold text-primary bg-primary/10 px-2 py-0.5 rounded-md">{{ $mk->kode_mk }}</span>
                        <span class="px-2 py-0.5 rounded-full text-[11px] font-medium {{ $mk->status === 'aktif' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ ucfirst($mk->status) }}
                        </span>
                    </div>
                    <h3 class="font-bold text-sm text-gray-900 leading-snug">{{ $mk->nama_mk }}</h3>
                    <div class="flex flex-wrap items-center gap-1.5 mt-2">
                        <span class="text-[11px] font-medium px-2 py-0.5 rounded bg-gray-50 text-gray-600 border border-gray-100">{{ $mk->sks }} SKS</span>
                        <span class="text-[11px] font-medium px-2 py-0.5 rounded bg-gray-50 text-gray-600 border border-gray-100">Smt {{ $mk->semester }}</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span class="truncate">{{ $mk->programStudi->nama_program_studi }}</span>
                    </p>
                </div>
                <div class="flex items-center justify-end gap-2 pt-2.5 mt-3 border-t border-gray-100">
                    <button wire:click="openEdit({{ $mk->id }})" class="px-2.5 py-1 text-xs font-semibold text-primary bg-primary/5 hover:bg-primary/15 rounded-lg transition">Edit</button>
                    <button wire:click="confirmDelete({{ $mk->id }})" class="px-2.5 py-1 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition">Hapus</button>
                </div>
            </div>
        @empty
            <div class="sm:col-span-2 bg-white rounded-xl p-8 text-center border border-gray-100">
                <p class="text-gray-500 text-sm">Belum ada data mata kuliah.</p>
            </div>
        @endforelse
    </div>

    {{ $mataKuliah->links() }}

    @if ($showModal)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 px-4">
            <div class="bg-white rounded-xl w-full max-w-md p-6 space-y-4">
                <h2 class="text-lg font-semibold text-gray-900">{{ $editId ? 'Edit Mata Kuliah' : 'Tambah Mata Kuliah' }}</h2>

                <div>
                    <label class="text-sm text-gray-600">Program Studi</label>
                    <select wire:model="program_studi_id" class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 text-sm py-2 mt-1 transition">
                        <option value="">-- Pilih Program Studi --</option>
                        @foreach ($programStudi as $ps)
                            <option value="{{ $ps->id }}">{{ $ps->nama_program_studi }}</option>
                        @endforeach
                    </select>
                    @error('program_studi_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm text-gray-600">Kode Mata Kuliah</label>
                    <input type="text" wire:model="kode_mk" class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 text-sm py-2 mt-1 transition">
                    @error('kode_mk') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm text-gray-600">Nama Mata Kuliah</label>
                    <input type="text" wire:model="nama_mk" class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 text-sm py-2 mt-1 transition">
                    @error('nama_mk') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-sm text-gray-600">SKS</label>
                        <input type="number" wire:model="sks" min="1" max="6" class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 text-sm py-2 mt-1 transition">
                        @error('sks') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Semester</label>
                        <input type="number" wire:model="semester" min="1" max="8" class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 text-sm py-2 mt-1 transition">
                        @error('semester') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="text-sm text-gray-600">Status</label>
                    <select wire:model="status" class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 text-sm py-2 mt-1 transition">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
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
                <h2 class="text-lg font-semibold text-gray-900">Hapus Mata Kuliah?</h2>
                <p class="text-sm text-gray-500">Data yang sudah dihapus tidak bisa dikembalikan.</p>
                <div class="flex justify-end gap-2">
                    <button wire:click="$set('confirmDeleteId', null)" class="px-4 py-2 text-sm text-gray-500 hover:bg-gray-900/5 rounded-lg">Batal</button>
                    <button wire:click="delete" class="px-4 py-2 text-sm bg-red-600 text-white font-medium rounded-lg hover:bg-red-700">Ya, Hapus</button>
                </div>
            </div>
        </div>
    @endif

</div>
