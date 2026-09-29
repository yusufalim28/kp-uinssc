<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-dark">Kalender Jadwal Praktikum</h1>
        <p class="text-dark/60 text-sm mt-1">Tampilan mingguan, dikelompokkan per hari.</p>
    </div>

    {{-- Grid mingguan: mobile jadi 1 kolom (scroll vertikal), desktop jadi 6 kolom sejajar --}}
    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
        @foreach ($hariList as $hari)
            <div class="bg-white border border-dark/10 rounded-xl overflow-hidden">
                <div class="bg-dark text-white text-sm font-semibold px-4 py-2 text-center">
                    {{ $hari }}
                </div>

                <div class="p-3 space-y-2 min-h-[120px]">
                    @forelse ($jadwalPerHari->get($hari, collect()) as $j)
                        <div class="bg-primary/10 border border-primary/30 rounded-lg px-3 py-2 text-xs">
                            <p class="font-semibold text-dark">
                                {{ \Illuminate\Support\Str::limit($j->jam_mulai, 5, '') }}–{{ \Illuminate\Support\Str::limit($j->jam_selesai, 5, '') }}
                            </p>
                            <p class="text-dark/80">{{ $j->mataKuliah->nama_mk }}</p>
                            <p class="text-dark/50">{{ $j->dosen->nama }} &middot; {{ $j->ruangPraktikum->nama_ruang }}</p>
                        </div>
                    @empty
                        <p class="text-dark/30 text-xs text-center pt-6">Tidak ada jadwal</p>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>

</div>
