<?php

namespace App\Imports;

use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\JadwalPraktikum;
use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use App\Models\RuangPraktikum;
use App\Models\TahunAkademik;
use App\Services\JadwalConflictService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class JadwalImport implements ToCollection
{
    public int $successCount = 0;

    public int $skippedCount = 0;

    /**
     * @var array<string>
     */
    public array $errors = [];

    public function collection(Collection $rows): void
    {
        $activeTahun = TahunAkademik::where('is_aktif', true)->first();
        $user = auth()->user();

        $defaultProdiId = $user?->program_studi_id
            ?? ($user?->fakultas_id ? ProgramStudi::where('fakultas_id', $user->fakultas_id)->value('id') : null)
            ?? ProgramStudi::value('id');

        $defaultFakultasId = $user?->fakultas_id
            ?? ($defaultProdiId ? ProgramStudi::find($defaultProdiId)?->fakultas_id : null)
            ?? Fakultas::value('id');

        // 1. Deteksi letak baris Header secara dinamis
        $headerRowIndex = null;
        $columnMap = []; // index => field_name

        foreach ($rows as $rowIndex => $row) {
            $rowArray = $row instanceof Collection ? $row->toArray() : (array) $row;
            $rowString = strtolower(implode(' ', array_filter(array_map('strval', $rowArray))));

            // Cek apakah baris ini memuat kata kunci jadwal
            $hasMk = str_contains($rowString, 'mata') || str_contains($rowString, 'mk') || str_contains($rowString, 'kuliah') || str_contains($rowString, 'kursus');
            $hasOther = str_contains($rowString, 'ruang') || str_contains($rowString, 'lab') || str_contains($rowString, 'hari') || str_contains($rowString, 'jam') || str_contains($rowString, 'dosen') || str_contains($rowString, 'waktu');

            if ($hasMk && $hasOther) {
                $headerRowIndex = $rowIndex;
                foreach ($rowArray as $colIdx => $colVal) {
                    $cleanHeader = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', (string) $colVal));
                    $field = $this->identifyField($cleanHeader);
                    if ($field) {
                        $columnMap[$colIdx] = $field;
                    }
                }
                break;
            }
        }

        // Jika tidak ditemukan baris ber-keyword khusus, asumsikan baris pertama adalah header
        if ($headerRowIndex === null) {
            $headerRowIndex = 0;
            if (isset($rows[0])) {
                $row0 = $rows[0] instanceof Collection ? $rows[0]->toArray() : (array) $rows[0];
                foreach ($row0 as $colIdx => $colVal) {
                    $cleanHeader = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', (string) $colVal));
                    $field = $this->identifyField($cleanHeader);
                    if ($field) {
                        $columnMap[$colIdx] = $field;
                    }
                }
            }
        }

        // 2. Proses data jadwal dari baris setelah header
        foreach ($rows as $rowIndex => $row) {
            if ($rowIndex <= $headerRowIndex) {
                continue;
            }

            $rowNumber = $rowIndex + 1; // Baris Excel asli (1-based)
            $rowArray = $row instanceof Collection ? $row->toArray() : (array) $row;

            // Kumpulkan nilai berdasarkan columnMap
            $rowData = [];
            foreach ($columnMap as $colIdx => $fieldName) {
                if (isset($rowArray[$colIdx])) {
                    $val = trim((string) $rowArray[$colIdx]);
                    if ($val !== '') {
                        $rowData[$fieldName] = $val;
                    }
                }
            }

            $mkRaw = $rowData['mk'] ?? null;
            $kelasRaw = $rowData['kelas'] ?? null;
            $dosenRaw = $rowData['dosen'] ?? null;
            $ruangRaw = $rowData['ruang'] ?? null;
            $hariRaw = $rowData['hari'] ?? null;
            $jamMulaiRaw = $rowData['jam_mulai'] ?? null;
            $jamSelesaiRaw = $rowData['jam_selesai'] ?? null;
            $waktuCombinedRaw = $rowData['waktu'] ?? null;
            $tahunRaw = $rowData['tahun'] ?? null;

            // Lewati baris kosong
            if (! $mkRaw && ! $kelasRaw && ! $dosenRaw && ! $ruangRaw) {
                continue;
            }

            // Validasi data esensial
            if (! $mkRaw || ! $kelasRaw || ! $dosenRaw || ! $ruangRaw || ! $hariRaw) {
                $this->errors[] = "Baris {$rowNumber}: Data tidak lengkap. Pastikan kolom Mata Kuliah, Kelas, Dosen, Ruang, dan Hari terisi.";
                $this->skippedCount++;

                continue;
            }

            // Normalisasi Waktu
            $jamMulai = null;
            $jamSelesai = null;

            if ($jamMulaiRaw && $jamSelesaiRaw) {
                $jamMulai = $this->formatTimeString($jamMulaiRaw);
                $jamSelesai = $this->formatTimeString($jamSelesaiRaw);
            } elseif ($waktuCombinedRaw) {
                $parts = preg_split('/(?:\s*-\s*|\s*–\s*|\s*—\s*|\s+s\/d\s+|\s+sampai\s+|\s+to\s+)/i', $waktuCombinedRaw);
                if (count($parts) >= 2) {
                    $jamMulai = $this->formatTimeString($parts[0]);
                    $jamSelesai = $this->formatTimeString($parts[1]);
                }
            }

            if (! $jamMulai || ! $jamSelesai) {
                $this->errors[] = "Baris {$rowNumber}: Format jam tidak valid (Mulai: '{$jamMulaiRaw}', Selesai: '{$jamSelesaiRaw}', Waktu: '{$waktuCombinedRaw}'). Gunakan format HH:MM (contoh 08:00 - 10:00).";
                $this->skippedCount++;

                continue;
            }

            // Normalisasi Hari
            $hari = $this->normalizeHari($hariRaw);
            if (! $hari) {
                $this->errors[] = "Baris {$rowNumber}: Nama hari '{$hariRaw}' tidak dikenali. Gunakan Senin, Selasa, Rabu, Kamis, Jumat, atau Sabtu.";
                $this->skippedCount++;

                continue;
            }

            // Tahun Akademik
            $tahun = $activeTahun;
            if ($tahunRaw) {
                $matchedTahun = TahunAkademik::where('nama', 'LIKE', "%{$tahunRaw}%")->first();
                if ($matchedTahun) {
                    $tahun = $matchedTahun;
                }
            }

            if (! $tahun) {
                $this->errors[] = "Baris {$rowNumber}: Tidak ada tahun akademik aktif di sistem.";
                $this->skippedCount++;

                continue;
            }

            // Mata Kuliah (Auto-Create jika belum ada)
            $mk = MataKuliah::where('kode_mk', $mkRaw)
                ->orWhere('nama_mk', $mkRaw)
                ->first();

            if (! $mk) {
                $kodeGenerated = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $mkRaw), 0, 8));
                if (empty($kodeGenerated) || strlen($kodeGenerated) < 3) {
                    $kodeGenerated = 'MK'.rand(100, 999);
                }
                if (MataKuliah::where('kode_mk', $kodeGenerated)->exists()) {
                    $kodeGenerated .= rand(1, 9);
                }

                $mk = MataKuliah::create([
                    'kode_mk' => $kodeGenerated,
                    'nama_mk' => $mkRaw,
                    'sks' => 2,
                    'semester' => 1,
                    'program_studi_id' => $defaultProdiId,
                ]);
            }

            // Kelas (Auto-Create jika belum ada)
            $kelas = Kelas::where('nama_kelas', $kelasRaw)->first();
            if (! $kelas) {
                $kelas = Kelas::create([
                    'nama_kelas' => $kelasRaw,
                    'program_studi_id' => $mk->program_studi_id ?? $defaultProdiId,
                    'semester' => $mk->semester ?? 1,
                ]);
            }

            // Dosen (Auto-Create jika belum ada)
            $dosen = Dosen::where('nidn', $dosenRaw)
                ->orWhere('nama', $dosenRaw)
                ->orWhere('nama', 'LIKE', "%{$dosenRaw}%")
                ->first();

            if (! $dosen) {
                $isNidnNumber = preg_match('/^\d{6,18}$/', $dosenRaw);
                $nidnVal = $isNidnNumber ? $dosenRaw : 'DSN-'.rand(100000, 999999);
                $namaVal = $isNidnNumber ? 'Dosen '.$dosenRaw : $dosenRaw;

                $dosen = Dosen::create([
                    'nidn' => $nidnVal,
                    'nama' => $namaVal,
                    'email' => strtolower(substr(preg_replace('/[^a-z0-9]/', '', $namaVal), 0, 15)).'@praktikum.ac.id',
                ]);
            }

            // Ruang Praktikum (Cari fleksibel kode atau nama)
            $ruang = RuangPraktikum::where('kode_ruang', $ruangRaw)
                ->orWhere('nama_ruang', $ruangRaw)
                ->first();

            if (! $ruang) {
                $ruang = RuangPraktikum::where('nama_ruang', 'LIKE', "%{$ruangRaw}%")
                    ->orWhere('kode_ruang', 'LIKE', "%{$ruangRaw}%")
                    ->first();
            }

            if (! $ruang) {
                if ($user && $user->hasRole('super-admin')) {
                    $ruang = RuangPraktikum::create([
                        'kode_ruang' => strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $ruangRaw), 0, 10)),
                        'nama_ruang' => $ruangRaw,
                        'tipe_ruang' => 'laboratorium',
                        'kapasitas' => 30,
                        'fakultas_id' => $defaultFakultasId,
                        'status' => 'aktif',
                    ]);
                } else {
                    $this->errors[] = "Baris {$rowNumber}: Ruangan '{$ruangRaw}' tidak ditemukan dalam katalog laboratorium resmi universitas.";
                    $this->skippedCount++;

                    continue;
                }
            }

            // Cek Hak Akses Multi-Tenancy (Operator Fakultas / Prodi)
            if ($user && $user->hasRole('operator')) {
                // Pastikan ruangan berada di bawah fakultas operator
                if ($ruang->fakultas_id !== null && $user->fakultas_id) {
                    if ($ruang->fakultas_id !== $user->fakultas_id) {
                        $this->errors[] = "Baris {$rowNumber}: Akses ditolak! Ruangan '{$ruang->nama_ruang}' adalah milik fakultas lain.";
                        $this->skippedCount++;

                        continue;
                    }
                }
            }

            // Pengecekan Bentrok Jadwal
            $conflict = JadwalConflictService::checkConflict(
                ruangPraktikumId: $ruang->id,
                dosenId: $dosen->id,
                hari: $hari,
                jamMulai: $jamMulai,
                jamSelesai: $jamSelesai,
                excludeId: null,
                tahunAkademikId: $tahun->id
            );

            if ($conflict) {
                $this->errors[] = "Baris {$rowNumber}: Bentrok Jadwal! {$conflict}";
                $this->skippedCount++;

                continue;
            }

            // Simpan Jadwal Praktikum
            JadwalPraktikum::create([
                'mata_kuliah_id' => $mk->id,
                'kelas_id' => $kelas->id,
                'dosen_id' => $dosen->id,
                'ruang_praktikum_id' => $ruang->id,
                'tahun_akademik_id' => $tahun->id,
                'hari' => $hari,
                'jam_mulai' => $jamMulai,
                'jam_selesai' => $jamSelesai,
                'status' => 'aktif',
            ]);

            $this->successCount++;
        }
    }

    /**
     * Memetakan nama kolom Excel ke field internal.
     */
    private function identifyField(string $cleanHeader): ?string
    {
        if (str_contains($cleanHeader, 'mata') || str_contains($cleanHeader, 'mk') || str_contains($cleanHeader, 'course') || str_contains($cleanHeader, 'subject') || str_contains($cleanHeader, 'makul')) {
            return 'mk';
        }
        if (str_contains($cleanHeader, 'kelas') || str_contains($cleanHeader, 'rombel') || str_contains($cleanHeader, 'section') || str_contains($cleanHeader, 'grup') || str_contains($cleanHeader, 'kls')) {
            return 'kelas';
        }
        if (str_contains($cleanHeader, 'dosen') || str_contains($cleanHeader, 'pengampu') || str_contains($cleanHeader, 'nidn') || str_contains($cleanHeader, 'pengajar') || str_contains($cleanHeader, 'lecturer')) {
            return 'dosen';
        }
        if (str_contains($cleanHeader, 'ruang') || str_contains($cleanHeader, 'lab') || str_contains($cleanHeader, 'tempat') || str_contains($cleanHeader, 'room')) {
            return 'ruang';
        }
        if (str_contains($cleanHeader, 'hari') || str_contains($cleanHeader, 'day')) {
            return 'hari';
        }
        if (str_contains($cleanHeader, 'mulai') || str_contains($cleanHeader, 'start') || str_contains($cleanHeader, 'awal')) {
            return 'jam_mulai';
        }
        if (str_contains($cleanHeader, 'selesai') || str_contains($cleanHeader, 'end') || str_contains($cleanHeader, 'akhir')) {
            return 'jam_selesai';
        }
        if (str_contains($cleanHeader, 'waktu') || str_contains($cleanHeader, 'jam') || str_contains($cleanHeader, 'pukul') || str_contains($cleanHeader, 'time') || str_contains($cleanHeader, 'jadwal')) {
            return 'waktu';
        }
        if (str_contains($cleanHeader, 'tahun') || str_contains($cleanHeader, 'semester') || str_contains($cleanHeader, 'ta')) {
            return 'tahun';
        }

        return null;
    }

    private function formatTimeString(mixed $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        if (is_numeric($value) && (float) $value >= 0 && (float) $value <= 1) {
            try {
                return ExcelDate::excelToDateTimeObject($value)->format('H:i');
            } catch (\Exception $e) {
            }
        }

        $str = trim((string) $value);
        $str = str_replace('.', ':', $str);
        $str = preg_replace('/[^\d:]/', '', $str);

        if (preg_match('/^(\d{1,2}):(\d{2})$/', $str, $m)) {
            return sprintf('%02d:%02d', (int) $m[1], (int) $m[2]);
        }

        try {
            return Carbon::parse($str)->format('H:i');
        } catch (\Exception $e) {
            return null;
        }
    }

    private function normalizeHari(string $hariRaw): ?string
    {
        $clean = strtolower(trim($hariRaw));

        return match (true) {
            str_contains($clean, 'senin') || str_contains($clean, 'mon') => 'Senin',
            str_contains($clean, 'selasa') || str_contains($clean, 'tue') => 'Selasa',
            str_contains($clean, 'rabu') || str_contains($clean, 'wed') => 'Rabu',
            str_contains($clean, 'kamis') || str_contains($clean, 'thu') => 'Kamis',
            str_contains($clean, 'jumat') || str_contains($clean, 'jum\'at') || str_contains($clean, 'fri') => "Jum'at",
            str_contains($clean, 'sabtu') || str_contains($clean, 'sat') => 'Sabtu',
            str_contains($clean, 'minggu') || str_contains($clean, 'sun') => 'Minggu',
            default => null,
        };
    }
}
