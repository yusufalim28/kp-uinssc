<x-layouts.admin :title="'Konfirmasi Kata Sandi — SI Jadwal Praktikum'">
    <div class="flex items-center justify-center min-h-[calc(100vh-180px)] py-8 px-4">
        <div class="w-full max-w-md bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 rounded-3xl shadow-xl p-6 sm:p-8 relative overflow-hidden">
            <!-- Decorative Accent Top -->
            <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-[#0B2E23] to-emerald-600"></div>

            <!-- Icon & Header -->
            <div class="text-center mb-6">
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800/50 flex items-center justify-center mx-auto mb-3 shadow-2xs">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h1 class="text-xl font-black text-gray-900 dark:text-zinc-100 tracking-tight">Konfirmasi Kata Sandi</h1>
                <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1 leading-relaxed">
                    Halaman keamanan memerlukan konfirmasi kata sandi Anda saat ini demi menjaga integritas akun.
                </p>
            </div>

            <!-- Session Status / Alert -->
            <x-auth-session-status class="text-center mb-4" :status="session('status')" />

            @if (Laravel\Fortify\Features::canManagePasskeys())
                <div class="mb-4">
                    <x-passkey-verify
                        options-route="passkey.confirm-options"
                        submit-route="passkey.confirm"
                        :label="__('Confirm with passkey')"
                        :loading-label="__('Confirming...')"
                        :separator="__('Or confirm with password')"
                    />
                </div>
            @endif

            <form method="POST" action="{{ route('password.confirm.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="password" class="block text-xs font-bold text-gray-700 dark:text-zinc-300 mb-1">
                        Kata Sandi Akun Anda <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            autofocus
                            autocomplete="current-password"
                            placeholder="Masukkan kata sandi akun login Anda"
                            class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs px-3.5 py-2.5 text-gray-900 dark:text-zinc-100 transition"
                        />
                    </div>
                    @error('password')
                        <p class="text-rose-600 text-xs mt-1.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <div class="pt-2 flex flex-col sm:flex-row items-center gap-2">
                    <a href="{{ route('profile.edit') }}"
                       class="w-full sm:w-auto px-4 py-2.5 rounded-xl border border-gray-200 dark:border-zinc-700 text-gray-600 dark:text-zinc-300 text-xs font-semibold text-center hover:bg-gray-50 dark:hover:bg-zinc-800 transition">
                        Batal
                    </a>
                    <button type="submit" data-test="confirm-password-button"
                            class="w-full sm:flex-1 inline-flex items-center justify-center gap-2 bg-[#0B2E23] hover:bg-[#124334] dark:bg-emerald-600 dark:hover:bg-emerald-700 text-white text-xs font-bold px-5 py-2.5 rounded-xl transition shadow-xs">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span>Konfirmasi & Lanjutkan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
