<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <style>
        @media print {
            @page {
                size: A4 landscape;
                margin: 5mm 6mm;
            }
            body {
                background: white !important;
                color: black !important;
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            header, nav, footer, .print\:hidden {
                display: none !important;
            }
            .max-w-7xl {
                max-width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            table {
                width: 100% !important;
                table-layout: fixed !important;
                border-collapse: collapse !important;
            }
            th, td {
                border: 1px solid #000 !important;
                page-break-inside: avoid !important;
                word-break: break-word !important;
                padding: 2px 1px !important;
            }
        }
    </style>

    {{-- Breadcrumb & Title --}}
    <div class="print:hidden flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-xs text-gray-500 dark:text-zinc-400 mb-2">
                <a href="{{ route('home') }}" class="hover:text-primary transition">Beranda</a>
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-gray-900 dark:text-zinc-200 font-medium">Jadwal Per Ruangan</span>
            </nav>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-zinc-100 tracking-tight">
                    Jadwal Pemakaian Ruang & Lab
                </h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                    Format Matriks Resmi
                </span>
            </div>
            <p class="text-sm text-gray-600 dark:text-zinc-400 mt-1">
                Visualisasi jadwal penggunaan laboratorium dan ruang praktikum per sesi waktu perkuliahan (Senin – Jum'at).
            </p>
        </div>

        @if($selectedRuang && $hasSearched)
            <div class="flex items-center gap-2">
                <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gray-900 dark:bg-zinc-100 text-white dark:text-zinc-900 text-xs font-semibold hover:bg-gray-800 dark:hover:bg-zinc-200 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Cetak Lembar Jadwal (Print / PDF)</span>
                </button>
            </div>
        @endif
    </div>

    {{-- Filter Bar --}}
    <div class="print:hidden bg-white dark:bg-zinc-900 rounded-2xl p-5 border border-gray-200/80 dark:border-zinc-800 shadow-xs space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            {{-- Tahun Akademik --}}
            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 uppercase tracking-wider mb-1.5">
                    Tahun Akademik
                </label>
                <select wire:model.live="tahunAkademikId" class="w-full rounded-xl border border-gray-300 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 text-sm px-3.5 py-2.5 text-gray-900 dark:text-zinc-100 focus:bg-white dark:focus:bg-zinc-700 focus:border-primary focus:ring-2 focus:ring-primary/20 transition">
                    @foreach($tahunAkademikOptions as $ta)
                        <option value="{{ $ta->id }}">
                            {{ $ta->nama }} — {{ $ta->semester }} {{ $ta->is_aktif ? ' (Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Fakultas --}}
            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 uppercase tracking-wider mb-1.5">
                    Fakultas
                </label>
                <select wire:model.live="fakultasId" class="w-full rounded-xl border border-gray-300 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 text-sm px-3.5 py-2.5 text-gray-900 dark:text-zinc-100 focus:bg-white dark:focus:bg-zinc-700 focus:border-primary focus:ring-2 focus:ring-primary/20 transition">
                    <option value="">-- Semua Fakultas / Fasilitas Bersama --</option>
                    @foreach($fakultasList as $fak)
                        <option value="{{ $fak->id }}">{{ $fak->nama_fakultas }} ({{ $fak->kode_fakultas }})</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Ruangan --}}
            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 uppercase tracking-wider mb-1.5">
                    Pilih Ruang Praktikum / Laboratorium *
                </label>
                <select wire:model.live="ruangPraktikumId" class="w-full rounded-xl border border-primary/40 dark:border-primary/50 bg-emerald-50/30 dark:bg-emerald-950/20 text-sm px-3.5 py-2.5 text-gray-900 dark:text-zinc-100 font-semibold focus:border-primary focus:ring-2 focus:ring-primary/20 transition">
                    <option value="">-- Pilih Laboratorium / Ruangan --</option>
                    @foreach($ruangList as $r)
                        <option value="{{ $r->id }}">
                            {{ $r->nama_ruang }} ({{ $r->kode_ruang }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Quick Shortcuts --}}
        <div class="pt-3 border-t border-gray-100 dark:border-zinc-800 flex flex-wrap items-center gap-2">
            <span class="text-xs text-gray-500 dark:text-zinc-400 font-medium">Pilihan Cepat:</span>
            @foreach($ruangList->take(6) as $qr)
                <button type="button" wire:click="selectRuang({{ $qr->id }})" class="px-2.5 py-1 rounded-lg text-xs font-medium transition {{ $ruangPraktikumId === $qr->id ? 'bg-primary text-white shadow-xs' : 'bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300 hover:bg-gray-200 dark:hover:bg-zinc-700' }}">
                    {{ $qr->nama_ruang }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- State 1: Belum Pilih Ruangan --}}
    @if(! $hasSearched || ! $selectedRuang)
        <div class="bg-white dark:bg-zinc-900 rounded-3xl p-10 sm:p-14 text-center border border-gray-200/80 dark:border-zinc-800 shadow-xs space-y-4">
            <div class="w-16 h-16 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-primary dark:text-emerald-400 flex items-center justify-center mx-auto border border-emerald-100 dark:border-emerald-800">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <div class="max-w-md mx-auto space-y-1.5">
                <h3 class="text-lg font-bold text-gray-900 dark:text-zinc-100">
                    Pilih Laboratorium untuk Menampilkan Jadwal
                </h3>
                <p class="text-sm text-gray-500 dark:text-zinc-400">
                    Silakan tentukan nama laboratorium atau ruang praktikum pada pilihan di atas untuk melihat lembar jadwal lengkap sesuai format tabel resmi.
                </p>
            </div>
            <div class="flex flex-wrap justify-center gap-2 pt-2">
                @php
                    $labPsiko = $ruangList->firstWhere('kode_ruang', 'LAB-PSIKO') ?? $ruangList->first();
                @endphp
                @if($labPsiko)
                    <button type="button" wire:click="selectRuang({{ $labPsiko->id }})" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-primary text-white text-xs font-semibold hover:bg-primary-dark transition shadow-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <span>Lihat Contoh: {{ $labPsiko->nama_ruang }}</span>
                    </button>
                @endif
            </div>
        </div>
    @else
        {{-- State 2: Matriks Jadwal Resmi Ruangan --}}
        <div class="space-y-6">

            {{-- Detail Header Info Ruangan (Screen Only) --}}
            <div class="print:hidden bg-linear-to-r from-emerald-50/80 via-white to-gray-50 dark:from-zinc-900 dark:via-zinc-900 dark:to-zinc-900 rounded-2xl p-4 sm:p-5 border border-emerald-100 dark:border-zinc-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2.5">
                        <span class="px-2 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider bg-primary text-white">
                            {{ $selectedRuang->kode_ruang }}
                        </span>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-zinc-100">
                            {{ $selectedRuang->nama_ruang }}
                        </h2>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-zinc-400">
                        Lokasi: <span class="font-medium text-gray-700 dark:text-zinc-300">{{ $selectedRuang->lokasi }}</span> • Kapasitas: <span class="font-medium text-gray-700 dark:text-zinc-300">{{ $selectedRuang->kapasitas }} Orang</span> • Fakultas: <span class="font-medium text-gray-700 dark:text-zinc-300">{{ $selectedRuang->fakultas?->nama_fakultas ?? 'Fasilitas Bersama Universitas' }}</span>
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 rounded-xl text-xs font-semibold bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 text-gray-800 dark:text-zinc-200">
                        Total: <strong class="text-primary">{{ $totalJadwal }}</strong> Sesi Praktikum
                    </span>
                    <a href="{{ route('jadwal.kalender', ['fakultas' => $selectedRuang->fakultas_id, 'ruang' => $selectedRuang->id]) }}" class="text-xs text-primary dark:text-emerald-400 font-semibold hover:underline">
                        Buka di Kalender &rarr;
                    </a>
                </div>
            </div>

            {{-- Lembar Matriks Resmi (Mirip Gambar 4) --}}
            <div class="bg-white dark:bg-zinc-900 border border-gray-300 dark:border-zinc-800 rounded-2xl shadow-sm p-4 sm:p-6 print:p-0 print:border-none print:shadow-none">
                
                {{-- Kop Resmi Tabel (Sesuai Gambar 4) --}}
                <div class="text-center pb-4 sm:pb-6 border-b border-gray-200 dark:border-zinc-800 print:border-b-2 print:border-black mb-3 sm:mb-4">
                    <h2 class="text-sm sm:text-base md:text-lg font-black tracking-wider uppercase text-gray-900 dark:text-zinc-100 print:text-black leading-tight">
                        JADWAL PENGGUNAAN {{ strtoupper($selectedRuang->nama_ruang) }}
                    </h2>
                    <h3 class="text-xs sm:text-sm font-bold uppercase text-gray-700 dark:text-zinc-300 print:text-black mt-0.5">
                        SEMESTER {{ strtoupper($selectedTa?->semester ?? 'GENAP') }} {{ $selectedTa?->nama ?? '2025/2026' }}
                    </h3>
                    <h4 class="text-[11px] sm:text-xs font-semibold uppercase text-gray-600 dark:text-zinc-400 print:text-black mt-0.5">
                        {{ strtoupper($selectedRuang->fakultas?->nama_fakultas ?? 'FAKULTAS USHULUDDIN DAN ADAB') }}
                    </h4>
                    <p class="text-[10px] sm:text-[11px] text-gray-500 dark:text-zinc-400 print:text-black">
                        UIN Siber Syekh Nurjati Cirebon
                    </p>
                </div>

                {{-- Tabel Grid Matriks 100% Lebar (Tanpa Scroll Horizontal, Minisize Responsif) --}}
                <div class="w-full overflow-x-hidden">
                    <table class="w-full table-fixed border-collapse border border-gray-300 dark:border-zinc-700 print:border-black text-center select-none">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-zinc-800 print:bg-gray-100 text-gray-800 dark:text-zinc-200 print:text-black font-bold">
                                <th class="border border-gray-300 dark:border-zinc-700 print:border-black py-1 px-0.5 w-9 sm:w-12 md:w-16 print:w-12 uppercase tracking-wider text-[7px] sm:text-[9px] md:text-xs print:text-[7pt] font-extrabold text-gray-900 dark:text-zinc-100 print:text-black">
                                    Hari
                                </th>
                                @foreach($timeSlots as $slot)
                                    <th class="border border-gray-300 dark:border-zinc-700 print:border-black p-0.5 text-center">
                                        <span class="block font-bold text-[6.5px] sm:text-[8px] md:text-[9.5px] print:text-[6.5pt] text-gray-900 dark:text-zinc-100 print:text-black leading-none">{{ substr($slot['start'], 0, 5) }}</span>
                                        <span class="block text-[5.5px] sm:text-[7px] md:text-[8px] print:text-[5.5pt] text-gray-400 dark:text-zinc-500 print:text-black leading-none mt-0.5 font-normal">{{ substr($slot['end'], 0, 5) }}</span>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hariList as $hari)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-zinc-800/40 transition">
                                    {{-- Kolom Hari --}}
                                    <td class="border border-gray-300 dark:border-zinc-700 print:border-black py-1.5 px-0.5 font-bold text-gray-900 dark:text-zinc-100 print:text-black text-[7.5px] sm:text-[9.5px] md:text-xs print:text-[7.5pt] text-center bg-gray-50/50 dark:bg-zinc-900/50 print:bg-white">
                                        {{ $hari }}
                                    </td>

                                    {{-- Kolom-kolom Sesi --}}
                                    @foreach($matrix[$hari] ?? [] as $cell)
                                        @if($cell['type'] === 'empty')
                                            <td colspan="{{ $cell['colspan'] }}" class="border border-gray-300 dark:border-zinc-700 print:border-black p-0 bg-white dark:bg-zinc-900 print:bg-white">
                                                {{-- Sel Kosong --}}
                                            </td>
                                        @elseif($cell['type'] === 'jadwal')
                                            @php $j = $cell['jadwal']; @endphp
                                            <td colspan="{{ $cell['colspan'] }}" class="border border-gray-400 dark:border-zinc-600 print:border-black p-0.5 sm:p-1 print:p-0.5 bg-emerald-50/95 dark:bg-emerald-950/50 print:bg-emerald-50/20 text-gray-900 dark:text-zinc-100 print:text-black align-middle text-left shadow-2xs overflow-hidden">
                                                <div class="space-y-0.5 leading-tight">
                                                    <div class="font-bold text-[7px] sm:text-[8.5px] md:text-[10px] print:text-[7pt] text-emerald-950 dark:text-emerald-200 print:text-black break-words line-clamp-2">
                                                        {{ $j->mataKuliah->nama_mk }}
                                                    </div>
                                                    @if($j->kelas_id && $j->kelas && $j->kelas->nama_kelas !== '-')
                                                        <div class="text-[6px] sm:text-[7.5px] md:text-[8.5px] print:text-[6pt] font-semibold text-emerald-800 dark:text-emerald-300 print:text-black truncate">
                                                            ({{ $j->kelas->nama_kelas }})
                                                        </div>
                                                    @endif
                                                    <div class="text-[6px] sm:text-[7px] md:text-[8px] print:text-[5.5pt] text-gray-700 dark:text-zinc-300 print:text-black italic truncate">
                                                        {{ $j->dosen->nama }}
                                                    </div>
                                                    <div class="text-[5.5px] sm:text-[6.5px] md:text-[7.5px] print:text-[5pt] text-gray-500 dark:text-zinc-400 print:text-black pt-0.5 border-t border-emerald-200/50 dark:border-emerald-800/50 print:border-black font-mono">
                                                        {{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}
                                                    </div>
                                                </div>
                                            </td>
                                        @endif
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Catatan Kaki Dokumen Resmi --}}
                <div class="pt-4 mt-3 border-t border-gray-200 dark:border-zinc-800 print:border-t-2 print:border-black flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-gray-500 dark:text-zinc-400 print:text-black">
                    <div>
                        <p class="font-medium text-gray-700 dark:text-zinc-300 print:text-black text-[10px] sm:text-xs">
                            * Hari operasional: Senin – Jum'at.
                        </p>
                        <p class="text-[9px] sm:text-[10px]">
                            Dicetak otomatis oleh Sistem Informasi Jadwal Praktikum - UIN Siber Syekh Nurjati Cirebon.
                        </p>
                    </div>
                    <div class="text-right text-[9px] sm:text-[10px] text-gray-400 dark:text-zinc-500 print:text-black">
                        Waktu Cetak: {{ now()->translatedFormat('d F Y, H:i') }} WIB
                    </div>
                </div>

            </div>
        </div>
    @endif

</div>

