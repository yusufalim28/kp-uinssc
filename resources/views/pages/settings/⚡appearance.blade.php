<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Title('Pengaturan Tampilan'), Layout('components.layouts.admin')] class extends Component {
    //
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-pages::settings.layout heading="Tema & Tampilan" subheading="Atur mode tampilan antarmuka sesuai preferensi Anda.">
        <div x-data="{
            current: localStorage.getItem('theme') || 'system',
            setTheme(mode) {
                this.current = mode;
                if (mode === 'dark') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                } else if (mode === 'light') {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    localStorage.removeItem('theme');
                    if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                }
            }
        }" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            {{-- Light Mode --}}
            <button type="button" @click="setTheme('light')"
                :class="current === 'light'
                    ? 'border-emerald-600 bg-emerald-50/50 dark:bg-emerald-950/30 ring-2 ring-emerald-500/20'
                    : 'border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700'"
                class="flex flex-col items-center gap-2.5 p-4 rounded-2xl border transition text-center">
                <div :class="current === 'light' ? 'bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300' : 'bg-gray-100 dark:bg-zinc-700 text-gray-500 dark:text-zinc-400'"
                    class="w-10 h-10 rounded-xl flex items-center justify-center transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <div>
                    <span :class="current === 'light' ? 'text-emerald-800 dark:text-emerald-200' : 'text-gray-700 dark:text-zinc-300'"
                        class="text-xs font-bold block">Terang</span>
                    <span class="text-[10px] text-gray-400 dark:text-zinc-500">Mode siang hari</span>
                </div>
            </button>

            {{-- Dark Mode --}}
            <button type="button" @click="setTheme('dark')"
                :class="current === 'dark'
                    ? 'border-emerald-600 bg-emerald-50/50 dark:bg-emerald-950/30 ring-2 ring-emerald-500/20'
                    : 'border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700'"
                class="flex flex-col items-center gap-2.5 p-4 rounded-2xl border transition text-center">
                <div :class="current === 'dark' ? 'bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300' : 'bg-gray-100 dark:bg-zinc-700 text-gray-500 dark:text-zinc-400'"
                    class="w-10 h-10 rounded-xl flex items-center justify-center transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                </div>
                <div>
                    <span :class="current === 'dark' ? 'text-emerald-800 dark:text-emerald-200' : 'text-gray-700 dark:text-zinc-300'"
                        class="text-xs font-bold block">Gelap</span>
                    <span class="text-[10px] text-gray-400 dark:text-zinc-500">Mode malam hari</span>
                </div>
            </button>

            {{-- System Mode --}}
            <button type="button" @click="setTheme('system')"
                :class="current === 'system'
                    ? 'border-emerald-600 bg-emerald-50/50 dark:bg-emerald-950/30 ring-2 ring-emerald-500/20'
                    : 'border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700'"
                class="flex flex-col items-center gap-2.5 p-4 rounded-2xl border transition text-center">
                <div :class="current === 'system' ? 'bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300' : 'bg-gray-100 dark:bg-zinc-700 text-gray-500 dark:text-zinc-400'"
                    class="w-10 h-10 rounded-xl flex items-center justify-center transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <span :class="current === 'system' ? 'text-emerald-800 dark:text-emerald-200' : 'text-gray-700 dark:text-zinc-300'"
                        class="text-xs font-bold block">Sistem</span>
                    <span class="text-[10px] text-gray-400 dark:text-zinc-500">Ikuti perangkat</span>
                </div>
            </button>
        </div>
    </x-pages::settings.layout>
</section>
