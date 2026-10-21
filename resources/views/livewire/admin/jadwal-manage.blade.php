<div class="space-y-6">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Kelola Jadwal Praktikum</h1>
            <p class="text-gray-500 text-sm">
                @if(auth()->user()->hasRole('operator') && auth()->user()->programStudi)
                    Akses Operator Prodi: <strong class="text-primary">{{ auth()->user()->programStudi->nama_program_studi }}</strong>
                @elseif(auth()->user()->hasRole('operator') && auth()->user()->fakultas)
                    Akses Operator Fakultas: <strong class="text-primary">{{ auth()->user()->fakultas->nama_fakultas }}</strong>
                @else
                    Akses Penuh Administrator Kampus
                @endif
                — Setiap jadwal otomatis dicek bentrok ruang & dosen.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('jadwal.template.download') }}" class="inline-flex items-center gap-1.5 px-3 py-2 border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-xs font-semibold rounded-xl transition shadow-xs">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Unduh Format Excel</span>
            </a>
            <button wire:click="openImportModal" class="inline-flex items-center gap-1.5 px-3 py-2 border border-primary/20 bg-primary/10 hover:bg-primary/20 text-primary text-xs font-semibold rounded-xl transition shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                <span>Import Excel</span>
            </button>
            <button wire:click="openCreate" class="inline-flex items-center gap-1.5 bg-primary text-white text-xs font-semibold px-4 py-2 rounded-xl hover:bg-primary-dark transition shadow-xs">
                <span>+ Tambah Jadwal</span>
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3">{{ session('error') }}</div>
    @endif

    {{-- Search & Filter Controls --}}
    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center justify-between">
        <div class="relative flex-1 max-w-md">
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </span>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari mata kuliah, dosen, kelas, atau ruang..."
                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 bg-white shadow-xs focus:border-primary focus:ring-2 focus:ring-primary/20 text-sm transition">
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <select wire:model.live="filterHari" class="rounded-xl border border-gray-200 bg-white text-xs px-3 py-2.5 shadow-xs focus:border-primary focus:ring-2 focus:ring-primary/20">
                <option value="">Semua Hari</option>
                @foreach ($hariList as $h)
                    <option value="{{ $h }}">{{ $h }}</option>
                @endforeach
            </select>

            <select wire:model.live="filterRuang" class="rounded-xl border border-gray-200 bg-white text-xs px-3 py-2.5 shadow-xs focus:border-primary focus:ring-2 focus:ring-primary/20">
                <option value="">Semua Ruangan</option>
                @foreach ($ruangList as $r)
                    <option value="{{ $r->id }}">{{ $r->nama_ruang }}</option>
                @endforeach
            </select>

            @if($search || $filterHari || $filterRuang)
                <button wire:click="resetFilters" class="text-xs text-red-600 hover:text-red-700 font-semibold px-2.5 py-2 hover:bg-red-50 rounded-xl transition">
                    Reset Filter
                </button>
            @endif
        </div>
    </div>

    {{-- Desktop Table --}}
    <div class="hidden md:block bg-white border border-gray-300/10 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-primary text-white text-left">
                <tr>
                    <th class="px-4 py-3">Mata Kuliah</th>
                    <th class="px-4 py-3">Kelas</th>
                    <th class="px-4 py-3">Dosen</th>
                    <th class="px-4 py-3">Hari / Jam</th>
                    <th class="px-4 py-3">Ruang</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark/10">
                @forelse ($jadwal as $j)
                    <tr class="hover:bg-primary/5">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $j->mataKuliah->nama_mk }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $j->kelas->nama_kelas }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $j->dosen->nama }}</td>
                        <td class="px-4 py-3 text-gray-600">
                            {{ $j->hari }}, {{ \Illuminate\Support\Str::limit($j->jam_mulai, 5, '') }}–{{ \Illuminate\Support\Str::limit($j->jam_selesai, 5, '') }}
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $j->ruangPraktikum->nama_ruang }}</td>
                        <td class="px-4 py-3">
                            <span @class([
                                'px-2 py-1 rounded-full text-xs font-medium',
                                'bg-green-100 text-green-700' => $j->status === 'aktif',
                                'bg-gray-900/10 text-gray-900/50' => $j->status === 'nonaktif',
                                'bg-red-100 text-red-700' => $j->status === 'dibatalkan',
                                'bg-yellow-100 text-yellow-700' => $j->status === 'reschedule',
                            ])>
                                {{ ucfirst($j->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <button wire:click="openEdit({{ $j->id }})" class="text-primary-dark font-medium hover:underline">Edit</button>
                            <button wire:click="confirmDelete({{ $j->id }})" class="text-red-600 font-medium hover:underline">Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-8 text-center text-gray-900/50">Belum ada jadwal.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Cards Grid --}}
    <div class="md:hidden grid grid-cols-1 sm:grid-cols-2 gap-3.5">
        @forelse ($jadwal as $j)
            <div class="bg-white border border-gray-100 rounded-xl p-3.5 shadow-xs hover:border-gray-200 transition flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between gap-2 mb-1.5">
                        <span class="px-2 py-0.5 text-xs font-bold rounded-md bg-primary/10 text-primary">
                            {{ $j->hari }}, {{ \Illuminate\Support\Str::limit($j->jam_mulai, 5, '') }}–{{ \Illuminate\Support\Str::limit($j->jam_selesai, 5, '') }}
                        </span>
                        <span @class([
                            'px-2 py-0.5 rounded-full text-[11px] font-medium shrink-0',
                            'bg-green-100 text-green-700' => $j->status === 'aktif',
                            'bg-gray-100 text-gray-500' => $j->status === 'nonaktif',
                            'bg-red-100 text-red-700' => $j->status === 'dibatalkan',
                            'bg-yellow-100 text-yellow-700' => $j->status === 'reschedule',
                        ])>
                            {{ ucfirst($j->status) }}
                        </span>
                    </div>
                    <h3 class="font-bold text-sm text-gray-900 leading-snug">{{ $j->mataKuliah->nama_mk }}</h3>
                    <div class="flex flex-wrap items-center gap-1.5 mt-2 text-xs">
                        <span class="px-2 py-0.5 rounded bg-gray-50 border border-gray-100 text-gray-700 font-medium">Kelas {{ $j->kelas->nama_kelas }}</span>
                        <span class="px-2 py-0.5 rounded bg-gray-50 border border-gray-100 text-gray-700 font-medium">{{ $j->ruangPraktikum->nama_ruang }}</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-2 truncate flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span class="truncate">{{ $j->dosen->nama }}</span>
                    </p>
                </div>
                <div class="flex items-center justify-end gap-2 pt-2.5 mt-3 border-t border-gray-100">
                    <button wire:click="openEdit({{ $j->id }})" class="px-2.5 py-1 text-xs font-semibold text-primary bg-primary/5 hover:bg-primary/15 rounded-lg transition">Edit</button>
                    <button wire:click="confirmDelete({{ $j->id }})" class="px-2.5 py-1 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition">Hapus</button>
                </div>
            </div>
        @empty
            <div class="sm:col-span-2 bg-white rounded-xl p-8 text-center border border-gray-100">
                <p class="text-gray-500 text-sm">Belum ada jadwal.</p>
            </div>
        @endforelse
    </div>

    {{ $jadwal->links() }}

    {{-- Modal Create/Edit --}}
    @if ($showModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4 sm:p-6 overflow-hidden">
            <div class="bg-white rounded-2xl w-full max-w-xl max-h-[90vh] flex flex-col shadow-2xl overflow-hidden my-auto animate-in fade-in zoom-in-95 duration-150">
                <!-- Header (Sticky/Fixed) -->
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-white shrink-0">
                    <h2 class="text-lg font-bold text-gray-900">{{ $editId ? 'Edit Jadwal Praktikum' : 'Tambah Jadwal Praktikum' }}</h2>
                    <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600 rounded-lg p-1 hover:bg-gray-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Body (Scrollable) -->
                <div class="p-6 overflow-y-auto space-y-4 flex-1">
                    {{-- Pesan bentrok — beda dari error validasi biasa --}}
                    @if ($conflictMessage)
                        <div class="bg-red-50 border border-red-300 text-red-700 text-sm rounded-xl p-4 flex gap-3 items-start">
                            <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <div>
                                <strong class="font-semibold">Jadwal bentrok!</strong>
                                <p class="mt-0.5 text-xs text-red-600 leading-relaxed">{{ $conflictMessage }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div class="sm:col-span-2">
                            <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Mata Kuliah</label>
                            <select wire:model="mata_kuliah_id" class="w-full rounded-xl border-gray-300 bg-gray-50 focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 text-sm py-2.5 mt-1 transition">
                                <option value="">-- Pilih Mata Kuliah --</option>
                                @foreach ($mataKuliah as $mk)
                                    <option value="{{ $mk->id }}">{{ $mk->nama_mk }} ({{ $mk->sks }} SKS)</option>
                                @endforeach
                            </select>
                            @error('mata_kuliah_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Kelas</label>
                            <select wire:model="kelas_id" class="w-full rounded-xl border-gray-300 bg-gray-50 focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 text-sm py-2.5 mt-1 transition">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach ($kelasList as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                            @error('kelas_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Dosen Pengampu</label>
                            <select wire:model="dosen_id" class="w-full rounded-xl border-gray-300 bg-gray-50 focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 text-sm py-2.5 mt-1 transition">
                                <option value="">-- Pilih Dosen --</option>
                                @foreach ($dosenList as $d)
                                    <option value="{{ $d->id }}">{{ $d->nama }}</option>
                                @endforeach
                            </select>
                            @error('dosen_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Ruang Praktikum</label>
                            <select wire:model="ruang_praktikum_id" class="w-full rounded-xl border-gray-300 bg-gray-50 focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 text-sm py-2.5 mt-1 transition">
                                <option value="">-- Pilih Ruang --</option>
                                @foreach ($ruangList as $r)
                                    <option value="{{ $r->id }}">{{ $r->nama_ruang }} (Kapasitas {{ $r->kapasitas }})</option>
                                @endforeach
                            </select>
                            @error('ruang_praktikum_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Tahun Akademik</label>
                            <select wire:model="tahun_akademik_id" class="w-full rounded-xl border-gray-300 bg-gray-50 focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 text-sm py-2.5 mt-1 transition">
                                <option value="">-- Pilih Tahun Akademik --</option>
                                @foreach ($tahunList as $t)
                                    <option value="{{ $t->id }}">{{ $t->nama }} ({{ $t->semester }})</option>
                                @endforeach
                            </select>
                            @error('tahun_akademik_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Hari</label>
                            <select wire:model="hari" class="w-full rounded-xl border-gray-300 bg-gray-50 focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 text-sm py-2.5 mt-1 transition">
                                <option value="">-- Pilih Hari --</option>
                                @foreach ($hariList as $h)
                                    <option value="{{ $h }}">{{ $h }}</option>
                                @endforeach
                            </select>
                            @error('hari') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Jam Mulai</label>
                            <input type="time" wire:model="jam_mulai" class="w-full rounded-xl border-gray-300 bg-gray-50 focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 text-sm py-2.5 mt-1 transition">
                            @error('jam_mulai') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Jam Selesai</label>
                            <input type="time" wire:model="jam_selesai" class="w-full rounded-xl border-gray-300 bg-gray-50 focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 text-sm py-2.5 mt-1 transition">
                            @error('jam_selesai') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</label>
                            <select wire:model="status" class="w-full rounded-xl border-gray-300 bg-gray-50 focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 text-sm py-2.5 mt-1 transition">
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                                <option value="dibatalkan">Dibatalkan</option>
                                <option value="reschedule">Reschedule</option>
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Keterangan (opsional)</label>
                            <textarea wire:model="keterangan" rows="2" placeholder="Catatan tambahan bila ada..." class="w-full rounded-xl border-gray-300 bg-gray-50 focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 text-sm py-2 mt-1 transition"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Footer (Sticky/Fixed) -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-2 shrink-0">
                    <button wire:click="$set('showModal', false)" class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-200/60 rounded-xl transition font-medium">
                        Batal
                    </button>
                    <button wire:click="save" wire:loading.attr="disabled"
                            class="px-5 py-2 text-sm bg-primary text-white font-medium rounded-xl hover:bg-primary-dark transition shadow-sm disabled:opacity-50">
                        <span wire:loading.remove wire:target="save">Simpan Jadwal</span>
                        <span wire:loading wire:target="save">Mengecek bentrok...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal konfirmasi hapus --}}
    @if ($confirmDeleteId)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 px-4">
            <div class="bg-white rounded-xl w-full max-w-sm p-6 space-y-4">
                <h2 class="text-lg font-semibold text-gray-900">Hapus Jadwal?</h2>
                <p class="text-sm text-gray-500">Data yang sudah dihapus tidak bisa dikembalikan.</p>
                <div class="flex justify-end gap-2">
                    <button wire:click="$set('confirmDeleteId', null)" class="px-4 py-2 text-sm text-gray-500 hover:bg-gray-900/5 rounded-lg">Batal</button>
                    <button wire:click="delete" class="px-4 py-2 text-sm bg-red-600 text-white font-medium rounded-lg hover:bg-red-700">Ya, Hapus</button>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Import Excel --}}
    @if ($showImportModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl p-6 space-y-4 animate-in fade-in zoom-in-95 duration-150">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        Import Jadwal Praktikum (Excel)
                    </h2>
                    <button wire:click="$set('showImportModal', false)" class="text-gray-400 hover:text-gray-600 rounded-lg p-1 hover:bg-gray-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="text-xs text-gray-600 leading-relaxed bg-emerald-50/70 border border-emerald-100 rounded-xl p-3.5 flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-primary shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <strong>Smart Importer Aktif:</strong> Anda dapat mengunggah file Excel jurusan secara langsung. Sistem secara otomatis membaca kolom, mendaftarkan mata kuliah/kelas baru, dan mendeteksi bentrok jadwal.
                        <a href="{{ route('jadwal.template.download') }}" class="font-semibold text-primary underline block mt-1">Unduh Template Contoh (.xlsx)</a>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Pilih File Excel (.xlsx / .xls / .csv)</label>
                    <input type="file" wire:model="excelFile" accept=".xlsx,.xls,.csv" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 border border-gray-200 rounded-xl p-1 bg-gray-50/50">
                    @error('excelFile') <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>

                @if($importErrors)
                    <div class="max-h-40 overflow-y-auto bg-red-50 border border-red-200 rounded-xl p-3 space-y-1">
                        <span class="text-xs font-bold text-red-800 block">Laporan Bentrok / Lewat ({{ count($importErrors) }} catatan):</span>
                        @foreach($importErrors as $err)
                            <p class="text-[11px] text-red-600 leading-tight">• {{ $err }}</p>
                        @endforeach
                    </div>
                @endif

                @if($importSuccessCount > 0)
                    <div class="bg-green-50 border border-green-200 rounded-xl p-3 text-xs text-green-800">
                        ✓ Berhasil mengimpor <strong>{{ $importSuccessCount }}</strong> jadwal praktikum!
                    </div>
                @endif

                <div class="flex justify-end gap-2 pt-3 border-t border-gray-100">
                    <button wire:click="$set('showImportModal', false)" class="px-4 py-2 text-xs font-medium text-gray-600 hover:bg-gray-100 rounded-xl transition">
                        Tutup
                    </button>
                    <button wire:click="importExcel" wire:loading.attr="disabled" class="px-4 py-2 text-xs font-semibold bg-primary hover:bg-primary-dark text-white rounded-xl transition shadow-xs disabled:opacity-50 flex items-center gap-1.5">
                        <span wire:loading.remove wire:target="importExcel">Proses Import</span>
                        <span wire:loading wire:target="importExcel">Mengimpor data...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
