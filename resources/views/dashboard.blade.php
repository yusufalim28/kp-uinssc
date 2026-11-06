<x-layouts.admin :title="'Dashboard'">
    @php
        $hariMap = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => "Jum'at",
            'Saturday' => 'Sabtu',
        ];
        $hariIni = $hariMap[now()->format('l')] ?? now()->format('l');
        $jamSekarang = now()->format('H:i:s');
        $taAktif = \App\Models\TahunAkademik::where('is_aktif', true)->first();

        // 1. Data Ringkasan Utama
        $totalJadwalAktif = \App\Models\JadwalPraktikum::where('status', 'aktif')->count();
        $totalDosen = \App\Models\Dosen::where('status', 'aktif')->count();
        $totalLab = \App\Models\RuangPraktikum::where('status', 'aktif')->count();
        $totalKapasitas = \App\Models\RuangPraktikum::where('status', 'aktif')->sum('kapasitas');

        // 2. Real-Time Status Lab Saat Ini
        $ruangAktifQuery = \App\Models\JadwalPraktikum::where('status', 'aktif')
            ->where('hari', $hariIni)
            ->whereTime('jam_mulai', '<=', $jamSekarang)
            ->whereTime('jam_selesai', '>=', $jamSekarang);
        if ($taAktif) {
            $ruangAktifQuery->where('tahun_akademik_id', $taAktif->id);
        }
        $labTerpakaiCount = $ruangAktifQuery->distinct('ruang_praktikum_id')->count('ruang_praktikum_id');
        $labKosongCount = max(0, $totalLab - $labTerpakaiCount);
        $persenTerpakai = $totalLab > 0 ? round(($labTerpakaiCount / $totalLab) * 100) : 0;

        // 3. Pengajuan Perubahan
        $pendingPengajuanCount = \App\Models\PengajuanPerubahan::where('status', 'menunggu')->count();
        $disetujuiCount = \App\Models\PengajuanPerubahan::where('status', 'disetujui')->count();
        $pengajuanTerbaru = \App\Models\PengajuanPerubahan::with(['dosen', 'jadwalPraktikum.mataKuliah'])
            ->latest()
            ->take(4)
            ->get();

        // 4. Jadwal Praktikum Hari Ini
        $jadwalHariIniQuery = \App\Models\JadwalPraktikum::with(['mataKuliah', 'kelas', 'ruangPraktikum', 'dosen'])
            ->where('status', 'aktif')
            ->where('hari', $hariIni);
        if ($taAktif) {
            $jadwalHariIniQuery->where('tahun_akademik_id', $taAktif->id);
        }
        $jadwalHariIni = $jadwalHariIniQuery->orderBy('jam_mulai')->get();

        $sesiBerjalanCount = $jadwalHariIni->filter(function ($j) use ($jamSekarang) {
            return $j->jam_mulai <= $jamSekarang && $j->jam_selesai >= $jamSekarang;
        })->count();

        $sesiAkanDatangCount = $jadwalHariIni->filter(function ($j) use ($jamSekarang) {
            return $j->jam_mulai > $jamSekarang;
        })->count();

        // 5. Riwayat Aktivitas Terkini (Spatie Activity Log)
        $aktivitasTerbaru = \Spatie\Activitylog\Models\Activity::with('causer')
            ->latest()
            ->take(5)
            ->get();
    @endphp

    <div class="space-y-6">
        
        {{-- Top Welcome & Header Info (Persis Gaya Gambar 1) --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-zinc-100 tracking-tight">
                    Dashboard Operasional
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 dark:text-zinc-400 mt-0.5">
                    Selamat datang kembali, <span class="font-bold text-gray-800 dark:text-zinc-200">{{ auth()->user()->name }}</span> • {{ auth()->user()->roles->first()?->name ?? 'Pengelola' }}
                </p>
            </div>
            
            <div class="flex items-center gap-2.5 flex-wrap">
                <div class="px-3.5 py-2 rounded-xl bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 text-xs font-semibold text-gray-700 dark:text-zinc-300 shadow-2xs flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Tahun Akademik: <strong class="text-gray-900 dark:text-zinc-100">{{ $taAktif?->nama ?? '2025/2026' }} ({{ ucfirst($taAktif?->semester ?? 'Genap') }})</strong></span>
                </div>
                
                @role('super-admin|operator')
                <a href="{{ route('jadwal.manage') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-primary hover:bg-primary-dark text-white text-xs font-bold transition shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Plot Jadwal Baru</span>
                </a>
                @endrole
            </div>
        </div>

        {{-- Baris Kartu Metrik (5 Kartu Minimalis Elegan - Menggunakan Palet Emerald Senada Tanpa Warna-Warni Norak) --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3.5 sm:gap-4">
            
            {{-- Kartu 1: Jadwal Aktif --}}
            <div class="bg-white dark:bg-zinc-900 rounded-2xl p-4 sm:p-5 border border-gray-200/70 dark:border-zinc-800 shadow-2xs flex flex-col justify-between hover:border-emerald-500/40 transition">
                <div class="flex items-center justify-between gap-2">
                    <span class="text-[10px] sm:text-[11px] font-bold text-gray-400 dark:text-zinc-500 uppercase tracking-wider">
                        Jadwal Aktif
                    </span>
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-100/70 dark:border-emerald-800/40 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                </div>
                <div class="my-2">
                    <span class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-zinc-100 tracking-tight">
                        {{ $totalJadwalAktif }}
                    </span>
                </div>
                <div class="flex items-center justify-between pt-1 border-t border-gray-100 dark:border-zinc-800/80 text-[11px]">
                    <span class="text-emerald-700 dark:text-emerald-300 font-semibold bg-emerald-50 dark:bg-emerald-950/50 px-2 py-0.5 rounded-full border border-emerald-200/50 dark:border-emerald-800/50">
                        Semester Aktif
                    </span>
                    <a href="{{ route('jadwal.manage') }}" class="text-gray-400 hover:text-emerald-600 transition font-bold" title="Kelola Jadwal">&rarr;</a>
                </div>
            </div>

            {{-- Kartu 2: Dosen Pengampu --}}
            <div class="bg-white dark:bg-zinc-900 rounded-2xl p-4 sm:p-5 border border-gray-200/70 dark:border-zinc-800 shadow-2xs flex flex-col justify-between hover:border-emerald-500/40 transition">
                <div class="flex items-center justify-between gap-2">
                    <span class="text-[10px] sm:text-[11px] font-bold text-gray-400 dark:text-zinc-500 uppercase tracking-wider">
                        Dosen Pengampu
                    </span>
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-100/70 dark:border-emerald-800/40 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                </div>
                <div class="my-2">
                    <span class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-zinc-100 tracking-tight">
                        {{ $totalDosen }}
                    </span>
                </div>
                <div class="flex items-center justify-between pt-1 border-t border-gray-100 dark:border-zinc-800/80 text-[11px]">
                    <span class="text-emerald-700 dark:text-emerald-300 font-semibold bg-emerald-50 dark:bg-emerald-950/50 px-2 py-0.5 rounded-full border border-emerald-200/50 dark:border-emerald-800/50">
                        Terverifikasi
                    </span>
                    <a href="{{ route('dosen.index') }}" class="text-gray-400 hover:text-emerald-600 transition font-bold" title="Lihat Dosen">&rarr;</a>
                </div>
            </div>

            {{-- Kartu 3: Ruang Lab --}}
            <div class="bg-white dark:bg-zinc-900 rounded-2xl p-4 sm:p-5 border border-gray-200/70 dark:border-zinc-800 shadow-2xs flex flex-col justify-between hover:border-emerald-500/40 transition">
                <div class="flex items-center justify-between gap-2">
                    <span class="text-[10px] sm:text-[11px] font-bold text-gray-400 dark:text-zinc-500 uppercase tracking-wider">
                        Ruang Lab
                    </span>
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-100/70 dark:border-emerald-800/40 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                </div>
                <div class="my-2">
                    <span class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-zinc-100 tracking-tight">
                        {{ $totalLab }} <span class="text-xs font-semibold text-gray-400 dark:text-zinc-500">Unit</span>
                    </span>
                </div>
                <div class="flex items-center justify-between pt-1 border-t border-gray-100 dark:border-zinc-800/80 text-[11px]">
                    <span class="text-emerald-700 dark:text-emerald-300 font-semibold bg-emerald-50 dark:bg-emerald-950/50 px-2 py-0.5 rounded-full border border-emerald-200/50 dark:border-emerald-800/50">
                        {{ $totalKapasitas }} Kursi
                    </span>
                    <a href="{{ route('ruang.index') }}" class="text-gray-400 hover:text-emerald-600 transition font-bold" title="Master Ruangan">&rarr;</a>
                </div>
            </div>

            {{-- Kartu 4: Real-time Lab Dipakai --}}
            <div class="bg-white dark:bg-zinc-900 rounded-2xl p-4 sm:p-5 border border-gray-200/70 dark:border-zinc-800 shadow-2xs flex flex-col justify-between hover:border-emerald-500/40 transition">
                <div class="flex items-center justify-between gap-2">
                    <span class="text-[10px] sm:text-[11px] font-bold text-gray-400 dark:text-zinc-500 uppercase tracking-wider">
                        Dipakai Sekarang
                    </span>
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-100/70 dark:border-emerald-800/40 flex items-center justify-center shrink-0">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                        </span>
                    </div>
                </div>
                <div class="my-2">
                    <span class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-zinc-100 tracking-tight">
                        {{ $labTerpakaiCount }} <span class="text-xs font-semibold text-gray-400 dark:text-zinc-500">Lab</span>
                    </span>
                </div>
                <div class="flex items-center justify-between pt-1 border-t border-gray-100 dark:border-zinc-800/80 text-[11px]">
                    <span class="text-gray-600 dark:text-zinc-300 font-semibold bg-gray-100 dark:bg-zinc-800 px-2 py-0.5 rounded-full">
                        {{ $labKosongCount }} Lab Kosong
                    </span>
                    <a href="{{ route('jadwal.ruang') }}" class="text-gray-400 hover:text-emerald-600 transition font-bold" title="Visual Matriks">&rarr;</a>
                </div>
            </div>

            {{-- Kartu 5: Pengajuan Antre --}}
            <div class="bg-white dark:bg-zinc-900 rounded-2xl p-4 sm:p-5 border border-gray-200/70 dark:border-zinc-800 shadow-2xs flex flex-col justify-between hover:border-emerald-500/40 transition">
                <div class="flex items-center justify-between gap-2">
                    <span class="text-[10px] sm:text-[11px] font-bold text-gray-400 dark:text-zinc-500 uppercase tracking-wider">
                        Pengajuan Antre
                    </span>
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-100/70 dark:border-emerald-800/40 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                </div>
                <div class="my-2">
                    <span class="text-2xl sm:text-3xl font-black {{ $pendingPengajuanCount > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-gray-900 dark:text-zinc-100' }} tracking-tight">
                        {{ $pendingPengajuanCount }}
                    </span>
                </div>
                <div class="flex items-center justify-between pt-1 border-t border-gray-100 dark:border-zinc-800/80 text-[11px]">
                    <span class="text-emerald-700 dark:text-emerald-300 font-semibold bg-emerald-50 dark:bg-emerald-950/50 px-2 py-0.5 rounded-full border border-emerald-200/50 dark:border-emerald-800/50">
                        {{ $disetujuiCount }} ACC
                    </span>
                    <a href="{{ route('pengajuan.index') }}" class="text-gray-400 hover:text-emerald-600 transition font-bold" title="Review Pengajuan">&rarr;</a>
                </div>
            </div>

        </div>

        {{-- Baris Tengah: Monitoring Sesi Praktikum Hari Ini & Utilisasi Lab (Layout 7:5 Persis Gambar 1) --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5" x-data="{ tabFilter: 'all' }">
            
            {{-- Kolom Kiri (7/12): Sesi Praktikum Hari Ini --}}
            <div class="lg:col-span-7 bg-white dark:bg-zinc-900 rounded-2xl p-5 sm:p-6 border border-gray-200/70 dark:border-zinc-800 shadow-2xs flex flex-col justify-between">
                <div>
                    {{-- Header Card & Tabs Filter --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-gray-100 dark:border-zinc-800">
                        <div>
                            <h2 class="text-base font-bold text-gray-900 dark:text-zinc-100 tracking-tight">
                                Sesi Praktikum Hari Ini ({{ $hariIni }})
                            </h2>
                            <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">
                                Menampilkan {{ $jadwalHariIni->count() }} sesi terjadwal di seluruh lab fakultas
                            </p>
                        </div>

                        {{-- Filter Tabs Minimalis --}}
                        <div class="flex items-center gap-1 bg-gray-100 dark:bg-zinc-800/70 p-1 rounded-xl text-xs font-medium self-start sm:self-auto">
                            <button type="button" @click="tabFilter = 'all'" :class="tabFilter === 'all' ? 'bg-white dark:bg-zinc-700 text-gray-900 dark:text-white shadow-2xs font-bold' : 'text-gray-600 dark:text-zinc-400 hover:text-gray-900'" class="px-2.5 py-1 rounded-lg transition text-[11px]">
                                Semua ({{ $jadwalHariIni->count() }})
                            </button>
                            <button type="button" @click="tabFilter = 'running'" :class="tabFilter === 'running' ? 'bg-white dark:bg-zinc-700 text-gray-900 dark:text-white shadow-2xs font-bold' : 'text-gray-600 dark:text-zinc-400 hover:text-gray-900'" class="px-2.5 py-1 rounded-lg transition text-[11px]">
                                Berlangsung ({{ $sesiBerjalanCount }})
                            </button>
                            <button type="button" @click="tabFilter = 'upcoming'" :class="tabFilter === 'upcoming' ? 'bg-white dark:bg-zinc-700 text-gray-900 dark:text-white shadow-2xs font-bold' : 'text-gray-600 dark:text-zinc-400 hover:text-gray-900'" class="px-2.5 py-1 rounded-lg transition text-[11px]">
                                Akan Datang ({{ $sesiAkanDatangCount }})
                            </button>
                        </div>
                    </div>

                    {{-- List Sesi Praktikum Hari Ini --}}
                    <div class="mt-4 space-y-2.5 max-h-[380px] overflow-y-auto pr-1 [scrollbar-width:thin]">
                        @forelse($jadwalHariIni as $j)
                            @php
                                $isRunning = $j->jam_mulai <= $jamSekarang && $j->jam_selesai >= $jamSekarang;
                                $isUpcoming = $j->jam_mulai > $jamSekarang;
                                $isFinished = $j->jam_selesai < $jamSekarang;
                                $itemFilter = $isRunning ? 'running' : ($isUpcoming ? 'upcoming' : 'finished');
                            @endphp
                            <div x-show="tabFilter === 'all' || tabFilter === '{{ $itemFilter }}'"
                                class="p-3.5 rounded-xl border border-gray-100 dark:border-zinc-800/80 bg-gray-50/50 dark:bg-zinc-800/30 hover:bg-white dark:hover:bg-zinc-800 hover:border-emerald-500/30 transition flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-2xs">
                                
                                <div class="flex items-start gap-3 min-w-0">
                                    <div class="px-2.5 py-1.5 rounded-xl bg-white dark:bg-zinc-900 border border-gray-200/70 dark:border-zinc-700 text-center shrink-0">
                                        <span class="block text-xs font-bold font-mono text-gray-900 dark:text-zinc-100">{{ substr($j->jam_mulai, 0, 5) }}</span>
                                        <span class="block text-[10px] text-gray-400 font-mono">{{ substr($j->jam_selesai, 0, 5) }}</span>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="text-xs font-bold text-gray-900 dark:text-zinc-100 truncate">
                                                {{ $j->mataKuliah->nama_mk }}
                                            </span>
                                            <span class="px-1.5 py-0.2 rounded text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200/50 dark:border-emerald-800/40">
                                                {{ $j->kelas->nama_kelas }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-2 mt-1 text-xs text-gray-500 dark:text-zinc-400">
                                            <span class="font-medium text-gray-700 dark:text-zinc-300 truncate">{{ $j->dosen->nama }}</span>
                                            <span>•</span>
                                            <span class="truncate">{{ $j->ruangPraktikum->nama_ruang }} ({{ $j->ruangPraktikum->lokasi }})</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="shrink-0 flex items-center gap-2 self-end sm:self-center">
                                    @if($isRunning)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-700 dark:text-emerald-300 font-bold text-[10px] border border-emerald-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                                            Sedang Berlangsung
                                        </span>
                                    @elseif($isUpcoming)
                                        <span class="px-2.5 py-1 rounded-full bg-blue-50 dark:bg-blue-950/30 text-blue-700 dark:text-blue-300 font-semibold text-[10px] border border-blue-200/40 dark:border-blue-800/40">
                                            Akan Datang
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full bg-gray-100 dark:bg-zinc-800 text-gray-400 font-medium text-[10px]">
                                            Selesai
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center rounded-xl bg-gray-50/50 dark:bg-zinc-800/30 border border-dashed border-gray-200 dark:border-zinc-800">
                                <svg class="w-10 h-10 text-gray-300 dark:text-zinc-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <p class="text-xs font-semibold text-gray-500 dark:text-zinc-400">Tidak ada sesi praktikum terjadwal pada hari {{ $hariIni }}.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Footer Link --}}
                <div class="pt-3 mt-3 border-t border-gray-100 dark:border-zinc-800 flex items-center justify-between text-xs">
                    <span class="text-gray-400 dark:text-zinc-500 text-[11px]">Data diperbarui secara otomatis sesuai jadwal aktif</span>
                    <a href="{{ route('jadwal.ruang') }}" class="font-bold text-primary dark:text-emerald-400 hover:underline inline-flex items-center gap-1">
                        <span>Buka Visual Jadwal Ruang</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>

            {{-- Kolom Kanan (5/12): Utilisasi Lab & Akses Cepat Minimalis (Tanpa Tombol Pelangi Norak) --}}
            <div class="lg:col-span-5 space-y-5">
                
                {{-- Card Utilisasi Kapasitas Lab --}}
                <div class="bg-white dark:bg-zinc-900 rounded-2xl p-5 sm:p-6 border border-gray-200/70 dark:border-zinc-800 shadow-2xs">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-zinc-800">
                        <div>
                            <h2 class="text-sm font-bold text-gray-900 dark:text-zinc-100 tracking-tight">Utilisasi Ruang Lab</h2>
                            <p class="text-[11px] text-gray-400 dark:text-zinc-500">Tingkat keterisian saat ini</p>
                        </div>
                        <span class="text-xs font-black text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 px-2 py-0.5 rounded-lg border border-emerald-200/50 dark:border-emerald-800/40">
                            {{ $persenTerpakai }}% Terpakai
                        </span>
                    </div>

                    {{-- Progress Bar Modern --}}
                    <div class="mt-4">
                        <div class="h-2.5 w-full bg-gray-100 dark:bg-zinc-800 rounded-full overflow-hidden flex">
                            <div class="bg-emerald-600 dark:bg-emerald-500 h-full transition-all duration-500 rounded-full" style="width: {{ $persenTerpakai }}%"></div>
                        </div>
                    </div>

                    {{-- Metric Rincian --}}
                    <div class="grid grid-cols-3 gap-2 mt-4 pt-3 border-t border-gray-100 dark:border-zinc-800 text-center">
                        <div class="p-2 rounded-xl bg-gray-50/70 dark:bg-zinc-800/40">
                            <span class="block text-xs font-black text-gray-900 dark:text-zinc-100">{{ $labTerpakaiCount }} Lab</span>
                            <span class="block text-[10px] text-gray-400 font-medium">Sedang Aktif</span>
                        </div>
                        <div class="p-2 rounded-xl bg-gray-50/70 dark:bg-zinc-800/40">
                            <span class="block text-xs font-black text-gray-900 dark:text-zinc-100">{{ $labKosongCount }} Lab</span>
                            <span class="block text-[10px] text-gray-400 font-medium">Siap Digunakan</span>
                        </div>
                        <div class="p-2 rounded-xl bg-gray-50/70 dark:bg-zinc-800/40">
                            <span class="block text-xs font-black text-gray-900 dark:text-zinc-100">{{ $totalLab }} Lab</span>
                            <span class="block text-[10px] text-gray-400 font-medium">Total Laboratorium</span>
                        </div>
                    </div>
                </div>

                {{-- Akses Cepat Operasional (Desain Row Elegan Senada, Menggantikan Tombol Bulat Pelangi Gambar 2) --}}
                <div class="bg-white dark:bg-zinc-900 rounded-2xl p-5 sm:p-6 border border-gray-200/70 dark:border-zinc-800 shadow-2xs">
                    <h2 class="text-sm font-bold text-gray-900 dark:text-zinc-100 tracking-tight mb-3">
                        Akses Cepat Pengelolaan
                    </h2>
                    
                    <div class="space-y-2">
                        @role('super-admin|operator')
                        <a href="{{ route('jadwal.manage') }}" class="group flex items-center justify-between p-3 rounded-xl border border-gray-100 dark:border-zinc-800 hover:border-emerald-500/40 hover:bg-emerald-50/20 dark:hover:bg-emerald-950/10 transition">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-100/60 dark:border-emerald-800/40">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <span class="block text-xs font-bold text-gray-800 dark:text-zinc-200 group-hover:text-primary transition">Kelola Jadwal Praktikum</span>
                                    <span class="block text-[10px] text-gray-400">Plotting semesteran dan bentrok jadwal</span>
                                </div>
                            </div>
                            <span class="text-gray-300 dark:text-zinc-600 group-hover:text-primary transition text-xs font-bold">&rarr;</span>
                        </a>

                        <a href="{{ route('pengajuan.index') }}" class="group flex items-center justify-between p-3 rounded-xl border border-gray-100 dark:border-zinc-800 hover:border-emerald-500/40 hover:bg-emerald-50/20 dark:hover:bg-emerald-950/10 transition">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-100/60 dark:border-emerald-800/40">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                </div>
                                <div>
                                    <span class="block text-xs font-bold text-gray-800 dark:text-zinc-200 group-hover:text-primary transition">Tinjau Pengajuan Dosen</span>
                                    <span class="block text-[10px] text-gray-400">Verifikasi permohonan reschedule</span>
                                </div>
                            </div>
                            <span class="text-gray-300 dark:text-zinc-600 group-hover:text-primary transition text-xs font-bold">&rarr;</span>
                        </a>

                        <a href="{{ route('ruang.index') }}" class="group flex items-center justify-between p-3 rounded-xl border border-gray-100 dark:border-zinc-800 hover:border-emerald-500/40 hover:bg-emerald-50/20 dark:hover:bg-emerald-950/10 transition">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-100/60 dark:border-emerald-800/40">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </div>
                                <div>
                                    <span class="block text-xs font-bold text-gray-800 dark:text-zinc-200 group-hover:text-primary transition">Master Ruang Praktikum</span>
                                    <span class="block text-[10px] text-gray-400">Kapasitas, lokasi, dan hak akses lab</span>
                                </div>
                            </div>
                            <span class="text-gray-300 dark:text-zinc-600 group-hover:text-primary transition text-xs font-bold">&rarr;</span>
                        </a>

                        <a href="{{ route('dosen.index') }}" class="group flex items-center justify-between p-3 rounded-xl border border-gray-100 dark:border-zinc-800 hover:border-emerald-500/40 hover:bg-emerald-50/20 dark:hover:bg-emerald-950/10 transition">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-100/60 dark:border-emerald-800/40">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <div>
                                    <span class="block text-xs font-bold text-gray-800 dark:text-zinc-200 group-hover:text-primary transition">Data Dosen Pengampu</span>
                                    <span class="block text-[10px] text-gray-400">Sinkronisasi NIDN dan akun pengajar</span>
                                </div>
                            </div>
                            <span class="text-gray-300 dark:text-zinc-600 group-hover:text-primary transition text-xs font-bold">&rarr;</span>
                        </a>
                        @endrole

                        @role('dosen')
                        <a href="{{ route('jadwal.saya') }}" class="group flex items-center justify-between p-3 rounded-xl border border-gray-100 dark:border-zinc-800 hover:border-emerald-500/40 hover:bg-emerald-50/20 dark:hover:bg-emerald-950/10 transition">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-100/60 dark:border-emerald-800/40">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <span class="block text-xs font-bold text-gray-800 dark:text-zinc-200 group-hover:text-primary transition">Jadwal Mengajar Saya</span>
                                    <span class="block text-[10px] text-gray-400">Daftar kelas dan laboratorium Anda</span>
                                </div>
                            </div>
                            <span class="text-gray-300 dark:text-zinc-600 group-hover:text-primary transition text-xs font-bold">&rarr;</span>
                        </a>

                        <a href="{{ route('pengajuan.saya') }}" class="group flex items-center justify-between p-3 rounded-xl border border-gray-100 dark:border-zinc-800 hover:border-emerald-500/40 hover:bg-emerald-50/20 dark:hover:bg-emerald-950/10 transition">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-100/60 dark:border-emerald-800/40">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div>
                                    <span class="block text-xs font-bold text-gray-800 dark:text-zinc-200 group-hover:text-primary transition">Pengajuan Perubahan Jadwal</span>
                                    <span class="block text-[10px] text-gray-400">Reschedule atau ganti ruangan lab</span>
                                </div>
                            </div>
                            <span class="text-gray-300 dark:text-zinc-600 group-hover:text-primary transition text-xs font-bold">&rarr;</span>
                        </a>
                        @endrole
                    </div>
                </div>

            </div>

        </div>

        {{-- Baris Bawah: Pengajuan Terkini & Audit Log Sistem (2 Kolom Bersih) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            
            {{-- Pengajuan Terkini --}}
            <div class="bg-white dark:bg-zinc-900 rounded-2xl p-5 sm:p-6 border border-gray-200/70 dark:border-zinc-800 shadow-2xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-zinc-800">
                        <div>
                            <h2 class="text-sm font-bold text-gray-900 dark:text-zinc-100 tracking-tight">Pengajuan Perubahan Terkini</h2>
                            <p class="text-[11px] text-gray-400 dark:text-zinc-500">Permohonan reschedule oleh dosen pengampu</p>
                        </div>
                        <a href="{{ route('pengajuan.index') }}" class="text-xs font-bold text-primary dark:text-emerald-400 hover:underline">
                            Lihat Semua &rarr;
                        </a>
                    </div>

                    <div class="mt-3.5 space-y-2.5">
                        @forelse($pengajuanTerbaru as $p)
                            <div class="p-3 rounded-xl border border-gray-100 dark:border-zinc-800/80 bg-gray-50/40 dark:bg-zinc-800/20 flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <span class="block text-xs font-bold text-gray-900 dark:text-zinc-100 truncate">
                                        {{ $p->jadwalPraktikum?->mataKuliah?->nama_mk ?? 'Jadwal Praktikum' }}
                                    </span>
                                    <span class="block text-[11px] text-gray-500 dark:text-zinc-400 truncate mt-0.5">
                                        Diajukan oleh: <strong class="text-gray-700 dark:text-zinc-300">{{ $p->dosen?->nama ?? 'Dosen' }}</strong>
                                    </span>
                                </div>
                                <div class="shrink-0">
                                    @if($p->status === 'menunggu')
                                        <span class="px-2 py-0.5 rounded-full bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 font-bold text-[10px] border border-amber-200/50 dark:border-amber-800/40">
                                            Menunggu
                                        </span>
                                    @elseif($p->status === 'disetujui')
                                        <span class="px-2 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 font-bold text-[10px] border border-emerald-200/50 dark:border-emerald-800/40">
                                            Disetujui
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 font-bold text-[10px] border border-rose-200/50 dark:border-rose-800/40">
                                            Ditolak
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center text-xs text-gray-400">
                                Belum ada riwayat permohonan pengajuan perubahan jadwal.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="pt-3 mt-3 border-t border-gray-100 dark:border-zinc-800 text-right">
                    <span class="text-[11px] text-gray-400">Dosen dapat mengajukan perubahan jika berhalangan hadir</span>
                </div>
            </div>

            {{-- Riwayat Aktivitas Sistem (Audit Log) --}}
            <div class="bg-white dark:bg-zinc-900 rounded-2xl p-5 sm:p-6 border border-gray-200/70 dark:border-zinc-800 shadow-2xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-zinc-800">
                        <div>
                            <h2 class="text-sm font-bold text-gray-900 dark:text-zinc-100 tracking-tight">Audit Log & Riwayat Sistem</h2>
                            <p class="text-[11px] text-gray-400 dark:text-zinc-500">Pencatatan aktivitas perubahan data terbaru</p>
                        </div>
                        @role('super-admin')
                        <a href="{{ route('activitylog.index') }}" class="text-xs font-bold text-primary dark:text-emerald-400 hover:underline">
                            Lihat Log &rarr;
                        </a>
                        @endrole
                    </div>

                    <div class="mt-3.5 space-y-2.5">
                        @forelse($aktivitasTerbaru as $log)
                            <div class="p-3 rounded-xl border border-gray-100 dark:border-zinc-800/80 bg-gray-50/40 dark:bg-zinc-800/20 flex items-center justify-between gap-3 text-xs">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-1.5">
                                        <span class="font-bold text-gray-800 dark:text-zinc-200 truncate">
                                            {{ $log->causer?->name ?? 'Sistem' }}
                                        </span>
                                        <span class="text-gray-400 text-[10px]">•</span>
                                        <span class="text-gray-500 dark:text-zinc-400 capitalize truncate">
                                            {{ $log->description ?? $log->event }}
                                        </span>
                                    </div>
                                    <span class="block text-[10px] text-gray-400 mt-0.5 truncate">
                                        Objek: {{ class_basename($log->subject_type ?? '') }} #{{ $log->subject_id }}
                                    </span>
                                </div>
                                <span class="shrink-0 text-[10px] text-gray-400 font-medium">
                                    {{ $log->created_at?->diffForHumans() ?? '-' }}
                                </span>
                            </div>
                        @empty
                            <div class="p-6 text-center text-xs text-gray-400">
                                Belum ada aktivitas yang dicatat.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="pt-3 mt-3 border-t border-gray-100 dark:border-zinc-800 text-right">
                    <span class="text-[11px] text-gray-400">Transparansi audit operasional laboratorium</span>
                </div>
            </div>

        </div>

    </div>
</x-layouts.admin>
