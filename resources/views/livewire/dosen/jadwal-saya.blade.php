<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">Jadwal Mengajar Saya</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-0.5">
                Daftar seluruh agenda mata kuliah dan sesi praktikum yang Anda ampu pada semester aktif.
            </p>
        </div>

        {{-- Filter Tahun Akademik --}}
        <div class="flex items-center gap-2">
            <label class="text-xs font-semibold text-gray-600 shrink-0">Periode:</label>
            <select wire:model.live="selectedTahunId" class="py-2 px-3 rounded-xl border border-gray-200 bg-white text-xs sm:text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 transition">
                <option value="">Semua Periode</option>
                @foreach ($tahunList as $t)
                    <option value="{{ $t->id }}">{{ $t->nama }} ({{ $t->semester }}) {{ $t->is_aktif ? '— Aktif' : '' }}</option>
                @endforeach
            </select>
        </div>
    </div>

    @if (! $dosen)
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 text-amber-900 flex items-start gap-3">
            <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <div class="text-xs sm:text-sm space-y-1">
                <strong class="font-bold">Profil Dosen Belum Terhubung</strong>
                <p>Akun login Anda belum terhubung dengan data Dosen resmi di sistem. Silakan hubungi Super Admin / Pustikom untuk menautkan akun Anda.</p>
            </div>
        </div>
    @else
        {{-- Profile Mini-Summary --}}
        <div class="bg-white border border-gray-200/80 rounded-2xl p-4 sm:p-5 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center font-bold text-lg">
                    🎓
                </div>
                <div>
                    <h2 class="font-bold text-sm sm:text-base text-gray-900">{{ $dosen->nama }}</h2>
                    <p class="text-xs text-gray-500 font-mono">NIDN / NIP: {{ $dosen->nidn ?? '-' }} • Email: {{ $dosen->email ?? '-' }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 text-xs">
                <div class="px-3.5 py-2 rounded-xl bg-gray-50 border border-gray-200/80 text-center">
                    <span class="text-gray-400 block text-[10px] uppercase font-bold">Total Sesi</span>
                    <strong class="text-gray-900 text-sm font-extrabold">{{ $jadwalList->count() }}</strong>
                </div>
                <div class="px-3.5 py-2 rounded-xl bg-primary/5 border border-primary/20 text-center">
                    <span class="text-primary/70 block text-[10px] uppercase font-bold">Total SKS</span>
                    <strong class="text-primary text-sm font-extrabold">{{ $jadwalList->sum(fn($j) => $j->mataKuliah?->sks ?? 0) }} SKS</strong>
                </div>
            </div>
        </div>

        {{-- Schedule Cards / Grid --}}
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <span>🗓️</span> Jadwal Praktikum Mingguan
                </h3>
                <a href="{{ route('pengajuan.saya') }}" class="text-xs font-semibold text-primary hover:text-primary-dark transition flex items-center gap-1">
                    <span>Kelola Pengajuan & Riwayat Perubahan</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            @if ($jadwalList->isEmpty())
                <div class="bg-white border border-gray-200/80 rounded-2xl p-10 text-center space-y-2">
                    <div class="w-12 h-12 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center mx-auto text-xl">📅</div>
                    <p class="text-sm font-semibold text-gray-700">Tidak Ada Jadwal Mengajar</p>
                    <p class="text-xs text-gray-400 max-w-sm mx-auto">Belum ada kelas praktikum yang dijadwalkan untuk Anda pada periode ini. Hubungi operator fakultas/prodi untuk informasi lebih lanjut.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($jadwalList as $j)
                        <div class="bg-white border border-gray-200/80 rounded-2xl p-5 shadow-xs hover:border-primary/40 hover:shadow-md transition flex flex-col justify-between space-y-4">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-primary/10 text-primary border border-primary/20">
                                        {{ $j->hari }}, {{ substr($j->jam_mulai, 0, 5) }} – {{ substr($j->jam_selesai, 0, 5) }} WIB
                                    </span>
                                    <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-gray-100 text-gray-700 font-mono">
                                        {{ $j->kelas?->nama_kelas }}
                                    </span>
                                </div>

                                <div>
                                    <h4 class="font-bold text-sm text-gray-900 leading-snug">{{ $j->mataKuliah?->nama_mk }}</h4>
                                    <p class="text-xs text-gray-400 font-mono mt-0.5">{{ $j->mataKuliah?->kode_mk }} • {{ $j->mataKuliah?->sks }} SKS</p>
                                </div>

                                <div class="text-xs text-gray-600 bg-gray-50/80 rounded-xl p-3 space-y-1">
                                    <div class="flex items-center gap-1.5 font-semibold text-gray-800">
                                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        <span>{{ $j->ruangPraktikum?->nama_ruang }}</span>
                                    </div>
                                    <p class="text-[11px] text-gray-500 pl-5.5">Lokasi: {{ $j->ruangPraktikum?->lokasi ?? '-' }} • Kapasitas: {{ $j->ruangPraktikum?->kapasitas }} org</p>
                                </div>
                            </div>

                            <div class="pt-3 border-t border-gray-100 flex items-center justify-between gap-2">
                                <span class="text-[11px] text-gray-400">Periode {{ $j->tahunAkademik?->nama }}</span>
                                <a href="{{ route('pengajuan.saya') }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 transition">
                                    <span>⚡ Pindah Jadwal</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif
</div>
