<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penanggung_jawab_lab', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fakultas_id')->nullable()->constrained('fakultas')->nullOnDelete();
            $table->foreignId('ruang_praktikum_id')->constrained('ruang_praktikum')->cascadeOnDelete();
            $table->string('nama', 150);
            $table->string('nip_nidn', 50)->nullable();
            $table->string('jabatan', 100);
            $table->string('tingkat', 20)->default('anggota');
            $table->unsignedInteger('urutan')->default(1);
            $table->string('foto')->nullable();
            $table->string('email', 150)->nullable();
            $table->string('no_telepon', 30)->nullable();
            $table->string('link_instagram', 255)->nullable();
            $table->string('link_linkedin', 255)->nullable();
            $table->string('status', 20)->default('aktif');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penanggung_jawab_lab');
    }
};
