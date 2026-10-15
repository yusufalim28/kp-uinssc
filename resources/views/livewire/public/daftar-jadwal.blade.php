<div class="space-y-6 pb-10">

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Daftar Jadwal Praktikum</h1>
            <p class="text-gray-500 text-sm mt-1">Gunakan filter di bawah untuk mempersempit pencarian.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('jadwal.export.pdf', array_filter([
                    'program_studi_id' => $programStudiId,
                    'mata_kuliah_id'   => $mataKuliahId,
                    'dosen_id'         => $dosenId,
                    'hari'             => $hari,
                ])) }}"
               target="_blank"
               class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium border border-gray-200 rounded-xl text-gray-700 bg-white hover:bg-gray-50 hover:text-red-600 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Unduh PDF
            </a>
            <a href="{{ route('jadwal.export.excel', array_filter([
                    'program_studi_id' => $programStudiId,
                    'mata_kuliah_id'   => $mataKuliahId,
                    'dosen_id'         => $dosenId,
                    'hari'             => $hari,
                ])) }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium border border-gray-200 rounded-xl text-gray-700 bg-white hover:bg-gray-50 hover:text-green-600 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Unduh Excel
            </a>
        </div>
    </div>

    {{-- Form filter --}}
    <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-5 grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Program Studi</label>
            <select wire:model.live="programStudiId" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-primary focus:ring-primary/20 text-sm py-2.5 transition">
                <option value="">Semua Program Studi</option>
                @foreach ($programStudi as $ps)
                    <option value="{{ $ps->id }}">{{ $ps->nama_program_studi }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Mata Kuliah</label>
            <select wire:model.live="mataKuliahId" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-primary focus:ring-primary/20 text-sm py-2.5 transition">
                <option value="">Semua Mata Kuliah</option>
                @foreach ($mataKuliah as $mk)
                    <option value="{{ $mk->id }}">{{ $mk->nama_mk }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Hari</label>
            <select wire:model.live="hari" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-primary focus:ring-primary/20 text-sm py-2.5 transition">
                <option value="">Semua Hari</option>
                @foreach ($hariList as $h)
                    <option value="{{ $h }}">{{ $h }}</option>
                @endforeach
            </select>
        </div>

        <div class="md:col-span-2 flex items-center justify-end md:justify-start">
            <button wire:click="resetFilter"
                    class="px-5 py-2.5 bg-gray-100 text-gray-600 hover:bg-gray-200 hover:text-gray-900 rounded-xl font-medium text-sm transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Reset Filter
            </button>
            
            {{-- Loading indicator saat filter berjalan --}}
            <div wire:loading class="ml-4 text-sm text-primary font-medium flex items-center gap-2">
                <svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memuat...
            </div>
        </div>
    </div>

    {{-- Tabel hasil --}}
    <div wire:loading.class="opacity-60 transition-opacity">

        {{-- Desktop Table (hidden on mobile) --}}
        <div class="hidden md:block bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-primary text-white text-left">
                    <tr>
                        <th class="px-6 py-4 font-semibold rounded-tl-2xl">Mata Kuliah</th>
                        <th class="px-6 py-4 font-semibold">Kelas</th>
                        <th class="px-6 py-4 font-semibold">Hari / Jam</th>
                        <th class="px-6 py-4 font-semibold">Ruang</th>
                        <th class="px-6 py-4 font-semibold rounded-tr-2xl text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($jadwal as $j)
                        <tr class="hover:bg-primary/5 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $j->mataKuliah->nama_mk }}</div>
                                <div class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    {{ $j->dosen->nama }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                <span class="bg-gray-100 text-gray-700 px-2.5 py-1 rounded-md text-xs font-semibold">{{ $j->kelas->nama_kelas }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $j->hari }}</div>
                                <div class="text-xs text-gray-500">{{ \Illuminate\Support\Str::limit($j->jam_mulai, 5, '') }} – {{ \Illuminate\Support\Str::limit($j->jam_selesai, 5, '') }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-700 font-medium">{{ $j->ruangPraktikum->nama_ruang }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('jadwal.detail', $j) }}" class="inline-flex items-center justify-center px-4 py-2 bg-primary/10 text-primary hover:bg-primary hover:text-white font-semibold rounded-xl transition text-xs">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 text-gray-400 mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <p class="text-gray-500 font-medium text-base">Tidak ada jadwal yang sesuai dengan filter.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards Grid (hidden on desktop) --}}
        <div class="md:hidden grid grid-cols-1 sm:grid-cols-2 gap-3.5">
            @forelse ($jadwal as $j)
                <a href="{{ route('jadwal.detail', $j) }}" class="block bg-white border border-gray-100 shadow-xs rounded-xl p-3.5 hover:border-primary hover:shadow-md transition flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <span class="px-2 py-0.5 text-xs font-bold rounded-md bg-primary/10 text-primary">
                                {{ $j->hari }}
                            </span>
                            <span class="text-xs font-semibold text-gray-500 bg-gray-50 px-2 py-0.5 rounded-md border border-gray-100">
                                {{ \Illuminate\Support\Str::limit($j->jam_mulai, 5, '') }} – {{ \Illuminate\Support\Str::limit($j->jam_selesai, 5, '') }}
                            </span>
                        </div>
                        <h3 class="font-bold text-gray-900 text-sm leading-snug">{{ $j->mataKuliah->nama_mk }}</h3>
                        <div class="mt-2 flex flex-wrap gap-1.5 text-xs">
                            <span class="px-2 py-0.5 rounded bg-gray-50 border border-gray-100 text-gray-700 font-medium">Kelas {{ $j->kelas->nama_kelas }}</span>
                            <span class="px-2 py-0.5 rounded bg-gray-50 border border-gray-100 text-gray-700 font-medium">{{ $j->ruangPraktikum->nama_ruang }}</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2 truncate flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span class="truncate">{{ $j->dosen->nama }}</span>
                        </p>
                    </div>
                    <div class="mt-3 pt-2 border-t border-gray-100 flex items-center justify-end text-xs font-semibold text-primary">
                        <span>Lihat Detail &rarr;</span>
                    </div>
                </a>
            @empty
                <div class="sm:col-span-2 bg-white rounded-xl p-8 text-center border border-gray-100">
                    <p class="text-gray-500 font-medium text-sm">Tidak ada jadwal yang sesuai dengan filter.</p>
                </div>
            @endforelse
        </div>

        @if($jadwal->hasPages())
        <div class="mt-4">
            {{ $jadwal->links() }}
        </div>
        @endif
    </div>
</div>
