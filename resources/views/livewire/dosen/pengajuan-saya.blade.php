<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-dark">Pengajuan Perubahan Saya</h1>
            <p class="text-dark/60 text-sm">Ajukan perubahan jadwal praktikum yang Anda ampu.</p>
        </div>
        @if (! $tidakAdaProfilDosen)
            <button wire:click="openCreate" class="bg-primary text-dark font-medium px-4 py-2 rounded-lg hover:bg-primary-dark transition">
                + Ajukan Perubahan
            </button>
        @endif
    </div>

    @if ($tidakAdaProfilDosen)
        <div class="bg-yellow-50 border border-yellow-300 text-yellow-800 text-sm rounded-lg px-4 py-3">
            Akun Anda belum terhubung ke data Dosen. Hubungi operator/admin untuk menautkan akun ini
            ke salah satu data Dosen di Master Data.
        </div>
    @endif

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
                    <th class="px-4 py-3">Jadwal</th>
                    <th class="px-4 py-3">Perubahan Diminta</th>
                    <th class="px-4 py-3">Alasan</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Catatan Operator</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark/10">
                @forelse ($pengajuan as $p)
                    <tr class="hover:bg-primary/5">
                        <td class="px-4 py-3 font-medium text-dark">{{ $p->jadwalPraktikum->mataKuliah->nama_mk }}</td>
                        <td class="px-4 py-3 text-dark/70">{{ $p->perubahan }}</td>
                        <td class="px-4 py-3 text-dark/70">{{ $p->alasan }}</td>
                        <td class="px-4 py-3">
                            <span @class([
                                'px-2 py-1 rounded-full text-xs',
                                'bg-yellow-100 text-yellow-700' => $p->status === 'menunggu',
                                'bg-green-100 text-green-700' => $p->status === 'disetujui',
                                'bg-red-100 text-red-700' => $p->status === 'ditolak',
                            ])>
                                {{ ucfirst($p->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-dark/60 text-xs">{{ $p->catatan_penolakan ?: '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-dark/50">Belum pernah mengajukan perubahan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $pengajuan->links() }}

    @if ($showModal)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 px-4">
            <div class="bg-white rounded-xl w-full max-w-md p-6 space-y-4">
                <h2 class="text-lg font-semibold text-dark">Ajukan Perubahan Jadwal</h2>

                <div>
                    <label class="text-sm text-dark/70">Jadwal yang Diajukan Perubahan</label>
                    <select wire:model="jadwal_praktikum_id" class="w-full rounded-lg border-dark/20 text-sm mt-1">
                        <option value="">-- Pilih Jadwal --</option>
                        @foreach ($jadwalSaya as $j)
                            <option value="{{ $j->id }}">
                                {{ $j->mataKuliah->nama_mk }} — {{ $j->hari }} {{ \Illuminate\Support\Str::limit($j->jam_mulai, 5, '') }}
                            </option>
                        @endforeach
                    </select>
                    @error('jadwal_praktikum_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm text-dark/70">Perubahan yang Diminta</label>
                    <textarea wire:model="perubahan" rows="2" placeholder="mis. Pindah ke Selasa jam 13.00-15.00"
                              class="w-full rounded-lg border-dark/20 text-sm mt-1"></textarea>
                    @error('perubahan') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm text-dark/70">Alasan</label>
                    <textarea wire:model="alasan" rows="3" class="w-full rounded-lg border-dark/20 text-sm mt-1"></textarea>
                    @error('alasan') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button wire:click="$set('showModal', false)" class="px-4 py-2 text-sm text-dark/60 hover:bg-dark/5 rounded-lg">Batal</button>
                    <button wire:click="save" class="px-4 py-2 text-sm bg-primary text-dark font-medium rounded-lg hover:bg-primary-dark">Kirim Pengajuan</button>
                </div>
            </div>
        </div>
    @endif

</div>
