<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-zinc-100 tracking-tight">Kelola Panduan & Tata Tertib Praktikum</h1>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-zinc-400 mt-0.5">Atur isi aturan, SOP operasional, dan petunjuk penggunaan sistem untuk publik maupun pengelola internal.</p>
        </div>
        <button wire:click="openCreate"
            class="bg-[#0F352A] hover:bg-[#164336] dark:bg-emerald-600 dark:hover:bg-emerald-500 text-white font-bold px-4 py-2.5 rounded-xl transition shadow-xs flex items-center justify-center gap-2 text-xs shrink-0 self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Tambah Panduan Baru</span>
        </button>
    </div>

    {{-- Alert --}}
    @if (session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 text-emerald-800 dark:text-emerald-300 text-xs rounded-xl px-4 py-3 shadow-2xs flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" @click="$el.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 dark:hover:text-emerald-200 text-xs font-bold">✕</button>
        </div>
    @endif

    {{-- Filter Bar & Stats --}}
    <div class="bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 rounded-2xl p-3.5 shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-2">
            <button wire:click="$set('filterKategori', 'semua')"
                @class([
                    'px-3.5 py-1.5 rounded-xl text-xs font-bold transition shadow-2xs',
                    'bg-[#0F352A] dark:bg-emerald-600 text-white' => $filterKategori === 'semua',
                    'bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 text-gray-700 dark:text-zinc-300 hover:bg-gray-50' => $filterKategori !== 'semua',
                ])>
                Semua Kategori ({{ $totalPublik + $totalInternal }})
            </button>
            <button wire:click="$set('filterKategori', 'publik')"
                @class([
                    'px-3.5 py-1.5 rounded-xl text-xs font-bold transition shadow-2xs flex items-center gap-1.5',
                    'bg-[#0F352A] dark:bg-emerald-600 text-white' => $filterKategori === 'publik',
                    'bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 text-gray-700 dark:text-zinc-300 hover:bg-gray-50' => $filterKategori !== 'publik',
                ])>
                <span>Publik / Mahasiswa</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-black/15 font-mono">{{ $totalPublik }}</span>
            </button>
            <button wire:click="$set('filterKategori', 'internal')"
                @class([
                    'px-3.5 py-1.5 rounded-xl text-xs font-bold transition shadow-2xs flex items-center gap-1.5',
                    'bg-[#0F352A] dark:bg-emerald-600 text-white' => $filterKategori === 'internal',
                    'bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 text-gray-700 dark:text-zinc-300 hover:bg-gray-50' => $filterKategori !== 'internal',
                ])>
                <span>SOP Internal</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-black/15 font-mono">{{ $totalInternal }}</span>
            </button>
        </div>

        <div class="w-full md:w-72">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari judul atau isi panduan..."
                class="w-full text-xs rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-900 dark:text-zinc-100 placeholder:text-gray-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 py-2 px-3 transition">
        </div>
    </div>

    {{-- Table Panduan --}}
    <div class="bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 rounded-2xl shadow-2xs overflow-hidden">
        <table class="w-full text-xs text-left">
            <thead class="bg-gray-50/80 dark:bg-zinc-800/60 border-b border-gray-200/80 dark:border-zinc-700/80 text-[11px] font-bold text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-3.5 w-20 text-center">Urutan</th>
                    <th class="px-5 py-3.5 w-36">Kategori</th>
                    <th class="px-5 py-3.5">Judul & Keterangan</th>
                    <th class="px-5 py-3.5 w-28 text-center">Status</th>
                    <th class="px-5 py-3.5 w-36 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-zinc-800 text-gray-800 dark:text-zinc-200">
                @forelse ($panduanList as $p)
                    <tr class="hover:bg-emerald-50/30 dark:hover:bg-zinc-800/40 transition">
                        <td class="px-5 py-3.5 text-center font-bold text-gray-700 dark:text-zinc-300">
                            <span class="bg-gray-100 dark:bg-zinc-800 px-2 py-1 rounded-md border border-gray-200/60 dark:border-zinc-700 font-mono text-[11px]">
                                #{{ $p->urutan }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            @if ($p->kategori === 'publik')
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border border-blue-200">
                                    Publik
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300 border border-purple-200">
                                    SOP Internal
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            <p class="font-bold text-gray-900 dark:text-zinc-100 text-sm leading-snug">{{ $p->judul }}</p>
                            @if ($p->subjudul)
                                <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">{{ $p->subjudul }}</p>
                            @endif
                            <div class="mt-1 text-[11px] text-gray-400 dark:text-zinc-500 line-clamp-1">
                                {{ strip_tags($p->konten) }}
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <button wire:click="toggleActive({{ $p->id }})"
                                class="cursor-pointer transition"
                                title="Klik untuk ubah status">
                                @if ($p->is_active)
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40">
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-gray-100 dark:bg-zinc-800 text-gray-500">
                                        Nonaktif
                                    </span>
                                @endif
                            </button>
                        </td>
                        <td class="px-5 py-3.5 text-right space-x-2">
                            <button wire:click="openEdit({{ $p->id }})" class="font-bold text-xs text-emerald-700 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 transition">Edit</button>
                            <button wire:click="delete({{ $p->id }})" wire:confirm="Yakin ingin menghapus panduan '{{ $p->judul }}'?" class="font-bold text-xs text-rose-600 dark:text-rose-400 hover:text-rose-700 dark:hover:text-rose-300 transition">Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-gray-400 dark:text-zinc-500 text-sm">
                            Tidak ada panduan yang cocok dengan pencarian atau filter.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($panduanList->hasPages())
            <div class="p-4 border-t border-gray-100 dark:border-zinc-800">
                {{ $panduanList->links() }}
            </div>
        @endif
    </div>

    {{-- Modal Tambah / Edit --}}
    @if ($showModal)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs z-50 overflow-y-auto p-4 sm:p-6">
            <div class="flex min-h-full items-start sm:items-center justify-center py-4 sm:py-6">
                <div class="bg-white dark:bg-zinc-900 rounded-3xl w-full max-w-2xl max-h-[90vh] flex flex-col shadow-2xl border border-gray-100 dark:border-zinc-800 text-gray-900 dark:text-zinc-100 overflow-hidden my-auto animate-in fade-in zoom-in-95 duration-150">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-zinc-800 flex items-center justify-between shrink-0">
                        <div>
                            <h2 class="text-base sm:text-lg font-bold text-gray-900 dark:text-zinc-100">
                                {{ $editingId ? 'Edit Butir Panduan / SOP' : 'Tambah Panduan / SOP Baru' }}
                            </h2>
                            <p class="text-xs text-gray-500 dark:text-zinc-400">Atur pedoman dan tata tertib operasional laboratorium.</p>
                        </div>
                        <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600 dark:hover:text-zinc-300 rounded-lg p-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="p-6 overflow-y-auto space-y-4 flex-1">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300 uppercase tracking-wider block mb-1">Kategori Sasaran *</label>
                                <select wire:model="kategori" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 text-xs py-2 px-3 text-gray-900 dark:text-zinc-100 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                                    <option value="publik">Publik (Mahasiswa & Tamu)</option>
                                    <option value="internal">SOP Internal (Operator & Dosen)</option>
                                </select>
                                @error('kategori') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300 uppercase tracking-wider block mb-1">Nomor Urutan *</label>
                                <input type="number" min="1" wire:model="urutan" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 text-xs py-2 px-3 text-gray-900 dark:text-zinc-100 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                                @error('urutan') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="flex items-center sm:pt-6">
                                <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-gray-700 dark:text-zinc-300 select-none">
                                    <input type="checkbox" wire:model="is_active" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500/20">
                                    <span>Tampilkan (Status Aktif)</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300 uppercase tracking-wider block mb-1">Judul Bagian *</label>
                            <input type="text" wire:model="judul" placeholder="Contoh: Tata Tertib Berpakaian & Penggunaan Jas Lab"
                                class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 text-xs py-2 px-3 text-gray-900 dark:text-zinc-100 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                            @error('judul') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300 uppercase tracking-wider block mb-1">Ringkasan / Subjudul (Opsional)</label>
                            <input type="text" wire:model="subjudul" placeholder="Contoh: Ketentuan pakaian resmi, batas waktu hadir, dan etika..."
                                class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 text-xs py-2 px-3 text-gray-900 dark:text-zinc-100 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                            @error('subjudul') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300 uppercase tracking-wider">Isi Detail Panduan (HTML & List)</label>
                                <span class="text-[11px] text-gray-400 dark:text-zinc-500">Mendukung &lt;p&gt;, &lt;ul&gt;, &lt;li&gt;, &lt;strong&gt;</span>
                            </div>
                            <textarea wire:model="konten" rows="8" placeholder="Tuliskan butir-butir aturan secara terperinci..."
                                class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 font-mono text-xs py-2.5 px-3 text-gray-900 dark:text-zinc-100 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20"></textarea>
                            @error('konten') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 dark:bg-zinc-800/60 border-t border-gray-100 dark:border-zinc-800 flex justify-end gap-2 shrink-0">
                        <button wire:click="$set('showModal', false)" type="button" class="px-4 py-2 text-xs font-semibold text-gray-600 dark:text-zinc-400 hover:bg-gray-100 dark:hover:bg-zinc-800 rounded-xl transition">
                            Batal
                        </button>
                        <button wire:click="save" type="button" class="px-5 py-2 text-xs bg-[#0F352A] hover:bg-[#164336] dark:bg-emerald-600 dark:hover:bg-emerald-500 text-white font-bold rounded-xl transition shadow-xs">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
