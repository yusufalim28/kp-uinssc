<div class="space-y-6">

    <div>
        <h1 class="text-xl font-bold text-gray-900">Pengajuan Perubahan Jadwal</h1>
        <p class="text-gray-500 text-sm">Review pengajuan dari dosen, lalu terapkan manual di Kelola Jadwal kalau disetujui.</p>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3">{{ session('success') }}</div>
    @endif

    {{-- Filter status --}}
    <div class="flex gap-2">
        @foreach (['menunggu' => 'Menunggu', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak', '' => 'Semua'] as $value => $label)
            <button wire:click="$set('filterStatus', '{{ $value }}')"
                    @class([
                        'px-3 py-1.5 rounded-lg text-sm border',
                        'bg-gray-900 text-white border-gray-300' => $filterStatus === $value,
                        'border-gray-300/20 text-gray-600 hover:bg-gray-900/5' => $filterStatus !== $value,
                    ])>
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Desktop Table --}}
    <div class="hidden md:block bg-white border border-gray-300/10 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-primary text-white text-left">
                <tr>
                    <th class="px-4 py-3">Dosen</th>
                    <th class="px-4 py-3">Jadwal</th>
                    <th class="px-4 py-3">Perubahan Diminta</th>
                    <th class="px-4 py-3">Alasan</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark/10">
                @forelse ($pengajuan as $p)
                    <tr class="hover:bg-primary/5">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $p->dosen->nama }}</td>
                        <td class="px-4 py-3 text-gray-600">
                            <span class="font-medium text-gray-900 block">{{ $p->jadwalPraktikum->mataKuliah->nama_mk }}</span>
                            <span class="text-xs text-gray-500">{{ $p->jadwalPraktikum->hari }} ({{ \Illuminate\Support\Str::limit($p->jadwalPraktikum->jam_mulai, 5, '') }}–{{ \Illuminate\Support\Str::limit($p->jadwalPraktikum->jam_selesai, 5, '') }})</span>
                        </td>
                        <td class="px-4 py-3 text-gray-800">
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
                            <p class="font-medium text-xs">{{ $p->perubahan }}</p>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $p->alasan }}</td>
                        <td class="px-4 py-3">
                            <span @class([
                                'px-2 py-1 rounded-full text-xs font-semibold',
                                'bg-yellow-100 text-yellow-800' => $p->status === 'menunggu',
                                'bg-green-100 text-green-700' => $p->status === 'disetujui',
                                'bg-red-100 text-red-700' => $p->status === 'ditolak',
                            ])>
                                {{ ucfirst($p->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right space-x-2">
                            @if ($p->status === 'menunggu')
                                <button wire:click="openApprove({{ $p->id }})" class="text-green-700 font-medium hover:underline">Setujui</button>
                                <button wire:click="openReject({{ $p->id }})" class="text-red-600 font-medium hover:underline">Tolak</button>
                            @else
                                <span class="text-gray-900/30 text-xs">Sudah diproses</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-gray-900/50">Tidak ada pengajuan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Card View --}}
    <div class="md:hidden grid grid-cols-1 sm:grid-cols-2 gap-3.5">
        @forelse ($pengajuan as $p)
            <div class="bg-white border border-gray-100 rounded-xl p-3.5 shadow-xs hover:border-gray-200 transition flex flex-col justify-between">
                <div>
                    <div class="flex items-start justify-between gap-2 mb-1.5">
                        <div class="min-w-0">
                            <h3 class="font-bold text-sm text-gray-900 leading-snug truncate">{{ $p->dosen->nama }}</h3>
                            <p class="text-xs text-gray-600 font-medium truncate mt-0.5">{{ $p->jadwalPraktikum->mataKuliah->nama_mk }}</p>
                        </div>
                        <span @class([
                            'px-2 py-0.5 rounded-full text-[11px] font-medium shrink-0',
                            'bg-yellow-100 text-yellow-800' => $p->status === 'menunggu',
                            'bg-emerald-100 text-emerald-800' => $p->status === 'disetujui',
                            'bg-red-100 text-red-700' => $p->status === 'ditolak',
                        ])>
                            {{ ucfirst($p->status) }}
                        </span>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-2.5 space-y-1.5 border border-gray-100 text-xs mt-2">
                        <div>
                            <span class="text-gray-400 block text-[10px] font-semibold uppercase">Perubahan Diminta:</span>
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
                    </div>
                </div>

                <div class="pt-2.5 mt-3 border-t border-gray-100 flex items-center justify-end">
                    @if ($p->status === 'menunggu')
                        <div class="flex items-center gap-1.5">
                            <button wire:click="openApprove({{ $p->id }})" class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 font-semibold hover:bg-emerald-100 transition text-xs">Setujui</button>
                            <button wire:click="openReject({{ $p->id }})" class="px-2.5 py-1 rounded-lg bg-red-50 text-red-700 font-semibold hover:bg-red-100 transition text-xs">Tolak</button>
                        </div>
                    @else
                        <span class="text-gray-400 text-xs italic">Sudah diproses</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="sm:col-span-2 bg-white rounded-xl p-8 text-center border border-gray-100 text-gray-500 text-sm">
                Tidak ada pengajuan.
            </div>
        @endforelse
    </div>

    {{ $pengajuan->links() }}

    {{-- Modal konfirmasi approve --}}
    @if ($processId && $processAction === 'approve')
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 px-4">
            <div class="bg-white rounded-xl w-full max-w-sm p-6 space-y-4">
                <h2 class="text-lg font-semibold text-gray-900">Setujui Pengajuan?</h2>
                <p class="text-sm text-gray-500">
                    Setelah disetujui, terapkan perubahannya secara manual di menu Kelola Jadwal
                    supaya tetap melewati pengecekan bentrok.
                </p>
                <div class="flex justify-end gap-2">
                    <button wire:click="$set('processId', null)" class="px-4 py-2 text-sm text-gray-500 hover:bg-gray-900/5 rounded-lg">Batal</button>
                    <button wire:click="confirmProcess" class="px-4 py-2 text-sm bg-green-600 text-white font-medium rounded-lg hover:bg-green-700">
                        Ya, Setujui
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal konfirmasi reject --}}
    @if ($processId && $processAction === 'reject')
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 px-4">
            <div class="bg-white rounded-xl w-full max-w-sm p-6 space-y-4">
                <h2 class="text-lg font-semibold text-gray-900">Tolak Pengajuan</h2>
                <div>
                    <label class="text-sm text-gray-600">Catatan Penolakan (wajib diisi)</label>
                    <textarea wire:model="catatan_penolakan" rows="3" class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:border-primary focus:ring focus:ring-primary/20 text-sm py-2 mt-1 transition"></textarea>
                    @error('catatan_penolakan') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex justify-end gap-2">
                    <button wire:click="$set('processId', null)" class="px-4 py-2 text-sm text-gray-500 hover:bg-gray-900/5 rounded-lg">Batal</button>
                    <button wire:click="confirmProcess" class="px-4 py-2 text-sm bg-red-600 text-white font-medium rounded-lg hover:bg-red-700">
                        Ya, Tolak
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
