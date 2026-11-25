<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk Portal — Sistem Informasi Manajemen Jadwal Praktikum</title>

    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body
    class="min-h-dvh lg:h-dvh lg:overflow-hidden bg-[#F5F7F4] dark:bg-zinc-950 text-gray-900 dark:text-zinc-100 antialiased transition-colors relative flex flex-col justify-between"
    x-data="{
        darkMode: document.documentElement.classList.contains('dark'),
        fillDemo() {
            const emailEl = document.getElementById('email');
            const passEl = document.getElementById('password');
            if (emailEl) { emailEl.value = 'demo@uinssc.ac.id'; emailEl.focus(); }
            if (passEl) { passEl.value = 'demo1234'; }
        }
    }"
>
    {{-- Latar Belakang Pola Geometris Islami Halus --}}
    <div aria-hidden="true" class="pointer-events-none select-none fixed inset-0 overflow-hidden opacity-[0.045] dark:opacity-[0.06] text-primary dark:text-emerald-300">
        <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="sjp-login-pattern" width="72" height="72" patternUnits="userSpaceOnUse">
                    <path d="M36 4 L45 27 L68 36 L45 45 L36 68 L27 45 L4 36 L27 27 Z" fill="none" stroke="currentColor" stroke-width="1.2" />
                    <rect x="20" y="20" width="32" height="32" fill="none" stroke="currentColor" stroke-width="0.9" />
                    <rect x="20" y="20" width="32" height="32" transform="rotate(45 36 36)" fill="none" stroke="currentColor" stroke-width="0.9" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#sjp-login-pattern)" />
        </svg>
    </div>

    {{-- Cahaya Aksen Halus di Sudut --}}
    <div aria-hidden="true" class="pointer-events-none fixed -top-32 -left-32 w-96 h-96 rounded-full bg-emerald-500/10 dark:bg-emerald-500/10 blur-3xl"></div>
    <div aria-hidden="true" class="pointer-events-none fixed -bottom-32 -right-24 w-96 h-96 rounded-full bg-amber-400/10 dark:bg-emerald-700/15 blur-3xl"></div>

    {{-- Kontrol Mengambang Kanan Atas (Beranda & Dark Mode) --}}
    <div class="relative z-20 max-w-6xl w-full mx-auto px-5 sm:px-8 pt-4 flex items-center justify-between lg:justify-end gap-2.5 shrink-0">
        <a href="{{ route('home') }}"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white/80 dark:bg-zinc-900/80 backdrop-blur-xs border border-gray-200/80 dark:border-zinc-800 text-xs font-semibold text-gray-700 dark:text-zinc-300 hover:text-primary dark:hover:text-emerald-400 hover:border-emerald-300 dark:hover:border-emerald-800 transition shadow-2xs">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Beranda Publik</span>
        </a>

        <button type="button"
                @click="darkMode = !darkMode; if (darkMode) { document.documentElement.classList.add('dark'); localStorage.setItem('theme', 'dark'); } else { document.documentElement.classList.remove('dark'); localStorage.setItem('theme', 'light'); }"
                class="p-2 rounded-xl bg-white/80 dark:bg-zinc-900/80 backdrop-blur-xs border border-gray-200/80 dark:border-zinc-800 text-gray-600 dark:text-zinc-300 hover:border-emerald-300 dark:hover:border-emerald-800 transition shadow-2xs"
                title="Alihkan Mode Gelap / Terang">
            <svg x-show="!darkMode" class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            <svg x-show="darkMode" x-cloak class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
        </button>
    </div>

    {{-- Konten Utama 2 Kolom (Kiri: Informasi & Visual | Kanan: Form Login) --}}
    <main class="relative z-10 flex-1 flex items-center justify-center px-5 sm:px-8 py-4 lg:py-0">
        <div class="max-w-6xl w-full grid grid-cols-1 lg:grid-cols-12 gap-7 xl:gap-12 items-center">

            {{-- KOLOM KIRI: Identitas, Judul, Visual Showcase & 3 Keunggulan --}}
            <div class="lg:col-span-7 space-y-5 hidden lg:block">
                {{-- Header Institusi --}}
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 shadow-xs flex items-center justify-center p-1.5 shrink-0">
                        <img src="{{ asset('img/uinsscv2.png') }}" alt="Logo UINSSC" class="w-full h-full object-contain">
                    </div>
                    <div class="leading-tight">
                        <p class="text-xs font-bold text-gray-800 dark:text-zinc-200">UIN Siber</p>
                        <p class="text-xs font-bold text-gray-800 dark:text-zinc-200">Syekh Nurjati Cirebon</p>
                    </div>
                </div>

                {{-- Judul Besar & Deskripsi --}}
                <div class="space-y-1.5">
                    <h1 class="text-4xl xl:text-[42px] font-black tracking-tight text-primary dark:text-emerald-400 leading-none">
                        SJP UINSSC
                    </h1>
                    <p class="text-base xl:text-lg font-bold text-gray-900 dark:text-zinc-100">
                        Sistem Informasi Manajemen Jadwal Praktikum
                    </p>
                    <p class="text-xs xl:text-sm text-gray-500 dark:text-zinc-400 max-w-lg leading-relaxed">
                        Portal terpadu pengelolaan jadwal laboratorium lintas fakultas, deteksi bentrok otomatis, dan transparansi ruang praktikum di bawah UPT PUSTIKOM.
                    </p>
                </div>

                {{-- Kartu Visual Kampus / Preview Sistem (Deep Forest Emerald) --}}
                <div data-keep-light class="relative rounded-2xl overflow-hidden bg-gradient-to-br from-[#0B2E23] via-[#0E3A2C] to-[#071E17] border border-emerald-900/60 shadow-lg p-5 xl:p-6 text-white">
                    {{-- Ornamen Geometris di Dalam Kartu --}}
                    <div aria-hidden="true" class="pointer-events-none absolute -right-8 -bottom-8 w-48 h-48 opacity-10 text-emerald-200">
                        <svg viewBox="0 0 120 120" fill="none" class="w-full h-full">
                            <path d="M60 6 L75 45 L114 60 L75 75 L60 114 L45 75 L6 60 L45 45 Z" stroke="currentColor" stroke-width="2" />
                            <rect x="30" y="30" width="60" height="60" stroke="currentColor" stroke-width="1.5" />
                            <rect x="30" y="30" width="60" height="60" transform="rotate(45 60 60)" stroke="currentColor" stroke-width="1.5" />
                        </svg>
                    </div>

                    <div class="relative z-10 flex items-center justify-between gap-4">
                        <div class="space-y-3 max-w-xs">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-400/15 border border-amber-400/30 text-amber-300 text-[10px] font-extrabold uppercase tracking-wider">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                UPT PUSTIKOM • UINSSC
                            </span>
                            <h2 class="text-lg xl:text-xl font-extrabold leading-snug text-white">
                                Jadwal Laboratorium Terpusat, Rapi &amp; Bebas Bentrok
                            </h2>
                            <div class="flex items-center gap-3 text-[11px] text-emerald-100/80 font-medium">
                                <span class="inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> 5 Fakultas
                                </span>
                                <span class="inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Matriks 12 Sesi
                                </span>
                                <span class="inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span> Multi-Role
                                </span>
                            </div>
                        </div>

                        {{-- Mini Ilustrasi Matriks Jadwal Lab --}}
                        <div class="hidden xl:block w-56 shrink-0 bg-white/10 backdrop-blur-xs border border-white/15 rounded-xl p-3 space-y-2 shadow-inner">
                            <div class="flex items-center justify-between text-[10px] font-bold text-emerald-100/90 border-b border-white/10 pb-1.5">
                                <span>Matriks Ruang Lab</span>
                                <span class="px-1.5 py-0.5 rounded bg-emerald-400/20 text-emerald-200 text-[9px]">Aktif</span>
                            </div>
                            <div class="grid grid-cols-5 gap-1 text-[8px] text-center text-emerald-200/70 font-semibold">
                                <span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span>
                            </div>
                            <div class="grid grid-cols-5 gap-1">
                                <div class="h-5 rounded bg-emerald-400/30 border border-emerald-300/30"></div>
                                <div class="h-5 rounded bg-white/10"></div>
                                <div class="h-5 rounded bg-amber-400/30 border border-amber-300/30"></div>
                                <div class="h-5 rounded bg-emerald-400/30 border border-emerald-300/30"></div>
                                <div class="h-5 rounded bg-white/10"></div>
                                <div class="h-5 rounded bg-white/10"></div>
                                <div class="h-5 rounded bg-emerald-400/30 border border-emerald-300/30"></div>
                                <div class="h-5 rounded bg-emerald-400/30 border border-emerald-300/30"></div>
                                <div class="h-5 rounded bg-white/10"></div>
                                <div class="h-5 rounded bg-amber-400/30 border border-amber-300/30"></div>
                                <div class="h-5 rounded bg-amber-400/30 border border-amber-300/30"></div>
                                <div class="h-5 rounded bg-white/10"></div>
                                <div class="h-5 rounded bg-emerald-400/30 border border-emerald-300/30"></div>
                                <div class="h-5 rounded bg-emerald-400/30 border border-emerald-300/30"></div>
                                <div class="h-5 rounded bg-white/10"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 3 Kartu Fitur Ringkas --}}
                <div class="grid grid-cols-3 gap-3">
                    <div class="bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 rounded-xl p-3 flex items-center gap-2.5 shadow-2xs">
                        <div class="w-9 h-9 rounded-lg bg-emerald-50 dark:bg-emerald-950/50 text-primary dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800/60 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div class="min-w-0 leading-tight">
                            <p class="text-xs font-bold text-gray-900 dark:text-zinc-100 truncate">Anti-Bentrok</p>
                            <p class="text-[11px] text-gray-500 dark:text-zinc-400 truncate mt-0.5">Ruang &amp; dosen otomatis</p>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 rounded-xl p-3 flex items-center gap-2.5 shadow-2xs">
                        <div class="w-9 h-9 rounded-lg bg-emerald-50 dark:bg-emerald-950/50 text-primary dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800/60 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="min-w-0 leading-tight">
                            <p class="text-xs font-bold text-gray-900 dark:text-zinc-100 truncate">Reschedule Cepat</p>
                            <p class="text-[11px] text-gray-500 dark:text-zinc-400 truncate mt-0.5">Cek slot secara real-time</p>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 rounded-xl p-3 flex items-center gap-2.5 shadow-2xs">
                        <div class="w-9 h-9 rounded-lg bg-emerald-50 dark:bg-emerald-950/50 text-primary dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800/60 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <div class="min-w-0 leading-tight">
                            <p class="text-xs font-bold text-gray-900 dark:text-zinc-100 truncate">Info Terbuka</p>
                            <p class="text-[11px] text-gray-500 dark:text-zinc-400 truncate mt-0.5">Jadwal &amp; modul publik</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: Kartu Form Login --}}
            <div class="lg:col-span-5 w-full max-w-md mx-auto lg:max-w-none">
                <div class="bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 rounded-3xl shadow-xl shadow-emerald-950/5 dark:shadow-black/40 p-6 sm:p-7 xl:p-8">

                    {{-- Header Form --}}
                    <div class="text-center mb-5">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200/70 dark:border-emerald-800/60 p-2 mb-3 shadow-2xs">
                            <img src="{{ asset('img/uinsscv2.png') }}" alt="Logo SJP UINSSC" class="w-full h-full object-contain">
                        </div>
                        <h2 class="text-xl font-black text-primary dark:text-zinc-100 tracking-tight">
                            Masuk ke SJP UINSSC
                        </h2>
                        <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">
                            Gunakan akun kampus (NIP / NIDN / Email) untuk melanjutkan.
                        </p>
                    </div>

                    {{-- Session Status / Flash Alert --}}
                    <x-auth-session-status class="text-center mb-3" :status="session('status')" />

                    <form method="POST" action="{{ route('login.store') }}" class="space-y-3.5">
                        @csrf

                        {{-- Input NIP / NIDN / Email --}}
                        <div>
                            <label for="email" class="block text-xs font-bold text-gray-700 dark:text-zinc-300 mb-1">
                                NIP / NIDN / Email
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 dark:text-zinc-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <input
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    type="text"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    placeholder="Masukkan NIP / NIDN / Email"
                                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-zinc-700 text-sm focus:border-primary dark:focus:border-emerald-500 focus:ring-2 focus:ring-primary/15 bg-gray-50/70 dark:bg-zinc-800 text-gray-900 dark:text-zinc-100 focus:bg-white dark:focus:bg-zinc-800 transition"
                                />
                            </div>
                            @error('email')
                                <p class="text-red-600 dark:text-red-400 text-[11px] mt-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>

                        {{-- Input Password --}}
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label for="password" class="block text-xs font-bold text-gray-700 dark:text-zinc-300">
                                    Kata Sandi
                                </label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-[11px] text-primary dark:text-emerald-400 hover:underline font-semibold transition" wire:navigate>
                                        Lupa sandi?
                                    </a>
                                @endif
                            </div>
                            <div class="relative" x-data="{ show: false }">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 dark:text-zinc-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <input
                                    id="password"
                                    name="password"
                                    :type="show ? 'text' : 'password'"
                                    required
                                    autocomplete="current-password"
                                    placeholder="Masukkan kata sandi"
                                    class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-gray-200 dark:border-zinc-700 text-sm focus:border-primary dark:focus:border-emerald-500 focus:ring-2 focus:ring-primary/15 bg-gray-50/70 dark:bg-zinc-800 text-gray-900 dark:text-zinc-100 focus:bg-white dark:focus:bg-zinc-800 transition"
                                />
                                <button type="button" @click="show = !show" tabindex="-1" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 dark:text-zinc-400 dark:hover:text-zinc-200 focus:outline-none">
                                    <span x-show="!show">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </span>
                                    <span x-show="show" x-cloak>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                                    </span>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-red-600 dark:text-red-400 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Ingat Saya & Info Bridge SSO --}}
                        <div class="flex items-center justify-between gap-2 pt-0.5">
                            <label class="inline-flex items-center gap-2 cursor-pointer text-xs text-gray-600 dark:text-zinc-400 select-none">
                                <input type="checkbox" name="remember" class="rounded border-gray-300 dark:border-zinc-700 text-primary dark:text-emerald-500 focus:ring-primary/20">
                                <span>Ingat saya</span>
                            </label>
                        </div>

                        {{-- Tombol Submit --}}
                        <button type="submit"
                                class="w-full bg-primary hover:bg-[#124334] dark:bg-emerald-600 dark:hover:bg-emerald-500 text-white font-bold py-2.5 px-4 rounded-xl transition shadow-md shadow-emerald-950/15 flex items-center justify-center gap-2 text-sm">
                            <span>Masuk ke SJP UINSSC</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </form>

                    {{-- Akses Cepat Demo (Read-Only Pameran) --}}
                    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-zinc-800 space-y-2.5">
                        <div class="flex items-center justify-between text-[11px]">
                            <span class="font-bold text-gray-700 dark:text-zinc-300 flex items-center gap-1">
                                <span class="text-amber-500">&#9889;</span> Akses Cepat Demo
                            </span>
                            <span class="text-gray-400 dark:text-zinc-500">1-Klik Isi Otomatis</span>
                        </div>

                        <button type="button"
                                @click="fillDemo()"
                                class="w-full flex items-center justify-center gap-2 px-3.5 py-2 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/70 dark:bg-zinc-800/70 hover:bg-emerald-50/60 dark:hover:bg-emerald-950/30 hover:border-emerald-300 dark:hover:border-emerald-800 text-xs font-semibold text-gray-700 dark:text-zinc-200 transition">
                            <svg class="w-4 h-4 text-primary dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <span>Pengunjung Demo (Read-Only)</span>
                        </button>

                        {{-- Info Singkat Mahasiswa (Tanpa Login) --}}
                        <p class="text-center text-[11px] text-gray-500 dark:text-zinc-400 pt-1">
                            Mahasiswa tidak perlu login?
                            <a href="{{ route('jadwal.index') }}" class="font-bold text-primary dark:text-emerald-400 hover:underline">
                                Lihat Daftar Jadwal &rarr;
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- Baris Keterangan Mini di Bawah (Bukan Footer Penuh, Tetap Dalam 1 Layar) --}}
    <div class="relative z-10 max-w-6xl w-full mx-auto px-5 sm:px-8 pb-4 pt-1 flex flex-col sm:flex-row items-center justify-between gap-1 text-[11px] text-gray-400 dark:text-zinc-500 shrink-0">
        <span>SJP &bull; UPT PUSTIKOM UIN Siber Syekh Nurjati Cirebon</span>
        <span>
            Butuh bantuan?
            <a href="{{ route('panduan.index') }}" class="font-semibold text-gray-600 dark:text-zinc-300 underline hover:text-primary dark:hover:text-emerald-400 transition">
                Panduan &amp; Layanan
            </a>
        </span>
    </div>

    @livewireScripts
</body>
</html>