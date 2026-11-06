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
        Schema::table('ruang_praktikum', function (Blueprint $table) {
            $table->string('cakupan_akses')->default('bersama_fakultas')->after('tipe_ruang');
        });

        Schema::create('ruang_praktikum_prodi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ruang_praktikum_id')->constrained('ruang_praktikum')->cascadeOnDelete();
            $table->foreignId('program_studi_id')->constrained('program_studi')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['ruang_praktikum_id', 'program_studi_id'], 'ruang_prodi_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruang_praktikum_prodi');

        Schema::table('ruang_praktikum', function (Blueprint $table) {
            $table->dropColumn('cakupan_akses');
        });
    }
};
