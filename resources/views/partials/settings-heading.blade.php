<div class="mb-6 space-y-2">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-zinc-100 tracking-tight">Pengaturan Akun</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-800 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/50">
                    {{ auth()->user()?->roles?->first()?->name ? strtoupper(auth()->user()->roles->first()->name) : 'PENGELOLA' }}
                </span>
            </div>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-zinc-400 mt-0.5">
                Kelola identitas profil, alamat Gmail login, dan kata sandi keamanan akun Anda.
            </p>
        </div>
        <div class="flex items-center gap-2 text-xs font-semibold text-gray-500 dark:text-zinc-400">
            @if(auth()->user()?->fakultas)
                <span class="px-2.5 py-1 rounded-xl bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300 border border-gray-200/80 dark:border-zinc-700">
                    🏛️ {{ auth()->user()->fakultas->nama_fakultas }}
                </span>
            @endif
        </div>
    </div>
    <div class="h-px bg-gray-200/80 dark:bg-zinc-800"></div>
</div>
