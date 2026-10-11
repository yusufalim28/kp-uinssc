<div class="max-w-3xl mx-auto space-y-6 pb-10">

    <a href="{{ route('jadwal.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-primary transition font-medium">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali ke Daftar Jadwal
    </a>

    <div class="bg-white border border-gray-100 shadow-xl rounded-3xl overflow-hidden relative">
        <div class="absolute top-0 left-0 w-full h-3 bg-gradient-to-r from-primary to-primary-dark"></div>
        <div class="bg-primary/5 px-8 py-8 border-b border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 pt-10">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $jadwal->mataKuliah->nama_mk }}</h1>
                <div class="flex items-center gap-3 mt-2 text-sm text-gray-600">
                    <span class="bg-white border border-gray-200 px-2.5 py-1 rounded-md font-semibold text-gray-700 shadow-sm">{{ $jadwal->mataKuliah->kode_mk }}</span>
                    <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg> {{ $jadwal->mataKuliah->sks }} SKS</span>
                </div>
            </div>
            <div class="text-right">
                <span class="inline-flex items-center gap-1.5 bg-primary text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    {{ $jadwal->hari }}
                </span>
            </div>
        </div>

        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
            <div>
                <dt class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Kelas</dt>
                <dd class="font-medium text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    {{ $jadwal->kelas->nama_kelas }}
                </dd>
            </div>
            
            <div>
                <dt class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Dosen Pengampu</dt>
                <dd class="font-medium text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    {{ $jadwal->dosen->nama }}
                </dd>
            </div>
            
            <div>
                <dt class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Jam Pelaksanaan</dt>
                <dd class="font-medium text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ \Illuminate\Support\Str::limit($jadwal->jam_mulai, 5, '') }} – {{ \Illuminate\Support\Str::limit($jadwal->jam_selesai, 5, '') }} WIB
                </dd>
            </div>
            
            <div>
                <dt class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Ruang Praktikum</dt>
                <dd class="font-medium text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    {{ $jadwal->ruangPraktikum->nama_ruang }}
                </dd>
            </div>

            <div class="md:col-span-2">
                <dt class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Tahun Akademik</dt>
                <dd class="font-medium text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
                    {{ $jadwal->tahunAkademik->nama }}
                </dd>
            </div>

            @if ($jadwal->keterangan)
                <div class="md:col-span-2 bg-gray-50 p-5 rounded-xl border border-gray-100 mt-2">
                    <dt class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Keterangan Tambahan
                    </dt>
                    <dd class="text-gray-700 leading-relaxed">{{ $jadwal->keterangan }}</dd>
                </div>
            @endif
        </div>
    </div>
</div>
