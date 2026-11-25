<div>
    @if ($show)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-black/70 backdrop-blur-sm p-4 sm:p-6 flex items-center justify-center animate-fade-in"
             x-data
             x-trap.noscroll="true">
            <div class="relative w-full max-w-2xl bg-white dark:bg-zinc-900 border-2 border-amber-400/80 dark:border-amber-500/70 rounded-3xl shadow-2xl overflow-hidden">
                {{-- Top Attention Banner --}}
                <div class="bg-gradient-to-r from-amber-500 via-amber-600 to-emerald-700 px-6 py-4 text-white flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-white/20 backdrop-blur-xs flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-widest bg-white/20 px-2 py-0.5 rounded-md">Pemberitahuan Serah Terima Sistem</span>
                            <h3 class="text-sm sm:text-base font-black tracking-tight mt-0.5">Wajib Diperiksa Saat Pertama Kali Login</h3>
                        </div>
                    </div>
                    <span class="text-xs bg-black/20 text-white font-mono px-2 py-1 rounded-lg">1x Peringatan</span>
                </div>

                {{-- Modal Body --}}
                <div class="p-6 sm:p-7 space-y-4 text-gray-800 dark:text-zinc-200">
                    <div class="flex items-start gap-3.5">
                        <div class="p-2.5 rounded-2xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 border border-amber-200/60 dark:border-amber-900/60 shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="space-y-1.5">
                            <h4 class="text-base font-black text-gray-900 dark:text-zinc-100">
                                {{ $title }}
                            </h4>
                            <p class="text-xs sm:text-sm text-gray-600 dark:text-zinc-300 leading-relaxed">
                                {{ $content }}
                            </p>
                        </div>
                    </div>

                    {{-- Checklist Poin Penting --}}
                    <div class="bg-amber-50/70 dark:bg-amber-950/20 border border-amber-200/70 dark:border-amber-900/50 rounded-2xl p-4 space-y-2.5 text-xs text-amber-950 dark:text-amber-200">
                        <div class="font-extrabold flex items-center gap-1.5 text-amber-900 dark:text-amber-300">
                            <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>Daftar Cek Yang Perlu Anda Lakukan:</span>
                        </div>
                        <ul class="space-y-2 pl-5 list-disc text-gray-700 dark:text-zinc-300">
                            <li>
                                <strong>Verifikasi NIDN Dosen:</strong> Karena sebagian jadwal belum menyertakan NIDN saat pengumpulan data, sistem mengisi NIDN sementara. Buka menu <em>Master Dosen Pengampu</em> dan masukkan NIDN resmi agar akun login dosen terhubung tepat dengan jadwal mengajar mereka.
                            </li>
                            <li>
                                <strong>Cek Kode & Nama Mata Kuliah:</strong> Pastikan kode dan nama mata kuliah praktikum sesuai kurikulum aktif fakultas Anda. Gunakan opsi <em>Bersama Fakultas</em> untuk mata kuliah gabungan.
                            </li>
                            <li>
                                <strong>Cek Hak Akses Ruangan / Lab:</strong> Pastikan kapasitas dan cakupan hak pakai ruangan lab Anda sudah sesuai kebutuhan praktikum.
                            </li>
                        </ul>
                    </div>

                    {{-- Quick Action Links --}}
                    <div class="flex items-center gap-2.5 flex-wrap pt-1 text-xs">
                        <span class="text-gray-400 dark:text-zinc-500 font-semibold">Tautan Cepat:</span>
                        <a href="{{ route('dosen.index') }}" target="_blank"
                           class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl font-bold bg-gray-100 hover:bg-gray-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-gray-800 dark:text-zinc-200 transition">
                            <span>Buka Master Dosen</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                        <a href="{{ route('matakuliah.index') }}" target="_blank"
                           class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl font-bold bg-gray-100 hover:bg-gray-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-gray-800 dark:text-zinc-200 transition">
                            <span>Buka Master Mata Kuliah</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                        <a href="{{ route('admin.panduan') }}" target="_blank"
                           class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl font-bold bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/40 dark:hover:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 transition">
                            <span>Baca Alur Kerja Lengkap</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Footer Action --}}
                <div class="px-6 py-4 bg-gray-50 dark:bg-zinc-800/80 border-t border-gray-100 dark:border-zinc-800 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <p class="text-[11px] text-gray-400 dark:text-zinc-500 text-center sm:text-left">
                        Setelah ditutup, peringatan ini tidak akan muncul lagi di sesi berikutnya.
                    </p>
                    <button wire:click="dismiss"
                            type="button"
                            class="w-full sm:w-auto px-6 py-2.5 rounded-xl font-bold text-xs bg-emerald-600 hover:bg-emerald-500 text-white shadow-md shadow-emerald-600/20 transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Saya Mengerti & Jangan Tampilkan Lagi</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
