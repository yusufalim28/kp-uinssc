<div class="space-y-10">

    {{-- Hero --}}
    <section class="bg-dark text-white rounded-2xl p-8 md:p-12">
        <h1 class="text-2xl md:text-3xl font-bold">
            Sistem Informasi Manajemen <span class="text-primary">Jadwal Praktikum</span>
        </h1>
        <p class="mt-3 text-white/70 max-w-xl">
            Lihat jadwal praktikum terkini, cari berdasarkan mata kuliah atau dosen,
            dan pantau perubahan jadwal secara real-time.
        </p>
        <div class="mt-6 flex gap-3">
            <a href="{{ route('jadwal.index') }}"
               class="bg-primary text-dark font-medium px-5 py-2.5 rounded-lg hover:bg-primary-dark transition">
                Lihat Jadwal
            </a>
            <a href="{{ route('jadwal.kalender') }}"
               class="border border-white/30 text-white px-5 py-2.5 rounded-lg hover:bg-white/10 transition">
                Lihat Kalender
            </a>
        </div>
    </section>

    {{-- Statistik ringkas --}}
    <section class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white border border-dark/10 rounded-xl p-5">
            <p class="text-sm text-dark/60">Jadwal Aktif</p>
            <p class="text-3xl font-bold text-dark">{{ $totalJadwalAktif }}</p>
        </div>
        <div class="bg-white border border-dark/10 rounded-xl p-5">
            <p class="text-sm text-dark/60">Mata Kuliah</p>
            <p class="text-3xl font-bold text-dark">{{ $totalMataKuliah }}</p>
        </div>
        <div class="bg-white border border-dark/10 rounded-xl p-5">
            <p class="text-sm text-dark/60">Dosen</p>
            <p class="text-3xl font-bold text-dark">{{ $totalDosen }}</p>
        </div>
    </section>

    {{-- Jadwal terbaru --}}
    <section>
        <h2 class="text-lg font-semibold text-dark mb-4">Jadwal Terbaru</h2>

        @if ($jadwalTerbaru->isEmpty())
            <p class="text-dark/50 text-sm">Belum ada jadwal yang ditambahkan.</p>
        @else
            <div class="grid gap-3">
                @foreach ($jadwalTerbaru as $jadwal)
                    <a href="{{ route('jadwal.detail', $jadwal) }}"
                       class="flex items-center justify-between bg-white border border-dark/10 rounded-xl px-5 py-4 hover:border-primary transition">
                        <div>
                            <p class="font-medium text-dark">{{ $jadwal->mataKuliah->nama_mk }}</p>
                            <p class="text-sm text-dark/60">{{ $jadwal->dosen->nama }} &middot; {{ $jadwal->hari }}</p>
                        </div>
                        <span class="text-sm text-dark/60">
                            {{ \Illuminate\Support\Str::limit($jadwal->jam_mulai, 5, '') }}–{{ \Illuminate\Support\Str::limit($jadwal->jam_selesai, 5, '') }}
                        </span>
                    </a>
                @endforeach
            </div>
        @endif
    </section>

</div>
