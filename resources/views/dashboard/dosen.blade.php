@php
    $user = auth()->user();
    $dosen = \App\Models\Dosen::where('user_id', $user->id)->first() ?? \App\Models\Dosen::where('email', $user->email)->first();
    $taAktif = \App\Models\TahunAkademik::where('is_aktif', true)->first();

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

    if ($dosen) {
        $allJadwalQuery = \App\Models\JadwalPraktikum::with(['mataKuliah.programStudi', 'kelas', 'ruangPraktikum.fakultas'])
            ->where('dosen_id', $dosen->id)
            ->where('status', 'aktif');
        if ($taAktif) {
            $allJadwalQuery->where('tahun_akademik_id', $taAktif->id);
        }
        $myJadwalList = $allJadwalQuery->orderByRaw("CASE hari WHEN 'Senin' THEN 1 WHEN 'Selasa' THEN 2 WHEN 'Rabu' THEN 3 WHEN 'Kamis' THEN 4 WHEN 'Jumat' THEN 5 WHEN 'Jum''at' THEN 5 WHEN 'Sabtu' THEN 6 ELSE 7 END")->orderBy('jam_mulai')->get();
        $myJadwalCount = $myJadwalList->count();
        $myTotalSks = $myJadwalList->sum(fn($j) => $j->mataKuliah?->sks ?? 0);
        $myJadwalHariIni = $myJadwalList->filter(fn($j) => $j->hari === $hariIni || ($hariIni === "Jum'at" && $j->hari === 'Jumat'));
        $myNextJadwal = $myJadwalList->first();

        $myPengajuanList = \App\Models\PengajuanPerubahan::with(['jadwalPraktikum.mataKuliah', 'jadwalPraktikum.ruangPraktikum'])
            ->where('dosen_id', $dosen->id)
            ->latest()
            ->take(5)
            ->get();
        $myPengajuanMenunggu = \App\Models\PengajuanPerubahan::where('dosen_id', $dosen->id)->where('status', 'menunggu')->count();
        $myPengajuanDisetujui = \App\Models\PengajuanPerubahan::where('dosen_id', $dosen->id)->where('status', 'disetujui')->count();
    } else {
        $myJadwalList = collect();
        $myJadwalCount = 0;
        $myTotalSks = 0;
        $myJadwalHariIni = collect();
        $myNextJadwal = null;
        $myPengajuanList = collect();
        $myPengajuanMenunggu = 0;
        $myPengajuanDisetujui = 0;
    }
@endphp

<div class="space-y-6">

    {{-- Welcome Header Dosen --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-md text-[10px] font-extrabold uppercase tracking-wider bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                    Portal Dosen
                </span>
                <span class="text-xs text-gray-400">•</span>
                <span class="text-xs font-mono text-gray-500 dark:text-zinc-400">NIDN: {{ $dosen?->nidn ?? 'Terhubung API Bridge' }}</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-zinc-100 tracking-tight mt-1">
                Dashboard Dosen Pengampu
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-zinc-400 mt-0.5">
                Selamat datang kembali, <strong class="text-gray-900 dark:text-zinc-100">{{ $dosen?->nama ?? $user->name }}</strong>
            </p>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap">
            <div class="px-3.5 py-2 rounded-xl bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 text-xs font-semibold text-gray-700 dark:text-zinc-300 shadow-2xs flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Semester Aktif: <strong class="text-gray-900 dark:text-zinc-100">{{ $taAktif?->nama ?? '2026/2027' }} ({{ ucfirst($taAktif?->semester ?? 'Ganjil') }})</strong></span>
            </div>
            <a href="{{ route('pengajuan.saya') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-primary hover:bg-primary-dark text-white text-xs font-bold transition shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Ajukan Reschedule</span>
            </a>
        </div>
    </div>

    {{-- Banner State Khusus: Dosen Tamu / Belum Ada Plotting Jadwal --}}
    @if(!$dosen || $myJadwalCount === 0)
        <div class="bg-linear-to-r from-emerald-50/90 via-teal-50/50 to-white dark:from-zinc-900 dark:via-zinc-900 dark:to-zinc-900 rounded-2xl p-5 sm:p-6 border border-emerald-200/80 dark:border-zinc-800 shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-white dark:bg-zinc-800 border border-emerald-200 dark:border-emerald-800 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 shadow-xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-zinc-100">
                        Akun Anda Terhubung Sebagai Dosen Universitas
                    </h3>
                    <p class="text-xs text-gray-600 dark:text-zinc-400 mt-1 max-w-2xl leading-relaxed">
                        Saat ini Anda belum memiliki jadwal perkuliahan praktikum di laboratorium pada semester aktif ini. Jika Anda ditugaskan mengampu praktikum atau membutuhkan ruangan laboratorium untuk praktikum susulan, silakan hubungi operator fakultas Anda atau ajukan penggunaan lab.
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0 self-end md:self-center">
                <a href="{{ route('jadwal.ruang') }}" class="px-3.5 py-2 rounded-xl bg-white dark:bg-zinc-800 text-gray-800 dark:text-zinc-200 hover:bg-gray-50 border border-gray-200 dark:border-zinc-700 text-xs font-bold transition shadow-2xs">
                    Cek Laboratorium Kampus
                </a>
            </div>
        </div>
    @endif

    {{-- Baris Kartu Metrik Dosen (4 Metrik Minimalis Elegan) --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4">
        
        {{-- Kartu 1: Jadwal Mengajar Saya --}}
        <div class="bg-white dark:bg-zinc-900 rounded-2xl p-4 sm:p-5 border border-gray-200/70 dark:border-zinc-800 shadow-2xs flex flex-col justify-between hover:border-emerald-500/40 transition">
            <div class="flex items-center justify-between gap-2">
                <span class="text-[10px] sm:text-[11px] font-bold text-gray-400 dark:text-zinc-500 uppercase tracking-wider">
                    Jadwal Praktikum Saya
                </span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-100/70 dark:border-emerald-800/40 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <div class="my-2">
                <span class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-zinc-100 tracking-tight">
                    {{ $myJadwalCount }} <span class="text-xs font-semibold text-gray-400 dark:text-zinc-500">Kelas</span>
                </span>
            </div>
            <div class="flex items-center justify-between pt-1 border-t border-gray-100 dark:border-zinc-800/80 text-[11px]">
                <span class="text-emerald-700 dark:text-emerald-300 font-semibold bg-emerald-50 dark:bg-emerald-950/50 px-2 py-0.5 rounded-full border border-emerald-200/50 dark:border-emerald-800/50">
                    Semester Aktif
                </span>
                <a href="{{ route('jadwal.saya') }}" class="text-gray-400 hover:text-emerald-600 transition font-bold" title="Lihat Semua">&rarr;</a>
            </div>
        </div>

        {{-- Kartu 2: Beban SKS Praktikum --}}
        <div class="bg-white dark:bg-zinc-900 rounded-2xl p-4 sm:p-5 border border-gray-200/70 dark:border-zinc-800 shadow-2xs flex flex-col justify-between hover:border-emerald-500/40 transition">
            <div class="flex items-center justify-between gap-2">
                <span class="text-[10px] sm:text-[11px] font-bold text-gray-400 dark:text-zinc-500 uppercase tracking-wider">
                    Total SKS Praktikum
                </span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-100/70 dark:border-emerald-800/40 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
            </div>
            <div class="my-2">
                <span class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-zinc-100 tracking-tight">
                    {{ $myTotalSks }} <span class="text-xs font-semibold text-gray-400 dark:text-zinc-500">SKS</span>
                </span>
            </div>
            <div class="flex items-center justify-between pt-1 border-t border-gray-100 dark:border-zinc-800/80 text-[11px]">
                <span class="text-gray-500 dark:text-zinc-400">
                    Beban Perkuliahan Lab
                </span>
                <span class="text-emerald-600 font-bold">&check;</span>
            </div>
        </div>

        {{-- Kartu 3: Sesi Mengajar Hari Ini --}}
        <div class="bg-white dark:bg-zinc-900 rounded-2xl p-4 sm:p-5 border border-gray-200/70 dark:border-zinc-800 shadow-2xs flex flex-col justify-between hover:border-emerald-500/40 transition">
            <div class="flex items-center justify-between gap-2">
                <span class="text-[10px] sm:text-[11px] font-bold text-gray-400 dark:text-zinc-500 uppercase tracking-wider">
                    Jadwal Hari Ini ({{ $hariIni }})
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
                    {{ $myJadwalHariIni->count() }} <span class="text-xs font-semibold text-gray-400 dark:text-zinc-500">Sesi</span>
                </span>
            </div>
            <div class="flex items-center justify-between pt-1 border-t border-gray-100 dark:border-zinc-800/80 text-[11px]">
                <span class="{{ $myJadwalHariIni->count() > 0 ? 'text-emerald-700 dark:text-emerald-300 font-semibold bg-emerald-50 dark:bg-emerald-950/50 px-2 py-0.5 rounded-full' : 'text-gray-400' }}">
                    {{ $myJadwalHariIni->count() > 0 ? 'Ada Kelas Hari Ini' : 'Tidak Ada Sesi' }}
                </span>
                <a href="{{ route('jadwal.saya') }}" class="text-gray-400 hover:text-emerald-600 transition font-bold">&rarr;</a>
            </div>
        </div>

        {{-- Kartu 4: Pengajuan Reschedule --}}
        <div class="bg-white dark:bg-zinc-900 rounded-2xl p-4 sm:p-5 border border-gray-200/70 dark:border-zinc-800 shadow-2xs flex flex-col justify-between hover:border-emerald-500/40 transition">
            <div class="flex items-center justify-between gap-2">
                <span class="text-[10px] sm:text-[11px] font-bold text-gray-400 dark:text-zinc-500 uppercase tracking-wider">
                    Pengajuan Saya
                </span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-100/70 dark:border-emerald-800/40 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
            <div class="my-2">
                <span class="text-2xl sm:text-3xl font-black {{ $myPengajuanMenunggu > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-gray-900 dark:text-zinc-100' }} tracking-tight">
                    {{ $myPengajuanList->count() }} <span class="text-xs font-semibold text-gray-400 dark:text-zinc-500">Total</span>
                </span>
            </div>
            <div class="flex items-center justify-between pt-1 border-t border-gray-100 dark:border-zinc-800/80 text-[11px]">
                <span class="text-amber-700 dark:text-amber-300 font-semibold bg-amber-50 dark:bg-amber-950/50 px-2 py-0.5 rounded-full border border-amber-200/50 dark:border-amber-800/50">
                    {{ $myPengajuanMenunggu }} Menunggu
                </span>
                <a href="{{ route('pengajuan.saya') }}" class="text-gray-400 hover:text-emerald-600 transition font-bold" title="Detail Pengajuan">&rarr;</a>
            </div>
        </div>

    </div>

    {{-- Baris Tengah: Sesi Hari Ini & Akses Cepat Dosen --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        
        {{-- Kolom Kiri (7/12): Agenda Praktikum Hari Ini Dosen --}}
        <div class="lg:col-span-7 bg-white dark:bg-zinc-900 rounded-2xl p-5 sm:p-6 border border-gray-200/70 dark:border-zinc-800 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-zinc-800">
                    <div>
                        <h2 class="text-base font-bold text-gray-900 dark:text-zinc-100 tracking-tight">
                            Agenda Mengajar Hari Ini ({{ $hariIni }})
                        </h2>
                        <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">
                            Sesi perkuliahan laboratorium yang harus Anda pandu hari ini
                        </p>
                    </div>
                    <span class="text-xs font-bold text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/40 px-2.5 py-1 rounded-lg border border-emerald-200/50 dark:border-emerald-800/40">
                        {{ $myJadwalHariIni->count() }} Sesi
                    </span>
                </div>

                <div class="mt-4 space-y-3">
                    @forelse($myJadwalHariIni as $j)
                        @php
                            $isRunning = $j->jam_mulai <= $jamSekarang && $j->jam_selesai >= $jamSekarang;
                            $isUpcoming = $j->jam_mulai > $jamSekarang;
                        @endphp
                        <div class="p-4 rounded-xl border border-gray-200/80 dark:border-zinc-800 bg-gray-50/40 dark:bg-zinc-800/30 hover:border-emerald-500/40 transition flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-2xs">
                            <div class="flex items-start gap-3.5 min-w-0">
                                <div class="px-3 py-2 rounded-xl bg-white dark:bg-zinc-900 border border-gray-200/70 dark:border-zinc-700 text-center shrink-0 shadow-2xs">
                                    <span class="block text-xs font-bold font-mono text-gray-900 dark:text-zinc-100">{{ substr($j->jam_mulai, 0, 5) }}</span>
                                    <span class="block text-[10px] text-gray-400 font-mono">{{ substr($j->jam_selesai, 0, 5) }}</span>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="text-xs font-bold text-gray-900 dark:text-zinc-100">
                                            {{ $j->mataKuliah->nama_mk }}
                                        </span>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200/50 dark:border-emerald-800/40">
                                            {{ ($j->kelas && $j->kelas->nama_kelas !== '-') ? $j->kelas->nama_kelas : 'Non-Kelas' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2 mt-1.5 text-xs text-gray-500 dark:text-zinc-400">
                                        <span class="font-medium text-emerald-800 dark:text-emerald-300">{{ $j->ruangPraktikum->nama_ruang }}</span>
                                        <span>•</span>
                                        <span>Lokasi: {{ $j->ruangPraktikum->lokasi ?? 'Gedung Lab' }}</span>
                                        <span>•</span>
                                        <span>{{ $j->mataKuliah->sks }} SKS</span>
                                    </div>
                                </div>
                            </div>

                            <div class="shrink-0 self-end sm:self-center">
                                @if($isRunning)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-700 dark:text-emerald-300 font-bold text-[10px] border border-emerald-500/20">
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
                        <div class="p-8 text-center rounded-2xl bg-gray-50/50 dark:bg-zinc-800/30 border border-dashed border-gray-200 dark:border-zinc-800 space-y-2">
                            <div class="w-10 h-10 rounded-xl bg-white dark:bg-zinc-800 text-gray-400 flex items-center justify-center mx-auto border border-gray-200 dark:border-zinc-700 shadow-2xs">
                                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <h3 class="text-xs font-bold text-gray-800 dark:text-zinc-200">
                                Tidak ada jadwal mengajar praktikum hari ini ({{ $hariIni }}).
                            </h3>
                            <p class="text-[11px] text-gray-500 dark:text-zinc-400 max-w-sm mx-auto">
                                Selamat beristirahat atau mempersiapkan materi perkuliahan untuk sesi praktikum berikutnya.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="pt-3 mt-4 border-t border-gray-100 dark:border-zinc-800 flex items-center justify-between text-xs">
                <span class="text-gray-400 text-[11px]">Tampilkan jadwal lengkap semesteran Anda</span>
                <a href="{{ route('jadwal.saya') }}" class="font-bold text-primary dark:text-emerald-400 hover:underline inline-flex items-center gap-1">
                    <span>Lihat Jadwal Saya Selengkapnya</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>

        {{-- Kolom Kanan (5/12): Menu Cepat & Ringkasan Pengajuan Terakhir --}}
        <div class="lg:col-span-5 space-y-5">
            
            {{-- Menu Aksi Cepat Dosen --}}
            <div class="bg-white dark:bg-zinc-900 rounded-2xl p-5 sm:p-6 border border-gray-200/70 dark:border-zinc-800 shadow-2xs">
                <h2 class="text-sm font-bold text-gray-900 dark:text-zinc-100 tracking-tight mb-3">
                    Layanan & Navigasi Cepat
                </h2>
                
                <div class="space-y-2">
                    <a href="{{ route('jadwal.saya') }}" class="group flex items-center justify-between p-3 rounded-xl border border-gray-100 dark:border-zinc-800 hover:border-emerald-500/40 hover:bg-emerald-50/20 dark:hover:bg-emerald-950/10 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-100/60 dark:border-emerald-800/40">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-gray-800 dark:text-zinc-200 group-hover:text-primary transition">Jadwal Mengajar Saya</span>
                                <span class="block text-[10px] text-gray-400">Daftar kelas praktikum dan ruang lab Anda</span>
                            </div>
                        </div>
                        <span class="text-gray-300 dark:text-zinc-600 group-hover:text-primary transition text-xs font-bold">&rarr;</span>
                    </a>

                    <a href="{{ route('pengajuan.saya') }}" class="group flex items-center justify-between p-3 rounded-xl border border-gray-100 dark:border-zinc-800 hover:border-emerald-500/40 hover:bg-emerald-50/20 dark:hover:bg-emerald-950/10 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-100/60 dark:border-emerald-800/40">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-gray-800 dark:text-zinc-200 group-hover:text-primary transition">Ajukan Perubahan Jadwal</span>
                                <span class="block text-[10px] text-gray-400">Reschedule hari/jam atau pindah ruangan</span>
                            </div>
                        </div>
                        <span class="text-gray-300 dark:text-zinc-600 group-hover:text-primary transition text-xs font-bold">&rarr;</span>
                    </a>

                    <a href="{{ route('jadwal.ruang') }}" class="group flex items-center justify-between p-3 rounded-xl border border-gray-100 dark:border-zinc-800 hover:border-emerald-500/40 hover:bg-emerald-50/20 dark:hover:bg-emerald-950/10 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-100/60 dark:border-emerald-800/40">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-gray-800 dark:text-zinc-200 group-hover:text-primary transition">Jadwal Ruangan Laboratorium</span>
                                <span class="block text-[10px] text-gray-400">Lihat lembar pemakaian lab format matriks</span>
                            </div>
                        </div>
                        <span class="text-gray-300 dark:text-zinc-600 group-hover:text-primary transition text-xs font-bold">&rarr;</span>
                    </a>

                    <a href="{{ route('panduan.index') }}" class="group flex items-center justify-between p-3 rounded-xl border border-gray-100 dark:border-zinc-800 hover:border-emerald-500/40 hover:bg-emerald-50/20 dark:hover:bg-emerald-950/10 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-100/60 dark:border-emerald-800/40">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-gray-800 dark:text-zinc-200 group-hover:text-primary transition">SOP & Panduan Praktikum</span>
                                <span class="block text-[10px] text-gray-400">Aturan pemakaian lab & batas reschedule</span>
                            </div>
                        </div>
                        <span class="text-gray-300 dark:text-zinc-600 group-hover:text-primary transition text-xs font-bold">&rarr;</span>
                    </a>
                </div>
            </div>

            {{-- Status Pengajuan Terakhir Milik Dosen --}}
            <div class="bg-white dark:bg-zinc-900 rounded-2xl p-5 sm:p-6 border border-gray-200/70 dark:border-zinc-800 shadow-2xs">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-zinc-800">
                    <div>
                        <h2 class="text-sm font-bold text-gray-900 dark:text-zinc-100 tracking-tight">Status Pengajuan Anda</h2>
                        <p class="text-[11px] text-gray-400 dark:text-zinc-500">Permohonan reschedule terkini</p>
                    </div>
                    <a href="{{ route('pengajuan.saya') }}" class="text-xs font-bold text-primary dark:text-emerald-400 hover:underline">
                        Riwayat &rarr;
                    </a>
                </div>

                <div class="mt-3.5 space-y-2.5">
                    @forelse($myPengajuanList as $p)
                        <div class="p-3 rounded-xl border border-gray-100 dark:border-zinc-800/80 bg-gray-50/40 dark:bg-zinc-800/20 flex items-center justify-between gap-3 text-xs">
                            <div class="min-w-0">
                                <span class="block font-bold text-gray-900 dark:text-zinc-100 truncate">
                                    {{ $p->jadwalPraktikum?->mataKuliah?->nama_mk ?? 'Jadwal Praktikum' }}
                                </span>
                                <span class="block text-[10px] text-gray-400 mt-0.5 truncate">
                                    Diajukan: {{ $p->created_at?->translatedFormat('d M Y') }}
                                </span>
                            </div>
                            <div class="shrink-0">
                                @if($p->status === 'menunggu')
                                    <span class="px-2 py-0.5 rounded-full bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 font-bold text-[10px] border border-amber-200/50 dark:border-amber-800/50">
                                        Menunggu
                                    </span>
                                @elseif($p->status === 'disetujui')
                                    <span class="px-2 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 font-bold text-[10px] border border-emerald-200/50 dark:border-emerald-800/50">
                                        Disetujui
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 font-bold text-[10px] border border-rose-200/50 dark:border-rose-800/50">
                                        Ditolak
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-5 text-center text-xs text-gray-400">
                            Belum ada riwayat permohonan perubahan jadwal.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

    {{-- Baris Bawah: Daftar Lengkap Mata Kuliah Praktikum Semester Ini yang Diampu --}}
    @if($myJadwalCount > 0)
        <div class="bg-white dark:bg-zinc-900 rounded-2xl p-5 sm:p-6 border border-gray-200/70 dark:border-zinc-800 shadow-2xs">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-zinc-800">
                <div>
                    <h2 class="text-sm sm:text-base font-bold text-gray-900 dark:text-zinc-100 tracking-tight">
                        Daftar Praktikum Semester Aktif Anda
                    </h2>
                    <p class="text-xs text-gray-400 dark:text-zinc-500 mt-0.5">
                        Daftar kelas dan alokasi laboratorium yang telah ditetapkan oleh operator fakultas
                    </p>
                </div>
                <a href="{{ route('jadwal.saya') }}" class="text-xs font-bold text-primary dark:text-emerald-400 hover:underline">
                    Lihat Mode Kalender &rarr;
                </a>
            </div>

            <div class="mt-4 overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-gray-50/80 dark:bg-zinc-800/60 border-b border-gray-200/80 dark:border-zinc-700/80 text-[11px] font-bold text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3">Hari & Jam</th>
                            <th class="px-4 py-3">Mata Kuliah</th>
                            <th class="px-4 py-3 text-center">SKS</th>
                            <th class="px-4 py-3">Kelas</th>
                            <th class="px-4 py-3">Laboratorium</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-zinc-800 text-gray-800 dark:text-zinc-200">
                        @foreach($myJadwalList as $mj)
                            <tr class="hover:bg-emerald-50/30 dark:hover:bg-zinc-800/40 transition">
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="font-bold text-gray-900 dark:text-zinc-100">{{ $mj->hari }}</span>
                                    <span class="block text-[10px] text-gray-400 font-mono">{{ substr($mj->jam_mulai, 0, 5) }} - {{ substr($mj->jam_selesai, 0, 5) }} WIB</span>
                                </td>
                                <td class="px-4 py-3.5">
                                    <span class="font-bold text-gray-900 dark:text-zinc-100 block">{{ $mj->mataKuliah->nama_mk }}</span>
                                    <span class="text-[10px] font-mono text-gray-400">{{ $mj->mataKuliah->kode_mk }} • {{ $mj->mataKuliah->programStudi?->nama_program_studi }}</span>
                                </td>
                                <td class="px-4 py-3.5 text-center font-bold text-gray-800 dark:text-zinc-200">
                                    {{ $mj->mataKuliah->sks }}
                                </td>
                                <td class="px-4 py-3.5">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200/50 dark:border-emerald-800/40">
                                        {{ ($mj->kelas && $mj->kelas->nama_kelas !== '-') ? $mj->kelas->nama_kelas : 'Non-Kelas' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5">
                                    <span class="font-bold text-gray-800 dark:text-zinc-200 block">{{ $mj->ruangPraktikum->nama_ruang }}</span>
                                    <span class="text-[10px] text-gray-400 block">{{ $mj->ruangPraktikum->fakultas?->nama_fakultas ?? 'Fakultas' }}</span>
                                </td>
                                <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                    <a href="{{ route('pengajuan.saya') }}" class="font-bold text-xs text-primary dark:text-emerald-400 hover:underline">
                                        Reschedule
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>

