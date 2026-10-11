<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-dark">Notifikasi Saya</h1>
            <p class="text-dark/60 text-sm">
                {{ $jumlahBelumDibaca }} notifikasi belum dibaca.
            </p>
        </div>
        @if ($jumlahBelumDibaca > 0)
            <button wire:click="markAllAsRead" class="text-sm text-primary-dark font-medium hover:underline">
                Tandai semua sudah dibaca
            </button>
        @endif
    </div>

    <div class="space-y-3">
        @forelse ($notifikasi as $n)
            <div wire:click="markAsRead('{{ $n->id }}')"
                 @class([
                     'bg-white border rounded-xl p-4 cursor-pointer transition',
                     'border-primary/40 bg-primary/5' => is_null($n->read_at),
                     'border-dark/10' => ! is_null($n->read_at),
                 ])>
                <div class="flex items-start justify-between gap-3">
                    <div>
                        @if ($n->data['status'] === 'disetujui')
                            <p class="font-medium text-green-700">Pengajuan Disetujui</p>
                        @else
                            <p class="font-medium text-red-600">Pengajuan Ditolak</p>
                        @endif
                        <p class="text-sm text-dark/70 mt-1">
                            Mata kuliah: <strong>{{ $n->data['nama_mk'] }}</strong>
                        </p>
                        <p class="text-sm text-dark/60 mt-1">{{ $n->data['perubahan'] }}</p>
                        @if ($n->data['status'] === 'ditolak' && $n->data['catatan_penolakan'])
                            <p class="text-sm text-dark/60 mt-1 italic">"{{ $n->data['catatan_penolakan'] }}"</p>
                        @endif
                    </div>
                    @if (is_null($n->read_at))
                        <span class="w-2 h-2 rounded-full bg-primary shrink-0 mt-1.5"></span>
                    @endif
                </div>
                <p class="text-xs text-dark/40 mt-2">{{ $n->created_at->diffForHumans() }}</p>
            </div>
        @empty
            <div class="bg-white border border-dark/10 rounded-xl p-8 text-center text-dark/50">
                Belum ada notifikasi.
            </div>
        @endforelse
    </div>

    {{ $notifikasi->links() }}

</div>
