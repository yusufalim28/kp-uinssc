<div class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-start">
    {{-- Menu Navigasi Pengaturan --}}
    <div class="lg:col-span-1 space-y-2">
        <a href="{{ route('profile.edit') }}" wire:navigate
           @class([
               'flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold transition shadow-2xs',
               'bg-[#0B2E23] dark:bg-emerald-600 text-white shadow-xs' => request()->routeIs('profile.edit'),
               'bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 text-gray-700 dark:text-zinc-300 hover:bg-gray-50 dark:hover:bg-zinc-800' => !request()->routeIs('profile.edit'),
           ])>
            <div @class([
                'w-8 h-8 rounded-xl flex items-center justify-center shrink-0',
                'bg-white/15 text-white' => request()->routeIs('profile.edit'),
                'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400' => !request()->routeIs('profile.edit'),
            ])>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <div class="flex flex-col min-w-0">
                <span class="truncate">Profil & Email Login</span>
                <span class="text-[10px] font-normal opacity-70 truncate">Ubah nama dan Gmail</span>
            </div>
        </a>

        <a href="{{ route('security.edit') }}" wire:navigate
           @class([
               'flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold transition shadow-2xs',
               'bg-[#0B2E23] dark:bg-emerald-600 text-white shadow-xs' => request()->routeIs('security.edit'),
               'bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 text-gray-700 dark:text-zinc-300 hover:bg-gray-50 dark:hover:bg-zinc-800' => !request()->routeIs('security.edit'),
           ])>
            <div @class([
                'w-8 h-8 rounded-xl flex items-center justify-center shrink-0',
                'bg-white/15 text-white' => request()->routeIs('security.edit'),
                'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400' => !request()->routeIs('security.edit'),
            ])>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <div class="flex flex-col min-w-0">
                <span class="truncate">Kata Sandi & Keamanan</span>
                <span class="text-[10px] font-normal opacity-70 truncate">Ganti password akun</span>
            </div>
        </a>

        <a href="{{ route('appearance.edit') }}" wire:navigate
           @class([
               'flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold transition shadow-2xs',
               'bg-[#0B2E23] dark:bg-emerald-600 text-white shadow-xs' => request()->routeIs('appearance.edit'),
               'bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 text-gray-700 dark:text-zinc-300 hover:bg-gray-50 dark:hover:bg-zinc-800' => !request()->routeIs('appearance.edit'),
           ])>
            <div @class([
                'w-8 h-8 rounded-xl flex items-center justify-center shrink-0',
                'bg-white/15 text-white' => request()->routeIs('appearance.edit'),
                'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400' => !request()->routeIs('appearance.edit'),
            ])>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
            </div>
            <div class="flex flex-col min-w-0">
                <span class="truncate">Tema & Tampilan</span>
                <span class="text-[10px] font-normal opacity-70 truncate">Mode Gelap / Terang</span>
            </div>
        </a>
    </div>

    {{-- Konten Utama Form --}}
    <div class="lg:col-span-3 bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 rounded-3xl p-6 sm:p-7 shadow-2xs">
        @if(isset($heading) || isset($subheading))
            <div class="border-b border-gray-100 dark:border-zinc-800 pb-4 mb-6">
                @if(isset($heading))
                    <h2 class="text-base sm:text-lg font-bold text-gray-900 dark:text-zinc-100">{{ $heading }}</h2>
                @endif
                @if(isset($subheading))
                    <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1 leading-relaxed">{{ $subheading }}</p>
                @endif
            </div>
        @endif

        <div class="w-full">
            {{ $slot }}
        </div>
    </div>
</div>
