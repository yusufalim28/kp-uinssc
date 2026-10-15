<div class="space-y-12 pb-10">

    {{-- Hero Section --}}
    <section class="relative bg-primary overflow-hidden rounded-3xl shadow-xl">
        <div class="absolute inset-0 bg-gradient-to-br from-primary to-primary-dark opacity-90"></div>
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-25"></div>
        
        <div class="relative p-10 md:p-16 flex flex-col md:flex-row items-center justify-between gap-10">
            <div class="max-w-2xl text-white">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 border border-white/30 text-sm mb-6 backdrop-blur-sm">
                    <span class="w-2 h-2 rounded-full bg-yellow-300 animate-pulse"></span>
                    Sistem Akademik Terpadu
                </div>
                <h1 class="text-3xl md:text-5xl font-bold leading-tight">
                    Manajemen <span class="text-yellow-500">Jadwal Praktikum</span>
                </h1>
                <p class="mt-5 text-lg text-white/90 leading-relaxed">
                    Akses informasi jadwal praktikum, ketersediaan ruang, dan pembaruan secara real-time. Membantu mahasiswa dan dosen dalam kegiatan akademik di lingkungan <i>Universitas Islam Negeri Siber Syekh Nurjati Cirebon</i>.
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('jadwal.index') }}"
                       class="bg-white text-primary font-bold px-6 py-3 rounded-xl hover:bg-gray-100 transition shadow-lg flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        Lihat Jadwal
                    </a>
                    <a href="{{ route('jadwal.kalender') }}"
                       class="bg-primary-dark border border-white/30 text-white font-medium px-6 py-3 rounded-xl hover:bg-white/10 transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Lihat Kalender
                    </a>
                </div>
            </div>
            
            {{-- Decorative Icon Right --}}
            <div class="hidden md:flex w-64 h-64 bg-white/70 rounded-full items-center justify-center backdrop-blur-md border border-white/20">
                <img src="{{ asset('img/uinssc.png') }}" alt="UINSSC" class="w-44 mb-5">
            </div>
        </div>
    </section>

    {{-- Jadwal Hari Ini --}}
    <section>
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-3">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Jadwal Hari Ini</h2>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $hariIni }}, {{ now()->translatedFormat('d F Y') }}
                </p>
            </div>
            <a href="{{ route('jadwal.index') }}" class="text-primary font-medium hover:underline flex items-center gap-1 text-sm">
                Semua Jadwal <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        @if ($jadwalHariIni->isEmpty())
            <div class="bg-white rounded-2xl p-8 text-center border border-gray-100 shadow-sm">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 text-gray-400 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <p class="text-gray-600 font-medium">Tidak ada jadwal praktikum untuk hari {{ $hariIni }}.</p>
                <p class="text-gray-400 text-sm mt-1">Cek halaman Daftar Jadwal untuk melihat jadwal di hari lain.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($jadwalHariIni as $jadwal)
                    <a href="{{ route('jadwal.detail', $jadwal) }}"
                       class="group bg-white border border-gray-100 shadow-xs rounded-xl p-4 hover:border-primary hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-3">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-md bg-primary/10 text-primary">
                                {{ $jadwal->hari }}
                            </span>
                            <span class="text-sm font-medium text-gray-500 bg-gray-50 px-2 py-1 rounded">
                                {{ \Illuminate\Support\Str::limit($jadwal->jam_mulai, 5, '') }}–{{ \Illuminate\Support\Str::limit($jadwal->jam_selesai, 5, '') }}
                            </span>
                        </div>
                        <h3 class="font-bold text-gray-900 group-hover:text-primary transition line-clamp-1 text-lg">
                            {{ $jadwal->mataKuliah->nama_mk }}
                        </h3>
                        <div class="mt-3 space-y-1.5 text-sm text-gray-500">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                <span class="line-clamp-1">{{ $jadwal->dosen->nama }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                <span>{{ $jadwal->ruangPraktikum->nama_ruang }}</span>
                            </div>
                            @if ($jadwal->kelas)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span>{{ $jadwal->kelas->nama_kelas }}</span>
                            </div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </section>

</div>
