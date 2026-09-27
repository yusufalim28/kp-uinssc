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
        Schema::create('mata_kuliah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_studi_id')->constrained('program_studi')->cascadeOnDelete();
            $table->string('kode_mk')->unique();
            $table->string('nama_mk');
            $table->integer('sks');       // SKS disimpan di sini (Keputusan Section 8)
            $table->integer('semester');  // Semester MK disimpan di sini
            $table->enum('status', ['aktif', 'non-aktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mata_kuliah');
    }
};
