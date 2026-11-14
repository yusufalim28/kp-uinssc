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
        Schema::table('mata_kuliah', function (Blueprint $table) {
            $table->foreignId('program_studi_id')->nullable()->change();

            if (! Schema::hasColumn('mata_kuliah', 'fakultas_id')) {
                $table->foreignId('fakultas_id')->nullable()->after('id')->constrained('fakultas')->nullOnDelete();
            }

            if (! Schema::hasColumn('mata_kuliah', 'cakupan_mk')) {
                $table->string('cakupan_mk', 30)->default('prodi')->after('fakultas_id');
            }
        });

        // Migrasi data existing: isi fakultas_id dari program_studi agar data lama tetap 100% utuh
        if (Schema::hasTable('mata_kuliah') && Schema::hasTable('program_studi')) {
            $rows = DB::table('mata_kuliah')
                ->whereNotNull('program_studi_id')
                ->whereNull('fakultas_id')
                ->select('id', 'program_studi_id')
                ->get();

            if ($rows->isNotEmpty()) {
                $prodiFakultasMap = DB::table('program_studi')->pluck('fakultas_id', 'id');
                foreach ($rows as $row) {
                    $fakId = $prodiFakultasMap[$row->program_studi_id] ?? null;
                    if ($fakId) {
                        DB::table('mata_kuliah')->where('id', $row->id)->update([
                            'fakultas_id' => $fakId,
                            'cakupan_mk' => 'prodi',
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mata_kuliah', function (Blueprint $table) {
            if (Schema::hasColumn('mata_kuliah', 'fakultas_id')) {
                $table->dropConstrainedForeignId('fakultas_id');
            }

            if (Schema::hasColumn('mata_kuliah', 'cakupan_mk')) {
                $table->dropColumn('cakupan_mk');
            }

            $table->foreignId('program_studi_id')->nullable(false)->change();
        });
    }
};
