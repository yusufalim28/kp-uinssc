<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-zinc-100 tracking-tight">Kelola Pengguna & Operator</h1>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-zinc-400 mt-0.5">Manajemen akun pengelola, hak akses peran (Super Admin, Operator, Dosen), dan penugasan fakultas/prodi.</p>
        </div>
        <button wire:click="openCreate"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#0F352A] hover:bg-[#164336] dark:bg-emerald-600 dark:hover:bg-emerald-500 text-white text-xs font-bold transition shadow-xs shrink-0 self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Tambah Pengguna</span>
        </button>
    </div>

    {{-- Flash Notifications --}}
    @if (session()->has('success'))
        <div class="bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 text-emerald-800 dark:text-emerald-300 text-xs rounded-xl px-4 py-3 shadow-2xs flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" @click="$el.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 dark:hover:text-emerald-200">✕</button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60 text-rose-800 dark:text-rose-300 text-xs rounded-xl px-4 py-3 shadow-2xs flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" @click="$el.parentElement.remove()" class="text-rose-600 hover:text-rose-800 dark:hover:text-rose-200">✕</button>
        </div>
    @endif

    {{-- Search & Filters --}}
    <div class="bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 rounded-2xl p-3.5 shadow-2xs flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
        <div class="relative flex-1">
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </span>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari berdasarkan nama atau email pengguna..."
                   class="w-full pl-10 pr-4 py-2 rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-xs text-gray-900 dark:text-zinc-100 placeholder:text-gray-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition">
        </div>

        <div class="w-full sm:w-56">
            <select wire:model.live="filterRole" class="w-full py-2 px-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-xs text-gray-900 dark:text-zinc-100 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition">
                <option value="">Semua Peran (Role)</option>
                <option value="super-admin">Super Admin</option>
                <option value="operator">Operator Fakultas/Prodi</option>
                <option value="dosen">Dosen</option>
            </select>
        </div>
    </div>

    {{-- Desktop Table View --}}
    <div class="hidden md:block bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 rounded-2xl shadow-2xs overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-gray-50/80 dark:bg-zinc-800/60 border-b border-gray-200/80 dark:border-zinc-700/80 text-[11px] font-bold text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-3.5">Nama & Email</th>
                    <th class="px-5 py-3.5">Peran (Role)</th>
                    <th class="px-5 py-3.5">Unit Penugasan (Fakultas / Prodi)</th>
                    <th class="px-5 py-3.5 text-right w-36">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-zinc-800 text-gray-800 dark:text-zinc-200">
                @forelse ($users as $u)
                    <tr class="hover:bg-emerald-50/30 dark:hover:bg-zinc-800/40 transition">
                        <td class="px-5 py-3.5">
                            <div class="font-bold text-gray-900 dark:text-zinc-100">{{ $u->name }}</div>
                            <div class="text-xs text-gray-400 dark:text-zinc-500 font-mono">{{ $u->email }}</div>
                        </td>
                        <td class="px-5 py-3.5">
                            @foreach ($u->roles as $r)
                                @if($r->name === 'super-admin')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800/50">
                                        👑 Super Admin
                                    </span>
                                @elseif($r->name === 'operator')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800/50">
                                        💼 Operator
                                    </span>
                                @elseif($r->name === 'dosen')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/50">
                                        🎓 Dosen
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-xs bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300">{{ $r->name }}</span>
                                @endif
                            @endforeach
                        </td>
                        <td class="px-5 py-3.5 text-xs text-gray-600 dark:text-zinc-300">
                            @if ($u->fakultas)
                                <div class="font-semibold text-gray-900 dark:text-zinc-100">🏛️ {{ $u->fakultas->nama_fakultas }}</div>
                                @if ($u->programStudi)
                                    <div class="text-gray-400 dark:text-zinc-400 font-normal">Prodi: {{ $u->programStudi->nama_program_studi }}</div>
                                @else
                                    <div class="text-gray-400 dark:text-zinc-500 italic">Tingkat Fakultas (Semua Prodi)</div>
                                @endif
                            @else
                                <span class="text-gray-400 dark:text-zinc-500 italic">Tingkat Universitas (Pusat)</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right space-x-2">
                            <button wire:click="openEdit({{ $u->id }})" class="font-bold text-xs text-emerald-700 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 transition">Edit</button>
                            @if ($u->id !== auth()->id())
                                <button wire:click="confirmDelete({{ $u->id }})" class="font-bold text-xs text-rose-600 dark:text-rose-400 hover:text-rose-700 dark:hover:text-rose-300 transition">Hapus</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-10 text-center text-gray-400 dark:text-zinc-500 text-sm">
                            Tidak ada data pengguna ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Card View --}}
    <div class="md:hidden grid grid-cols-1 sm:grid-cols-2 gap-3.5">
        @forelse ($users as $u)
            <div class="bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 rounded-2xl p-4 shadow-2xs space-y-3">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <h3 class="font-bold text-sm text-gray-900 dark:text-zinc-100 leading-snug">{{ $u->name }}</h3>
                        <p class="text-xs text-gray-400 dark:text-zinc-500 font-mono">{{ $u->email }}</p>
                    </div>
                    @foreach ($u->roles as $r)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold shrink-0 {{ $r->name === 'super-admin' ? 'bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300 border border-purple-200' : ($r->name === 'operator' ? 'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border border-blue-200' : 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60') }}">
                            {{ ucfirst($r->name) }}
                        </span>
                    @endforeach
                </div>

                <div class="text-xs text-gray-600 dark:text-zinc-400 bg-gray-50/80 dark:bg-zinc-800/60 rounded-xl p-2.5 space-y-0.5 border border-gray-100 dark:border-zinc-700">
                    @if ($u->fakultas)
                        <div class="font-semibold text-gray-900 dark:text-zinc-200">🏛️ {{ $u->fakultas->nama_fakultas }}</div>
                        @if ($u->programStudi)
                            <div class="text-gray-500 dark:text-zinc-400">Prodi: {{ $u->programStudi->nama_program_studi }}</div>
                        @endif
                    @else
                        <div class="text-gray-400 dark:text-zinc-500 italic">Universitas / Pusat</div>
                    @endif
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100 dark:border-zinc-800">
                    <button wire:click="openEdit({{ $u->id }})" class="px-3 py-1.5 text-xs font-semibold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 hover:bg-emerald-100 rounded-xl transition">Edit</button>
                    @if ($u->id !== auth()->id())
                        <button wire:click="confirmDelete({{ $u->id }})" class="px-3 py-1.5 text-xs font-semibold text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 rounded-xl transition">Hapus</button>
                    @endif
                </div>
            </div>
        @empty
            <div class="sm:col-span-2 bg-white dark:bg-zinc-900 rounded-2xl p-8 text-center border border-gray-200/80 dark:border-zinc-800 text-gray-500 dark:text-zinc-400 text-sm">
                Tidak ada data pengguna ditemukan.
            </div>
        @endforelse
    </div>

    {{ $users->links() }}

    {{-- Create / Edit Modal --}}
    @if ($showModal)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs z-50 overflow-y-auto p-4 sm:p-6">
            <div class="flex min-h-full items-start sm:items-center justify-center py-4 sm:py-6">
                <div class="bg-white dark:bg-zinc-900 rounded-3xl w-full max-w-lg p-6 space-y-4 shadow-2xl border border-gray-100 dark:border-zinc-800 my-auto text-gray-900 dark:text-zinc-100 animate-in fade-in zoom-in-95 duration-150">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-zinc-800">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 dark:text-zinc-100">{{ $editId ? 'Edit Akun Pengguna' : 'Tambah Pengguna / Operator' }}</h2>
                            <p class="text-xs text-gray-500 dark:text-zinc-400">Daftarkan akun operator jurusan, dosen, atau administrator sistem.</p>
                        </div>
                        <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600 dark:hover:text-zinc-300 p-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="space-y-3.5">
                        <div>
                            <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300">Nama Lengkap & Gelar *</label>
                            <input type="text" wire:model="name" placeholder="mis. Siti Nurhaliza, S.Kom." class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100 transition">
                            @error('name') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300">Alamat Email *</label>
                            <input type="email" wire:model="email" placeholder="mis. operator.if@praktikum.ac.id" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100 transition">
                            @error('email') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300">{{ $editId ? 'Kata Sandi Baru (Kosongkan bila tidak diubah)' : 'Kata Sandi *' }}</label>
                            <input type="password" wire:model="password" placeholder="Minimal 6 karakter" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100 transition">
                            @error('password') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300">Peran / Hak Akses (Role) *</label>
                            <select wire:model.live="role" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100 transition">
                                <option value="operator">Operator (Pengelola Jadwal Prodi / Fakultas)</option>
                                <option value="dosen">Dosen Pengampu</option>
                                <option value="super-admin">Super Admin (Otoritas Penuh)</option>
                            </select>
                            @error('role') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        @if ($role === 'operator')
                            <div class="border-t border-gray-100 dark:border-zinc-800 pt-3 space-y-3 bg-emerald-50/30 dark:bg-emerald-950/30 p-3.5 rounded-2xl border border-emerald-100 dark:border-emerald-800/40">
                                <div class="text-xs font-bold text-emerald-900 dark:text-emerald-300 flex items-center gap-1.5">
                                    <span>🏛️</span> Unit Penugasan Operator
                                </div>

                                <div>
                                    <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300">Fakultas</label>
                                    <select wire:model.live="fakultas_id" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100 transition">
                                        <option value="">-- Pilih Fakultas --</option>
                                        @foreach ($fakultasList as $f)
                                            <option value="{{ $f->id }}">{{ $f->nama_fakultas }}</option>
                                        @endforeach
                                    </select>
                                    @error('fakultas_id') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                @if ($fakultas_id)
                                    <div>
                                        <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300">Program Studi (Opsional - bila khusus 1 jurusan)</label>
                                        <select wire:model="program_studi_id" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100 transition">
                                            <option value="">-- Semua Prodi di Fakultas Ini --</option>
                                            @foreach ($prodiList as $p)
                                                <option value="{{ $p->id }}">{{ $p->nama_program_studi }} ({{ $p->kode_program_studi }})</option>
                                            @endforeach
                                        </select>
                                        @error('program_studi_id') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100 dark:border-zinc-800">
                        <button wire:click="$set('showModal', false)" class="px-4 py-2 text-xs font-semibold text-gray-600 dark:text-zinc-400 hover:bg-gray-100 dark:hover:bg-zinc-800 rounded-xl transition">Batal</button>
                        <button wire:click="save" class="px-5 py-2 text-xs font-bold bg-[#0F352A] hover:bg-[#164336] dark:bg-emerald-600 dark:hover:bg-emerald-500 text-white rounded-xl transition shadow-xs">
                            Simpan Pengguna
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    @if ($confirmDeleteId)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-zinc-900 rounded-3xl w-full max-w-sm p-6 space-y-4 shadow-2xl border border-gray-100 dark:border-zinc-800 text-gray-900 dark:text-zinc-100 animate-in fade-in zoom-in-95 duration-150">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center mx-auto border border-rose-200 dark:border-rose-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div class="text-center">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-zinc-100">Konfirmasi Hapus Pengguna</h3>
                    <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">Apakah Anda yakin ingin menghapus akun pengguna ini? Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="flex items-center justify-center gap-2 pt-2">
                    <button wire:click="$set('confirmDeleteId', null)" class="px-4 py-2 text-xs font-semibold text-gray-600 dark:text-zinc-400 hover:bg-gray-100 dark:hover:bg-zinc-800 rounded-xl transition">Batal</button>
                    <button wire:click="delete" class="px-4 py-2 text-xs font-bold bg-rose-600 hover:bg-rose-700 text-white rounded-xl shadow-xs transition">Hapus Pengguna</button>
                </div>
            </div>
        </div>
    @endif
</div>
