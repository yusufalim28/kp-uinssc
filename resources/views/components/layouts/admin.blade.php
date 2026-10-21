<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dashboard' }} — SI Jadwal Praktikum</title>

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
<body class="min-h-screen flex bg-surface text-dark dark:bg-zinc-950 dark:text-zinc-100 transition-colors" x-data="{ sidebarOpen: false, darkMode: document.documentElement.classList.contains('dark') }">

    {{-- Sidebar --}}
    <aside data-keep-light
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed md:static md:translate-x-0 z-30 inset-y-0 left-0 w-64 bg-primary text-white transition-transform duration-200">
        <div class="h-16 flex items-center gap-2 px-5 border-b border-white/10">
            <span class="w-8 h-8 rounded bg-white text-primary flex items-center justify-center font-bold">SP</span>
            <span class="font-semibold">SI Jadwal</span>
        </div>

        <nav class="p-4 space-y-1 text-sm">
            <a href="{{ route('dashboard') }}"
               class="block px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('dashboard') ? 'bg-white text-primary font-medium' : '' }}">
                Dashboard
            </a>

            @role('super-admin|operator')
                <p class="px-3 pt-4 pb-1 text-white/40 uppercase text-xs tracking-wide">Master Data</p>
                <a href="{{ route('fakultas.index') }}"
                   class="block px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('fakultas.*') ? 'bg-white text-primary font-medium' : '' }}">
                    Fakultas
                </a>

                {{-- Link di bawah ini sengaja dibungkus Route::has() supaya
                     sidebar tidak error saat route-nya belum dibuat.
                     Hapus pembungkus @if setelah masing-masing komponen
                     CRUD-nya jadi dibuat mengikuti pola FakultasIndex. --}}
                @if (Route::has('prodi.index'))
                    <a href="{{ route('prodi.index') }}"
                       class="block px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('prodi.*') ? 'bg-white text-primary font-medium' : '' }}">
                        Program Studi
                    </a>
                @endif
                @if (Route::has('kelas.index'))
                    <a href="{{ route('kelas.index') }}"
                       class="block px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('kelas.*') ? 'bg-white text-primary font-medium' : '' }}">
                        Kelas
                    </a>
                @endif
                @if (Route::has('matakuliah.index'))
                    <a href="{{ route('matakuliah.index') }}"
                       class="block px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('matakuliah.*') ? 'bg-white text-primary font-medium' : '' }}">
                        Mata Kuliah
                    </a>
                @endif
                @if (Route::has('dosen.index'))
                    <a href="{{ route('dosen.index') }}"
                       class="block px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('dosen.*') ? 'bg-white text-primary font-medium' : '' }}">
                        Dosen
                    </a>
                @endif
                @if (Route::has('ruang.index'))
                    <a href="{{ route('ruang.index') }}"
                       class="block px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('ruang.*') ? 'bg-white text-primary font-medium' : '' }}">
                        Ruang Praktikum
                    </a>
                @endif
                @if (Route::has('tahunakademik.index'))
                    <a href="{{ route('tahunakademik.index') }}"
                       class="block px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('tahunakademik.*') ? 'bg-white text-primary font-medium' : '' }}">
                        Tahun Akademik
                    </a>
                @endif

                @if (Route::has('jadwal.manage') || Route::has('pengajuan.index'))
                    <p class="px-3 pt-4 pb-1 text-white/40 uppercase text-xs tracking-wide">Jadwal</p>
                @endif
                @if (Route::has('jadwal.manage'))
                    <a href="{{ route('jadwal.manage') }}"
                       class="block px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('jadwal.manage') ? 'bg-white text-primary font-medium' : '' }}">
                        Kelola Jadwal
                    </a>
                @endif
                @if (Route::has('pengajuan.index'))
                    <a href="{{ route('pengajuan.index') }}"
                       class="block px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('pengajuan.index') ? 'bg-white text-primary font-medium' : '' }}">
                        Pengajuan Perubahan
                    </a>
                @endif
            @endrole

            @role('super-admin')
                <p class="px-3 pt-4 pb-1 text-white/40 uppercase text-xs tracking-wide">Super Admin</p>
                @if (Route::has('users.index'))
                    <a href="{{ route('users.index') }}"
                       class="block px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('users.*') ? 'bg-white text-primary font-medium' : '' }}">
                        Pengguna & Operator
                    </a>
                @endif
                @if (Route::has('sampah.index'))
                    <a href="{{ route('sampah.index') }}"
                       class="block px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('sampah.*') ? 'bg-white text-primary font-medium' : '' }}">
                        Sampah
                    </a>
                @endif
                @if (Route::has('activitylog.index'))
                    <a href="{{ route('activitylog.index') }}"
                       class="block px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('activitylog.*') ? 'bg-white text-primary font-medium' : '' }}">
                        Riwayat Aktivitas
                    </a>
                @endif
            @endrole

            @role('dosen')
                <p class="px-3 pt-4 pb-1 text-white/40 uppercase text-xs tracking-wide">Menu Dosen</p>
                @if (Route::has('jadwal.saya'))
                    <a href="{{ route('jadwal.saya') }}"
                       class="block px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('jadwal.saya') ? 'bg-white text-primary font-medium' : '' }}">
                        Jadwal Mengajar Saya
                    </a>
                @endif
                @if (Route::has('pengajuan.saya'))
                    <a href="{{ route('pengajuan.saya') }}"
                       class="block px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('pengajuan.saya') ? 'bg-white text-primary font-medium' : '' }}">
                        Pengajuan Perubahan
                    </a>
                @endif
                @if (Route::has('notifikasi.saya'))
                    <a href="{{ route('notifikasi.saya') }}"
                    class="flex items-center justify-between px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('notifikasi.saya') ? 'bg-white text-primary font-medium' : '' }}">
                        <span>Notifikasi</span>
                        @php $jumlahBelumDibaca = auth()->user()->unreadNotifications()->count(); @endphp
                        @if ($jumlahBelumDibaca > 0)
                            <span class="bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5 min-w-[18px] text-center">
                                {{ $jumlahBelumDibaca }}
                            </span>
                        @endif
                    </a>
                @endif
            @endrole

            <p class="px-3 pt-4 pb-1 text-white/40 uppercase text-xs tracking-wide">Bantuan</p>
            <a href="{{ route('admin.panduan') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('admin.panduan') ? 'bg-white text-primary font-medium' : '' }}">
                <svg class="w-4 h-4 shrink-0 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <span>Panduan & SOP</span>
            </a>
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
    <div class="flex-1 min-w-0 flex flex-col min-h-screen bg-gray-50/50 dark:bg-zinc-950">
        <header class="h-16 bg-white dark:bg-zinc-900 border-b border-gray-100 dark:border-zinc-800 flex items-center justify-between px-5 md:px-8">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = true" class="md:hidden text-gray-600 dark:text-zinc-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <span class="font-bold text-gray-900 dark:text-zinc-100 text-base md:text-lg">{{ $title ?? 'Dashboard' }}</span>
            </div>

            <div class="flex items-center gap-3">
                {{-- Dark Mode Toggle --}}
                <button type="button"
                        @click="darkMode = !darkMode; if (darkMode) { document.documentElement.classList.add('dark'); localStorage.setItem('theme', 'dark'); } else { document.documentElement.classList.remove('dark'); localStorage.setItem('theme', 'light'); }"
                        class="p-2 rounded-xl text-gray-500 hover:text-gray-700 dark:text-zinc-400 dark:hover:text-zinc-200 bg-gray-100 dark:bg-zinc-800 transition"
                        title="Alihkan Mode Gelap / Terang">
                    <svg x-show="!darkMode" class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <svg x-show="darkMode" x-cloak class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                </button>

                @if(auth()->check())
                    <div class="hidden sm:flex items-center gap-2 text-xs text-gray-500 dark:text-zinc-400 pl-2 border-l border-gray-200 dark:border-zinc-800">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span class="font-medium text-gray-700 dark:text-zinc-200">{{ auth()->user()->name }}</span>
                        <span class="px-1.5 py-0.5 rounded bg-gray-100 dark:bg-zinc-800 text-[10px] uppercase font-semibold text-gray-500 dark:text-zinc-400">{{ auth()->user()->roles->first()?->name ?? 'User' }}</span>
                    </div>
                @endif
            </div>
        </header>

        <main class="p-4 md:p-6 flex-1">
            {{ $slot }}
        </main>
    </div>

    @livewireScripts
</body>
</html>
