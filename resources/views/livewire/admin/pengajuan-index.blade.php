<div class="space-y-6">

    <div>
        <h1 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-zinc-100 tracking-tight">Pengajuan Perubahan Jadwal</h1>
        <p class="text-xs sm:text-sm text-gray-500 dark:text-zinc-400 mt-0.5">Review permohonan pergantian jadwal atau ruang dari dosen pengampu untuk ditindaklanjuti.</p>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 text-emerald-800 dark:text-emerald-300 text-xs rounded-xl px-4 py-3 shadow-2xs flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Filter status --}}
    <div class="flex items-center gap-2 flex-wrap">
        @foreach (['menunggu' => 'Menunggu', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak', '' => 'Semua'] as $value => $label)
            <button wire:click="$set('filterStatus', '{{ $value }}')"
                    @class([
                        'px-3.5 py-1.5 rounded-xl text-xs font-bold transition shadow-2xs',
                        'bg-[#0F352A] dark:bg-emerald-600 text-white' => $filterStatus === $value,
                        'bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 text-gray-600 dark:text-zinc-300 hover:bg-gray-50 dark:hover:bg-zinc-800' => $filterStatus !== $value,
                    ])>
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Desktop Table --}}
    <div class="hidden md:block bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 rounded-2xl overflow-hidden shadow-2xs">
        <table class="w-full text-xs text-left">
            <thead class="bg-gray-50/80 dark:bg-zinc-800/60 border-b border-gray-200/80 dark:border-zinc-700/80 text-[11px] font-bold text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-3.5 w-48">Dosen Pengampu</th>
                    <th class="px-5 py-3.5">Jadwal Praktikum</th>
                    <th class="px-5 py-3.5">Perubahan Diminta</th>
                    <th class="px-5 py-3.5">Alasan</th>
                    <th class="px-5 py-3.5 w-32">Status</th>
                    <th class="px-5 py-3.5 text-right w-44">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-zinc-800 text-gray-800 dark:text-zinc-200">
                @forelse ($pengajuan as $p)
                    <tr class="hover:bg-emerald-50/30 dark:hover:bg-zinc-800/40 transition">
                        <td class="px-5 py-3.5 font-bold text-gray-900 dark:text-zinc-100">{{ $p->dosen->nama }}</td>
                        <td class="px-5 py-3.5 text-gray-600 dark:text-zinc-300">
                            <span class="font-bold text-gray-900 dark:text-zinc-100 block">{{ $p->jadwalPraktikum->mataKuliah->nama_mk }}</span>
                            <span class="text-xs text-gray-400 font-mono">{{ $p->jadwalPraktikum->hari }} ({{ \Illuminate\Support\Str::limit($p->jadwalPraktikum->jam_mulai, 5, '') }}–{{ \Illuminate\Support\Str::limit($p->jadwalPraktikum->jam_selesai, 5, '') }})</span>
                        </td>
                        <td class="px-5 py-3.5 text-gray-800 dark:text-zinc-200">
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
                            <p class="font-medium text-xs">{{ $p->perubahan }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-gray-600 dark:text-zinc-400 text-xs">{{ $p->alasan }}</td>
                        <td class="px-5 py-3.5">
                            <span @class([
                                'px-2.5 py-1 rounded-full text-xs font-semibold inline-flex items-center gap-1.5',
                                'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200/60 dark:border-amber-800/40' => $p->status === 'menunggu',
                                'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40' => $p->status === 'disetujui',
                                'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-200/60 dark:border-rose-800/40' => $p->status === 'ditolak',
                            ])>
                                <span class="w-1.5 h-1.5 rounded-full {{ $p->status === 'disetujui' ? 'bg-emerald-500' : ($p->status === 'menunggu' ? 'bg-amber-500' : 'bg-rose-500') }}"></span>
                                {{ ucfirst($p->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right space-x-2">
                            @if ($p->status === 'menunggu')
                                <button wire:click="openApprove({{ $p->id }})" class="font-bold text-xs text-emerald-700 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 transition">Setujui</button>
                                <button wire:click="openReject({{ $p->id }})" class="font-bold text-xs text-rose-600 dark:text-rose-400 hover:text-rose-700 dark:hover:text-rose-300 transition">Tolak</button>
                            @else
                                <span class="text-gray-400 dark:text-zinc-500 text-xs italic">Selesai</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-8 text-center text-gray-400 dark:text-zinc-500 text-sm">Tidak ada permohonan pengajuan.</td></tr>
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
                            <h3 class="font-bold text-sm text-gray-900 dark:text-zinc-100 leading-snug truncate">{{ $p->dosen->nama }}</h3>
                            <p class="text-xs text-emerald-700 dark:text-emerald-400 font-semibold truncate mt-0.5">{{ $p->jadwalPraktikum->mataKuliah->nama_mk }}</p>
                        </div>
                        <span @class([
                            'px-2 py-0.5 rounded-full text-[11px] font-semibold shrink-0',
                            'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200/60' => $p->status === 'menunggu',
                            'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60' => $p->status === 'disetujui',
                            'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-200/60' => $p->status === 'ditolak',
                        ])>
                            {{ ucfirst($p->status) }}
                        </span>
                    </div>

                    <div class="bg-gray-50/80 dark:bg-zinc-800/60 rounded-xl p-3 space-y-1.5 border border-gray-100 dark:border-zinc-700/80 text-xs mt-2">
                        <div>
                            <span class="text-gray-400 dark:text-zinc-400 block text-[10px] font-semibold uppercase">Perubahan Diminta:</span>
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
                    </div>
                </div>

                <div class="pt-3 mt-3 border-t border-gray-100 dark:border-zinc-800 flex items-center justify-end">
                    @if ($p->status === 'menunggu')
                        <div class="flex items-center gap-2">
                            <button wire:click="openApprove({{ $p->id }})" class="px-3 py-1.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-300 font-bold hover:bg-emerald-100 transition text-xs">Setujui</button>
                            <button wire:click="openReject({{ $p->id }})" class="px-3 py-1.5 rounded-xl bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 font-bold hover:bg-rose-100 transition text-xs">Tolak</button>
                        </div>
                    @else
                        <span class="text-gray-400 dark:text-zinc-500 text-xs italic">Selesai</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="sm:col-span-2 bg-white dark:bg-zinc-900 rounded-2xl p-8 text-center border border-gray-200/80 dark:border-zinc-800 text-gray-500 dark:text-zinc-400 text-sm">
                Tidak ada permohonan pengajuan.
            </div>
        @endforelse
    </div>

    {{ $pengajuan->links() }}

    {{-- Modal konfirmasi approve --}}
    @if ($processId && $processAction === 'approve')
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-zinc-900 rounded-3xl w-full max-w-sm p-6 space-y-4 shadow-2xl border border-gray-100 dark:border-zinc-800 text-gray-900 dark:text-zinc-100 animate-in fade-in zoom-in-95 duration-150">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto border border-emerald-200 dark:border-emerald-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div class="text-center">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-zinc-100">Setujui Pengajuan?</h2>
                    <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">
                        Setelah disetujui, terapkan perubahannya di menu Kelola Jadwal agar jadwal resmi terbaharui.
                    </p>
                </div>
                <div class="flex justify-center gap-2 pt-2">
                    <button wire:click="$set('processId', null)" class="px-4 py-2 text-xs text-gray-600 dark:text-zinc-400 hover:bg-gray-100 dark:hover:bg-zinc-800 font-semibold rounded-xl transition">Batal</button>
                    <button wire:click="confirmProcess" class="px-4 py-2 text-xs bg-[#0F352A] hover:bg-[#164336] dark:bg-emerald-600 dark:hover:bg-emerald-500 text-white font-bold rounded-xl shadow-xs transition">
                        Ya, Setujui
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal konfirmasi reject --}}
    @if ($processId && $processAction === 'reject')
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-zinc-900 rounded-3xl w-full max-w-sm p-6 space-y-4 shadow-2xl border border-gray-100 dark:border-zinc-800 text-gray-900 dark:text-zinc-100 animate-in fade-in zoom-in-95 duration-150">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center mx-auto border border-rose-200 dark:border-rose-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <div class="text-center">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-zinc-100">Tolak Pengajuan</h2>
                    <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">Berikan alasan penolakan untuk diinformasikan kepada dosen.</p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-700 dark:text-zinc-300">Catatan Penolakan (Wajib)</label>
                    <textarea wire:model="catatan_penolakan" rows="3" placeholder="mis. Ruangan bentrok dengan ujian praktikum..." class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs py-2 px-3 mt-1 text-gray-900 dark:text-zinc-100 transition"></textarea>
                    @error('catatan_penolakan') <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex justify-center gap-2 pt-2">
                    <button wire:click="$set('processId', null)" class="px-4 py-2 text-xs text-gray-600 dark:text-zinc-400 hover:bg-gray-100 dark:hover:bg-zinc-800 font-semibold rounded-xl transition">Batal</button>
                    <button wire:click="confirmProcess" class="px-4 py-2 text-xs bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl shadow-xs transition">
                        Ya, Tolak
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
