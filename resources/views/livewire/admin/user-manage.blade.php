<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">Kelola Pengguna & Operator</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Manajemen akun pengelola, hak akses peran (Super Admin, Operator, Dosen), dan penugasan fakultas/prodi.</p>
        </div>
        <button wire:click="openCreate"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-primary hover:bg-primary-dark text-white text-xs sm:text-sm font-semibold transition shadow-xs shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>+ Tambah Pengguna</span>
        </button>
    </div>

    {{-- Flash Notifications --}}
    @if (session()->has('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 text-xs sm:text-sm rounded-xl p-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" @click="$el.parentElement.remove()" class="text-green-600 hover:text-green-800">✕</button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 text-xs sm:text-sm rounded-xl p-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" @click="$el.parentElement.remove()" class="text-red-600 hover:text-red-800">✕</button>
        </div>
    @endif

    {{-- Search & Filters --}}
    <div class="bg-white border border-gray-200/80 rounded-2xl p-4 shadow-xs flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
        <div class="relative flex-1">
            <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari berdasarkan nama atau email pengguna..."
                   class="w-full pl-9 pr-4 py-2 rounded-xl border border-gray-200 bg-gray-50/50 text-xs sm:text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition">
        </div>

        <div class="w-full sm:w-48">
            <select wire:model.live="filterRole" class="w-full py-2 px-3 rounded-xl border border-gray-200 bg-gray-50/50 text-xs sm:text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition">
                <option value="">Semua Peran (Role)</option>
                <option value="super-admin">Super Admin</option>
                <option value="operator">Operator Fakultas/Prodi</option>
                <option value="dosen">Dosen</option>
            </select>
        </div>
    </div>

    {{-- Desktop Table View --}}
    <div class="hidden md:block bg-white border border-gray-200/80 rounded-2xl shadow-xs overflow-hidden">
        <table class="w-full text-left text-xs sm:text-sm">
            <thead class="bg-gray-50/80 text-gray-600 font-semibold border-b border-gray-200/80 uppercase text-[11px] tracking-wider">
                <tr>
                    <th class="px-5 py-3.5">Nama & Email</th>
                    <th class="px-5 py-3.5">Peran (Role)</th>
                    <th class="px-5 py-3.5">Unit Penugasan (Fakultas / Prodi)</th>
                    <th class="px-5 py-3.5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($users as $u)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-5 py-3.5">
                            <div class="font-bold text-gray-900">{{ $u->name }}</div>
                            <div class="text-xs text-gray-400 font-mono">{{ $u->email }}</div>
                        </td>
                        <td class="px-5 py-3.5">
                            @foreach ($u->roles as $r)
                                @if($r->name === 'super-admin')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-100 text-purple-800 border border-purple-200">
                                        👑 Super Admin
                                    </span>
                                @elseif($r->name === 'operator')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                                        💼 Operator
                                    </span>
                                @elseif($r->name === 'dosen')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        🎓 Dosen
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-xs bg-gray-100 text-gray-700">{{ $r->name }}</span>
                                @endif
                            @endforeach
                        </td>
                        <td class="px-5 py-3.5 text-xs text-gray-600">
                            @if ($u->fakultas)
                                <div class="font-semibold text-gray-800">🏛️ {{ $u->fakultas->nama_fakultas }}</div>
                                @if ($u->programStudi)
                                    <div class="text-gray-500 font-normal">Prodi: {{ $u->programStudi->nama_program_studi }}</div>
                                @else
                                    <div class="text-gray-400 italic">Tingkat Fakultas (Semua Prodi)</div>
                                @endif
                            @else
                                <span class="text-gray-400 italic">Tingkat Universitas (Pusat)</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right space-x-2">
                            <button wire:click="openEdit({{ $u->id }})" class="text-primary hover:text-primary-dark font-semibold text-xs transition">Edit</button>
                            @if ($u->id !== auth()->id())
                                <button wire:click="confirmDelete({{ $u->id }})" class="text-red-600 hover:text-red-700 font-semibold text-xs transition">Hapus</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-10 text-center text-gray-400 text-sm">
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
            <div class="bg-white border border-gray-200/80 rounded-2xl p-4 shadow-xs space-y-3">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <h3 class="font-bold text-sm text-gray-900 leading-snug">{{ $u->name }}</h3>
                        <p class="text-xs text-gray-400 font-mono">{{ $u->email }}</p>
                    </div>
                    @foreach ($u->roles as $r)
                        <span class="px-2 py-0.5 rounded-full text-[11px] font-bold shrink-0 {{ $r->name === 'super-admin' ? 'bg-purple-100 text-purple-800' : ($r->name === 'operator' ? 'bg-blue-100 text-blue-800' : 'bg-emerald-100 text-emerald-800') }}">
                            {{ ucfirst($r->name) }}
                        </span>
                    @endforeach
                </div>

                <div class="text-xs text-gray-600 bg-gray-50/80 rounded-xl p-2.5 space-y-0.5">
                    @if ($u->fakultas)
                        <div class="font-semibold text-gray-800">🏛️ {{ $u->fakultas->nama_fakultas }}</div>
                        @if ($u->programStudi)
                            <div class="text-gray-500">Prodi: {{ $u->programStudi->nama_program_studi }}</div>
                        @endif
                    @else
                        <div class="text-gray-400 italic">Universitas / Pusat</div>
                    @endif
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100">
                    <button wire:click="openEdit({{ $u->id }})" class="px-3 py-1.5 text-xs font-semibold text-primary bg-primary/5 hover:bg-primary/15 rounded-lg transition">Edit</button>
                    @if ($u->id !== auth()->id())
                        <button wire:click="confirmDelete({{ $u->id }})" class="px-3 py-1.5 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition">Hapus</button>
                    @endif
                </div>
            </div>
        @empty
            <div class="sm:col-span-2 bg-white rounded-2xl p-8 text-center border border-gray-200/80 text-gray-500 text-sm">
                Tidak ada data pengguna ditemukan.
            </div>
        @endforelse
    </div>

    {{ $users->links() }}

    {{-- Create / Edit Modal --}}
    @if ($showModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-xs flex items-center justify-center z-50 p-4 overflow-y-auto">
            <div class="bg-white rounded-2xl w-full max-w-lg p-6 space-y-4 shadow-xl border border-gray-100 my-8">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">{{ $editId ? 'Edit Akun Pengguna' : 'Tambah Pengguna / Operator' }}</h2>
                        <p class="text-xs text-gray-500">Daftarkan akun operator jurusan, dosen, atau administrator sistem.</p>
                    </div>
                    <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600 p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="space-y-3.5">
                    <div>
                        <label class="text-xs font-semibold text-gray-700">Nama Lengkap & Gelar *</label>
                        <input type="text" wire:model="name" placeholder="mis. Siti Nurhaliza, S.Kom." class="w-full rounded-xl border border-gray-300 bg-gray-50/50 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 text-sm py-2 px-3 mt-1 transition">
                        @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-700">Alamat Email *</label>
                        <input type="email" wire:model="email" placeholder="mis. operator.if@praktikum.ac.id" class="w-full rounded-xl border border-gray-300 bg-gray-50/50 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 text-sm py-2 px-3 mt-1 transition">
                        @error('email') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-700">{{ $editId ? 'Kata Sandi Baru (Kosongkan bila tidak diubah)' : 'Kata Sandi *' }}</label>
                        <input type="password" wire:model="password" placeholder="Minimal 6 karakter" class="w-full rounded-xl border border-gray-300 bg-gray-50/50 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 text-sm py-2 px-3 mt-1 transition">
                        @error('password') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-700">Peran / Hak Akses (Role) *</label>
                        <select wire:model.live="role" class="w-full rounded-xl border border-gray-300 bg-gray-50/50 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 text-sm py-2 px-3 mt-1 transition">
                            <option value="operator">Operator (Pengelola Jadwal Prodi / Fakultas)</option>
                            <option value="dosen">Dosen Pengampu</option>
                            <option value="super-admin">Super Admin (Otoritas Penuh)</option>
                        </select>
                        @error('role') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    @if ($role === 'operator')
                        <div class="border-t border-gray-100 pt-3 space-y-3 bg-blue-50/40 p-3.5 rounded-xl border border-blue-100">
                            <div class="text-xs font-bold text-blue-900 flex items-center gap-1.5">
                                <span>🏛️</span> Unit Penugasan Operator
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-gray-700">Fakultas</label>
                                <select wire:model.live="fakultas_id" class="w-full rounded-xl border border-gray-300 bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 text-sm py-2 px-3 mt-1 transition">
                                    <option value="">-- Pilih Fakultas --</option>
                                    @foreach ($fakultasList as $f)
                                        <option value="{{ $f->id }}">{{ $f->nama_fakultas }}</option>
                                    @endforeach
                                </select>
                                @error('fakultas_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            @if ($fakultas_id)
                                <div>
                                    <label class="text-xs font-semibold text-gray-700">Program Studi (Opsional - bila khusus 1 jurusan)</label>
                                    <select wire:model="program_studi_id" class="w-full rounded-xl border border-gray-300 bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 text-sm py-2 px-3 mt-1 transition">
                                        <option value="">-- Semua Prodi di Fakultas Ini --</option>
                                        @foreach ($prodiList as $p)
                                            <option value="{{ $p->id }}">{{ $p->nama_program_studi }} ({{ $p->kode_program_studi }})</option>
                                        @endforeach
                                    </select>
                                    @error('program_studi_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                    <button wire:click="$set('showModal', false)" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-100 rounded-xl transition">Batal</button>
                    <button wire:click="save" class="px-4 py-2 text-xs font-semibold bg-primary hover:bg-primary-dark text-white rounded-xl transition shadow-xs">
                        Simpan Pengguna
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    @if ($confirmDeleteId)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-xs flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl w-full max-w-sm p-6 space-y-4 shadow-xl border border-gray-100">
                <h3 class="text-base font-bold text-gray-900">Konfirmasi Hapus Pengguna</h3>
                <p class="text-xs text-gray-500">Apakah Anda yakin ingin menghapus akun pengguna ini? Tindakan ini tidak dapat dibatalkan.</p>
                <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100">
                    <button wire:click="$set('confirmDeleteId', null)" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-100 rounded-xl transition">Batal</button>
                    <button wire:click="delete" class="px-4 py-2 text-xs font-semibold bg-red-600 hover:bg-red-700 text-white rounded-xl transition">Hapus Pengguna</button>
                </div>
            </div>
        </div>
    @endif
</div>
