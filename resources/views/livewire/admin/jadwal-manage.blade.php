<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-dark">Kelola Jadwal Praktikum</h1>
            <p class="text-dark/60 text-sm">Setiap jadwal otomatis dicek bentrok ruang & dosen sebelum disimpan.</p>
        </div>
        <button wire:click="openCreate" class="bg-primary text-dark font-medium px-4 py-2 rounded-lg hover:bg-primary-dark transition">
            + Tambah Jadwal
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
                        <td class="px-4 py-3 font-medium text-dark">{{ $j->mataKuliah->nama_mk }}</td>
                        <td class="px-4 py-3 text-dark/70">{{ $j->kelas->nama_kelas }}</td>
                        <td class="px-4 py-3 text-dark/70">{{ $j->dosen->nama }}</td>
                        <td class="px-4 py-3 text-dark/70">
                            {{ $j->hari }}, {{ \Illuminate\Support\Str::limit($j->jam_mulai, 5, '') }}–{{ \Illuminate\Support\Str::limit($j->jam_selesai, 5, '') }}
                        </td>
                        <td class="px-4 py-3 text-dark/70">{{ $j->ruangPraktikum->nama_ruang }}</td>
                        <td class="px-4 py-3">
                            <span @class([
                                'px-2 py-1 rounded-full text-xs',
                                'bg-green-100 text-green-700' => $j->status === 'aktif',
                                'bg-dark/10 text-dark/50' => $j->status === 'nonaktif',
                                'bg-red-100 text-red-700' => $j->status === 'dibatalkan',
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
                    <tr><td colspan="7" class="px-4 py-8 text-center text-dark/50">Belum ada jadwal.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $jadwal->links() }}

    {{-- Modal Create/Edit --}}
    @if ($showModal)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 px-4 py-8 overflow-y-auto">
            <div class="bg-white rounded-xl w-full max-w-lg p-6 space-y-4">
                <h2 class="text-lg font-semibold text-dark">{{ $editId ? 'Edit Jadwal' : 'Tambah Jadwal' }}</h2>

                {{-- Pesan bentrok — beda dari error validasi biasa --}}
                @if ($conflictMessage)
                    <div class="bg-red-50 border border-red-300 text-red-700 text-sm rounded-lg px-4 py-3">
                        <strong>Jadwal bentrok!</strong> {{ $conflictMessage }}
                    </div>
                @endif

                <div class="grid grid-cols-2 gap-3">
                    <div class="col-span-2">
                        <label class="text-sm text-dark/70">Mata Kuliah</label>
                        <select wire:model="mata_kuliah_id" class="w-full rounded-lg border-dark/20 text-sm mt-1">
                            <option value="">-- Pilih Mata Kuliah --</option>
                            @foreach ($mataKuliah as $mk)
                                <option value="{{ $mk->id }}">{{ $mk->nama_mk }} ({{ $mk->sks }} SKS)</option>
                            @endforeach
                        </select>
                        @error('mata_kuliah_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-sm text-dark/70">Kelas</label>
                        <select wire:model="kelas_id" class="w-full rounded-lg border-dark/20 text-sm mt-1">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($kelasList as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                        @error('kelas_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-sm text-dark/70">Dosen</label>
                        <select wire:model="dosen_id" class="w-full rounded-lg border-dark/20 text-sm mt-1">
                            <option value="">-- Pilih Dosen --</option>
                            @foreach ($dosenList as $d)
                                <option value="{{ $d->id }}">{{ $d->nama }}</option>
                            @endforeach
                        </select>
                        @error('dosen_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-sm text-dark/70">Ruang</label>
                        <select wire:model="ruang_praktikum_id" class="w-full rounded-lg border-dark/20 text-sm mt-1">
                            <option value="">-- Pilih Ruang --</option>
                            @foreach ($ruangList as $r)
                                <option value="{{ $r->id }}">{{ $r->nama_ruang }}</option>
                            @endforeach
                        </select>
                        @error('ruang_praktikum_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-sm text-dark/70">Tahun Akademik</label>
                        <select wire:model="tahun_akademik_id" class="w-full rounded-lg border-dark/20 text-sm mt-1">
                            <option value="">-- Pilih Tahun Akademik --</option>
                            @foreach ($tahunList as $t)
                                <option value="{{ $t->id }}">{{ $t->nama }}</option>
                            @endforeach
                        </select>
                        @error('tahun_akademik_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-2">
                        <label class="text-sm text-dark/70">Hari</label>
                        <select wire:model="hari" class="w-full rounded-lg border-dark/20 text-sm mt-1">
                            <option value="">-- Pilih Hari --</option>
                            @foreach ($hariList as $h)
                                <option value="{{ $h }}">{{ $h }}</option>
                            @endforeach
                        </select>
                        @error('hari') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-sm text-dark/70">Jam Mulai</label>
                        <input type="time" wire:model="jam_mulai" class="w-full rounded-lg border-dark/20 text-sm mt-1">
                        @error('jam_mulai') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-sm text-dark/70">Jam Selesai</label>
                        <input type="time" wire:model="jam_selesai" class="w-full rounded-lg border-dark/20 text-sm mt-1">
                        @error('jam_selesai') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-2">
                        <label class="text-sm text-dark/70">Status</label>
                        <select wire:model="status" class="w-full rounded-lg border-dark/20 text-sm mt-1">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                            <option value="dibatalkan">Dibatalkan</option>
                        </select>
                    </div>

                    <div class="col-span-2">
                        <label class="text-sm text-dark/70">Keterangan (opsional)</label>
                        <textarea wire:model="keterangan" rows="2" class="w-full rounded-lg border-dark/20 text-sm mt-1"></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button wire:click="$set('showModal', false)" class="px-4 py-2 text-sm text-dark/60 hover:bg-dark/5 rounded-lg">
                        Batal
                    </button>
                    <button wire:click="save" wire:loading.attr="disabled"
                            class="px-4 py-2 text-sm bg-primary text-dark font-medium rounded-lg hover:bg-primary-dark disabled:opacity-50">
                        <span wire:loading.remove wire:target="save">Simpan</span>
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
                <h2 class="text-lg font-semibold text-dark">Hapus Jadwal?</h2>
                <p class="text-sm text-dark/60">Data yang sudah dihapus tidak bisa dikembalikan.</p>
                <div class="flex justify-end gap-2">
                    <button wire:click="$set('confirmDeleteId', null)" class="px-4 py-2 text-sm text-dark/60 hover:bg-dark/5 rounded-lg">Batal</button>
                    <button wire:click="delete" class="px-4 py-2 text-sm bg-red-600 text-white font-medium rounded-lg hover:bg-red-700">Ya, Hapus</button>
                </div>
            </div>
        </div>
    @endif

</div>
