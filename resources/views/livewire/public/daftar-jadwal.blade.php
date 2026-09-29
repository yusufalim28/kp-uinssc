<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-dark">Daftar Jadwal Praktikum</h1>
        <p class="text-dark/60 text-sm mt-1">Gunakan filter di bawah untuk mempersempit pencarian.</p>
    </div>

    {{-- Form filter --}}
    <div class="bg-white border border-dark/10 rounded-xl p-4 grid grid-cols-1 md:grid-cols-5 gap-3">
        <select wire:model.live="programStudiId" class="rounded-lg border-dark/20 text-sm">
            <option value="">Semua Program Studi</option>
            @foreach ($programStudi as $ps)
                <option value="{{ $ps->id }}">{{ $ps->nama_program_studi }}</option>
            @endforeach
        </select>

        <select wire:model.live="mataKuliahId" class="rounded-lg border-dark/20 text-sm">
            <option value="">Semua Mata Kuliah</option>
            @foreach ($mataKuliah as $mk)
                <option value="{{ $mk->id }}">{{ $mk->nama_mk }}</option>
            @endforeach
        </select>

        {{-- <select wire:model.live="dosenId" class="rounded-lg border-dark/20 text-sm">
            <option value="">Semua Dosen</option>
            @foreach ($dosen as $d)
                <option value="{{ $d->id }}">{{ $d->nama }}</option>
            @endforeach
        </select> --}}

        <select wire:model.live="hari" class="rounded-lg border-dark/20 text-sm">
            <option value="">Semua Hari</option>
            @foreach ($hariList as $h)
                <option value="{{ $h }}">{{ $h }}</option>
            @endforeach
        </select>

        <button wire:click="resetFilter"
                class="border border-dark/20 rounded-lg text-sm text-dark/70 hover:bg-dark/5 transition">
            Reset Filter
        </button>
    </div>

    {{-- Loading indicator saat filter berjalan --}}
    <div wire:loading class="text-sm text-dark/50">Memuat data...</div>

    {{-- Tabel hasil --}}
    <div class="bg-white border border-dark/10 rounded-xl overflow-x-auto" wire:loading.class="opacity-50">
        <table class="w-full text-sm">
            <thead class="bg-dark text-white text-left">
                <tr>
                    <th class="px-4 py-3">Mata Kuliah</th>
                    <th class="px-4 py-3">Kelas</th>
                    {{-- <th class="px-4 py-3">Dosen</th> --}}
                    <th class="px-4 py-3">Hari / Jam</th>
                    <th class="px-4 py-3">Ruang</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark/10">
                @forelse ($jadwal as $j)
                    <tr class="hover:bg-primary/5">
                        <td class="px-4 py-3 font-medium text-dark">{{ $j->mataKuliah->nama_mk }}</td>
                        <td class="px-4 py-3 text-dark/70">{{ $j->kelas->nama_kelas }}</td>
                        <td class="px-4 py-3 text-dark/70">{{ $j->dosen->nama }}</td>
                        <td class="px-4 py-3 text-dark/70">
                            {{ $j->hari }}, {{ \Illuminate\Support\Str::limit($j->jam_mulai, 5, '') }}–{{ \Illuminate\Support\Str::limit($j->jam_selesai, 5, '') }}
                        </td>
                        <td class="px-4 py-3 text-dark/70">{{ $j->ruangPraktikum->nama_ruang }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('jadwal.detail', $j) }}" class="text-primary-dark font-medium hover:underline">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-dark/50">
                            Tidak ada jadwal yang sesuai dengan filter.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $jadwal->links() }}

</div>
