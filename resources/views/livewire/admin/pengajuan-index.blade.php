<div class="space-y-6">

    <div>
        <h1 class="text-xl font-bold text-dark">Pengajuan Perubahan Jadwal</h1>
        <p class="text-dark/60 text-sm">Review pengajuan dari dosen, lalu terapkan manual di Kelola Jadwal kalau disetujui.</p>
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
                        'bg-dark text-white border-dark' => $filterStatus === $value,
                        'border-dark/20 text-dark/70 hover:bg-dark/5' => $filterStatus !== $value,
                    ])>
                {{ $label }}
            </button>
        @endforeach
    </div>

    <div class="bg-white border border-dark/10 rounded-xl overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-dark text-white text-left">
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
                        <td class="px-4 py-3 font-medium text-dark">{{ $p->dosen->nama }}</td>
                        <td class="px-4 py-3 text-dark/70">{{ $p->jadwalPraktikum->mataKuliah->nama_mk }}</td>
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
                        <td class="px-4 py-3 text-right space-x-2">
                            @if ($p->status === 'menunggu')
                                <button wire:click="openApprove({{ $p->id }})" class="text-green-700 font-medium hover:underline">Setujui</button>
                                <button wire:click="openReject({{ $p->id }})" class="text-red-600 font-medium hover:underline">Tolak</button>
                            @else
                                <span class="text-dark/30 text-xs">Sudah diproses</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-dark/50">Tidak ada pengajuan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $pengajuan->links() }}

    {{-- Modal konfirmasi approve --}}
    @if ($processId && $processAction === 'approve')
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 px-4">
            <div class="bg-white rounded-xl w-full max-w-sm p-6 space-y-4">
                <h2 class="text-lg font-semibold text-dark">Setujui Pengajuan?</h2>
                <p class="text-sm text-dark/60">
                    Setelah disetujui, terapkan perubahannya secara manual di menu Kelola Jadwal
                    supaya tetap melewati pengecekan bentrok.
                </p>
                <div class="flex justify-end gap-2">
                    <button wire:click="$set('processId', null)" class="px-4 py-2 text-sm text-dark/60 hover:bg-dark/5 rounded-lg">Batal</button>
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
                <h2 class="text-lg font-semibold text-dark">Tolak Pengajuan</h2>
                <div>
                    <label class="text-sm text-dark/70">Catatan Penolakan (wajib diisi)</label>
                    <textarea wire:model="catatan_penolakan" rows="3" class="w-full rounded-lg border-dark/20 text-sm mt-1"></textarea>
                    @error('catatan_penolakan') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex justify-end gap-2">
                    <button wire:click="$set('processId', null)" class="px-4 py-2 text-sm text-dark/60 hover:bg-dark/5 rounded-lg">Batal</button>
                    <button wire:click="confirmProcess" class="px-4 py-2 text-sm bg-red-600 text-white font-medium rounded-lg hover:bg-red-700">
                        Ya, Tolak
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
