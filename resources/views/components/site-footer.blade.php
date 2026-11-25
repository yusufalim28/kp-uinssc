@props(['variant' => 'public'])

<footer class="relative overflow-hidden bg-primary dark:bg-primary-dark text-white border-t border-emerald-900/60 print:hidden">
    {{-- Ornamen Pola Geometris Islami (Kiri Bawah & Kanan Atas) --}}
    <div aria-hidden="true" class="pointer-events-none select-none absolute -bottom-12 -left-12 w-72 h-72 opacity-[0.06] text-emerald-200">
        <svg viewBox="0 0 200 200" fill="none" class="w-full h-full">
            <defs>
                <pattern id="sjp-islamic-bl-{{ $variant }}" width="50" height="50" patternUnits="userSpaceOnUse">
                    <path d="M25 2 L31.5 18.5 L48 25 L31.5 31.5 L25 48 L18.5 31.5 L2 25 L18.5 18.5 Z" stroke="currentColor" stroke-width="1.2" />
                    <rect x="13" y="13" width="24" height="24" stroke="currentColor" stroke-width="1" />
                    <rect x="13" y="13" width="24" height="24" transform="rotate(45 25 25)" stroke="currentColor" stroke-width="1" />
                </pattern>
            </defs>
            <rect width="200" height="200" fill="url(#sjp-islamic-bl-{{ $variant }})" />
        </svg>
    </div>

    <div aria-hidden="true" class="pointer-events-none select-none absolute -top-10 -right-10 w-72 h-72 opacity-[0.06] text-emerald-200">
        <svg viewBox="0 0 200 200" fill="none" class="w-full h-full">
            <defs>
                <pattern id="sjp-islamic-tr-{{ $variant }}" width="50" height="50" patternUnits="userSpaceOnUse">
                    <path d="M25 2 L31.5 18.5 L48 25 L31.5 31.5 L25 48 L18.5 31.5 L2 25 L18.5 18.5 Z" stroke="currentColor" stroke-width="1.2" />
                    <rect x="13" y="13" width="24" height="24" stroke="currentColor" stroke-width="1" />
                    <rect x="13" y="13" width="24" height="24" transform="rotate(45 25 25)" stroke="currentColor" stroke-width="1" />
                </pattern>
            </defs>
            <rect width="200" height="200" fill="url(#sjp-islamic-tr-{{ $variant }})" />
        </svg>
    </div>

    <div class="relative z-10 {{ $variant === 'admin' ? 'max-w-7xl mx-auto px-5 md:px-8 pt-9 pb-6' : 'max-w-7xl mx-auto px-6 lg:px-8 pt-12 pb-8' }}">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 lg:gap-10">

            {{-- Kolom 1: Identitas & Deskripsi Sistem --}}
            <div class="md:col-span-5 space-y-3.5">
                <a href="{{ $variant === 'admin' ? route('dashboard') : route('home') }}" class="inline-flex items-center gap-3 group">
                    <div class="w-11 h-11 rounded-xl bg-white/8 border border-amber-400/25 flex items-center justify-center p-1.5 shadow-sm shrink-0">
                        <img src="{{ asset('img/uinsscv2.png') }}" alt="Logo UINSSC" class="w-full h-full object-contain">
                    </div>
                    <div class="leading-tight">
                        <span class="block text-white font-black text-base sm:text-lg tracking-wide">
                            SJP UINSSC
                        </span>
                        <span class="block text-amber-400 font-semibold text-xs mt-0.5">
                            Sistem Informasi Manajemen Jadwal Praktikum
                        </span>
                    </div>
                </a>

                <p class="text-xs sm:text-[13px] text-emerald-100/75 leading-relaxed max-w-md">
                    Layanan informasi resmi penjadwalan praktikum laboratorium, matriks penggunaan ruang, modul ajar praktikum, dan struktur penanggung jawab laboratorium di lingkungan UIN Siber Syekh Nurjati Cirebon.
                </p>
            </div>

            {{-- Kolom 2: Menu Navigasi --}}
            <div class="md:col-span-3">
                <h3 class="flex items-center gap-2 text-xs font-extrabold uppercase tracking-wider text-white mb-4">
                    <span class="w-1 h-4 rounded-full bg-amber-400 shrink-0"></span>
                    <span>Menu Navigasi</span>
                </h3>

                @if ($variant === 'admin')
                    <ul class="space-y-2 text-xs sm:text-[13px] text-emerald-100/80">
                        <li>
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 hover:text-white transition">
                                <span class="text-amber-400/80 font-bold">&rsaquo;</span>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 hover:text-white transition">
                                <span class="text-amber-400/80 font-bold">&rsaquo;</span>
                                <span>Portal Publik</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('jadwal.ruang') }}" class="inline-flex items-center gap-2 hover:text-white transition">
                                <span class="text-amber-400/80 font-bold">&rsaquo;</span>
                                <span>Jadwal Ruangan</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('struktur.lab') }}" class="inline-flex items-center gap-2 hover:text-white transition">
                                <span class="text-amber-400/80 font-bold">&rsaquo;</span>
                                <span>Struktur Lab</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.panduan') }}" class="inline-flex items-center gap-2 hover:text-white transition">
                                <span class="text-amber-400/80 font-bold">&rsaquo;</span>
                                <span>Panduan &amp; SOP</span>
                            </a>
                        </li>
                    </ul>
                @else
                    <ul class="grid grid-cols-2 sm:grid-cols-1 gap-x-4 gap-y-2 text-xs sm:text-[13px] text-emerald-100/80">
                        <li>
                            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 hover:text-white transition">
                                <span class="text-amber-400/80 font-bold">&rsaquo;</span>
                                <span>Beranda</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('jadwal.index') }}" class="inline-flex items-center gap-2 hover:text-white transition">
                                <span class="text-amber-400/80 font-bold">&rsaquo;</span>
                                <span>Daftar Jadwal</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('jadwal.kalender') }}" class="inline-flex items-center gap-2 hover:text-white transition">
                                <span class="text-amber-400/80 font-bold">&rsaquo;</span>
                                <span>Kalender</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('jadwal.ruang') }}" class="inline-flex items-center gap-2 hover:text-white transition">
                                <span class="text-amber-400/80 font-bold">&rsaquo;</span>
                                <span>Jadwal Ruangan</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('struktur.lab') }}" class="inline-flex items-center gap-2 hover:text-white transition">
                                <span class="text-amber-400/80 font-bold">&rsaquo;</span>
                                <span>Struktur Lab</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('panduan.index') }}" class="inline-flex items-center gap-2 hover:text-white transition">
                                <span class="text-amber-400/80 font-bold">&rsaquo;</span>
                                <span>Panduan &amp; Aturan</span>
                            </a>
                        </li>
                    </ul>
                @endif
            </div>

            {{-- Kolom 3: Kontak Layanan --}}
            <div class="md:col-span-4">
                <h3 class="flex items-center gap-2 text-xs font-extrabold uppercase tracking-wider text-white mb-4">
                    <span class="w-1 h-4 rounded-full bg-amber-400 shrink-0"></span>
                    <span>Kontak Layanan</span>
                </h3>

                <div class="space-y-3 text-xs sm:text-[13px]">
                    <div>
                        <p class="font-semibold text-white">Lokasi Pelayanan</p>
                        <p class="text-emerald-100/75 mt-0.5 leading-relaxed">
                            UPT PUSTIKOM, Kampus UIN Siber Syekh Nurjati Cirebon<br>
                            Jl. Perjuangan By Pass Sunyaragi, Kota Cirebon
                        </p>
                    </div>

                    <div>
                        <p class="font-semibold text-white">Helpdesk &amp; Email</p>
                        <a href="https://pustikom.uinssc.ac.id/" class="inline-block text-emerald-100/80 hover:text-white transition mt-0.5">
                            pustikom.uinssc.ac.id
                        </a>
                    </div>

                    <div>
                        <p class="font-semibold text-white">Jam Pelayanan</p>
                        <p class="text-emerald-100/75 mt-0.5">
                            Senin &ndash; Jumat: 07.30 &ndash; 16.00 WIB
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Baris Hak Cipta Bawah --}}
        <div class="mt-8 pt-5 border-t border-white/10 text-center text-xs text-emerald-100/60">
            &copy; {{ date('Y') }} <span class="text-white font-semibold">SJP PUSTIKOM UIN Siber Syekh Nurjati Cirebon</span>. All Rights Reserved.
        </div>
    </div>
</footer>
