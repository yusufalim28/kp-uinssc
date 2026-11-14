<?php

namespace App\Console\Commands;

use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\JadwalPraktikum;
use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\PengajuanPerubahan;
use App\Models\ProgramStudi;
use App\Models\RuangPraktikum;
use App\Models\TahunAkademik;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

#[Signature('app:clear-dummy-data {--force : Jalankan pembersihan tanpa konfirmasi}')]
#[Description('Membersihkan seluruh data dummy operasional (Jadwal, Pengajuan, Ruang, MK, Kelas, Dosen) dan mempertahankan Roles, Fakultas, Prodi, Tahun Akademik, serta akun Admin & Operator.')]
class ClearDummyData extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('==================================================');
        $this->info('  PEMBERSIHAN DATA DUMMY SISTEM JADWAL PRAKTIKUM  ');
        $this->info('==================================================');

        if (! $this->option('force') && ! $this->confirm('Apakah Anda yakin ingin menghapus seluruh data dummy operasional untuk persiapan input data asli?', true)) {
            $this->warn('Operasi dibatalkan.');

            return self::SUCCESS;
        }

        $this->newLine();
        $this->line('1. Mengosongkan data transaksi dan operasional praktikum...');

        Schema::disableForeignKeyConstraints();

        // 1. Bersihkan transaksi pengajuan perubahan
        $pengajuanCount = PengajuanPerubahan::count();
        PengajuanPerubahan::truncate();
        $this->line("   - Pengajuan Perubahan: {$pengajuanCount} data dihapus.");

        // 2. Bersihkan jadwal praktikum
        $jadwalCount = JadwalPraktikum::count();
        JadwalPraktikum::truncate();
        $this->line("   - Jadwal Praktikum: {$jadwalCount} data dihapus.");

        // 3. Bersihkan ruang praktikum & pivot prodi
        if (Schema::hasTable('ruang_praktikum_prodi')) {
            DB::table('ruang_praktikum_prodi')->truncate();
        }
        $ruangCount = RuangPraktikum::count();
        RuangPraktikum::truncate();
        $this->line("   - Ruang Praktikum: {$ruangCount} data dihapus.");

        // 4. Bersihkan mata kuliah
        $mkCount = MataKuliah::count();
        MataKuliah::truncate();
        $this->line("   - Mata Kuliah: {$mkCount} data dihapus.");

        // 5. Bersihkan kelas
        $kelasCount = Kelas::count();
        Kelas::truncate();
        $this->line("   - Kelas: {$kelasCount} data dihapus.");

        // 6. Bersihkan dosen
        $dosenCount = Dosen::count();
        Dosen::truncate();
        $this->line("   - Dosen & Tutor: {$dosenCount} data dihapus.");

        // 7. Bersihkan log aktivitas & notifikasi dummy
        if (Schema::hasTable('activity_log')) {
            DB::table('activity_log')->truncate();
            $this->line('   - Activity Log: dibersihkan.');
        }
        if (Schema::hasTable('notifications')) {
            DB::table('notifications')->truncate();
            $this->line('   - Notifikasi: dibersihkan.');
        }

        // 8. Bersihkan akun user dummy (hanya pertahankan Super Admin & Operator Fakultas)
        $this->newLine();
        $this->line('2. Menyeleksi akun pengguna...');
        $dummyUsers = User::whereDoesntHave('roles', function ($q) {
            $q->whereIn('name', ['super-admin', 'operator']);
        })->get();

        $deletedUserCount = $dummyUsers->count();
        foreach ($dummyUsers as $user) {
            $user->roles()->detach();
            $user->delete();
        }

        // Reset ruang_praktikum_id pada operator/superadmin jika ada
        User::query()->update(['ruang_praktikum_id' => null]);
        $this->line("   - Akun Pengguna Dummy (Dosen, Tutor, Ketua Lab dummy): {$deletedUserCount} akun dihapus.");

        Schema::enableForeignKeyConstraints();

        // Rekapitulasi Data yang Dipertahankan
        $this->newLine();
        $this->info('==================================================');
        $this->info('  DATA BERHASIL DIBERSIHKAN! STATUS SISTEM SAAT INI:');
        $this->info('==================================================');
        $this->table(
            ['Entitas', 'Jumlah Tersisa', 'Keterangan'],
            [
                ['Fakultas', Fakultas::count(), 'DIPERTAHANKAN (FITK, FEBI, FASYA, FUA, FDKI)'],
                ['Program Studi', ProgramStudi::count(), 'DIPERTAHANKAN (15 Program Studi)'],
                ['Tahun Akademik', TahunAkademik::count(), 'DIPERTAHANKAN ('.(TahunAkademik::where('is_aktif', true)->first()?->nama ?? 'Aktif').')'],
                ['Akun Super Admin', User::role('super-admin')->count(), 'DIPERTAHANKAN (superadmin@praktikum.ac.id)'],
                ['Akun Operator Fakultas', User::role('operator')->count(), 'DIPERTAHANKAN (5 Akun Operator Fakultas)'],
                ['Dosen & Tutor', Dosen::count(), '0 (Siap diinput data asli)'],
                ['Ruang Praktikum', RuangPraktikum::count(), '0 (Siap diinput data asli)'],
                ['Mata Kuliah', MataKuliah::count(), '0 (Siap diinput data asli)'],
                ['Kelas', Kelas::count(), '0 (Siap diinput data asli)'],
                ['Jadwal Praktikum', JadwalPraktikum::count(), '0 (Siap diinput data asli)'],
                ['Pengajuan Perubahan', PengajuanPerubahan::count(), '0 (Siap diinput data asli)'],
            ]
        );

        $this->info('Aplikasi kini dalam kondisi bersih dan siap untuk penginputan data asli.');

        return self::SUCCESS;
    }
}
