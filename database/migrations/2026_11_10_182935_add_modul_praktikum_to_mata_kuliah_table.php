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
        Schema::table('mata_kuliah', function (Blueprint $table) {
            $table->string('file_modul')->nullable()->after('status');
            $table->string('tautan_modul', 500)->nullable()->after('file_modul');
            $table->text('deskripsi_modul')->nullable()->after('tautan_modul');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mata_kuliah', function (Blueprint $table) {
            $table->dropColumn(['file_modul', 'tautan_modul', 'deskripsi_modul']);
        });
    }
};
