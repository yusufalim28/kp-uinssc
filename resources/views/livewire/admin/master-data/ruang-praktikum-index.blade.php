<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Master Data — Ruang & Auditorium</h1>
            <p class="text-gray-500 text-sm">Kelola data laboratorium, studio, dan auditorium bersama universitas / fakultas.</p>
        </div>
        <button wire:click="openCreate" class="inline-flex items-center justify-center gap-2 bg-primary hover:bg-primary-dark text-white font-medium px-4 py-2.5 rounded-xl transition shadow-xs text-xs sm:text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>+ Tambah Ruangan</span>
        </button>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl px-4 py-3 flex items-center gap-2">
            <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3 flex items-center gap-2">
            <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Filter & Search Bar --}}
    <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center justify-between">
        <div class="relative flex-1 max-w-md">
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </span>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama, kode ruang, atau lokasi..."
                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 bg-white shadow-xs focus:border-primary focus:ring-2 focus:ring-primary/20 text-sm transition">
        </div>

        <div class="flex items-center gap-2">
            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider shrink-0">Tipe:</label>
            <select wire:model.live="filterTipe" class="rounded-xl border border-gray-200 bg-white text-sm px-3 py-2.5 shadow-xs focus:border-primary focus:ring-2 focus:ring-primary/20">
                <option value="">Semua Tipe Ruang</option>
                <option value="laboratorium">Laboratorium</option>
                <option value="auditorium">Auditorium / Gedung Besar</option>
                <option value="studio">Studio</option>
                <option value="kelas">Kelas Praktikum</option>
            </select>
        </div>
    </div>

    {{-- Desktop Table --}}
    <div class="hidden md:block bg-white border border-gray-200/80 rounded-2xl overflow-hidden shadow-xs">
        <table class="w-full text-sm">
            <thead class="bg-gray-50/80 text-gray-700 text-left border-b border-gray-200/80">
                <tr>
                    <th class="px-5 py-3.5 font-semibold">Kode</th>
                    <th class="px-5 py-3.5 font-semibold">Nama Ruang</th>
                    <th class="px-5 py-3.5 font-semibold">Tipe & Kepemilikan</th>
                    <th class="px-5 py-3.5 font-semibold">Lokasi</th>
                    <th class="px-5 py-3.5 font-semibold">Kapasitas</th>
                    <th class="px-5 py-3.5 font-semibold">Status</th>
                    <th class="px-5 py-3.5 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($ruang as $r)
                    <tr class="hover:bg-primary/[0.02] transition">
                        <td class="px-5 py-3.5 font-mono text-xs font-bold text-gray-700">
                            <span class="px-2 py-1 bg-gray-100 rounded-md border border-gray-200/60">{{ $r->kode_ruang }}</span>
                        </td>
                        <td class="px-5 py-3.5 font-semibold text-gray-900">
                            {{ $r->nama_ruang }}
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex flex-col gap-1 items-start">
                                @if($r->tipe_ruang === 'auditorium')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-100 text-purple-800 border border-purple-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        Auditorium
                                    </span>
                                @elseif($r->tipe_ruang === 'studio')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                        Studio
                                    </span>
                                @elseif($r->tipe_ruang === 'kelas')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                        Kelas
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        Laboratorium
                                    </span>
                                @endif

                                @if($r->fakultas)
                                    <span class="text-[11px] text-gray-600 font-medium">
                                        🏛️ {{ $r->fakultas->nama_fakultas }}
                                        @if($r->programStudi)
                                            <span class="text-gray-400">/</span> {{ $r->programStudi->nama_program_studi }}
                                        @endif
                                    </span>
                                @else
                                    <span class="text-[11px] text-emerald-700 font-semibold flex items-center gap-1">
                                        🌐 Fasilitas Bersama Universitas
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-gray-600">{{ $r->lokasi }}</td>
                        <td class="px-5 py-3.5 text-gray-700 font-medium">
                            {{ $r->kapasitas }} <span class="text-xs text-gray-400 font-normal">kursi</span>
                        </td>
                        <td class="px-5 py-3.5">
                            <span @class([
                                'px-2.5 py-1 rounded-full text-xs font-medium inline-flex items-center gap-1',
                                'bg-green-100 text-green-700' => in_array($r->status, ['aktif', 'tersedia']),
                                'bg-yellow-100 text-yellow-800' => $r->status === 'perbaikan',
                                'bg-gray-100 text-gray-600' => !in_array($r->status, ['aktif', 'tersedia', 'perbaikan']),
                            ])>
                                <span class="w-1.5 h-1.5 rounded-full {{ in_array($r->status, ['aktif', 'tersedia']) ? 'bg-green-500' : ($r->status === 'perbaikan' ? 'bg-yellow-500' : 'bg-gray-400') }}"></span>
                                {{ $r->status === 'perbaikan' ? 'Perbaikan' : ucfirst($r->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right space-x-2">
                            @if(auth()->user()->hasRole('super-admin') || (auth()->user()->fakultas_id && auth()->user()->fakultas_id === $r->fakultas_id))
                                <button wire:click="openEdit({{ $r->id }})" class="text-primary hover:text-primary-dark font-semibold text-xs transition">Edit</button>
                                <button wire:click="confirmDelete({{ $r->id }})" class="text-red-600 hover:text-red-700 font-semibold text-xs transition">Hapus</button>
                            @else
                                <span class="text-xs text-gray-400 italic">Read-only</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-10 text-center text-gray-400 text-sm">
                            Tidak ada data ruangan ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Card View --}}
    <div class="md:hidden grid grid-cols-1 sm:grid-cols-2 gap-3.5">
        @forelse ($ruang as $r)
            <div class="bg-white border border-gray-200/80 rounded-2xl p-4 shadow-xs hover:border-primary/30 transition flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between gap-2 mb-2">
                        <span class="inline-block px-2 py-0.5 rounded bg-gray-100 font-mono text-xs text-gray-700 font-bold border border-gray-200">{{ $r->kode_ruang }}</span>
                        <span @class([
                            'px-2 py-0.5 rounded-full text-[11px] font-medium shrink-0',
                            'bg-green-100 text-green-700' => in_array($r->status, ['aktif', 'tersedia']),
                            'bg-yellow-100 text-yellow-800' => $r->status === 'perbaikan',
                            'bg-gray-100 text-gray-600' => !in_array($r->status, ['aktif', 'tersedia', 'perbaikan']),
                        ])>
                            {{ $r->status === 'perbaikan' ? 'Perbaikan' : ucfirst($r->status) }}
                        </span>
                    </div>

                    <h3 class="font-bold text-sm text-gray-900 leading-snug">{{ $r->nama_ruang }}</h3>

                    <div class="mt-2 space-y-1">
                        <div class="flex items-center gap-1.5 flex-wrap">
                            @if($r->tipe_ruang === 'auditorium')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-800">Auditorium</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-100 text-emerald-800">{{ ucfirst($r->tipe_ruang) }}</span>
                            @endif

                            @if($r->fakultas)
                                <span class="text-[11px] text-gray-500">{{ $r->fakultas->nama_fakultas }}</span>
                            @else
                                <span class="text-[11px] text-emerald-700 font-semibold">Fasilitas Bersama</span>
                            @endif
                        </div>

                        <div class="flex items-center gap-2 text-xs text-gray-500 pt-1">
                            <span>Kapasitas: <strong class="text-gray-800">{{ $r->kapasitas }} org</strong></span>
                            @if($r->lokasi)
                                <span>• {{ $r->lokasi }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                @if(auth()->user()->hasRole('super-admin') || (auth()->user()->fakultas_id && auth()->user()->fakultas_id === $r->fakultas_id))
                    <div class="flex items-center justify-end gap-2 pt-3 mt-3 border-t border-gray-100">
                        <button wire:click="openEdit({{ $r->id }})" class="px-3 py-1.5 text-xs font-semibold text-primary bg-primary/5 hover:bg-primary/15 rounded-lg transition">Edit</button>
                        <button wire:click="confirmDelete({{ $r->id }})" class="px-3 py-1.5 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition">Hapus</button>
                    </div>
                @endif
            </div>
        @empty
            <div class="sm:col-span-2 bg-white rounded-2xl p-8 text-center border border-gray-200/80 text-gray-500 text-sm">
                Belum ada data ruang praktikum atau auditorium.
            </div>
        @endforelse
    </div>

    {{ $ruang->links() }}

    {{-- Create / Edit Modal --}}
    @if ($showModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-xs flex items-center justify-center z-50 p-4 overflow-y-auto">
            <div class="bg-white rounded-2xl w-full max-w-lg p-6 space-y-4 shadow-xl border border-gray-100 my-8">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">{{ $editId ? 'Edit Ruangan' : 'Tambah Ruang / Auditorium' }}</h2>
                        <p class="text-xs text-gray-500">Konfigurasi ruangan praktikum, laboratorium, atau auditorium bersama.</p>
                    </div>
                    <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600 p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label class="text-xs font-semibold text-gray-700">Kode Ruang *</label>
                        <input type="text" wire:model="kode_ruang" placeholder="mis. LAB-KOM-01" class="w-full rounded-xl border border-gray-300 bg-gray-50/50 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 text-sm py-2 px-3 mt-1 transition">
                        @error('kode_ruang') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-700">Tipe Ruangan *</label>
                        <select wire:model="tipe_ruang" class="w-full rounded-xl border border-gray-300 bg-gray-50/50 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 text-sm py-2 px-3 mt-1 transition">
                            <option value="laboratorium">Laboratorium</option>
                            <option value="auditorium">Auditorium / Gedung Besar</option>
                            <option value="studio">Studio</option>
                            <option value="kelas">Kelas Praktikum</option>
                        </select>
                        @error('tipe_ruang') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-700">Nama Ruang / Auditorium *</label>
                    <input type="text" wire:model="nama_ruang" placeholder="mis. Lab Jaringan Komputer / Auditorium Utama Siber" class="w-full rounded-xl border border-gray-300 bg-gray-50/50 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 text-sm py-2 px-3 mt-1 transition">
                    @error('nama_ruang') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label class="text-xs font-semibold text-gray-700">Kepemilikan Fakultas</label>
                        <select wire:model="fakultas_id" class="w-full rounded-xl border border-gray-300 bg-gray-50/50 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 text-sm py-2 px-3 mt-1 transition">
                            <option value="">-- Fasilitas Bersama Universitas --</option>
                            @foreach ($fakultasList as $fak)
                                <option value="{{ $fak->id }}">{{ $fak->nama_fakultas }}</option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-gray-400 mt-1">Kosongkan jika fasilitas bersama (seperti Gedung Siber, ICC, dll).</p>
                        @error('fakultas_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-700">Program Studi (Opsional)</label>
                        <select wire:model="program_studi_id" class="w-full rounded-xl border border-gray-300 bg-gray-50/50 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 text-sm py-2 px-3 mt-1 transition">
                            <option value="">-- Umum / Semua Prodi --</option>
                            @foreach ($prodiList as $prd)
                                <option value="{{ $prd->id }}">{{ $prd->nama_program_studi }} ({{ $prd->fakultas?->nama_fakultas }})</option>
                            @endforeach
                        </select>
                        @error('program_studi_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label class="text-xs font-semibold text-gray-700">Lokasi / Gedung *</label>
                        <input type="text" wire:model="lokasi" placeholder="mis. Gedung Siber Lt. 3" class="w-full rounded-xl border border-gray-300 bg-gray-50/50 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 text-sm py-2 px-3 mt-1 transition">
                        @error('lokasi') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-700">Kapasitas (Kursi/Orang) *</label>
                        <input type="number" wire:model="kapasitas" min="1" max="1000" placeholder="mis. 40" class="w-full rounded-xl border border-gray-300 bg-gray-50/50 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 text-sm py-2 px-3 mt-1 transition">
                        @error('kapasitas') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-700">Status Operasional *</label>
                    <select wire:model="status" class="w-full rounded-xl border border-gray-300 bg-gray-50/50 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 text-sm py-2 px-3 mt-1 transition">
                        <option value="aktif">Aktif / Siap Pakai</option>
                        <option value="perbaikan">Dalam Perbaikan (Maintenance)</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                    @error('status') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end gap-2.5 pt-3 border-t border-gray-100">
                    <button wire:click="$set('showModal', false)" type="button" class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 font-medium rounded-xl transition">Batal</button>
                    <button wire:click="save" type="button" class="px-5 py-2 text-sm bg-primary text-white font-semibold rounded-xl hover:bg-primary-dark shadow-sm transition">Simpan Ruangan</button>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    @if ($confirmDeleteId)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-xs flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl w-full max-w-sm p-6 space-y-4 shadow-xl border border-gray-100">
                <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div class="text-center">
                    <h2 class="text-lg font-bold text-gray-900">Hapus Ruang / Auditorium?</h2>
                    <p class="text-xs text-gray-500 mt-1">Data yang dihapus tidak bisa dikembalikan. Ruang yang masih memiliki jadwal tidak dapat dihapus.</p>
                </div>
                <div class="flex justify-center gap-2 pt-2">
                    <button wire:click="$set('confirmDeleteId', null)" class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 font-medium rounded-xl transition">Batal</button>
                    <button wire:click="delete" class="px-4 py-2 text-sm bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 shadow-sm transition">Ya, Hapus</button>
                </div>
            </div>
        </div>
    @endif

</div>

