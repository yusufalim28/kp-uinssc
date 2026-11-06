<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-zinc-100 tracking-tight">Pengajuan Perubahan Saya</h1>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-zinc-400 mt-0.5">Ajukan permohonan pemindahan jadwal atau reschedule kuliah praktikum yang Anda ampu.</p>
        </div>
        @if (! $tidakAdaProfilDosen)
            <button wire:click="openCreate" class="inline-flex items-center gap-2 bg-[#0F352A] hover:bg-[#164336] dark:bg-emerald-600 dark:hover:bg-emerald-500 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow-xs self-start sm:self-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Ajukan Perubahan</span>
            </button>
        @endif
    </div>

    @if ($tidakAdaProfilDosen)
        <div class="bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/60 rounded-2xl p-4 text-amber-900 dark:text-amber-200 text-xs shadow-2xs">
            Akun Anda belum terhubung ke data Dosen resmi. Hubungi operator fakultas atau admin untuk menautkan akun ini ke data Dosen.
        </div>
    @endif

    @if (session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 text-emerald-800 dark:text-emerald-300 text-xs rounded-xl px-4 py-3 shadow-2xs flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60 text-rose-800 dark:text-rose-300 text-xs rounded-xl px-4 py-3 shadow-2xs flex items-center gap-2">
            <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Desktop Table --}}
    <div class="hidden md:block bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 rounded-2xl shadow-2xs overflow-hidden">
        <table class="w-full text-xs text-left">
            <thead class="bg-gray-50/80 dark:bg-zinc-800/60 border-b border-gray-200/80 dark:border-zinc-700/80 text-[11px] font-bold text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-3.5">Jadwal Praktikum</th>
                    <th class="px-5 py-3.5">Jenis & Permintaan Perubahan</th>
                    <th class="px-5 py-3.5">Alasan</th>
                    <th class="px-5 py-3.5 w-32">Status</th>
                    <th class="px-5 py-3.5">Catatan Operator</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-zinc-800 text-gray-800 dark:text-zinc-200">
                @forelse ($pengajuan as $p)
                    <tr class="hover:bg-emerald-50/30 dark:hover:bg-zinc-800/40 transition">
                        <td class="px-5 py-3.5">
                            <span class="font-bold text-gray-900 dark:text-zinc-100 block">{{ $p->jadwalPraktikum->mataKuliah->nama_mk }}</span>
                            <span class="text-xs text-gray-400 font-mono">{{ $p->jadwalPraktikum->hari }}, {{ \Illuminate\Support\Str::limit($p->jadwalPraktikum->jam_mulai, 5, '') }}–{{ \Illuminate\Support\Str::limit($p->jadwalPraktikum->jam_selesai, 5, '') }} ({{ $p->jadwalPraktikum->ruangPraktikum->nama_ruang ?? 'Ruang TBD' }})</span>
                        </td>
                        <td class="px-5 py-3.5">
                            @if ($p->jenis_perubahan)
                                <span @class([
                                    'px-2 py-0.5 rounded text-[10px] font-bold inline-block mb-1',
                                    'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border border-blue-200' => $p->jenis_perubahan === 'ganti_jadwal',
                                    'bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300 border border-purple-200' => $p->jenis_perubahan === 'ganti_ruang',
                                    'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200' => $p->jenis_perubahan === 'ganti_jadwal_ruang',
                                    'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200' => $p->jenis_perubahan === 'reschedule',
                                    'bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300 border border-gray-200' => !in_array($p->jenis_perubahan, ['ganti_jadwal', 'ganti_ruang', 'ganti_jadwal_ruang', 'reschedule']),
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
                            <p class="text-gray-800 dark:text-zinc-200 font-medium text-xs">{{ $p->perubahan }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-gray-600 dark:text-zinc-400 text-xs">{{ $p->alasan }}</td>
                        <td class="px-5 py-3.5">
                            <span @class([
                                'px-2.5 py-1 rounded-full text-xs font-semibold inline-flex items-center gap-1.5',
                                'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200/60 dark:border-amber-800/40' => strtolower($p->status) === 'menunggu',
                                'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40' => strtolower($p->status) === 'disetujui',
                                'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-200/60 dark:border-rose-800/40' => strtolower($p->status) === 'ditolak',
                            ])>
                                <span class="w-1.5 h-1.5 rounded-full {{ strtolower($p->status) === 'disetujui' ? 'bg-emerald-500' : (strtolower($p->status) === 'menunggu' ? 'bg-amber-500' : 'bg-rose-500') }}"></span>
                                {{ ucfirst($p->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-gray-500 dark:text-zinc-400 text-xs font-medium">{{ $p->catatan_penolakan ?: '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-10 text-center text-gray-400 dark:text-zinc-500 text-sm">Belum pernah mengajukan perubahan jadwal.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Card View --}}
    <div class="md:hidden grid grid-cols-1 sm:grid-cols-2 gap-3.5">
        @forelse ($pengajuan as $p)
            <div class="bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 rounded-2xl p-4 shadow-2xs flex flex-col justify-between">
                <div>
                    <div class="flex items-start justify-between gap-2 mb-2">
                        <div class="min-w-0">
                            <h3 class="font-bold text-sm text-gray-900 dark:text-zinc-100 leading-snug truncate">{{ $p->jadwalPraktikum->mataKuliah->nama_mk }}</h3>
                            <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5 truncate font-mono">{{ $p->jadwalPraktikum->hari }}, {{ \Illuminate\Support\Str::limit($p->jadwalPraktikum->jam_mulai, 5, '') }}–{{ \Illuminate\Support\Str::limit($p->jadwalPraktikum->jam_selesai, 5, '') }}</p>
                        </div>
                        <span @class([
                            'px-2 py-0.5 rounded-full text-[11px] font-semibold shrink-0',
                            'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200/60' => strtolower($p->status) === 'menunggu',
                            'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60' => strtolower($p->status) === 'disetujui',
                            'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-200/60' => strtolower($p->status) === 'ditolak',
                        ])>
                            {{ ucfirst($p->status) }}
                        </span>
                    </div>

                    <div class="bg-gray-50/80 dark:bg-zinc-800/60 rounded-xl p-3 space-y-1.5 border border-gray-100 dark:border-zinc-700/80 text-xs mt-2">
                        <div>
                            <span class="text-gray-400 dark:text-zinc-400 block text-[10px] font-semibold uppercase">Permintaan Perubahan:</span>
                            @if ($p->jenis_perubahan)
                                <span @class([
                                    'px-1.5 py-0.5 rounded text-[10px] font-bold inline-block my-0.5',
                                    'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border border-blue-200' => $p->jenis_perubahan === 'ganti_jadwal',
                                    'bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300 border border-purple-200' => $p->jenis_perubahan === 'ganti_ruang',
                                    'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200' => $p->jenis_perubahan === 'ganti_jadwal_ruang',
                                    'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200' => $p->jenis_perubahan === 'reschedule',
                                    'bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300 border border-gray-200' => !in_array($p->jenis_perubahan, ['ganti_jadwal', 'ganti_ruang', 'ganti_jadwal_ruang', 'reschedule']),
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
                            <p class="font-medium text-gray-800 dark:text-zinc-200 text-xs">{{ $p->perubahan }}</p>
                        </div>
                        <div>
                            <span class="text-gray-400 dark:text-zinc-400 block text-[10px] font-semibold uppercase">Alasan:</span>
                            <p class="text-gray-600 dark:text-zinc-300 text-xs line-clamp-2">{{ $p->alasan }}</p>
                        </div>
                        @if ($p->catatan_penolakan)
                            <div class="pt-1.5 border-t border-gray-200/60 dark:border-zinc-700">
                                <span class="text-rose-500 block text-[10px] font-bold uppercase">Catatan Operator:</span>
                                <p class="text-rose-700 dark:text-rose-300 font-medium text-xs">{{ $p->catatan_penolakan }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="sm:col-span-2 bg-white dark:bg-zinc-900 rounded-2xl p-8 text-center border border-gray-200/80 dark:border-zinc-800 text-gray-400 dark:text-zinc-500 text-sm">
                Belum pernah mengajukan perubahan.
            </div>
        @endforelse
    </div>

    {{ $pengajuan->links() }}

    {{-- Modal Pengajuan Perubahan --}}
    @if ($showModal)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs flex items-center justify-center z-50 p-4 sm:p-6 overflow-hidden">
            <div class="bg-white dark:bg-zinc-900 rounded-3xl w-full max-w-lg max-h-[90vh] flex flex-col shadow-2xl border border-gray-100 dark:border-zinc-800 text-gray-900 dark:text-zinc-100 overflow-hidden my-auto animate-in fade-in zoom-in-95 duration-150">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-100 dark:border-zinc-800 flex items-center justify-between bg-white dark:bg-zinc-900 shrink-0">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-gray-900 dark:text-zinc-100">Ajukan Perubahan Jadwal</h2>
                        <p class="text-xs text-gray-500 dark:text-zinc-400">Pilih jadwal praktikum dan tentukan perubahan yang diinginkan.</p>
                    </div>
                    <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600 dark:hover:text-zinc-300 rounded-lg p-1 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Body (Scrollable) -->
                <div class="p-6 overflow-y-auto space-y-4 flex-1">
                    <div>
                        <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300 uppercase tracking-wider">Jadwal yang Ingin Diubah</label>
                        <select wire:model.live="jadwal_praktikum_id" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs py-2.5 px-3 mt-1 text-gray-900 dark:text-zinc-100 transition">
                            <option value="">-- Pilih Jadwal Praktikum --</option>
                            @foreach ($jadwalSaya as $j)
                                <option value="{{ $j->id }}">
                                    {{ $j->mataKuliah->nama_mk }} — {{ $j->hari }} ({{ \Illuminate\Support\Str::limit($j->jam_mulai, 5, '') }}–{{ \Illuminate\Support\Str::limit($j->jam_selesai, 5, '') }}) di {{ $j->ruangPraktikum->nama_ruang ?? 'Ruang' }}
                                </option>
                            @endforeach
                        </select>
                        @error('jadwal_praktikum_id') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300 uppercase tracking-wider">Jenis Perubahan yang Diminta</label>
                        <select wire:model.live="jenis_perubahan" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs py-2.5 px-3 mt-1 text-gray-900 dark:text-zinc-100 transition">
                            <option value="ganti_jadwal">Pindah Jadwal (Ganti Hari / Jam)</option>
                            <option value="ganti_ruang">Pindah Ruang Praktikum (Lab Baru)</option>
                            <option value="ganti_jadwal_ruang">Pindah Jadwal & Ruang Sekaligus</option>
                            <option value="reschedule">Kuliah Pengganti / Reschedule</option>
                            <option value="lainnya">Lainnya / Permintaan Khusus</option>
                        </select>
                        @error('jenis_perubahan') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Opsi Input Dinamis Berdasarkan Jenis Perubahan --}}
                    @if (in_array($jenis_perubahan, ['ganti_jadwal', 'ganti_jadwal_ruang']))
                        <div class="p-4 rounded-2xl bg-gray-50/80 dark:bg-zinc-800/60 border border-gray-200/80 dark:border-zinc-700 space-y-3">
                            <h3 class="text-xs font-bold text-gray-800 dark:text-zinc-200 uppercase tracking-wider">Tentukan Jadwal Baru</h3>
                            <div>
                                <label class="text-xs text-gray-600 dark:text-zinc-400 font-medium">Hari Baru</label>
                                <select wire:model.live="hari_baru" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100">
                                    @foreach ($hariList as $h)
                                        <option value="{{ $h }}">{{ $h }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-zinc-400 font-medium">Jam Mulai Baru</label>
                                    <input type="time" wire:model.live.debounce.300ms="jam_mulai_baru" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-zinc-400 font-medium">Jam Selesai Baru</label>
                                    <input type="time" wire:model.live.debounce.300ms="jam_selesai_baru" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100">
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (in_array($jenis_perubahan, ['ganti_ruang', 'ganti_jadwal_ruang']))
                        <div class="p-4 rounded-2xl bg-gray-50/80 dark:bg-zinc-800/60 border border-gray-200/80 dark:border-zinc-700 space-y-3">
                            <h3 class="text-xs font-bold text-gray-800 dark:text-zinc-200 uppercase tracking-wider">Pilih Ruang Praktikum Baru</h3>
                            <div>
                                <label class="text-xs text-gray-600 dark:text-zinc-400 font-medium">Ruang Lab yang Dikehendaki</label>
                                <select wire:model.live="ruang_praktikum_id_baru" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100">
                                    <option value="">-- Pilih Ruang Baru --</option>
                                    @foreach ($ruangList as $r)
                                        <option value="{{ $r->id }}">
                                            {{ $r->nama_ruang }} ({{ $r->tipe_ruang === 'auditorium' ? 'Auditorium' : 'Lab' }} - Kapasitas {{ $r->kapasitas }}){{ $r->cakupan_akses === 'khusus_multi_prodi' ? ' [Multi-Prodi]' : ($r->cakupan_akses === 'umum_universitas' ? ' [Umum Kampus]' : ($r->cakupan_akses === 'eksklusif' ? ' [Eksklusif]' : '')) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ruang_praktikum_id_baru') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    @endif

                    @if ($jenis_perubahan === 'reschedule')
                        <div class="p-4 rounded-2xl bg-gray-50/80 dark:bg-zinc-800/60 border border-gray-200/80 dark:border-zinc-700 space-y-3">
                            <h3 class="text-xs font-bold text-gray-800 dark:text-zinc-200 uppercase tracking-wider">Detail Kuliah Pengganti</h3>
                            <div>
                                <label class="text-xs text-gray-600 dark:text-zinc-400 font-medium">Tanggal Pengganti</label>
                                <input type="date" wire:model.live="tanggal_pengganti" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100">
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-zinc-400 font-medium">Jam Mulai</label>
                                    <input type="time" wire:model.live.debounce.300ms="jam_mulai_baru" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 dark:text-zinc-400 font-medium">Jam Selesai</label>
                                    <input type="time" wire:model.live.debounce.300ms="jam_selesai_baru" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100">
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($jenis_perubahan === 'lainnya')
                        <div class="p-4 rounded-2xl bg-gray-50/80 dark:bg-zinc-800/60 border border-gray-200/80 dark:border-zinc-700 space-y-2">
                            <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300 uppercase tracking-wider">Detail Permintaan Khusus</label>
                            <input type="text" wire:model="detail_kustom" placeholder="mis. Penggabungan kelas atau pertukaran shift" class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100">
                        </div>
                    @endif

                    {{-- Live Conflict / Vacancy Indicator --}}
                    @if ($jadwal_praktikum_id && $jenis_perubahan !== 'lainnya')
                        <div wire:loading.remove wire:target="updatedJadwalPraktikumId, updatedJenisPerubahan, updatedHariBaru, updatedJamMulaiBaru, updatedJamSelesaiBaru, updatedRuangPraktikumIdBaru, updatedTanggalPengganti">
                            @if ($isSlotAvailable)
                                <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/70 text-emerald-800 dark:text-emerald-200 text-xs flex items-start gap-3 animate-in fade-in">
                                    <div class="w-7 h-7 rounded-full bg-emerald-100 dark:bg-emerald-900/70 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-emerald-950 dark:text-emerald-300 text-sm">
                                            Slot Ruangan Kosong & Bebas Bentrok!
                                        </p>
                                        <p class="text-[11px] text-emerald-700 dark:text-emerald-300 mt-0.5 leading-relaxed">
                                            Ruangan dan jam yang Anda pilih saat ini sedang <strong class="text-emerald-900 dark:text-emerald-100">tidak digunakan</strong> oleh kelas lain. Anda dapat langsung memindahkan jadwal praktikum ini secara instan tanpa menunggu review operator.
                                        </p>
                                    </div>
                                </div>
                            @elseif ($conflictMessage)
                                <div class="p-4 rounded-2xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/70 text-amber-900 dark:text-amber-200 text-xs flex items-start gap-3 animate-in fade-in">
                                    <div class="w-7 h-7 rounded-full bg-amber-100 dark:bg-amber-900/70 text-amber-700 dark:text-amber-400 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-amber-950 dark:text-amber-300 text-sm">Slot Jadwal Bentrok / Terisi</p>
                                        <p class="text-[11px] text-amber-800 dark:text-amber-300 mt-0.5 leading-relaxed">{{ $conflictMessage }}</p>
                                        <p class="text-[10px] text-amber-600 dark:text-amber-400 mt-1">Anda dapat memilih jam/ruang lain yang kosong, atau tetap kirim pengajuan manual jika ingin dibantu koordinasi oleh operator.</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div wire:loading wire:target="updatedJadwalPraktikumId, updatedJenisPerubahan, updatedHariBaru, updatedJamMulaiBaru, updatedJamSelesaiBaru, updatedRuangPraktikumIdBaru, updatedTanggalPengganti"
                             class="p-3.5 rounded-2xl bg-gray-50 dark:bg-zinc-800/60 border border-gray-200 dark:border-zinc-700 text-gray-500 dark:text-zinc-400 text-xs flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-emerald-600 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span>Memeriksa ketersediaan ruangan & jadwal dosen...</span>
                        </div>
                    @endif

                    <div>
                        <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300 uppercase tracking-wider">Alasan Pengajuan (Wajib)</label>
                        <textarea wire:model="alasan" rows="3" placeholder="Jelaskan alasan pengajuan perubahan jadwal ini (mis. bentrok kegiatan akademik, dinas luar, perawatan perangkat lab)..." class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100 transition"></textarea>
                        @error('alasan') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 dark:bg-zinc-800/60 border-t border-gray-100 dark:border-zinc-800 flex flex-col sm:flex-row items-center justify-between gap-2 shrink-0">
                    <button wire:click="$set('showModal', false)" type="button" class="w-full sm:w-auto px-4 py-2 text-xs text-gray-600 dark:text-zinc-400 hover:bg-gray-200/60 dark:hover:bg-zinc-700 rounded-xl transition font-semibold text-center">
                        Batal
                    </button>

                    <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                        @if ($isSlotAvailable)
                            <button wire:click="save" wire:loading.attr="disabled" type="button" class="px-3.5 py-2 text-xs text-gray-600 dark:text-zinc-300 hover:bg-gray-200/50 dark:hover:bg-zinc-700 rounded-xl transition font-semibold border border-gray-300 dark:border-zinc-700">
                                Pengajuan Biasa
                            </button>
                            <button wire:click="rescheduleInstant" wire:loading.attr="disabled" type="button" class="px-5 py-2 text-xs bg-[#0F352A] hover:bg-[#164336] dark:bg-emerald-600 dark:hover:bg-emerald-500 text-white font-bold rounded-xl transition shadow-xs inline-flex items-center justify-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                <span wire:loading.remove wire:target="rescheduleInstant">⚡ Pindahkan Langsung</span>
                                <span wire:loading wire:target="rescheduleInstant">Memproses...</span>
                            </button>
                        @else
                            <button wire:click="save" wire:loading.attr="disabled" type="button" class="w-full sm:w-auto px-5 py-2 text-xs bg-[#0F352A] hover:bg-[#164336] dark:bg-emerald-600 dark:hover:bg-emerald-500 text-white font-bold rounded-xl transition shadow-xs disabled:opacity-50">
                                <span wire:loading.remove wire:target="save">Kirim Pengajuan</span>
                                <span wire:loading wire:target="save">Mengirim...</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
