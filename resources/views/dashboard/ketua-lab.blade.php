@php
    $user = auth()->user();
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

    // 1. Tentukan Ruangan di bawah Wewenang Ketua Lab
    $ruangUtama = null;
    if ($user->ruang_praktikum_id) {
        $ruangUtama = \App\Models\RuangPraktikum::with(['fakultas', 'programStudi', 'prodiPengguna'])->find($user->ruang_praktikum_id);
    }

    if (! $ruangUtama && $user->fakultas_id) {
        $ruangUtama = \App\Models\RuangPraktikum::with(['fakultas', 'programStudi', 'prodiPengguna'])
            ->where('fakultas_id', $user->fakultas_id)
            ->where('status', 'aktif')
            ->first();
    }

    if (! $ruangUtama) {
        $ruangUtama = \App\Models\RuangPraktikum::with(['fakultas', 'programStudi', 'prodiPengguna'])->where('status', 'aktif')->first();
    }

    // Koleksi ID Ruang yang dinaungi
    $managedRuangIds = collect();
    if ($user->ruang_praktikum_id) {
        $managedRuangIds->push($user->ruang_praktikum_id);
    } elseif ($user->fakultas_id) {
        $managedRuangIds = \App\Models\RuangPraktikum::where('fakultas_id', $user->fakultas_id)->pluck('id');
    } elseif ($ruangUtama) {
        $managedRuangIds->push($ruangUtama->id);
    }

    // 2. Real-time Status Sesi Lab Saat Ini
    $sesiBerjalan = \App\Models\JadwalPraktikum::with(['mataKuliah', 'kelas', 'dosen', 'ruangPraktikum'])
        ->where('status', 'aktif')
        ->where('hari', $hariIni)
        ->whereTime('jam_mulai', '<=', $jamSekarang)
        ->whereTime('jam_selesai', '>=', $jamSekarang)
        ->whereIn('ruang_praktikum_id', $managedRuangIds)
        ->when($taAktif, fn ($q) => $q->where('tahun_akademik_id', $taAktif->id))
        ->first();

    // 3. Total Jadwal Aktif Semester Ini
    $jadwalLabQuery = \App\Models\JadwalPraktikum::where('status', 'aktif')
        ->whereIn('ruang_praktikum_id', $managedRuangIds);
    if ($taAktif) {
        $jadwalLabQuery->where('tahun_akademik_id', $taAktif->id);
    }
    $totalJadwalLab = $jadwalLabQuery->count();
    $totalDosenLab = (clone $jadwalLabQuery)->distinct('dosen_id')->count('dosen_id');

    // 4. Agenda Sesi Hari Ini
    $jadwalHariIniQuery = \App\Models\JadwalPraktikum::with(['mataKuliah', 'kelas', 'dosen', 'ruangPraktikum'])
        ->where('status', 'aktif')
        ->where('hari', $hariIni)
        ->whereIn('ruang_praktikum_id', $managedRuangIds);
    if ($taAktif) {
        $jadwalHariIniQuery->where('tahun_akademik_id', $taAktif->id);
    }
    $jadwalHariIni = $jadwalHariIniQuery->orderBy('jam_mulai')->get();
    $sesiHariIniCount = $jadwalHariIni->count();
    $sesiSelesaiCount = $jadwalHariIni->filter(fn ($j) => $j->jam_selesai < $jamSekarang)->count();
    $sesiAkanDatangCount = $jadwalHariIni->filter(fn ($j) => $j->jam_mulai > $jamSekarang)->count();

    // 5. Pengajuan Perubahan Terkait Lab Ini
    $pengajuanLabQuery = \App\Models\PengajuanPerubahan::with(['dosen', 'jadwalPraktikum.mataKuliah', 'jadwalPraktikum.ruangPraktikum'])
        ->whereHas('jadwalPraktikum', fn ($q) => $q->whereIn('ruang_praktikum_id', $managedRuangIds));
    $pendingPengajuanLabCount = (clone $pengajuanLabQuery)->where('status', 'menunggu')->count();
    $pengajuanTerbaru = (clone $pengajuanLabQuery)->latest()->take(4)->get();
@endphp

<div class="space-y-6">

    {{-- Welcome Header Ketua Lab --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex flex-wrap items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-md text-[10px] font-extrabold uppercase tracking-wider bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                    Ketua Laboratorium
                </span>
                <span class="text-xs text-gray-400">•</span>
                <span class="text-xs font-bold text-gray-700 dark:text-zinc-300">
                    {{ $ruangUtama?->nama_ruang ?? 'Laboratorium' }}
                </span>
                @if($ruangUtama?->fakultas)
                    <span class="text-xs text-gray-400">•</span>
                    <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">
                        {{ $ruangUtama->fakultas->kode_fakultas }}
                    </span>
                @endif
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-zinc-100 tracking-tight mt-1">
                Dashboard {{ $ruangUtama?->nama_ruang ?? 'Ketua Laboratorium' }}
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-zinc-400 mt-0.5">
                Selamat datang, <strong class="text-gray-900 dark:text-zinc-100">{{ $user->name }}</strong> • Tahun Akademik {{ $taAktif ? $taAktif->tahun . ' (' . ucfirst($taAktif->semester) . ')' : 'Tidak Diketahui' }}
            </p>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap">
            <a href="{{ route('jadwal.manage') }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-500 rounded-xl transition shadow-xs shadow-emerald-600/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Kelola / Plotting Jadwal</span>
            </a>
        </div>
    </div>

    {{-- 4 Stat Cards Khusus Ketua Lab --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- 1. Status Real-Time Lab --}}
        <div class="relative overflow-hidden bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl p-5 shadow-xs transition hover:border-emerald-500/30">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 dark:text-zinc-400">Status Lab Saat Ini</span>
                <div class="w-9 h-9 rounded-xl flex items-center justify-center {{ $sesiBerjalan ? 'bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400' : 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
            </div>
            <div class="mt-3">
                @if($sesiBerjalan)
                    <div class="flex items-center gap-2">
                        <span class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                        </span>
                        <span class="text-base font-extrabold text-amber-700 dark:text-amber-400">Sedang Digunakan</span>
                    </div>
                    <p class="text-xs font-medium text-gray-600 dark:text-zinc-300 mt-1 line-clamp-1">
                        {{ $sesiBerjalan->mataKuliah->nama_mata_kuliah }}
                    </p>
                    <p class="text-[11px] text-gray-400 dark:text-zinc-500">
                        {{ substr($sesiBerjalan->jam_mulai, 0, 5) }} - {{ substr($sesiBerjalan->jam_selesai, 0, 5) }} WIB ({{ $sesiBerjalan->kelas?->nama_kelas }})
                    </p>
                @else
                    <div class="flex items-center gap-2">
                        <span class="relative flex h-3 w-3">
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                        </span>
                        <span class="text-base font-extrabold text-emerald-700 dark:text-emerald-400">Kosong / Siap Pakai</span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">
                        Kapasitas {{ $ruangUtama?->kapasitas ?? 0 }} Mahasiswa
                    </p>
                @endif
            </div>
        </div>

        {{-- 2. Total Jadwal Lab --}}
        <div class="relative overflow-hidden bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl p-5 shadow-xs transition hover:border-emerald-500/30">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 dark:text-zinc-400">Total Jadwal Praktikum</span>
                <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-blue-100 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-gray-900 dark:text-zinc-100 tracking-tight">
                    {{ $totalJadwalLab }}
                </div>
                <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">
                    Diampu oleh <strong class="text-gray-900 dark:text-zinc-200">{{ $totalDosenLab }} Dosen / Tutor</strong>
                </p>
            </div>
        </div>

        {{-- 3. Sesi Hari Ini --}}
        <div class="relative overflow-hidden bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl p-5 shadow-xs transition hover:border-emerald-500/30">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 dark:text-zinc-400">Sesi Hari {{ $hariIni }}</span>
                <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-purple-100 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-gray-900 dark:text-zinc-100 tracking-tight">
                    {{ $sesiHariIniCount }} <span class="text-xs font-normal text-gray-400">sesi</span>
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-zinc-400 mt-1">
                    <span>{{ $sesiAkanDatangCount }} akan datang</span>
                    <span>•</span>
                    <span>{{ $sesiSelesaiCount }} selesai</span>
                </div>
            </div>
        </div>

        {{-- 4. Pengajuan Menunggu --}}
        <div class="relative overflow-hidden bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl p-5 shadow-xs transition hover:border-emerald-500/30">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 dark:text-zinc-400">Pengajuan Perubahan</span>
                <div class="w-9 h-9 rounded-xl flex items-center justify-center {{ $pendingPengajuanLabCount > 0 ? 'bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400' : 'bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-zinc-400' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="flex items-baseline gap-2">
                    <span class="text-2xl font-black {{ $pendingPengajuanLabCount > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-gray-900 dark:text-zinc-100' }} tracking-tight">
                        {{ $pendingPengajuanLabCount }}
                    </span>
                    <span class="text-xs font-semibold text-gray-400">menunggu konfirmasi</span>
                </div>
                <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">
                    @if($pendingPengajuanLabCount > 0)
                        <a href="{{ route('pengajuan.index') }}" class="font-bold text-rose-600 dark:text-rose-400 hover:underline">Periksa sekarang &rarr;</a>
                    @else
                        Tidak ada pengajuan tertunda
                    @endif
                </p>
            </div>
        </div>

    </div>

    {{-- Detail Ruang Lab & Agenda Hari Ini (2 Kolom) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom Kiri: Profil & Spesifikasi Laboratorium (1 Kolom) --}}
        <div class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl p-5 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-zinc-800">
                <h3 class="text-sm font-extrabold text-gray-900 dark:text-zinc-100 uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Profil Laboratorium
                </h3>
                @if($ruangUtama)
                    <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider {{ $ruangUtama->status === 'aktif' ? 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400' : 'bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400' }}">
                        {{ $ruangUtama->status }}
                    </span>
                @endif
            </div>

            @if($ruangUtama)
                {{-- Foto Laboratorium --}}
                <div class="relative w-full h-44 rounded-xl overflow-hidden bg-zinc-100 dark:bg-zinc-800 border border-gray-100 dark:border-zinc-700 group">
                    @if($ruangUtama->foto_url)
                        <img src="{{ $ruangUtama->foto_url }}" alt="{{ $ruangUtama->nama_ruang }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 dark:text-zinc-500 bg-gradient-to-br from-emerald-900/10 via-zinc-800/10 to-teal-900/20">
                            <svg class="w-12 h-12 stroke-[1.2] text-emerald-600/60 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                            <span class="text-xs font-semibold text-gray-500 dark:text-zinc-400">Belum ada foto laboratorium</span>
                            <span class="text-[10px] text-gray-400 dark:text-zinc-500">Unggah foto di Master Ruang Praktikum</span>
                        </div>
                    @endif
                    <div class="absolute bottom-2 left-2 bg-black/60 backdrop-blur-xs text-white text-[10px] font-mono px-2 py-0.5 rounded-md font-bold">
                        {{ $ruangUtama->kode_ruang }}
                    </div>
                </div>

                {{-- Spesifikasi Ringkas --}}
                <div class="space-y-2.5 text-xs">
                    <div class="flex items-center justify-between py-1.5 border-b border-gray-100 dark:border-zinc-800">
                        <span class="text-gray-500 dark:text-zinc-400">Nama Laboratorium</span>
                        <span class="font-bold text-gray-900 dark:text-zinc-100 text-right">{{ $ruangUtama->nama_ruang }}</span>
                    </div>
                    <div class="flex items-center justify-between py-1.5 border-b border-gray-100 dark:border-zinc-800">
                        <span class="text-gray-500 dark:text-zinc-400">Lokasi / Gedung</span>
                        <span class="font-semibold text-gray-700 dark:text-zinc-300 text-right">{{ $ruangUtama->lokasi ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-1.5 border-b border-gray-100 dark:border-zinc-800">
                        <span class="text-gray-500 dark:text-zinc-400">Kapasitas Tempat Duduk</span>
                        <span class="font-extrabold text-emerald-600 dark:text-emerald-400">{{ $ruangUtama->kapasitas }} Mahasiswa</span>
                    </div>
                    <div class="flex items-center justify-between py-1.5 border-b border-gray-100 dark:border-zinc-800">
                        <span class="text-gray-500 dark:text-zinc-400">Tipe Ruang</span>
                        <span class="font-semibold text-gray-700 dark:text-zinc-300 capitalize">{{ $ruangUtama->tipe_ruang }}</span>
                    </div>
                    <div class="flex items-center justify-between py-1.5">
                        <span class="text-gray-500 dark:text-zinc-400">Cakupan Akses</span>
                        <span class="font-semibold text-gray-700 dark:text-zinc-300">{{ str_replace('_', ' ', ucfirst($ruangUtama->cakupan_akses)) }}</span>
                    </div>
                </div>

                <div class="pt-2">
                    <a href="{{ route('ruang.index') }}"
                        class="w-full flex items-center justify-center gap-2 px-3 py-2 text-xs font-bold text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/40 hover:bg-emerald-100 dark:hover:bg-emerald-950/70 border border-emerald-200 dark:border-emerald-800 rounded-xl transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        <span>Edit Profil & Foto Laboratorium</span>
                    </a>
                </div>
            @else
                <p class="text-xs text-gray-500 text-center py-6">Belum ada laboratorium yang ditetapkan ke akun ini.</p>
            @endif
        </div>

        {{-- Kolom Kanan: Agenda Praktikum Hari Ini (2 Kolom) --}}
        <div class="lg:col-span-2 bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl p-5 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-zinc-800">
                    <div>
                        <h3 class="text-sm font-extrabold text-gray-900 dark:text-zinc-100 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Agenda Praktikum Hari {{ $hariIni }}
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">Timeline sesi terjadwal di laboratorium hari ini</p>
                    </div>
                    <span class="text-xs font-mono font-bold text-gray-600 dark:text-zinc-400 bg-gray-100 dark:bg-zinc-800 px-2.5 py-1 rounded-lg">
                        {{ now()->format('d M Y') }}
                    </span>
                </div>

                @if($jadwalHariIni->isNotEmpty())
                    <div class="divide-y divide-gray-100 dark:divide-zinc-800 mt-2">
                        @foreach($jadwalHariIni as $j)
                            @php
                                $isNow = ($j->jam_mulai <= $jamSekarang && $j->jam_selesai >= $jamSekarang);
                                $isDone = ($j->jam_selesai < $jamSekarang);
                            @endphp
                            <div class="py-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3 {{ $isNow ? 'bg-emerald-50/60 dark:bg-emerald-950/20 -mx-2 px-2 rounded-xl' : '' }}">
                                <div class="flex items-start gap-3">
                                    <div class="shrink-0 w-20 text-center font-mono py-1 rounded-lg {{ $isNow ? 'bg-emerald-600 text-white font-bold' : ($isDone ? 'bg-gray-100 dark:bg-zinc-800 text-gray-400' : 'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 font-semibold') }}">
                                        <div class="text-xs">{{ substr($j->jam_mulai, 0, 5) }}</div>
                                        <div class="text-[10px] opacity-80">{{ substr($j->jam_selesai, 0, 5) }}</div>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h4 class="text-xs sm:text-sm font-bold text-gray-900 dark:text-zinc-100">
                                                {{ $j->mataKuliah->nama_mata_kuliah }}
                                            </h4>
                                            <span class="px-2 py-0.2 rounded text-[10px] font-black uppercase tracking-wider bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300">
                                                {{ $j->kelas?->nama_kelas }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-zinc-400 mt-0.5">
                                            <span>{{ $j->dosen?->nama_dosen ?? 'Dosen Belum Ditentukan' }}</span>
                                            <span>•</span>
                                            <span>{{ $j->ruangPraktikum->nama_ruang }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="shrink-0 self-start sm:self-center">
                                    @if($isNow)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-emerald-100 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-300">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Berlangsung
                                        </span>
                                    @elseif($isDone)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-gray-100 dark:bg-zinc-800 text-gray-500 dark:text-zinc-400">
                                            Selesai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300">
                                            Akan Datang
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-12 flex flex-col items-center justify-center text-center">
                        <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-zinc-800 flex items-center justify-center text-gray-400 mb-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <h4 class="text-sm font-bold text-gray-900 dark:text-zinc-100">Tidak Ada Jadwal Hari Ini</h4>
                        <p class="text-xs text-gray-500 dark:text-zinc-400 max-w-sm mt-0.5">Laboratorium sedang tidak digunakan untuk sesi terjadwal pada hari {{ $hariIni }}.</p>
                    </div>
                @endif
            </div>

            <div class="pt-3 border-t border-gray-100 dark:border-zinc-800 flex items-center justify-between text-xs">
                <span class="text-gray-500 dark:text-zinc-400">Ingin melihat jadwal hari lain?</span>
                <a href="{{ route('jadwal.manage') }}" class="font-bold text-emerald-600 dark:text-emerald-400 hover:underline">
                    Buka Kelola Jadwal Lengkap &rarr;
                </a>
            </div>
        </div>

    </div>

    {{-- Akses Cepat Wewenang CRUD Master Data Ketua Lab (Poin 3) --}}
    <div>
        <div class="flex items-center justify-between mb-3">
            <div>
                <h3 class="text-sm font-black text-gray-900 dark:text-zinc-100 uppercase tracking-wider">
                    Akses Cepat Pengelolaan Operasional Lab
                </h3>
                <p class="text-xs text-gray-500 dark:text-zinc-400">Hak akses penuh Ketua Lab untuk master data kelas, mata kuliah, dosen/tutor, dan ruangan</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            {{-- Master Ruang Praktikum --}}
            <a href="{{ route('ruang.index') }}"
                class="group bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl p-4 shadow-xs hover:border-emerald-500 transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 flex items-center justify-center shrink-0 group-hover:scale-105 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <h4 class="text-xs font-bold text-gray-900 dark:text-zinc-100 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition truncate">
                            Ruang Praktikum
                        </h4>
                        <p class="text-[11px] text-gray-500 dark:text-zinc-400 truncate">Kelola profil, foto, & kapasitas</p>
                    </div>
                </div>
            </a>

            {{-- Master Kelas --}}
            <a href="{{ route('kelas.index') }}"
                class="group bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl p-4 shadow-xs hover:border-emerald-500 transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 flex items-center justify-center shrink-0 group-hover:scale-105 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <h4 class="text-xs font-bold text-gray-900 dark:text-zinc-100 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition truncate">
                            Master Kelas
                        </h4>
                        <p class="text-[11px] text-gray-500 dark:text-zinc-400 truncate">Data rombel mahasiswa lab</p>
                    </div>
                </div>
            </a>

            {{-- Master Mata Kuliah --}}
            <a href="{{ route('matakuliah.index') }}"
                class="group bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl p-4 shadow-xs hover:border-emerald-500 transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 flex items-center justify-center shrink-0 group-hover:scale-105 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <div class="min-w-0">
                        <h4 class="text-xs font-bold text-gray-900 dark:text-zinc-100 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition truncate">
                            Mata Kuliah Praktikum
                        </h4>
                        <p class="text-[11px] text-gray-500 dark:text-zinc-400 truncate">Silabus, SKS, & modul ajar</p>
                    </div>
                </div>
            </a>

            {{-- Master Dosen / Tutor --}}
            <a href="{{ route('dosen.index') }}"
                class="group bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl p-4 shadow-xs hover:border-emerald-500 transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 flex items-center justify-center shrink-0 group-hover:scale-105 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <h4 class="text-xs font-bold text-gray-900 dark:text-zinc-100 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition truncate">
                            Dosen / Tutor
                        </h4>
                        <p class="text-[11px] text-gray-500 dark:text-zinc-400 truncate">Pengampu praktikum lab</p>
                    </div>
                </div>
            </a>

        </div>
    </div>

    {{-- Pengajuan Perubahan Terkini --}}
    @if($pengajuanTerbaru->isNotEmpty())
        <div class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl p-5 shadow-xs">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-zinc-800">
                <div>
                    <h3 class="text-sm font-extrabold text-gray-900 dark:text-zinc-100 uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        Pengajuan Perubahan Jadwal Terkini
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">Permohonan reschedule atau penggantian sesi oleh dosen pengampu</p>
                </div>
                <a href="{{ route('pengajuan.index') }}" class="text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:underline">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="divide-y divide-gray-100 dark:divide-zinc-800 mt-2">
                @foreach($pengajuanTerbaru as $p)
                    <div class="py-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-xs sm:text-sm text-gray-900 dark:text-zinc-100">
                                    {{ $p->jadwalPraktikum?->mataKuliah?->nama_mata_kuliah }}
                                </span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wider
                                    {{ $p->status === 'menunggu' ? 'bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300' :
                                       ($p->status === 'disetujui' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300' : 'bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300') }}">
                                    {{ ucfirst($p->status) }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">
                                Diajukan oleh <strong class="text-gray-700 dark:text-zinc-300">{{ $p->dosen?->nama_dosen }}</strong> • Alasan: {{ Str::limit($p->alasan, 70) }}
                            </p>
                        </div>
                        <div class="shrink-0">
                            <a href="{{ route('pengajuan.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-gray-700 dark:text-zinc-200 bg-gray-100 dark:bg-zinc-800 hover:bg-gray-200 dark:hover:bg-zinc-700 rounded-lg transition">
                                <span>Tinjau</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>

