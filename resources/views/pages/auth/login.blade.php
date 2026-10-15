<x-layouts.guest :title="'Masuk Portal — SI Jadwal Praktikum'">
    <div class="flex items-center justify-center min-h-[calc(100vh-140px)] py-8 px-4">
        <div class="w-full max-w-lg bg-white border border-gray-100 rounded-3xl shadow-xl p-7 md:p-9 relative overflow-hidden">
            <!-- Decorative Accent Bar -->
            <div class="absolute top-0 left-0 w-full h-2.5 bg-gradient-to-r from-primary to-primary-dark"></div>

            <!-- Header Portal -->
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-primary/10 text-primary mb-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Portal Dosen & Pengelola Lab</h1>
                <p class="text-gray-500 text-xs md:text-sm mt-1.5 leading-relaxed">
                    Sistem Informasi Jadwal Praktikum — UIN Siber Syekh Nurjati Cirebon
                </p>
            </div>

            <!-- Pengumuman Mahasiswa -->
            <div class="mb-6 p-4 rounded-2xl bg-amber-50/80 border border-amber-200/80 flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="text-xs text-amber-900 leading-relaxed">
                    <strong class="font-semibold block text-amber-950">Akses Terbuka untuk Mahasiswa:</strong>
                    Mahasiswa tidak memerlukan akun untuk login. Seluruh agenda jadwal dan kalender praktikum dapat dilihat langsung secara terbuka melalui
                    <a href="{{ route('jadwal.index') }}" class="font-semibold text-primary underline hover:text-primary-dark">Daftar Jadwal</a> atau
                    <a href="{{ route('home') }}" class="font-semibold text-primary underline hover:text-primary-dark">Beranda</a>.
                </div>
            </div>

            <!-- Session Status / Flash Alert -->
            <x-auth-session-status class="text-center mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-4">
                @csrf

                <!-- Input Username / NIP / Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                        NIP / NIDN / Email Akun
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
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
                            placeholder="Contoh: 19850101... atau admin@uinssc.ac.id"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 bg-gray-50/50 focus:bg-white transition"
                        />
                    </div>
                    @error('email')
                        <p class="text-red-600 text-xs mt-1.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Input Password -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Kata Sandi
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs text-primary hover:text-primary-dark font-medium transition" wire:navigate>
                                Lupa kata sandi?
                            </a>
                        @endif
                    </div>
                    <div class="relative" x-data="{ show: false }">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
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
                            placeholder="Masukkan kata sandi portal"
                            class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 bg-gray-50/50 focus:bg-white transition"
                        />
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600">
                            <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-xs text-gray-600 select-none">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-primary focus:ring-primary/20">
                        <span>Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="mt-3">
                    <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-2.5 px-4 rounded-xl transition shadow-md hover:shadow-lg flex items-center justify-center gap-2 text-sm">
                        <span>Masuk ke Sistem</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>
            </form>

            <!-- Footer Bantuan Kampus -->
            <div class="mt-8 pt-5 border-t border-gray-100 text-center text-xs text-gray-400 leading-relaxed">
                <span class="block text-gray-500 font-medium">Terintegrasi dengan PUSTIKOM UINSSC</span>
                Kendala login akun atau permohonan hak akses laboratorium, hubungi Helpdesk PUSTIKOM Gedung Rektorat.
            </div>
        </div>
    </div>
</x-layouts.guest>
