<div class="space-y-6 pb-10">

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Kalender Jadwal Praktikum</h1>
        <p class="text-gray-500 text-sm mt-1">Tampilan mingguan, dikelompokkan per hari.</p>
    </div>

    {{-- Grid mingguan: mobile jadi 1 kolom (scroll vertikal), desktop jadi 6 kolom sejajar --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
        @foreach ($hariList as $hari)
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden flex flex-col">
                <div class="bg-primary text-white text-sm font-bold uppercase tracking-wider px-4 py-3 text-center shadow-sm">
                    {{ $hari }}
                </div>

                <div class="p-3 space-y-3 min-h-[150px] flex-1 bg-gray-50/50">
                    @forelse ($jadwalPerHari->get($hari, collect()) as $j)
                        <div class="bg-white border border-gray-100 shadow-sm rounded-xl px-3 py-3 text-xs hover:border-primary hover:shadow-md transition relative overflow-hidden group">
                            <!-- Left accent line -->
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary/40 group-hover:bg-primary transition"></div>
                            
                            <p class="font-bold text-gray-900 mb-1">
                                {{ \Illuminate\Support\Str::limit($j->jam_mulai, 5, '') }}–{{ \Illuminate\Support\Str::limit($j->jam_selesai, 5, '') }}
                            </p>
                            <p class="font-semibold text-primary line-clamp-2 mb-1.5">{{ $j->mataKuliah->nama_mk }}</p>
                            <div class="space-y-0.5">
                                <p class="text-gray-500 flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> <span class="line-clamp-1">{{ $j->dosen->nama }}</span></p>
                                <p class="text-gray-500 flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg> <span class="line-clamp-1">{{ $j->ruangPraktikum->nama_ruang }}</span></p>
                            </div>
                        </div>
                    @empty
                        <div class="h-full flex flex-col items-center justify-center opacity-50 py-8">
                            <svg class="w-8 h-8 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <p class="text-gray-400 text-xs font-medium">Kosong</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>

</div>
