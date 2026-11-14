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
            $table->string('tipe_kapasitas')->default('tetap')->after('kapasitas');
            $table->integer('kapasitas_maksimal')->nullable()->after('tipe_kapasitas');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('ruang_praktikum_id')->nullable()->after('program_studi_id')->constrained('ruang_praktikum')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('ruang_praktikum_id');
        });

        Schema::table('ruang_praktikum', function (Blueprint $table) {
            $table->dropColumn(['tipe_kapasitas', 'kapasitas_maksimal']);
        });
    }
};
