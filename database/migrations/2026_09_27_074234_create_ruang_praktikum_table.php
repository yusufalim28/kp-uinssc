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
        Schema::create('ruang_praktikum', function (Blueprint $table) {
            $table->id();
            $table->string('kode_ruang')->default('TBD');
            $table->string('nama_ruang')->default('TBD');
            $table->string('lokasi')->default('TBD');
            $table->integer('kapasitas')->default(0);
            $table->text('keterangan')->nullable();
            $table->enum('status', ['tersedia', 'perbaikan', 'non-aktif'])->default('tersedia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruang_praktikum');
    }
};
