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
            $table->foreignId('fakultas_id')->nullable()->after('id')->constrained('fakultas')->nullOnDelete();
            $table->foreignId('program_studi_id')->nullable()->after('fakultas_id')->constrained('program_studi')->nullOnDelete();
            $table->string('tipe_ruang')->default('laboratorium')->after('kapasitas');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('fakultas_id')->nullable()->after('email')->constrained('fakultas')->nullOnDelete();
            $table->foreignId('program_studi_id')->nullable()->after('fakultas_id')->constrained('program_studi')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('program_studi_id');
            $table->dropConstrainedForeignId('fakultas_id');
        });

        Schema::table('ruang_praktikum', function (Blueprint $table) {
            $table->dropColumn('tipe_ruang');
            $table->dropConstrainedForeignId('program_studi_id');
            $table->dropConstrainedForeignId('fakultas_id');
        });
    }
};
