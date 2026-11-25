<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="min-w-0">
            <h1 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-zinc-100 tracking-tight">Penanggung Jawab Laboratorium</h1>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-zinc-400 mt-0.5">
                Kelola struktur pimpinan, pranata, teknisi, dan asisten penanggung jawab tiap ruang laboratorium yang tampil di halaman publik.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2 shrink-0">
            <a href="{{ route('struktur.lab') }}" target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-1.5 px-3 py-2 border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 hover:bg-gray-50 dark:hover:bg-zinc-800 text-gray-700 dark:text-zinc-300 text-xs font-semibold rounded-xl transition shadow-2xs whitespace-nowrap">
                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span>Lihat Halaman Publik</span>
            </a>
            <button wire:click="openCreate" type="button"
                    class="inline-flex items-center gap-1.5 bg-[#0B2E23] hover:bg-[#124334] dark:bg-emerald-600 dark:hover:bg-emerald-500 text-white text-xs font-bold px-3.5 py-2 rounded-xl transition shadow-xs whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Penanggung Jawab</span>
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 text-emerald-800 dark:text-emerald-300 text-xs rounded-xl px-4 py-3 shadow-2xs">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60 text-rose-800 dark:text-rose-300 text-xs rounded-xl px-4 py-3 shadow-2xs">{{ session('error') }}</div>
    @endif

    {{-- Search & Filter Controls --}}
    <div class="space-y-2.5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
            <div class="relative flex-1 min-w-0">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama, jabatan, NIP/NIDN, atau ruang lab..."
                       class="w-full pl-10 pr-4 py-2 rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-2xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs text-gray-900 dark:text-zinc-100 placeholder:text-gray-400 transition">
            </div>

            <div class="flex items-center justify-end gap-2 shrink-0">
                @if ($search !== '' || $filterFakultas || $filterRuang || $filterTingkat !== '')
                    <button wire:click="resetFilters" type="button"
                            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-rose-200 dark:border-rose-800/60 bg-rose-50/70 dark:bg-rose-950/40 text-xs text-rose-600 dark:text-rose-400 hover:bg-rose-100 font-bold transition whitespace-nowrap">
                        Reset Filter
                    </button>
                @endif

                <div class="flex items-center gap-1 bg-gray-100 dark:bg-zinc-800 p-1 rounded-xl shrink-0">
                    <button wire:click="$set('viewMode', 'grid')" type="button" title="Tampilan Kartu"
                            @class([
                                'p-1.5 rounded-lg transition',
                                'bg-white dark:bg-zinc-900 text-emerald-700 dark:text-emerald-400 shadow-2xs' => $viewMode === 'grid',
                                'text-gray-500 dark:text-zinc-400 hover:text-gray-700 dark:hover:text-zinc-200' => $viewMode !== 'grid',
                            ])>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    </button>
                    <button wire:click="$set('viewMode', 'table')" type="button" title="Tampilan Tabel"
                            @class([
                                'p-1.5 rounded-lg transition',
                                'bg-white dark:bg-zinc-900 text-emerald-700 dark:text-emerald-400 shadow-2xs' => $viewMode === 'table',
                                'text-gray-500 dark:text-zinc-400 hover:text-gray-700 dark:hover:text-zinc-200' => $viewMode !== 'table',
                            ])>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 {{ $isSuperAdmin ? 'lg:grid-cols-3' : 'lg:grid-cols-2' }} gap-2.5">
            @if ($isSuperAdmin)
                <select wire:model.live="filterFakultas" class="w-full truncate rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-xs px-3 py-2 text-gray-900 dark:text-zinc-100 shadow-2xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                    <option value="">Semua Fakultas</option>
                    @foreach ($fakultasList as $fak)
                        <option value="{{ $fak->id }}" wire:key="filter-fak-{{ $fak->id }}">[{{ $fak->kode_fakultas }}] {{ $fak->nama_fakultas }}</option>
                    @endforeach
                </select>
            @endif

            <select wire:model.live="filterRuang" class="w-full truncate rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-xs px-3 py-2 text-gray-900 dark:text-zinc-100 shadow-2xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                <option value="">Semua Ruang Laboratorium</option>
                @foreach ($ruangList as $r)
                    <option value="{{ $r->id }}" wire:key="filter-ruang-{{ $r->id }}">{{ $r->nama_ruang }}</option>
                @endforeach
            </select>

            <select wire:model.live="filterTingkat" class="w-full truncate rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-xs px-3 py-2 text-gray-900 dark:text-zinc-100 shadow-2xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                <option value="">Semua Tingkat Struktur</option>
                <option value="pimpinan">Pimpinan (Baris Atas / Utama)</option>
                <option value="anggota">Anggota / Pranata / Teknisi (Grid Bawah)</option>
            </select>
        </div>
    </div>

    {{-- List Data --}}
    @if ($viewMode === 'grid')
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3.5">
            @forelse ($penanggungJawab as $pj)
                <div wire:key="pj-card-{{ $pj->id }}" class="bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 {{ $pj->tingkat === 'pimpinan' ? 'border-t-4 border-t-[#0B2E23] dark:border-t-emerald-500' : '' }} rounded-2xl p-3.5 shadow-2xs hover:shadow-md transition flex flex-col justify-between gap-3">
                    <div class="flex flex-col items-center text-center gap-2">
                        <div class="flex items-center justify-between w-full gap-1">
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold {{ $pj->tingkat === 'pimpinan' ? 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300' : 'bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-zinc-300' }}">
                                {{ $pj->tingkat === 'pimpinan' ? 'Pimpinan' : 'Anggota' }} #{{ $pj->urutan }}
                            </span>
                            <span class="w-2 h-2 rounded-full {{ $pj->status === 'aktif' ? 'bg-emerald-500' : 'bg-gray-400' }}" title="{{ ucfirst($pj->status) }}"></span>
                        </div>

                        <div class="w-16 h-20 rounded-xl bg-gray-100 dark:bg-zinc-800 border border-gray-200/70 dark:border-zinc-700 overflow-hidden flex items-center justify-center shrink-0">
                            @if ($pj->foto_url)
                                <img src="{{ $pj->foto_url }}" alt="{{ $pj->nama }}" class="w-full h-full object-cover object-top">
                            @else
                                <span class="text-base font-black text-[#0B2E23] dark:text-emerald-300">{{ $pj->inisial }}</span>
                            @endif
                        </div>

                        <div class="space-y-0.5 w-full">
                            <h3 class="font-bold text-xs sm:text-sm text-gray-900 dark:text-zinc-100 leading-snug line-clamp-2">{{ $pj->nama }}</h3>
                            <p class="text-[11px] font-medium text-emerald-700 dark:text-emerald-400 leading-tight">{{ $pj->jabatan }}</p>
                            @if ($pj->nip_nidn)
                                <p class="text-[10px] font-mono text-gray-400 dark:text-zinc-500 truncate">{{ $pj->nip_nidn }}</p>
                            @endif
                            <p class="text-[10px] text-gray-500 dark:text-zinc-400 pt-1.5 mt-1 border-t border-gray-100 dark:border-zinc-800 truncate">
                                {{ $pj->ruangPraktikum?->nama_ruang ?? '-' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center justify-center gap-1.5 pt-1">
                        <button wire:click="openEdit({{ $pj->id }})" type="button" class="flex-1 px-2.5 py-1.5 text-[11px] font-semibold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 hover:bg-emerald-100 rounded-xl transition">Edit</button>
                        <button wire:click="confirmDelete({{ $pj->id }})" type="button" class="flex-1 px-2.5 py-1.5 text-[11px] font-semibold text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 rounded-xl transition">Hapus</button>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white dark:bg-zinc-900 rounded-2xl p-12 text-center border border-gray-200/80 dark:border-zinc-800">
                    <p class="text-sm font-semibold text-gray-700 dark:text-zinc-300">Belum ada data penanggung jawab laboratorium</p>
                    <p class="text-xs text-gray-400 dark:text-zinc-500 mt-1">Klik tombol "Tambah Penanggung Jawab" untuk menyusun struktur tim laboratorium.</p>
                </div>
            @endforelse
        </div>
    @else
        <div class="bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 rounded-2xl overflow-x-auto shadow-2xs">
            <table class="w-full text-xs text-left">
                <thead class="bg-gray-50/80 dark:bg-zinc-800/60 border-b border-gray-200/80 dark:border-zinc-700/80 text-[11px] font-bold text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">Nama & Kontak</th>
                        <th class="px-5 py-3.5">Jabatan</th>
                        <th class="px-5 py-3.5">Ruang Laboratorium</th>
                        <th class="px-5 py-3.5">Tingkat / Urutan</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-zinc-800 text-gray-800 dark:text-zinc-200">
                    @forelse ($penanggungJawab as $pj)
                        <tr wire:key="pj-row-{{ $pj->id }}" class="hover:bg-emerald-50/30 dark:hover:bg-zinc-800/40 transition">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    @if ($pj->foto_url)
                                        <img src="{{ $pj->foto_url }}" alt="{{ $pj->nama }}" class="w-9 h-9 rounded-lg object-cover object-top shrink-0 border border-gray-200 dark:border-zinc-700">
                                    @else
                                        <div class="w-9 h-9 rounded-lg bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 flex items-center justify-center font-bold text-xs shrink-0">
                                            {{ $pj->inisial }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-bold text-gray-900 dark:text-zinc-100">{{ $pj->nama }}</p>
                                        @if ($pj->nip_nidn)
                                            <p class="text-[11px] font-mono text-gray-400">{{ $pj->nip_nidn }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 font-medium">{{ $pj->jabatan }}</td>
                            <td class="px-5 py-3.5">
                                <p class="font-medium text-gray-900 dark:text-zinc-100">{{ $pj->ruangPraktikum?->nama_ruang ?? '-' }}</p>
                                <p class="text-[11px] text-gray-400">{{ $pj->ruangPraktikum?->fakultas?->nama_fakultas ?? '' }}</p>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="px-2 py-0.5 rounded-md text-[11px] font-bold {{ $pj->tingkat === 'pimpinan' ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300' : 'bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-zinc-300' }}">
                                    {{ ucfirst($pj->tingkat) }} (#{{ $pj->urutan }})
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold {{ $pj->status === 'aktif' ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300' : 'bg-gray-100 dark:bg-zinc-800 text-gray-500' }}">
                                    {{ ucfirst($pj->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right space-x-2">
                                <button wire:click="openEdit({{ $pj->id }})" type="button" class="font-bold text-xs text-emerald-700 dark:text-emerald-400 hover:underline">Edit</button>
                                <button wire:click="confirmDelete({{ $pj->id }})" type="button" class="font-bold text-xs text-rose-600 dark:text-rose-400 hover:underline">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-8 text-center text-gray-400 dark:text-zinc-500">Belum ada data penanggung jawab laboratorium.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    {{ $penanggungJawab->links() }}

    {{-- Modal Create/Edit --}}
    @if ($showModal)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs z-50 overflow-y-auto p-4 sm:p-6">
            <div class="flex min-h-full items-start sm:items-center justify-center py-4 sm:py-6">
                <div class="bg-white dark:bg-zinc-900 rounded-3xl w-full max-w-xl max-h-[90vh] flex flex-col shadow-2xl border border-gray-100 dark:border-zinc-800 overflow-hidden my-auto text-gray-900 dark:text-zinc-100">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-zinc-800 flex items-center justify-between shrink-0">
                        <div>
                            <h2 class="text-base sm:text-lg font-bold">{{ $editId ? 'Edit Penanggung Jawab Lab' : 'Tambah Penanggung Jawab Lab' }}</h2>
                            <p class="text-xs text-gray-500 dark:text-zinc-400">Data aktif akan ditampilkan pada halaman publik Struktur Laboratorium.</p>
                        </div>
                        <button wire:click="$set('showModal', false)" type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-zinc-300 rounded-lg p-1 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="p-6 overflow-y-auto space-y-4 flex-1">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <div class="sm:col-span-2">
                                <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300">Ruang Laboratorium *</label>
                                <select wire:model="ruang_praktikum_id" @disabled($isKetuaLabLocked)
                                        class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100 disabled:opacity-60">
                                    <option value="">-- Pilih Ruang Laboratorium --</option>
                                    @foreach ($ruangList as $r)
                                        <option value="{{ $r->id }}" wire:key="modal-ruang-{{ $r->id }}">
                                            {{ $r->nama_ruang }} {{ $r->fakultas ? '('.$r->fakultas->kode_fakultas.')' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ruang_praktikum_id') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300">Nama Lengkap & Gelar *</label>
                                <input type="text" wire:model="nama" placeholder="Contoh: Abdurrozaq Nasrudin, S.Kom., M.M."
                                       class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100">
                                @error('nama') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300">Jabatan *</label>
                                <input type="text" wire:model="jabatan" placeholder="Contoh: Ketua Laboratorium / Pranata Lab"
                                       class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100">
                                @error('jabatan') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300">NIP / NIDN (Opsional)</label>
                                <input type="text" wire:model="nip_nidn" placeholder="Contoh: 198109102008011010"
                                       class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100">
                                @error('nip_nidn') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300">Tingkat Posisi di Struktur *</label>
                                <select wire:model="tingkat" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100">
                                    <option value="pimpinan">Pimpinan (Baris Atas / Terpusat)</option>
                                    <option value="anggota">Anggota / Pranata / Teknisi (Grid Bawah)</option>
                                </select>
                                @error('tingkat') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300">Urutan Tampil *</label>
                                <input type="number" min="1" max="999" wire:model="urutan"
                                       class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100">
                                @error('urutan') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Upload Foto --}}
                            <div class="sm:col-span-2">
                                <div class="flex items-center justify-between gap-2">
                                    <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300">Foto Profil (JPG, PNG, WebP, HEIC — Maks. 20 MB)</label>
                                    @if ($foto || $existingFoto)
                                        <button type="button" wire:click="removeFoto"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-rose-50 dark:bg-rose-950/50 hover:bg-rose-100 dark:hover:bg-rose-950 text-rose-600 dark:text-rose-400 border border-rose-200/70 dark:border-rose-800/60 text-[11px] font-bold transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            <span>{{ $foto ? 'Batalkan Foto' : 'Hapus Foto' }}</span>
                                        </button>
                                    @endif
                                </div>
                                <input type="file" wire:model="foto" accept=".jpg,.jpeg,.png,.heic,.heif,.webp"
                                       class="w-full mt-1.5 text-xs text-gray-500 dark:text-zinc-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 dark:file:bg-emerald-950/50 file:text-emerald-700 dark:file:text-emerald-300 hover:file:bg-emerald-100 border border-gray-200 dark:border-zinc-700 rounded-xl p-1 bg-gray-50/50 dark:bg-zinc-800">
                                <div wire:loading wire:target="foto" class="text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold mt-1.5">
                                    Mengunggah foto... mohon tunggu.
                                </div>
                                @if ($hapusFoto && ! $foto)
                                    <p class="text-[11px] font-semibold text-amber-600 dark:text-amber-400 mt-1.5">Foto profil ditandai untuk dihapus. Klik Simpan untuk menerapkan.</p>
                                @endif
                                @error('foto') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror

                                @if ($foto)
                                    <div class="mt-2.5 flex items-center gap-3 p-2.5 rounded-xl bg-emerald-50/60 dark:bg-emerald-950/30 border border-emerald-200/70 dark:border-emerald-800/60">
                                        @if (method_exists($foto, 'isPreviewable') && $foto->isPreviewable())
                                            <img src="{{ $foto->temporaryUrl() }}" alt="Preview Foto" class="w-14 h-16 rounded-lg object-cover object-top shrink-0">
                                        @else
                                            <div class="w-14 h-16 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 flex items-center justify-center text-[10px] font-bold shrink-0">
                                                {{ strtoupper($foto->getClientOriginalExtension()) }}
                                            </div>
                                        @endif
                                        <div class="min-w-0 text-xs">
                                            <p class="font-bold text-emerald-900 dark:text-emerald-200 truncate">{{ $foto->getClientOriginalName() }}</p>
                                            <p class="text-[11px] text-emerald-700 dark:text-emerald-400">Siap disimpan</p>
                                        </div>
                                    </div>
                                @elseif ($existingFoto && \Illuminate\Support\Facades\Storage::disk('public')->exists($existingFoto))
                                    <div class="mt-2.5 flex items-center gap-3 p-2.5 rounded-xl bg-gray-50 dark:bg-zinc-800/60 border border-gray-200 dark:border-zinc-700">
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($existingFoto) }}" alt="Foto Saat Ini" class="w-14 h-16 rounded-lg object-cover object-top shrink-0">
                                        <div class="text-xs text-gray-600 dark:text-zinc-300">
                                            <p class="font-semibold">Foto tersimpan saat ini</p>
                                            <p class="text-[11px] text-gray-400">Pilih berkas baru jika ingin mengganti foto.</p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300">Email / Gmail (Opsional)</label>
                                <input type="email" wire:model="email" placeholder="nama@gmail.com / nama@uinssc.ac.id"
                                       class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100">
                                <p class="text-[10px] text-gray-400 dark:text-zinc-500 mt-1">Gunakan format email valid dengan domain (mis. @gmail.com / @uinssc.ac.id).</p>
                                @error('email') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300">No. WhatsApp (Opsional)</label>
                                <input type="tel" inputmode="tel" wire:model="no_telepon" placeholder="081234567890 atau +6281234567890"
                                       class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100">
                                <p class="text-[10px] text-gray-400 dark:text-zinc-500 mt-1">Format nomor Indonesia diawali 08 atau +628 (10–14 digit).</p>
                                @error('no_telepon') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300">Tautan Instagram (Opsional)</label>
                                <input type="url" wire:model="link_instagram" placeholder="https://instagram.com/..."
                                       class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100">
                                @error('link_instagram') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300">Tautan LinkedIn (Opsional)</label>
                                <input type="url" wire:model="link_linkedin" placeholder="https://linkedin.com/in/..."
                                       class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100">
                                @error('link_linkedin') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300">Status Tampil *</label>
                                <select wire:model="status" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100">
                                    <option value="aktif">Aktif (Tampil di Halaman Publik)</option>
                                    <option value="nonaktif">Nonaktif (Disembunyikan)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 dark:bg-zinc-800/60 border-t border-gray-100 dark:border-zinc-800 flex justify-end gap-2 shrink-0">
                        <button wire:click="$set('showModal', false)" type="button" class="px-4 py-2 text-xs font-semibold text-gray-600 dark:text-zinc-400 hover:bg-gray-200/60 dark:hover:bg-zinc-700 rounded-xl transition">
                            Batal
                        </button>
                        <button wire:click="save" wire:loading.attr="disabled" type="button"
                                class="px-5 py-2 text-xs bg-[#0B2E23] hover:bg-[#124334] dark:bg-emerald-600 dark:hover:bg-emerald-500 text-white font-bold rounded-xl transition shadow-xs disabled:opacity-50">
                            <span wire:loading.remove wire:target="save,foto">Simpan</span>
                            <span wire:loading wire:target="save,foto">Menyimpan...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Konfirmasi Hapus --}}
    @if ($confirmDeleteId)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs z-50 overflow-y-auto p-4 sm:p-6">
            <div class="flex min-h-full items-start sm:items-center justify-center py-4 sm:py-6">
                <div class="bg-white dark:bg-zinc-900 rounded-3xl w-full max-w-sm p-6 space-y-4 shadow-2xl border border-gray-100 dark:border-zinc-800 text-gray-900 dark:text-zinc-100 my-auto">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center mx-auto border border-rose-200 dark:border-rose-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div class="text-center">
                        <h2 class="text-lg font-bold">Hapus Penanggung Jawab?</h2>
                        <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">Data ini tidak akan lagi ditampilkan pada halaman publik Struktur Laboratorium.</p>
                    </div>
                    <div class="flex justify-center gap-2 pt-2">
                        <button wire:click="$set('confirmDeleteId', null)" type="button" class="px-4 py-2 text-xs text-gray-600 dark:text-zinc-400 hover:bg-gray-100 dark:hover:bg-zinc-800 font-semibold rounded-xl transition">Batal</button>
                        <button wire:click="delete" type="button" class="px-4 py-2 text-xs bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl shadow-xs transition">Ya, Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
