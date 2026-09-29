<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dashboard' }} — SI Jadwal Praktikum</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen flex" x-data="{ sidebarOpen: false }">

    {{-- Sidebar --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed md:static md:translate-x-0 z-30 inset-y-0 left-0 w-64 bg-dark text-white transition-transform duration-200">
        <div class="h-16 flex items-center gap-2 px-5 border-b border-white/10">
            <span class="w-8 h-8 rounded bg-primary text-dark flex items-center justify-center font-bold">SP</span>
            <span class="font-semibold">SI Jadwal</span>
        </div>

        <nav class="p-4 space-y-1 text-sm">
            <a href="{{ route('dashboard') }}"
               class="block px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('dashboard') ? 'bg-primary text-dark font-medium' : '' }}">
                Dashboard
            </a>

            @role('super-admin|operator')
                <p class="px-3 pt-4 pb-1 text-white/40 uppercase text-xs tracking-wide">Master Data</p>
                <a href="{{ route('fakultas.index') }}"
                   class="block px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('fakultas.*') ? 'bg-primary text-dark font-medium' : '' }}">
                    Fakultas
                </a>

                {{-- Link di bawah ini sengaja dibungkus Route::has() supaya
                     sidebar tidak error saat route-nya belum dibuat.
                     Hapus pembungkus @if setelah masing-masing komponen
                     CRUD-nya jadi dibuat mengikuti pola FakultasIndex. --}}
                @if (Route::has('prodi.index'))
                    <a href="{{ route('prodi.index') }}"
                       class="block px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('prodi.*') ? 'bg-primary text-dark font-medium' : '' }}">
                        Program Studi
                    </a>
                @endif
                @if (Route::has('kelas.index'))
                    <a href="{{ route('kelas.index') }}"
                       class="block px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('kelas.*') ? 'bg-primary text-dark font-medium' : '' }}">
                        Kelas
                    </a>
                @endif
                @if (Route::has('matakuliah.index'))
                    <a href="{{ route('matakuliah.index') }}"
                       class="block px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('matakuliah.*') ? 'bg-primary text-dark font-medium' : '' }}">
                        Mata Kuliah
                    </a>
                @endif
                @if (Route::has('dosen.index'))
                    <a href="{{ route('dosen.index') }}"
                       class="block px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('dosen.*') ? 'bg-primary text-dark font-medium' : '' }}">
                        Dosen
                    </a>
                @endif
                @if (Route::has('ruang.index'))
                    <a href="{{ route('ruang.index') }}"
                       class="block px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('ruang.*') ? 'bg-primary text-dark font-medium' : '' }}">
                        Ruang Praktikum
                    </a>
                @endif
                @if (Route::has('tahunakademik.index'))
                    <a href="{{ route('tahunakademik.index') }}"
                       class="block px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('tahunakademik.*') ? 'bg-primary text-dark font-medium' : '' }}">
                        Tahun Akademik
                    </a>
                @endif

                @if (Route::has('jadwal.manage') || Route::has('pengajuan.index'))
                    <p class="px-3 pt-4 pb-1 text-white/40 uppercase text-xs tracking-wide">Jadwal</p>
                @endif
                @if (Route::has('jadwal.manage'))
                    <a href="{{ route('jadwal.manage') }}"
                       class="block px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('jadwal.manage') ? 'bg-primary text-dark font-medium' : '' }}">
                        Kelola Jadwal
                    </a>
                @endif
                @if (Route::has('pengajuan.index'))
                    <a href="{{ route('pengajuan.index') }}"
                       class="block px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('pengajuan.index') ? 'bg-primary text-dark font-medium' : '' }}">
                        Pengajuan Perubahan
                    </a>
                @endif
            @endrole

            @role('dosen')
                @if (Route::has('pengajuan.saya'))
                    <p class="px-3 pt-4 pb-1 text-white/40 uppercase text-xs tracking-wide">Menu Dosen</p>
                    <a href="{{ route('pengajuan.saya') }}"
                       class="block px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('pengajuan.saya') ? 'bg-primary text-dark font-medium' : '' }}">
                        Pengajuan Saya
                    </a>
                @endif
            @endrole
        </nav>

        <form method="POST" action="{{ route('logout') }}" class="p-4 mt-auto">
            @csrf
            <button class="w-full text-left px-3 py-2 rounded-lg text-white/70 hover:bg-white/10 text-sm">
                Logout
            </button>
        </form>
    </aside>

    {{-- Overlay mobile --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak
         class="fixed inset-0 bg-black/40 z-20 md:hidden"></div>

    {{-- Konten utama --}}
    <div class="flex-1 min-w-0">
        <header class="h-16 bg-white border-b border-dark/10 flex items-center justify-between px-5 md:hidden">
            <button @click="sidebarOpen = true">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <span class="font-semibold text-dark">{{ $title ?? 'Dashboard' }}</span>
            <span></span>
        </header>

        <main class="p-6">
            {{ $slot }}
        </main>
    </div>

    @livewireScripts
</body>
</html>
