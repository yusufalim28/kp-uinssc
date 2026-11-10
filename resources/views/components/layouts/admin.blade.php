<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dashboard' }} — SI Jadwal Praktikum</title>

    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="min-h-screen flex bg-[#F6F8F7] text-dark dark:bg-zinc-950 dark:text-zinc-100 transition-colors"
    x-data="{ sidebarOpen: false, darkMode: document.documentElement.classList.contains('dark') }"
    x-init="$watch('sidebarOpen', val => { if(window.innerWidth < 768) { document.body.classList.toggle('overflow-hidden', val); } })">

    {{-- Sidebar (Tema Forest Emerald Premium - Sesuai Gambar 1) --}}
    <aside data-keep-light :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
        class="fixed md:sticky top-0 inset-y-0 left-0 z-40 w-64 shrink-0 h-screen max-h-screen flex flex-col bg-[#0F352A] text-white transition-transform duration-200 shadow-2xl md:shadow-none border-r border-[#164336]">
        
        {{-- Brand / Header Sidebar --}}
        <div class="h-16 shrink-0 bg-[#0F352A] border-b border-white/8 flex items-center justify-between px-5">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 font-bold text-white tracking-tight">
                <div class="w-10 h-10 rounded-lg bg-white/2 border border-emerald-400/30 flex items-center justify-center     text-emerald-300 shadow-2xs">
                    <img class="h-7" src="{{ asset('img/uinsscv2.png') }}" alt="logo uinssc">
                </div>
                <div class="flex flex-col leading-tight">
                    <span class="text-white font-black text-sm tracking-wide">UINSSC</span>
                    <span class="text-emerald-200/60 font-semibold text-[10px]">Sistem Jadwal Lab</span>
                </div>
            </a>
            <div class="flex items-center gap-1">
                <a href="{{ route('home') }}" title="Buka Portal Publik" class="hidden md:flex p-1.5 rounded-lg text-white/50 hover:text-white hover:bg-white/10 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
                <button type="button" @click="sidebarOpen = false" class="md:hidden p-1.5 rounded-lg text-white/70 hover:text-white hover:bg-white/10 transition" title="Tutup Menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        {{-- Mini Search Box Sidebar (Mirip Gambar 1) --}}
        <div class="px-4 pt-3.5 pb-2">
            <a href="{{ route('jadwal.manage') }}" class="flex items-center gap-2 bg-white/6 hover:bg-white/10 transition rounded-xl px-3 py-2 text-xs text-white/60 border border-white/5">
                <svg class="w-3.5 h-3.5 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <span class="flex-1 font-medium truncate">Cari jadwal atau sesi...</span>
                <kbd class="text-[9px] bg-white/10 px-1.5 py-0.5 rounded text-white/50 font-mono">⌘ K</kbd>
            </a>
        </div>

        {{-- Navigation Menu List --}}
        <nav class="p-3.5 space-y-1 text-sm flex-1 overflow-y-auto overscroll-contain [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">
            
            <p class="px-3 pt-1 pb-1 text-white/40 font-bold uppercase text-[10px] tracking-wider">Main Menu</p>
            <a href="{{ route('dashboard') }}"
                class="group relative flex items-center gap-3 px-3 py-2 rounded-xl transition text-xs font-semibold {{ request()->routeIs('dashboard') ? 'bg-white/12 text-white shadow-xs' : 'text-white/70 hover:text-white hover:bg-white/6' }}">
                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('dashboard') ? 'text-emerald-300' : 'text-white/60 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span class="truncate">Dashboard</span>
                @if(request()->routeIs('dashboard'))
                    <span class="absolute right-0 top-1.5 bottom-1.5 w-1 rounded-l-full bg-emerald-400"></span>
                @endif
            </a>

            @role('super-admin|operator')
                <p class="px-3 pt-3.5 pb-1 text-white/40 font-bold uppercase text-[10px] tracking-wider">Master Data</p>
                
                <a href="{{ route('fakultas.index') }}"
                    class="group relative flex items-center gap-3 px-3 py-2 rounded-xl transition text-xs font-semibold {{ request()->routeIs('fakultas.*') ? 'bg-white/12 text-white shadow-xs' : 'text-white/70 hover:text-white hover:bg-white/6' }}">
                    <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('fakultas.*') ? 'text-emerald-300' : 'text-white/60 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span class="truncate">Fakultas</span>
                    @if(request()->routeIs('fakultas.*'))
                        <span class="absolute right-0 top-1.5 bottom-1.5 w-1 rounded-l-full bg-emerald-400"></span>
                    @endif
                </a>

                @if (Route::has('prodi.index'))
                    <a href="{{ route('prodi.index') }}"
                        class="group relative flex items-center gap-3 px-3 py-2 rounded-xl transition text-xs font-semibold {{ request()->routeIs('prodi.*') ? 'bg-white/12 text-white shadow-xs' : 'text-white/70 hover:text-white hover:bg-white/6' }}">
                        <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('prodi.*') ? 'text-emerald-300' : 'text-white/60 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                        <span class="truncate">Program Studi</span>
                        @if(request()->routeIs('prodi.*'))
                            <span class="absolute right-0 top-1.5 bottom-1.5 w-1 rounded-l-full bg-emerald-400"></span>
                        @endif
                    </a>
                @endif

                @if (Route::has('kelas.index'))
                    <a href="{{ route('kelas.index') }}"
                        class="group relative flex items-center gap-3 px-3 py-2 rounded-xl transition text-xs font-semibold {{ request()->routeIs('kelas.*') ? 'bg-white/12 text-white shadow-xs' : 'text-white/70 hover:text-white hover:bg-white/6' }}">
                        <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('kelas.*') ? 'text-emerald-300' : 'text-white/60 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span class="truncate">Kelas</span>
                        @if(request()->routeIs('kelas.*'))
                            <span class="absolute right-0 top-1.5 bottom-1.5 w-1 rounded-l-full bg-emerald-400"></span>
                        @endif
                    </a>
                @endif

                @if (Route::has('matakuliah.index'))
                    <a href="{{ route('matakuliah.index') }}"
                        class="group relative flex items-center gap-3 px-3 py-2 rounded-xl transition text-xs font-semibold {{ request()->routeIs('matakuliah.*') ? 'bg-white/12 text-white shadow-xs' : 'text-white/70 hover:text-white hover:bg-white/6' }}">
                        <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('matakuliah.*') ? 'text-emerald-300' : 'text-white/60 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        <span class="truncate">Mata Kuliah</span>
                        @if(request()->routeIs('matakuliah.*'))
                            <span class="absolute right-0 top-1.5 bottom-1.5 w-1 rounded-l-full bg-emerald-400"></span>
                        @endif
                    </a>
                @endif

                @if (Route::has('dosen.index'))
                    <a href="{{ route('dosen.index') }}"
                        class="group relative flex items-center gap-3 px-3 py-2 rounded-xl transition text-xs font-semibold {{ request()->routeIs('dosen.*') ? 'bg-white/12 text-white shadow-xs' : 'text-white/70 hover:text-white hover:bg-white/6' }}">
                        <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('dosen.*') ? 'text-emerald-300' : 'text-white/60 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span class="truncate">Dosen Pengampu</span>
                        @if(request()->routeIs('dosen.*'))
                            <span class="absolute right-0 top-1.5 bottom-1.5 w-1 rounded-l-full bg-emerald-400"></span>
                        @endif
                    </a>
                @endif

                @if (Route::has('ruang.index'))
                    <a href="{{ route('ruang.index') }}"
                        class="group relative flex items-center gap-3 px-3 py-2 rounded-xl transition text-xs font-semibold {{ request()->routeIs('ruang.*') ? 'bg-white/12 text-white shadow-xs' : 'text-white/70 hover:text-white hover:bg-white/6' }}">
                        <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('ruang.*') ? 'text-emerald-300' : 'text-white/60 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span class="truncate">Ruang Praktikum</span>
                        @if(request()->routeIs('ruang.*'))
                            <span class="absolute right-0 top-1.5 bottom-1.5 w-1 rounded-l-full bg-emerald-400"></span>
                        @endif
                    </a>
                @endif

                @if (Route::has('tahunakademik.index'))
                    <a href="{{ route('tahunakademik.index') }}"
                        class="group relative flex items-center gap-3 px-3 py-2 rounded-xl transition text-xs font-semibold {{ request()->routeIs('tahunakademik.*') ? 'bg-white/12 text-white shadow-xs' : 'text-white/70 hover:text-white hover:bg-white/6' }}">
                        <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('tahunakademik.*') ? 'text-emerald-300' : 'text-white/60 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span class="truncate">Tahun Akademik</span>
                        @if(request()->routeIs('tahunakademik.*'))
                            <span class="absolute right-0 top-1.5 bottom-1.5 w-1 rounded-l-full bg-emerald-400"></span>
                        @endif
                    </a>
                @endif

                @if (Route::has('jadwal.manage') || Route::has('pengajuan.index'))
                    <p class="px-3 pt-3.5 pb-1 text-white/40 font-bold uppercase text-[10px] tracking-wider">Jadwal & Operasional</p>
                @endif

                @if (Route::has('jadwal.manage'))
                    <a href="{{ route('jadwal.manage') }}"
                        class="group relative flex items-center gap-3 px-3 py-2 rounded-xl transition text-xs font-semibold {{ request()->routeIs('jadwal.manage') ? 'bg-white/12 text-white shadow-xs' : 'text-white/70 hover:text-white hover:bg-white/6' }}">
                        <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('jadwal.manage') ? 'text-emerald-300' : 'text-white/60 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span class="truncate">Kelola Jadwal</span>
                        @if(request()->routeIs('jadwal.manage'))
                            <span class="absolute right-0 top-1.5 bottom-1.5 w-1 rounded-l-full bg-emerald-400"></span>
                        @endif
                    </a>
                @endif

                @if (Route::has('pengajuan.index'))
                    <a href="{{ route('pengajuan.index') }}"
                        class="group relative flex items-center justify-between px-3 py-2 rounded-xl transition text-xs font-semibold {{ request()->routeIs('pengajuan.index') ? 'bg-white/12 text-white shadow-xs' : 'text-white/70 hover:text-white hover:bg-white/6' }}">
                        <div class="flex items-center gap-3 min-w-0">
                            <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('pengajuan.index') ? 'text-emerald-300' : 'text-white/60 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            <span class="truncate">Pengajuan Perubahan</span>
                        </div>
                        @php $antreCount = \App\Models\PengajuanPerubahan::where('status', 'menunggu')->count(); @endphp
                        @if ($antreCount > 0)
                            <span class="bg-emerald-400 text-emerald-950 font-black text-[10px] rounded-md px-1.5 py-0.2 shrink-0">
                                {{ $antreCount }}
                            </span>
                        @endif
                        @if(request()->routeIs('pengajuan.index'))
                            <span class="absolute right-0 top-1.5 bottom-1.5 w-1 rounded-l-full bg-emerald-400"></span>
                        @endif
                    </a>
                @endif
            @endrole

            @role('super-admin')
                <p class="px-3 pt-3.5 pb-1 text-white/40 font-bold uppercase text-[10px] tracking-wider">Super Admin</p>
                
                @if (Route::has('users.index'))
                    <a href="{{ route('users.index') }}"
                        class="group relative flex items-center gap-3 px-3 py-2 rounded-xl transition text-xs font-semibold {{ request()->routeIs('users.*') ? 'bg-white/12 text-white shadow-xs' : 'text-white/70 hover:text-white hover:bg-white/6' }}">
                        <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('users.*') ? 'text-emerald-300' : 'text-white/60 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <span class="truncate">Pengguna & Operator</span>
                        @if(request()->routeIs('users.*'))
                            <span class="absolute right-0 top-1.5 bottom-1.5 w-1 rounded-l-full bg-emerald-400"></span>
                        @endif
                    </a>
                @endif

                @if (Route::has('activitylog.index'))
                    <a href="{{ route('activitylog.index') }}"
                        class="group relative flex items-center gap-3 px-3 py-2 rounded-xl transition text-xs font-semibold {{ request()->routeIs('activitylog.*') ? 'bg-white/12 text-white shadow-xs' : 'text-white/70 hover:text-white hover:bg-white/6' }}">
                        <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('activitylog.*') ? 'text-emerald-300' : 'text-white/60 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="truncate">Riwayat Aktivitas</span>
                        @if(request()->routeIs('activitylog.*'))
                            <span class="absolute right-0 top-1.5 bottom-1.5 w-1 rounded-l-full bg-emerald-400"></span>
                        @endif
                    </a>
                @endif

                @if (Route::has('sampah.index'))
                    <a href="{{ route('sampah.index') }}"
                        class="group relative flex items-center gap-3 px-3 py-2 rounded-xl transition text-xs font-semibold {{ request()->routeIs('sampah.*') ? 'bg-white/12 text-white shadow-xs' : 'text-white/70 hover:text-white hover:bg-white/6' }}">
                        <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('sampah.*') ? 'text-emerald-300' : 'text-white/60 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span class="truncate">Tempat Sampah</span>
                        @if(request()->routeIs('sampah.*'))
                            <span class="absolute right-0 top-1.5 bottom-1.5 w-1 rounded-l-full bg-emerald-400"></span>
                        @endif
                    </a>
                @endif

                @if (Route::has('panduan.manage'))
                    <a href="{{ route('panduan.manage') }}"
                        class="group relative flex items-center gap-3 px-3 py-2 rounded-xl transition text-xs font-semibold {{ request()->routeIs('panduan.manage') ? 'bg-white/12 text-white shadow-xs' : 'text-white/70 hover:text-white hover:bg-white/6' }}">
                        <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('panduan.manage') ? 'text-emerald-300' : 'text-white/60 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        <span class="truncate">Kelola Panduan & SOP</span>
                        @if(request()->routeIs('panduan.manage'))
                            <span class="absolute right-0 top-1.5 bottom-1.5 w-1 rounded-l-full bg-emerald-400"></span>
                        @endif
                    </a>
                @endif
            @endrole

            @role('dosen')
                <p class="px-3 pt-3.5 pb-1 text-white/40 font-bold uppercase text-[10px] tracking-wider">Menu Dosen</p>
                
                @if (Route::has('jadwal.saya'))
                    <a href="{{ route('jadwal.saya') }}"
                        class="group relative flex items-center gap-3 px-3 py-2 rounded-xl transition text-xs font-semibold {{ request()->routeIs('jadwal.saya') ? 'bg-white/12 text-white shadow-xs' : 'text-white/70 hover:text-white hover:bg-white/6' }}">
                        <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('jadwal.saya') ? 'text-emerald-300' : 'text-white/60 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span class="truncate">Jadwal Mengajar Saya</span>
                        @if(request()->routeIs('jadwal.saya'))
                            <span class="absolute right-0 top-1.5 bottom-1.5 w-1 rounded-l-full bg-emerald-400"></span>
                        @endif
                    </a>
                @endif

                @if (Route::has('pengajuan.saya'))
                    <a href="{{ route('pengajuan.saya') }}"
                        class="group relative flex items-center gap-3 px-3 py-2 rounded-xl transition text-xs font-semibold {{ request()->routeIs('pengajuan.saya') ? 'bg-white/12 text-white shadow-xs' : 'text-white/70 hover:text-white hover:bg-white/6' }}">
                        <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('pengajuan.saya') ? 'text-emerald-300' : 'text-white/60 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span class="truncate">Pengajuan Perubahan</span>
                        @if(request()->routeIs('pengajuan.saya'))
                            <span class="absolute right-0 top-1.5 bottom-1.5 w-1 rounded-l-full bg-emerald-400"></span>
                        @endif
                    </a>
                @endif

                @if (Route::has('notifikasi.saya'))
                    <a href="{{ route('notifikasi.saya') }}"
                        class="group relative flex items-center justify-between px-3 py-2 rounded-xl transition text-xs font-semibold {{ request()->routeIs('notifikasi.saya') ? 'bg-white/12 text-white shadow-xs' : 'text-white/70 hover:text-white hover:bg-white/6' }}">
                        <div class="flex items-center gap-3 min-w-0">
                            <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('notifikasi.saya') ? 'text-emerald-300' : 'text-white/60 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            <span class="truncate">Notifikasi</span>
                        </div>
                        @php $jumlahBelumDibaca = auth()->user()->unreadNotifications()->count(); @endphp
                        @if ($jumlahBelumDibaca > 0)
                            <span class="bg-emerald-400 text-emerald-950 font-black text-[10px] rounded-md px-1.5 py-0.2 shrink-0">
                                {{ $jumlahBelumDibaca }}
                            </span>
                        @endif
                        @if(request()->routeIs('notifikasi.saya'))
                            <span class="absolute right-0 top-1.5 bottom-1.5 w-1 rounded-l-full bg-emerald-400"></span>
                        @endif
                    </a>
                @endif
            @endrole

            <p class="px-3 pt-3.5 pb-1 text-white/40 font-bold uppercase text-[10px] tracking-wider">Bantuan</p>
            <a href="{{ route('admin.panduan') }}"
                class="group relative flex items-center gap-3 px-3 py-2 rounded-xl transition text-xs font-semibold {{ request()->routeIs('admin.panduan') ? 'bg-white/12 text-white shadow-xs' : 'text-white/70 hover:text-white hover:bg-white/6' }}">
                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.panduan') ? 'text-emerald-300' : 'text-white/60 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <span class="truncate">Panduan & SOP</span>
                @if(request()->routeIs('admin.panduan'))
                    <span class="absolute right-0 top-1.5 bottom-1.5 w-1 rounded-l-full bg-emerald-400"></span>
                @endif
            </a>
        </nav>

        {{-- Profile User Card di Bagian Bawah Sidebar (Persis Gambar 1) --}}
        <div class="p-3 shrink-0 border-t border-white/8 bg-[#0B2A21] mt-auto">
            <div class="flex items-center justify-between gap-2 p-2 rounded-xl bg-white/5 hover:bg-white/8 transition border border-white/5">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 flex items-center justify-center font-bold text-xs shrink-0">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                    </div>
                    <div class="min-w-0 leading-tight">
                        <span class="block text-xs font-semibold text-white truncate">{{ auth()->user()->name }}</span>
                        <span class="block text-[10px] text-emerald-200/60 truncate uppercase tracking-wider font-medium">{{ auth()->user()->roles->first()?->name ?? 'Pengelola' }}</span>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                    @csrf
                    <button type="submit" title="Keluar / Logout" class="p-1.5 rounded-lg text-white/50 hover:text-white hover:bg-white/10 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Overlay mobile --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak class="fixed inset-0 bg-black/60 z-30 md:hidden backdrop-blur-xs">
    </div>

    {{-- Konten utama --}}
    <div class="flex-1 min-w-0 flex flex-col min-h-screen bg-[#F6F8F7] dark:bg-zinc-950">
        <header
            class="h-16 bg-white dark:bg-zinc-900 border-b border-gray-200/70 dark:border-zinc-800 flex items-center justify-between px-5 md:px-8">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = true" class="md:hidden text-gray-600 dark:text-zinc-300 p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-zinc-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div>
                    <h1 class="font-bold text-gray-900 dark:text-zinc-100 text-base md:text-lg tracking-tight">{{ $title ?? 'Dashboard' }}</h1>
                </div>
            </div>

            <div class="flex items-center gap-3">
                {{-- Quick Link ke Jadwal Ruangan / Public --}}
                <a href="{{ route('jadwal.ruang') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-xs font-semibold text-gray-700 dark:text-zinc-300 hover:bg-gray-50 dark:hover:bg-zinc-700 transition shadow-2xs">
                    <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <span>Visual Jadwal Ruang</span>
                </a>

                {{-- Dark Mode Toggle --}}
                <button type="button"
                    @click="darkMode = !darkMode; if (darkMode) { document.documentElement.classList.add('dark'); localStorage.setItem('theme', 'dark'); } else { document.documentElement.classList.remove('dark'); localStorage.setItem('theme', 'light'); }"
                    class="p-2 rounded-xl text-gray-500 hover:text-gray-700 dark:text-zinc-400 dark:hover:text-zinc-200 bg-gray-100 dark:bg-zinc-800 transition"
                    title="Alihkan Mode Gelap / Terang">
                    <svg x-show="!darkMode" class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg x-show="darkMode" x-cloak class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>

                @if (auth()->check())
                    <div class="hidden sm:flex items-center gap-2.5 text-xs text-gray-500 dark:text-zinc-400 pl-3 border-l border-gray-200 dark:border-zinc-800">
                        <div class="w-7 h-7 rounded-lg bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 flex items-center justify-center font-bold text-[11px]">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="leading-tight">
                            <span class="block font-semibold text-gray-800 dark:text-zinc-200">{{ auth()->user()->name }}</span>
                            <span class="block text-[10px] text-gray-400 uppercase font-medium">{{ auth()->user()->roles->first()?->name ?? 'User' }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </header>

        <main class="p-4 md:p-6 lg:p-7 flex-1">
            {{ $slot }}
        </main>
    </div>

    @livewireScripts
</body>

</html>
