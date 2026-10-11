<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Sistem Informasi Manajemen Jadwal Praktikum' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen flex flex-col">

    {{-- Navbar publik --}}
    <header class="bg-primary text-white sticky top-0 z-40">
        <nav class="max-w-6xl mx-auto px-4 flex items-center justify-between h-16">
            <a href="{{ route('home') }}" class="flex items-center gap-2 font-semibold text-lg">
                <span class="w-8 h-8 rounded bg-white text-primary flex items-center justify-center font-bold">SP</span>
                <span>SI Jadwal Praktikum</span>
            </a>

            {{-- Menu desktop --}}
            <div class="hidden md:flex items-center gap-6 text-sm">
                <a href="{{ route('home') }}" class="hover:text-white/80 transition">Beranda</a>
                <a href="{{ route('jadwal.index') }}" class="hover:text-white/80 transition">Daftar Jadwal</a>
                <a href="{{ route('jadwal.kalender') }}" class="hover:text-white/80 transition">Kalender</a>
                <a href="{{ route('login') }}"
                   class="bg-white text-primary font-medium px-4 py-2 rounded-lg hover:bg-gray-100 transition">
                    Login
                </a>
            </div>

            {{-- Toggle mobile --}}
            <button x-data @click="$dispatch('toggle-mobile-menu')" class="md:hidden">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </nav>

        {{-- Menu mobile --}}
        <div x-data="{ open: false }" @toggle-mobile-menu.window="open = !open" x-show="open" x-cloak
             class="md:hidden border-t border-white/20 px-4 py-3 flex flex-col gap-3 text-sm">
            <a href="{{ route('home') }}" class="hover:text-white/80">Beranda</a>
            <a href="{{ route('jadwal.index') }}" class="hover:text-white/80">Daftar Jadwal</a>
            <a href="{{ route('jadwal.kalender') }}" class="hover:text-white/80">Kalender</a>
            <a href="{{ route('login') }}" class="text-white font-medium">Login</a>
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
