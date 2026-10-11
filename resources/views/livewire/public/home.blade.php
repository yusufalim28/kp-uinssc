<div class="space-y-12 pb-10">

    {{-- Hero Section --}}
    <section class="relative bg-primary overflow-hidden rounded-3xl shadow-xl">
        <div class="absolute inset-0 bg-gradient-to-br from-primary to-primary-dark opacity-90"></div>
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
        
        <div class="relative p-10 md:p-16 flex flex-col md:flex-row items-center justify-between gap-10">
            <div class="max-w-2xl text-white">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 border border-white/30 text-sm mb-6 backdrop-blur-sm">
                    <span class="w-2 h-2 rounded-full bg-yellow-300 animate-pulse"></span>
                    Sistem Akademik Terpadu
                </div>
                <h1 class="text-3xl md:text-5xl font-bold leading-tight">
                    Manajemen <span class="text-yellow-300">Jadwal Praktikum</span> Mudah & Cepat
                </h1>
                <p class="mt-5 text-lg text-white/90 leading-relaxed">
                    Akses informasi jadwal praktikum, ketersediaan ruang, dan pembaruan secara real-time. Membantu mahasiswa dan dosen dalam kegiatan akademik di lingkungan Universitas.
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
            <div class="hidden md:flex w-64 h-64 bg-white/10 rounded-full items-center justify-center backdrop-blur-md border border-white/20">
                <svg class="w-32 h-32 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
        </div>
    </section>

    {{-- Statistik ringkas --}}
    <section class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-5 hover:shadow-md transition">
            <div class="w-14 h-14 rounded-full bg-green-50 text-primary flex items-center justify-center">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Jadwal Aktif</p>
                <p class="text-3xl font-bold text-gray-900">{{ $totalJadwalAktif }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-5 hover:shadow-md transition">
            <div class="w-14 h-14 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Mata Kuliah</p>
                <p class="text-3xl font-bold text-gray-900">{{ $totalMataKuliah }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-5 hover:shadow-md transition">
            <div class="w-14 h-14 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Dosen Pengajar</p>
                <p class="text-3xl font-bold text-gray-900">{{ $totalDosen }}</p>
            </div>
        </div>
    </section>

    {{-- Informasi & Fitur Utama --}}
    <section class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center bg-white rounded-3xl p-8 md:p-12 shadow-sm border border-gray-100">
        <div class="order-2 md:order-1">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Mengapa Menggunakan Sistem Ini?</h2>
            <div class="mt-6 space-y-5">
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 text-lg">Informasi Terpusat</h3>
                        <p class="text-gray-500 mt-1">Seluruh jadwal, ruangan, dan data praktikum dikelola dalam satu platform yang terintegrasi.</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 text-lg">Notifikasi Real-time</h3>
                        <p class="text-gray-500 mt-1">Dapatkan pembaruan langsung jika ada perubahan ruangan atau waktu praktikum dari dosen.</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 text-lg">Akuntabilitas & Riwayat</h3>
                        <p class="text-gray-500 mt-1">Fitur pengajuan dan riwayat aktivitas memastikan setiap perubahan dapat dipertanggungjawabkan.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="order-1 md:order-2 flex justify-center">
            <div class="w-full max-w-sm bg-gray-50 rounded-2xl border border-gray-100 p-2 shadow-inner">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <h4 class="font-semibold text-gray-800 border-b pb-2 mb-3">Live Updates</h4>
                    <div class="space-y-3">
                        <div class="animate-pulse flex space-x-4">
                            <div class="rounded-full bg-gray-200 h-10 w-10"></div>
                            <div class="flex-1 space-y-2 py-1">
                                <div class="h-2 bg-gray-200 rounded w-3/4"></div>
                                <div class="h-2 bg-gray-200 rounded w-1/2"></div>
                            </div>
                        </div>
                        <div class="animate-pulse flex space-x-4">
                            <div class="rounded-full bg-gray-200 h-10 w-10"></div>
                            <div class="flex-1 space-y-2 py-1">
                                <div class="h-2 bg-gray-200 rounded w-5/6"></div>
                                <div class="h-2 bg-gray-200 rounded w-2/3"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Jadwal terbaru --}}
    <section>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Jadwal Ditambahkan Terakhir</h2>
            <a href="{{ route('jadwal.index') }}" class="text-primary font-medium hover:underline flex items-center gap-1">
                Semua Jadwal <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        @if ($jadwalTerbaru->isEmpty())
            <div class="bg-white rounded-2xl p-8 text-center border border-gray-100">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 text-gray-400 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <p class="text-gray-500 font-medium">Belum ada jadwal yang ditambahkan.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($jadwalTerbaru as $jadwal)
                    <a href="{{ route('jadwal.detail', $jadwal) }}"
                       class="group bg-white border border-gray-100 shadow-sm rounded-2xl p-5 hover:border-primary hover:shadow-md transition">
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
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </section>

</div>
