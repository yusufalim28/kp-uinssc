<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Sistem Informasi Manajemen Jadwal Praktikum' }}</title>

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
<body class="min-h-screen flex flex-col bg-surface text-dark dark:bg-zinc-950 dark:text-zinc-100 transition-colors" x-data="{ darkMode: document.documentElement.classList.contains('dark') }">

    {{-- Navbar publik --}}
    {{-- Navbar publik dengan desain modern & split background logo --}}
    <header class="bg-primary text-white sticky top-0 z-45 shadow-sm">
        <div class="max-w-7xl mx-auto flex items-center justify-between h-13">
            
            {{-- Bagian Kiri: Logo dengan Background Putih Penuh sampai Ujung Kiri --}}
            <div data-keep-light class="bg-white text-gray-900 h-full flex items-center px-6 md:px-10 shadow-sm rounded-br-2xl">
                <a href="{{ route('home') }}" class="flex items-center gap-3 font-bold text-base md:text-lg tracking-tight">
                    <img src="{{ asset('img/uinsscv2.png') }}" alt="Logo SJP" class="h-10 w-auto object-contain">
                    <span class="text-primary font-extrabold hidden sm:inline">Jadwal Praktikum</span>
                </a>
            </div>

            {{-- Bagian Tengah / Menu Navigasi Utama (Desktop) --}}
            <nav class="hidden md:flex items-center gap-8 text-sm font-medium px-6">
                <a href="{{ route('home') }}" class="hover:text-white/80 transition py-2 border-b-2 {{ request()->routeIs('home') ? 'border-white' : 'border-transparent' }}">Beranda</a>
                <a href="{{ route('jadwal.index') }}" class="hover:text-white/80 transition py-2 border-b-2 {{ request()->routeIs('jadwal.index') ? 'border-white' : 'border-transparent' }}">Daftar Jadwal</a>
                <a href="{{ route('jadwal.kalender') }}" class="hover:text-white/80 transition py-2 border-b-2 {{ request()->routeIs('jadwal.kalender') ? 'border-white' : 'border-transparent' }}">Kalender</a>
                <a href="{{ route('panduan.index') }}" class="hover:text-white/80 transition py-2 border-b-2 {{ request()->routeIs('panduan.index') ? 'border-white' : 'border-transparent' }}">Panduan & Aturan</a>
            </nav>

            {{-- Bagian Kanan: Aksi (Dark Mode & Login Dosen/Admin) --}}
            <div class="hidden md:flex items-center gap-3 px-6">
                {{-- Tombol Dark Mode Desktop --}}
                <button type="button"
                        @click="darkMode = !darkMode; if (darkMode) { document.documentElement.classList.add('dark'); localStorage.setItem('theme', 'dark'); } else { document.documentElement.classList.remove('dark'); localStorage.setItem('theme', 'light'); }"
                        class="p-2 rounded-xl bg-primary-dark/40 hover:bg-primary-dark/70 border border-white/20 text-white transition"
                        title="Alihkan Mode Gelap / Terang">
                    <svg x-show="!darkMode" class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <svg x-show="darkMode" x-cloak class="w-4 h-4 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                </button>

                <div class="relative" x-data="{ openAuth: false }">
                    <button @click="openAuth = !openAuth" class="flex items-center gap-2 bg-primary-dark/40 hover:bg-primary-dark/60 border border-white/20 text-white text-sm font-medium px-4 py-2 rounded-xl transition shadow-inner">
                        <svg class="w-4 h-4 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>Portal Akses</span>
                        <svg class="w-3 h-3 ml-1 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    
                    {{-- Dropdown khusus Admin/Dosen agar lebih eksklusif dan rapi --}}
                    <div x-show="openAuth" @click.away="openAuth = false" x-cloak 
                         class="absolute right-0 mt-2 w-48 bg-white dark:bg-zinc-900 text-gray-800 dark:text-zinc-100 rounded-xl shadow-xl py-2 border border-gray-100 dark:border-zinc-800 text-sm z-50">
                        <div class="px-4 py-1.5 text-xs font-semibold text-gray-400 dark:text-zinc-500 uppercase tracking-wider">Khusus Pengelola</div>
                        <a href="{{ route('login') }}" class="block px-4 py-2 hover:bg-gray-50 dark:hover:bg-zinc-800 text-primary dark:text-emerald-400 font-semibold transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                            Login Dosen/Admin
                        </a>
                    </div>
                </div>
            </div>

            {{-- Toggle Mobile Button & Mobile Dark Mode --}}
            <div class="flex md:hidden items-center gap-2 px-4">
                <button type="button"
                        @click="darkMode = !darkMode; if (darkMode) { document.documentElement.classList.add('dark'); localStorage.setItem('theme', 'dark'); } else { document.documentElement.classList.remove('dark'); localStorage.setItem('theme', 'light'); }"
                        class="p-2 rounded-lg bg-primary-dark/30 text-white hover:bg-primary-dark/50 transition">
                    <svg x-show="!darkMode" class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <svg x-show="darkMode" x-cloak class="w-5 h-5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                </button>
                <button x-data @click="$dispatch('toggle-mobile-menu')" class="p-2 rounded-lg bg-primary-dark/30 text-white hover:bg-primary-dark/50 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Menu Mobile --}}
        <div x-data="{ open: false }" @toggle-mobile-menu.window="open = !open" x-show="open" x-cloak
             class="md:hidden border-t border-white/10 bg-primary px-6 py-4 flex flex-col gap-3 text-sm font-medium">
            <a href="{{ route('home') }}" class="hover:text-white/80 py-1">Beranda</a>
            <a href="{{ route('jadwal.index') }}" class="hover:text-white/80 py-1">Daftar Jadwal</a>
            <a href="{{ route('jadwal.kalender') }}" class="hover:text-white/80 py-1">Kalender</a>
            <a href="{{ route('panduan.index') }}" class="hover:text-white/80 py-1">Panduan & Aturan</a>
            <div class="pt-2 border-t border-white/10 mt-1">
                <a data-keep-light href="{{ route('login') }}" class="flex items-center justify-center gap-2 bg-white text-primary font-semibold px-4 py-2.5 rounded-xl text-center shadow">
                    Login Dosen / Admin
                </a>
            </div>
        </div>
    </header>

    <main class="flex-1 max-w-6xl w-full mx-auto px-4 py-8">
        {{ $slot }}
    </main>

    <footer class="bg-primary-dark text-white/80 text-sm text-center py-6">
        &copy; {{ date('Y') }} Sistem Informasi Manajemen Jadwal Praktikum
    </footer>

    @livewireScripts
</body>
</html>
