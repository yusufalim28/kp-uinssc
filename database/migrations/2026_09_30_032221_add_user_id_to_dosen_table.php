<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Dosen butuh ditautkan ke akun User supaya saat login sebagai dosen,
     * sistem tahu "jadwal saya" dan "pengajuan saya" itu milik siapa.
     * Kolom ini tidak ada di ERD awal karena awalnya Dosen dianggap
     * cuma data referensi, bukan aktor yang login.
     */
    public function up(): void
    {
        Schema::table('dosen', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('dosen', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
