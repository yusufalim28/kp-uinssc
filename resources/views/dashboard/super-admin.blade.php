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

    // 1. Data Ringkasan Universitas
    $totalJadwalAktif = \App\Models\JadwalPraktikum::where('status', 'aktif')->count();
    $totalDosen = \App\Models\Dosen::where('status', 'aktif')->count();
    $totalLab = \App\Models\RuangPraktikum::where('status', 'aktif')->count();
    $totalKapasitas = \App\Models\RuangPraktikum::where('status', 'aktif')->sum('kapasitas');
    $totalFakultas = \App\Models\Fakultas::where('status', 'aktif')->count();
    $totalUsers = \App\Models\User::count();

    // 2. Real-Time Status Lab Kampus
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

    // 3. Pengajuan Perubahan Kampus
    $pendingPengajuanCount = \App\Models\PengajuanPerubahan::where('status', 'menunggu')->count();
    $disetujuiCount = \App\Models\PengajuanPerubahan::where('status', 'disetujui')->count();
    $pengajuanTerbaru = \App\Models\PengajuanPerubahan::with(['dosen', 'jadwalPraktikum.mataKuliah', 'jadwalPraktikum.ruangPraktikum'])
        ->latest()
        ->take(4)
        ->get();

    // 4. Jadwal Praktikum Hari Ini
    $jadwalHariIniQuery = \App\Models\JadwalPraktikum::with(['mataKuliah', 'kelas', 'ruangPraktikum.fakultas', 'dosen'])
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

    // 5. Rekap Antar Fakultas Resmi (FITK, FEBI, FASYA, FUA, FDKI)
    $fakultasList = \App\Models\Fakultas::withCount(['ruangPraktikum' => function ($q) {
        $q->where('status', 'aktif');
    }])->get()->map(function ($fak) use ($taAktif) {
        $jadwalQuery = \App\Models\JadwalPraktikum::where('status', 'aktif')
            ->whereHas('ruangPraktikum', fn ($rq) => $rq->where('fakultas_id', $fak->id));
        if ($taAktif) {
            $jadwalQuery->where('tahun_akademik_id', $taAktif->id);
        }
        $fak->jadwal_count = $jadwalQuery->count();
        $fak->operator = \App\Models\User::role('operator')->where('fakultas_id', $fak->id)->first();

        return $fak;
    });

    // 6. Riwayat Aktivitas Sistem (Spatie Activity Log)
    $aktivitasTerbaru = \Spatie\Activitylog\Models\Activity::with('causer')
        ->latest()
        ->take(5)
        ->get();
@endphp

<div class="space-y-6">

    {{-- Welcome Header Super Admin --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-md text-[10px] font-extrabold uppercase tracking-wider bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                    Super Administrator
                </span>
                <span class="text-xs text-gray-400">•</span>
                <span class="text-xs font-semibold text-gray-500 dark:text-zinc-400">Pengawasan Seluruh Fakultas</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-zinc-100 tracking-tight mt-1">
                Dashboard Eksekutif Universitas
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-zinc-400 mt-0.5">
                Selamat datang kembali, <strong class="text-gray-900 dark:text-zinc-100">{{ auth()->user()->name }}</strong> • Monitoring Terpadu
            </p>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap">
            <div class="px-3.5 py-2 rounded-xl bg-white dark:bg-zinc-900 border border-gray-200/80 dark:border-zinc-800 text-xs font-semibold text-gray-700 dark:text-zinc-300 shadow-2xs flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Tahun Akademik: <strong class="text-gray-900 dark:text-zinc-100">{{ $taAktif?->nama ?? '2026/2027' }} ({{ ucfirst($taAktif?->semester ?? 'Ganjil') }})</strong></span>
            </div>
            <a href="{{ route('users.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-primary hover:bg-primary-dark text-white text-xs font-bold transition shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                <span>Kelola Pengguna</span>
            </a>
        </div>
    </div>

    {{-- Baris Kartu Metrik Global Universitas (5 Kartu) --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3.5 sm:gap-4">
        
        {{-- Kartu 1: Total Jadwal Kampus --}}
        <div class="bg-white dark:bg-zinc-900 rounded-2xl p-4 sm:p-5 border border-gray-200/70 dark:border-zinc-800 shadow-2xs flex flex-col justify-between hover:border-emerald-500/40 transition">
            <div class="flex items-center justify-between gap-2">
                <span class="text-[10px] sm:text-[11px] font-bold text-gray-400 dark:text-zinc-500 uppercase tracking-wider">
                    Jadwal Universitas
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
                    5 Fakultas
                </span>
                <a href="{{ route('jadwal.manage') }}" class="text-gray-400 hover:text-emerald-600 transition font-bold" title="Kelola Jadwal">&rarr;</a>
            </div>
        </div>

        {{-- Kartu 2: Total Laboratorium --}}
        <div class="bg-white dark:bg-zinc-900 rounded-2xl p-4 sm:p-5 border border-gray-200/70 dark:border-zinc-800 shadow-2xs flex flex-col justify-between hover:border-emerald-500/40 transition">
            <div class="flex items-center justify-between gap-2">
                <span class="text-[10px] sm:text-[11px] font-bold text-gray-400 dark:text-zinc-500 uppercase tracking-wider">
                    Total Laboratorium
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

        {{-- Kartu 3: Total Dosen --}}
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
                <span class="text-gray-500 dark:text-zinc-400">
                    Terdaftar di Sistem
                </span>
                <a href="{{ route('dosen.index') }}" class="text-gray-400 hover:text-emerald-600 transition font-bold" title="Lihat Dosen">&rarr;</a>
            </div>
        </div>

        {{-- Kartu 4: Real-time Lab Dipakai Kampus --}}
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
                    {{ $labKosongCount }} Kosong
                </span>
                <a href="{{ route('jadwal.ruang') }}" class="text-gray-400 hover:text-emerald-600 transition font-bold" title="Visual Matriks">&rarr;</a>
            </div>
        </div>

        {{-- Kartu 5: Pengajuan Perubahan Kampus --}}
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

    {{-- Rekap Distribusi Antar Fakultas Resmi (Tabel Eksekutif Khusus Super Admin) --}}
    <div class="bg-white dark:bg-zinc-900 rounded-2xl p-5 sm:p-6 border border-gray-200/70 dark:border-zinc-800 shadow-2xs">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-zinc-800">
            <div>
                <h2 class="text-sm sm:text-base font-bold text-gray-900 dark:text-zinc-100 tracking-tight">
                    Distribusi Fasilitas & Jadwal Antar Fakultas
                </h2>
                <p class="text-xs text-gray-400 dark:text-zinc-500 mt-0.5">
                    Ringkasan data operasional 5 fakultas resmi UIN Siber Syekh Nurjati Cirebon
                </p>
            </div>
            <a href="{{ route('fakultas.index') }}" class="text-xs font-bold text-primary dark:text-emerald-400 hover:underline">
                Kelola Fakultas &rarr;
            </a>
        </div>

        <div class="mt-4 overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-gray-50/80 dark:bg-zinc-800/60 border-b border-gray-200/80 dark:border-zinc-700/80 text-[11px] font-bold text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3">Fakultas</th>
                        <th class="px-4 py-3 text-center">Laboratorium</th>
                        <th class="px-4 py-3 text-center">Jadwal Aktif</th>
                        <th class="px-4 py-3">Operator Penanggung Jawab</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-zinc-800 text-gray-800 dark:text-zinc-200">
                    @foreach($fakultasList as $fak)
                        <tr class="hover:bg-emerald-50/30 dark:hover:bg-zinc-800/40 transition">
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded font-mono text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border border-emerald-200/50 dark:border-emerald-800/40">
                                        {{ $fak->kode_fakultas }}
                                    </span>
                                    <span class="font-bold text-gray-900 dark:text-zinc-100">{{ $fak->nama_fakultas }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 text-center font-bold text-gray-800 dark:text-zinc-200">
                                {{ $fak->ruang_praktikum_count }} Unit
                            </td>
                            <td class="px-4 py-3.5 text-center font-bold text-gray-800 dark:text-zinc-200">
                                {{ $fak->jadwal_count }} Sesi
                            </td>
                            <td class="px-4 py-3.5">
                                @if($fak->operator)
                                    <div class="text-xs">
                                        <span class="font-bold text-gray-900 dark:text-zinc-100 block">{{ $fak->operator->name }}</span>
                                        <span class="text-[10px] text-gray-400 font-mono">{{ $fak->operator->email }}</span>
                                    </div>
                                @else
                                    <span class="text-[11px] text-amber-600 dark:text-amber-400 italic">Belum ditetapkan</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                <a href="{{ route('jadwal.ruang', ['fakultas' => $fak->id]) }}" class="font-bold text-xs text-primary dark:text-emerald-400 hover:underline">
                                    Lihat Lab
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Baris Tengah: Monitoring Sesi Kampus & Utilisasi Lab Kampus --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5" x-data="{ tabFilter: 'all' }">
        
        {{-- Kolom Kiri (7/12): Sesi Praktikum Kampus Hari Ini --}}
        <div class="lg:col-span-7 bg-white dark:bg-zinc-900 rounded-2xl p-5 sm:p-6 border border-gray-200/70 dark:border-zinc-800 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-gray-100 dark:border-zinc-800">
                    <div>
                        <h2 class="text-base font-bold text-gray-900 dark:text-zinc-100 tracking-tight">
                            Monitoring Sesi Kampus Hari Ini ({{ $hariIni }})
                        </h2>
                        <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">
                            Menampilkan {{ $jadwalHariIni->count() }} sesi terjadwal di seluruh lab fakultas
                        </p>
                    </div>

                    <div class="flex items-center gap-1 bg-gray-100 dark:bg-zinc-800/70 p-1 rounded-xl text-xs font-medium self-start sm:self-auto">
                        <button type="button" @click="tabFilter = 'all'" :class="tabFilter === 'all' ? 'bg-white dark:bg-zinc-700 text-gray-900 dark:text-white shadow-2xs font-bold' : 'text-gray-600 dark:text-zinc-400 hover:text-gray-900'" class="px-2.5 py-1 rounded-lg transition text-[11px]">
                            Semua ({{ $jadwalHariIni->count() }})
                        </button>
                        <button type="button" @click="tabFilter = 'running'" :class="tabFilter === 'running' ? 'bg-white dark:bg-zinc-700 text-gray-900 dark:text-white shadow-2xs font-bold' : 'text-gray-600 dark:text-zinc-400 hover:text-gray-900'" class="px-2.5 py-1 rounded-lg transition text-[11px]">
                            Aktif ({{ $sesiBerjalanCount }})
                        </button>
                        <button type="button" @click="tabFilter = 'upcoming'" :class="tabFilter === 'upcoming' ? 'bg-white dark:bg-zinc-700 text-gray-900 dark:text-white shadow-2xs font-bold' : 'text-gray-600 dark:text-zinc-400 hover:text-gray-900'" class="px-2.5 py-1 rounded-lg transition text-[11px]">
                            Nanti ({{ $sesiAkanDatangCount }})
                        </button>
                    </div>
                </div>

                <div class="mt-4 space-y-2.5 max-h-[380px] overflow-y-auto pr-1 [scrollbar-width:thin]">
                    @forelse($jadwalHariIni as $j)
                        @php
                            $isRunning = $j->jam_mulai <= $jamSekarang && $j->jam_selesai >= $jamSekarang;
                            $isUpcoming = $j->jam_mulai > $jamSekarang;
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
                                            {{ ($j->kelas && $j->kelas->nama_kelas !== '-') ? $j->kelas->nama_kelas : 'Non-Kelas' }}
                                        </span>
                                        <span class="text-[10px] text-gray-400 font-mono">
                                            [{{ $j->ruangPraktikum?->fakultas?->kode_fakultas ?? 'Lab' }}]
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
                                        Berlangsung
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
                            <p class="text-xs font-semibold text-gray-500 dark:text-zinc-400">Tidak ada sesi praktikum terjadwal pada hari {{ $hariIni }}.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="pt-3 mt-3 border-t border-gray-100 dark:border-zinc-800 flex items-center justify-between text-xs">
                <span class="text-gray-400 dark:text-zinc-500 text-[11px]">Monitoring seluruh aktivitas laboratorium aktif</span>
                <a href="{{ route('jadwal.ruang') }}" class="font-bold text-primary dark:text-emerald-400 hover:underline inline-flex items-center gap-1">
                    <span>Visual Matriks Lab Kampus</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>

        {{-- Kolom Kanan (5/12): Utilisasi Ruang Lab & Akses Super Admin --}}
        <div class="lg:col-span-5 space-y-5">
            
            {{-- Utilisasi Ruang Lab Kampus --}}
            <div class="bg-white dark:bg-zinc-900 rounded-2xl p-5 sm:p-6 border border-gray-200/70 dark:border-zinc-800 shadow-2xs">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-zinc-800">
                    <div>
                        <h2 class="text-sm font-bold text-gray-900 dark:text-zinc-100 tracking-tight">Utilisasi Lab Universitas</h2>
                        <p class="text-[11px] text-gray-400 dark:text-zinc-500">Tingkat pemakaian fasilitas saat ini</p>
                    </div>
                    <span class="text-xs font-black text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 px-2 py-0.5 rounded-lg border border-emerald-200/50 dark:border-emerald-800/40">
                        {{ $persenTerpakai }}% Terpakai
                    </span>
                </div>

                <div class="mt-4">
                    <div class="h-2.5 w-full bg-gray-100 dark:bg-zinc-800 rounded-full overflow-hidden flex">
                        <div class="bg-emerald-600 dark:bg-emerald-500 h-full transition-all duration-500 rounded-full" style="width: {{ $persenTerpakai }}%"></div>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-2 mt-4 pt-3 border-t border-gray-100 dark:border-zinc-800 text-center">
                    <div class="p-2 rounded-xl bg-gray-50/70 dark:bg-zinc-800/40">
                        <span class="block text-xs font-black text-gray-900 dark:text-zinc-100">{{ $labTerpakaiCount }} Lab</span>
                        <span class="block text-[10px] text-gray-400 font-medium">Sedang Aktif</span>
                    </div>
                    <div class="p-2 rounded-xl bg-gray-50/70 dark:bg-zinc-800/40">
                        <span class="block text-xs font-black text-gray-900 dark:text-zinc-100">{{ $labKosongCount }} Lab</span>
                        <span class="block text-[10px] text-gray-400 font-medium">Siap Dipakai</span>
                    </div>
                    <div class="p-2 rounded-xl bg-gray-50/70 dark:bg-zinc-800/40">
                        <span class="block text-xs font-black text-gray-900 dark:text-zinc-100">{{ $totalLab }} Lab</span>
                        <span class="block text-[10px] text-gray-400 font-medium">Total Seluruh Kampus</span>
                    </div>
                </div>
            </div>

            {{-- Akses Cepat Super Admin --}}
            <div class="bg-white dark:bg-zinc-900 rounded-2xl p-5 sm:p-6 border border-gray-200/70 dark:border-zinc-800 shadow-2xs">
                <h2 class="text-sm font-bold text-gray-900 dark:text-zinc-100 tracking-tight mb-3">
                    Akses Cepat Super Admin
                </h2>
                
                <div class="space-y-2">
                    <a href="{{ route('users.index') }}" class="group flex items-center justify-between p-3 rounded-xl border border-gray-100 dark:border-zinc-800 hover:border-emerald-500/40 hover:bg-emerald-50/20 dark:hover:bg-emerald-950/10 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-100/60 dark:border-emerald-800/40">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-gray-800 dark:text-zinc-200 group-hover:text-primary transition">Pengguna & Operator Fakultas</span>
                                <span class="block text-[10px] text-gray-400">Atur penugasan operator 5 fakultas</span>
                            </div>
                        </div>
                        <span class="text-gray-300 dark:text-zinc-600 group-hover:text-primary transition text-xs font-bold">&rarr;</span>
                    </a>

                    <a href="{{ route('activitylog.index') }}" class="group flex items-center justify-between p-3 rounded-xl border border-gray-100 dark:border-zinc-800 hover:border-emerald-500/40 hover:bg-emerald-50/20 dark:hover:bg-emerald-950/10 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-100/60 dark:border-emerald-800/40">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-gray-800 dark:text-zinc-200 group-hover:text-primary transition">Riwayat & Log Audit Sistem</span>
                                <span class="block text-[10px] text-gray-400">Pantau seluruh modifikasi data oleh user</span>
                            </div>
                        </div>
                        <span class="text-gray-300 dark:text-zinc-600 group-hover:text-primary transition text-xs font-bold">&rarr;</span>
                    </a>

                    <a href="{{ route('sampah.index') }}" class="group flex items-center justify-between p-3 rounded-xl border border-gray-100 dark:border-zinc-800 hover:border-emerald-500/40 hover:bg-emerald-50/20 dark:hover:bg-emerald-950/10 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-100/60 dark:border-emerald-800/40">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-gray-800 dark:text-zinc-200 group-hover:text-primary transition">Tempat Sampah (Recycle Bin)</span>
                                <span class="block text-[10px] text-gray-400">Pulihkan atau hapus permanen data</span>
                            </div>
                        </div>
                        <span class="text-gray-300 dark:text-zinc-600 group-hover:text-primary transition text-xs font-bold">&rarr;</span>
                    </a>

                    <a href="{{ route('panduan.manage') }}" class="group flex items-center justify-between p-3 rounded-xl border border-gray-100 dark:border-zinc-800 hover:border-emerald-500/40 hover:bg-emerald-50/20 dark:hover:bg-emerald-950/10 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-100/60 dark:border-emerald-800/40">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-gray-800 dark:text-zinc-200 group-hover:text-primary transition">Kelola Panduan & SOP</span>
                                <span class="block text-[10px] text-gray-400">Update konten panduan publik dan internal</span>
                            </div>
                        </div>
                        <span class="text-gray-300 dark:text-zinc-600 group-hover:text-primary transition text-xs font-bold">&rarr;</span>
                    </a>
                </div>
            </div>

        </div>

    </div>

    {{-- Baris Bawah: Pengajuan Kampus & Audit Log Sistem --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        
        {{-- Pengajuan Terkini Seluruh Kampus --}}
        <div class="bg-white dark:bg-zinc-900 rounded-2xl p-5 sm:p-6 border border-gray-200/70 dark:border-zinc-800 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-zinc-800">
                    <div>
                        <h2 class="text-sm font-bold text-gray-900 dark:text-zinc-100 tracking-tight">Pengajuan Perubahan Terkini</h2>
                        <p class="text-[11px] text-gray-400 dark:text-zinc-500">Permohonan reschedule dari seluruh dosen kampus</p>
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
                <a href="{{ route('pengajuan.index') }}" class="text-xs font-bold text-primary dark:text-emerald-400 hover:underline">
                    Kelola Seluruh Pengajuan &rarr;
                </a>
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
                    <a href="{{ route('activitylog.index') }}" class="text-xs font-bold text-primary dark:text-emerald-400 hover:underline">
                        Lihat Log &rarr;
                    </a>
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
                <a href="{{ route('activitylog.index') }}" class="text-xs font-bold text-primary dark:text-emerald-400 hover:underline">
                    Buka Riwayat Audit Penuh &rarr;
                </a>
            </div>
        </div>

    </div>

</div>

