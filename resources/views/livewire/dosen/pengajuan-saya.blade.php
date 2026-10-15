<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Pengajuan Perubahan Saya</h1>
            <p class="text-gray-500 text-sm">Ajukan permohonan perubahan atau reschedule jadwal praktikum yang Anda ampu.</p>
        </div>
        @if (! $tidakAdaProfilDosen)
            <button wire:click="openCreate" class="bg-primary text-white font-medium px-4 py-2 rounded-xl hover:bg-primary-dark transition shadow-sm">
                + Ajukan Perubahan
            </button>
        @endif
    </div>

    @if ($tidakAdaProfilDosen)
        <div class="bg-amber-50 border border-amber-300 text-amber-800 text-sm rounded-xl px-4 py-3">
            Akun Anda belum terhubung ke data Dosen. Hubungi operator/admin untuk menautkan akun ini
            ke salah satu data Dosen di Master Data.
        </div>
    @endif

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl px-4 py-3">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3">{{ session('error') }}</div>
    @endif

    {{-- Desktop Table --}}
    <div class="hidden md:block bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-primary text-white text-left">
                <tr>
                    <th class="px-4 py-3">Jadwal Praktikum</th>
                    <th class="px-4 py-3">Jenis & Permintaan Perubahan</th>
                    <th class="px-4 py-3">Alasan</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Catatan Operator</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($pengajuan as $p)
                    <tr class="hover:bg-primary/5 transition">
                        <td class="px-4 py-3">
                            <span class="font-semibold text-gray-900 block">{{ $p->jadwalPraktikum->mataKuliah->nama_mk }}</span>
                            <span class="text-xs text-gray-500">{{ $p->jadwalPraktikum->hari }}, {{ \Illuminate\Support\Str::limit($p->jadwalPraktikum->jam_mulai, 5, '') }}–{{ \Illuminate\Support\Str::limit($p->jadwalPraktikum->jam_selesai, 5, '') }} ({{ $p->jadwalPraktikum->ruangPraktikum->nama_ruang ?? 'Ruang TBD' }})</span>
                        </td>
                        <td class="px-4 py-3">
                            @if ($p->jenis_perubahan)
                                <span @class([
                                    'px-2 py-0.5 rounded text-[11px] font-semibold inline-block mb-1',
                                    'bg-blue-50 text-blue-700 border border-blue-200' => $p->jenis_perubahan === 'ganti_jadwal',
                                    'bg-purple-50 text-purple-700 border border-purple-200' => $p->jenis_perubahan === 'ganti_ruang',
                                    'bg-emerald-50 text-emerald-700 border border-emerald-200' => $p->jenis_perubahan === 'ganti_jadwal_ruang',
                                    'bg-amber-50 text-amber-700 border border-amber-200' => $p->jenis_perubahan === 'reschedule',
                                    'bg-gray-100 text-gray-700 border border-gray-200' => !in_array($p->jenis_perubahan, ['ganti_jadwal', 'ganti_ruang', 'ganti_jadwal_ruang', 'reschedule']),
                                ])>
                                    {{ match($p->jenis_perubahan) {
                                        'ganti_jadwal' => 'Pindah Jadwal',
                                        'ganti_ruang' => 'Pindah Ruang',
                                        'ganti_jadwal_ruang' => 'Pindah Jadwal & Ruang',
                                        'reschedule' => 'Kuliah Pengganti',
                                        default => 'Permintaan Khusus',
                                    } }}
                                </span>
                            @endif
                            <p class="text-gray-800 font-medium text-xs">{{ $p->perubahan }}</p>
                        </td>
                        <td class="px-4 py-3 text-gray-600 text-xs">{{ $p->alasan }}</td>
                        <td class="px-4 py-3">
                            <span @class([
                                'px-2.5 py-1 rounded-full text-xs font-semibold',
                                'bg-amber-100 text-amber-800' => strtolower($p->status) === 'menunggu',
                                'bg-green-100 text-green-800' => strtolower($p->status) === 'disetujui',
                                'bg-red-100 text-red-800' => strtolower($p->status) === 'ditolak',
                            ])>
                                {{ ucfirst($p->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600 text-xs">{{ $p->catatan_penolakan ?: '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Belum pernah mengajukan perubahan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="md:hidden grid grid-cols-1 sm:grid-cols-2 gap-3.5">
        @forelse ($pengajuan as $p)
            <div class="bg-white border border-gray-100 rounded-xl p-3.5 shadow-xs hover:border-gray-200 transition flex flex-col justify-between">
                <div>
                    <div class="flex items-start justify-between gap-2 mb-1.5">
                        <div class="min-w-0">
                            <h3 class="font-bold text-sm text-gray-900 leading-snug truncate">{{ $p->jadwalPraktikum->mataKuliah->nama_mk }}</h3>
                            <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $p->jadwalPraktikum->hari }}, {{ \Illuminate\Support\Str::limit($p->jadwalPraktikum->jam_mulai, 5, '') }}–{{ \Illuminate\Support\Str::limit($p->jadwalPraktikum->jam_selesai, 5, '') }}</p>
                        </div>
                        <span @class([
                            'px-2 py-0.5 rounded-full text-[11px] font-medium shrink-0',
                            'bg-amber-100 text-amber-800' => strtolower($p->status) === 'menunggu',
                            'bg-emerald-100 text-emerald-800' => strtolower($p->status) === 'disetujui',
                            'bg-red-100 text-red-800' => strtolower($p->status) === 'ditolak',
                        ])>
                            {{ ucfirst($p->status) }}
                        </span>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-2.5 space-y-1.5 border border-gray-100 text-xs mt-2">
                        <div>
                            <span class="text-gray-400 block text-[10px] font-semibold uppercase">Permintaan Perubahan:</span>
                            @if ($p->jenis_perubahan)
                                <span @class([
                                    'px-1.5 py-0.5 rounded text-[10px] font-semibold inline-block my-0.5',
                                    'bg-blue-50 text-blue-700 border border-blue-200' => $p->jenis_perubahan === 'ganti_jadwal',
                                    'bg-purple-50 text-purple-700 border border-purple-200' => $p->jenis_perubahan === 'ganti_ruang',
                                    'bg-emerald-50 text-emerald-700 border border-emerald-200' => $p->jenis_perubahan === 'ganti_jadwal_ruang',
                                    'bg-amber-50 text-amber-700 border border-amber-200' => $p->jenis_perubahan === 'reschedule',
                                    'bg-gray-100 text-gray-700 border border-gray-200' => !in_array($p->jenis_perubahan, ['ganti_jadwal', 'ganti_ruang', 'ganti_jadwal_ruang', 'reschedule']),
                                ])>
                                    {{ match($p->jenis_perubahan) {
                                        'ganti_jadwal' => 'Pindah Jadwal',
                                        'ganti_ruang' => 'Pindah Ruang',
                                        'ganti_jadwal_ruang' => 'Pindah Jadwal & Ruang',
                                        'reschedule' => 'Kuliah Pengganti',
                                        default => 'Permintaan Khusus',
                                    } }}
                                </span>
                            @endif
                            <p class="font-medium text-gray-800 text-xs">{{ $p->perubahan }}</p>
                        </div>
                        <div>
                            <span class="text-gray-400 block text-[10px] font-semibold uppercase">Alasan:</span>
                            <p class="text-gray-600 text-xs line-clamp-2">{{ $p->alasan }}</p>
                        </div>
                        @if ($p->catatan_penolakan)
                            <div class="pt-1.5 border-t border-gray-200/60">
                                <span class="text-red-500 block text-[10px] font-semibold uppercase">Catatan Operator:</span>
                                <p class="text-red-700 font-medium text-xs">{{ $p->catatan_penolakan }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="sm:col-span-2 bg-white rounded-xl p-8 text-center border border-gray-100 text-gray-400 text-sm">
                Belum pernah mengajukan perubahan.
            </div>
        @endforelse
    </div>

    {{ $pengajuan->links() }}

    {{-- Modal Pengajuan Perubahan --}}
    @if ($showModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4 sm:p-6 overflow-hidden">
            <div class="bg-white rounded-2xl w-full max-w-lg max-h-[90vh] flex flex-col shadow-2xl overflow-hidden my-auto animate-in fade-in zoom-in-95 duration-150">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-white shrink-0">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Ajukan Perubahan Jadwal</h2>
                        <p class="text-xs text-gray-500">Pilih jadwal praktikum dan tentukan perubahan yang diinginkan.</p>
                    </div>
                    <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600 rounded-lg p-1 hover:bg-gray-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Body (Scrollable) -->
                <div class="p-6 overflow-y-auto space-y-4 flex-1">
                    <div>
                        <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Jadwal yang Ingin Diubah</label>
                        <select wire:model.live="jadwal_praktikum_id" class="w-full rounded-xl border-gray-300 bg-gray-50 focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 text-sm py-2.5 mt-1 transition">
                            <option value="">-- Pilih Jadwal Praktikum --</option>
                            @foreach ($jadwalSaya as $j)
                                <option value="{{ $j->id }}">
                                    {{ $j->mataKuliah->nama_mk }} — {{ $j->hari }} ({{ \Illuminate\Support\Str::limit($j->jam_mulai, 5, '') }}–{{ \Illuminate\Support\Str::limit($j->jam_selesai, 5, '') }}) di {{ $j->ruangPraktikum->nama_ruang ?? 'Ruang' }}
                                </option>
                            @endforeach
                        </select>
                        @error('jadwal_praktikum_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Jenis Perubahan yang Diminta</label>
                        <select wire:model.live="jenis_perubahan" class="w-full rounded-xl border-gray-300 bg-gray-50 focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 text-sm py-2.5 mt-1 transition">
                            <option value="ganti_jadwal">Pindah Jadwal (Ganti Hari / Jam)</option>
                            <option value="ganti_ruang">Pindah Ruang Praktikum (Lab Baru)</option>
                            <option value="ganti_jadwal_ruang">Pindah Jadwal & Ruang Sekaligus</option>
                            <option value="reschedule">Kuliah Pengganti / Reschedule</option>
                            <option value="lainnya">Lainnya / Permintaan Khusus</option>
                        </select>
                        @error('jenis_perubahan') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Opsi Input Dinamis Berdasarkan Jenis Perubahan --}}
                    @if (in_array($jenis_perubahan, ['ganti_jadwal', 'ganti_jadwal_ruang']))
                        <div class="p-4 rounded-xl bg-gray-50 border border-gray-200/60 space-y-3">
                            <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Tentukan Jadwal Baru</h3>
                            <div>
                                <label class="text-xs text-gray-600 font-medium">Hari Baru</label>
                                <select wire:model="hari_baru" class="w-full rounded-lg border-gray-300 bg-white text-sm py-2 mt-1">
                                    @foreach ($hariList as $h)
                                        <option value="{{ $h }}">{{ $h }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-xs text-gray-600 font-medium">Jam Mulai Baru</label>
                                    <input type="time" wire:model="jam_mulai_baru" class="w-full rounded-lg border-gray-300 bg-white text-sm py-2 mt-1">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 font-medium">Jam Selesai Baru</label>
                                    <input type="time" wire:model="jam_selesai_baru" class="w-full rounded-lg border-gray-300 bg-white text-sm py-2 mt-1">
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (in_array($jenis_perubahan, ['ganti_ruang', 'ganti_jadwal_ruang']))
                        <div class="p-4 rounded-xl bg-gray-50 border border-gray-200/60 space-y-3">
                            <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Pilih Ruang Praktikum Baru</h3>
                            <div>
                                <label class="text-xs text-gray-600 font-medium">Ruang Lab yang Dikehendaki</label>
                                <select wire:model="ruang_praktikum_id_baru" class="w-full rounded-lg border-gray-300 bg-white text-sm py-2 mt-1">
                                    <option value="">-- Pilih Ruang Baru --</option>
                                    @foreach ($ruangList as $r)
                                        <option value="{{ $r->id }}">{{ $r->nama_ruang }} (Kapasitas {{ $r->kapasitas }})</option>
                                    @endforeach
                                </select>
                                @error('ruang_praktikum_id_baru') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    @endif

                    @if ($jenis_perubahan === 'reschedule')
                        <div class="p-4 rounded-xl bg-gray-50 border border-gray-200/60 space-y-3">
                            <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Detail Kuliah Pengganti</h3>
                            <div>
                                <label class="text-xs text-gray-600 font-medium">Tanggal Pengganti</label>
                                <input type="date" wire:model="tanggal_pengganti" class="w-full rounded-lg border-gray-300 bg-white text-sm py-2 mt-1">
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-xs text-gray-600 font-medium">Jam Mulai</label>
                                    <input type="time" wire:model="jam_mulai_baru" class="w-full rounded-lg border-gray-300 bg-white text-sm py-2 mt-1">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 font-medium">Jam Selesai</label>
                                    <input type="time" wire:model="jam_selesai_baru" class="w-full rounded-lg border-gray-300 bg-white text-sm py-2 mt-1">
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($jenis_perubahan === 'lainnya')
                        <div class="p-4 rounded-xl bg-gray-50 border border-gray-200/60 space-y-2">
                            <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Detail Permintaan Khusus</label>
                            <input type="text" wire:model="detail_kustom" placeholder="mis. Penggabungan kelas atau pertukaran shift" class="w-full rounded-lg border-gray-300 bg-white text-sm py-2 mt-1">
                        </div>
                    @endif

                    <div>
                        <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Alasan Pengajuan (Wajib)</label>
                        <textarea wire:model="alasan" rows="3" placeholder="Jelaskan alasan pengajuan perubahan jadwal ini (mis. bentrok kegiatan akademik, dinas luar, perawatan perangkat lab)..." class="w-full rounded-xl border-gray-300 bg-gray-50 focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 text-sm py-2.5 mt-1 transition"></textarea>
                        @error('alasan') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-2 shrink-0">
                    <button wire:click="$set('showModal', false)" class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-200/60 rounded-xl transition font-medium">
                        Batal
                    </button>
                    <button wire:click="save" wire:loading.attr="disabled" class="px-5 py-2 text-sm bg-primary text-white font-medium rounded-xl hover:bg-primary-dark transition shadow-sm disabled:opacity-50">
                        <span wire:loading.remove wire:target="save">Kirim Pengajuan</span>
                        <span wire:loading wire:target="save">Mengirim...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
