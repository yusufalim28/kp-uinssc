<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Ubah enum status menjadi string agar fleksibel dan tidak error data truncated
        if (Schema::hasTable('fakultas')) {
            Schema::table('fakultas', function (Blueprint $table) {
                $table->string('status', 20)->default('aktif')->change();
            });
            DB::table('fakultas')->where('status', 'non-aktif')->update(['status' => 'nonaktif']);
        }

        if (Schema::hasTable('program_studi')) {
            Schema::table('program_studi', function (Blueprint $table) {
                $table->string('status', 20)->default('aktif')->change();
            });
            DB::table('program_studi')->where('status', 'non-aktif')->update(['status' => 'nonaktif']);
        }

        if (Schema::hasTable('kelas')) {
            Schema::table('kelas', function (Blueprint $table) {
                $table->string('status', 20)->default('aktif')->change();
            });
            DB::table('kelas')->where('status', 'non-aktif')->update(['status' => 'nonaktif']);
        }

        if (Schema::hasTable('mata_kuliah')) {
            Schema::table('mata_kuliah', function (Blueprint $table) {
                $table->string('status', 20)->default('aktif')->change();
            });
            DB::table('mata_kuliah')->where('status', 'non-aktif')->update(['status' => 'nonaktif']);
        }

        if (Schema::hasTable('dosen')) {
            Schema::table('dosen', function (Blueprint $table) {
                $table->string('status', 20)->default('aktif')->change();
            });
            DB::table('dosen')->where('status', 'non-aktif')->update(['status' => 'nonaktif']);
        }

        if (Schema::hasTable('ruang_praktikum')) {
            Schema::table('ruang_praktikum', function (Blueprint $table) {
                $table->string('status', 20)->default('aktif')->change();
            });
            // Samakan data ruang agar berstatus 'aktif' sehingga muncul di dropdown Kelola Jadwal
            DB::table('ruang_praktikum')->where('status', 'tersedia')->update(['status' => 'aktif']);
            DB::table('ruang_praktikum')->where('status', 'non-aktif')->update(['status' => 'nonaktif']);
        }

        if (Schema::hasTable('tahun_akademik')) {
            Schema::table('tahun_akademik', function (Blueprint $table) {
                $table->string('semester', 20)->default('Ganjil')->change();
                $table->year('tahun_selesai')->nullable()->change();
            });
        }

        if (Schema::hasTable('jadwal_praktikum')) {
            Schema::table('jadwal_praktikum', function (Blueprint $table) {
                $table->string('hari', 20)->change();
                $table->string('status', 20)->default('aktif')->change();
            });
        }

        if (Schema::hasTable('pengajuan_perubahan')) {
            Schema::table('pengajuan_perubahan', function (Blueprint $table) {
                if (! Schema::hasColumn('pengajuan_perubahan', 'jenis_perubahan')) {
                    $table->string('jenis_perubahan', 50)->nullable()->after('dosen_id');
                }
                if (! Schema::hasColumn('pengajuan_perubahan', 'perubahan')) {
                    $table->text('perubahan')->nullable()->after('jenis_perubahan');
                }
                $table->string('status', 20)->default('menunggu')->change();
            });

            DB::table('pengajuan_perubahan')->where('status', 'Menunggu')->update(['status' => 'menunggu']);
            DB::table('pengajuan_perubahan')->where('status', 'Disetujui')->update(['status' => 'disetujui']);
            DB::table('pengajuan_perubahan')->where('status', 'Ditolak')->update(['status' => 'ditolak']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op rollback for safety
    }
};
