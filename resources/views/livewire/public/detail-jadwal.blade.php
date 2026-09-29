<div class="max-w-2xl mx-auto space-y-6">

    <a href="{{ route('jadwal.index') }}" class="text-sm text-dark/60 hover:text-primary-dark">
        &larr; Kembali ke Daftar Jadwal
    </a>

    <div class="bg-white border border-dark/10 rounded-2xl overflow-hidden">
        <div class="bg-dark text-white px-6 py-5">
            <h1 class="text-xl font-bold">{{ $jadwal->mataKuliah->nama_mk }}</h1>
            <p class="text-white/60 text-sm">{{ $jadwal->mataKuliah->kode_mk }} &middot; {{ $jadwal->mataKuliah->sks }} SKS</p>
        </div>

        <dl class="divide-y divide-dark/10">
            <div class="px-6 py-4 flex justify-between">
                <dt class="text-dark/60 text-sm">Kelas</dt>
                <dd class="font-medium text-dark">{{ $jadwal->kelas->nama_kelas }}</dd>
            </div>
            <div class="px-6 py-4 flex justify-between">
                <dt class="text-dark/60 text-sm">Dosen Pengampu</dt>
                <dd class="font-medium text-dark">{{ $jadwal->dosen->nama }}</dd>
            </div>
            <div class="px-6 py-4 flex justify-between">
                <dt class="text-dark/60 text-sm">Hari</dt>
                <dd class="font-medium text-dark">{{ $jadwal->hari }}</dd>
            </div>
            <div class="px-6 py-4 flex justify-between">
                <dt class="text-dark/60 text-sm">Jam</dt>
                <dd class="font-medium text-dark">
                    {{ \Illuminate\Support\Str::limit($jadwal->jam_mulai, 5, '') }}–{{ \Illuminate\Support\Str::limit($jadwal->jam_selesai, 5, '') }}
                </dd>
            </div>
            <div class="px-6 py-4 flex justify-between">
                <dt class="text-dark/60 text-sm">Ruang</dt>
                <dd class="font-medium text-dark">{{ $jadwal->ruangPraktikum->nama_ruang }}</dd>
            </div>
            <div class="px-6 py-4 flex justify-between">
                <dt class="text-dark/60 text-sm">Tahun Akademik</dt>
                <dd class="font-medium text-dark">{{ $jadwal->tahunAkademik->nama }}</dd>
            </div>
            @if ($jadwal->keterangan)
                <div class="px-6 py-4">
                    <dt class="text-dark/60 text-sm mb-1">Keterangan</dt>
                    <dd class="text-dark">{{ $jadwal->keterangan }}</dd>
                </div>
            @endif
        </dl>
    </div>

</div>
