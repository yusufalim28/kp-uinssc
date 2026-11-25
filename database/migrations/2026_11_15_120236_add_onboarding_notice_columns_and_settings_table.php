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
        // 1. Tambah kolom pelacak pemberitahuan di tabel users
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('onboarding_notice_version')->default(0)->after('ruang_praktikum_id');
            $table->timestamp('onboarding_notice_dismissed_at')->nullable()->after('onboarding_notice_version');
        });

        // 2. Tabel pengaturan sistem (key-value)
        Schema::create('pengaturan', function (Blueprint $table) {
            $table->id();
            $table->string('kunci', 60)->unique();
            $table->text('nilai')->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });

        // 3. Seed pengaturan awal pemberitahuan verifikasi data
        DB::table('pengaturan')->insert([
            [
                'kunci' => 'onboarding_notice_active',
                'nilai' => '1',
                'keterangan' => 'Status aktif pengumuman onboarding pertama login (1 = aktif, 0 = nonaktif)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kunci' => 'onboarding_notice_version',
                'nilai' => '1',
                'keterangan' => 'Versi pengumuman saat ini. Menaikkan versi akan menampilkan ulang pengumuman ke semua user.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kunci' => 'onboarding_notice_title',
                'nilai' => 'Penting: Verifikasi Data Dosen (NIDN) & Kode Mata Kuliah',
                'keterangan' => 'Judul modal overlay pengumuman',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kunci' => 'onboarding_notice_content',
                'nilai' => 'Selamat datang di Sistem Jadwal Praktikum. Harap diperhatikan bahwa sebagian NIDN Dosen saat ini masih berupa data sementara (dummy) meskipun nama dosen sudah sesuai dengan jadwal asli. Mohon periksa dan perbarui NIDN dosen Anda serta kode mata kuliah dan ruang lab di fakultas Anda sebelum jadwal dirilis penuh, karena NIDN digunakan dosen untuk login dan mengelola jadwal mengajar mereka.',
                'keterangan' => 'Isi teks pengumuman verifikasi data',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kunci' => 'onboarding_notice_target_roles',
                'nilai' => 'operator,ketua-lab',
                'keterangan' => 'Role yang wajib melihat modal overlay pengumuman',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['onboarding_notice_version', 'onboarding_notice_dismissed_at']);
        });
    }
};
