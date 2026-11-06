<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class RuangPraktikum extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $table = 'ruang_praktikum';

    protected $guarded = ['id'];

    public function jadwalPraktikum()
    {
        return $this->hasMany(JadwalPraktikum::class);
    }

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class);
    }

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class);
    }

    public function prodiPengguna(): BelongsToMany
    {
        return $this->belongsToMany(ProgramStudi::class, 'ruang_praktikum_prodi', 'ruang_praktikum_id', 'program_studi_id')
            ->withTimestamps();
    }

    /**
     * Mengecek apakah ruangan ini diizinkan untuk digunakan oleh program studi tertentu.
     */
    public function canBeUsedByProdi(?int $prodiId, ?int $fakultasId = null): bool
    {
        if (! $prodiId) {
            return false;
        }

        $cakupan = $this->cakupan_akses ?? 'bersama_fakultas';

        // 1. Umum Universitas: bisa dipakai semua prodi
        if ($cakupan === 'umum_universitas') {
            return true;
        }

        // 2. Eksklusif Prodi: hanya prodi yang bersangkutan
        if ($cakupan === 'eksklusif') {
            return $this->program_studi_id === $prodiId;
        }

        // 3. Khusus Multi-Prodi: harus terdaftar di tabel pivot prodiPengguna
        if ($cakupan === 'khusus_multi_prodi') {
            if ($this->relationLoaded('prodiPengguna')) {
                return $this->prodiPengguna->contains('id', $prodiId);
            }

            return $this->prodiPengguna()->where('program_studi.id', $prodiId)->exists();
        }

        // 4. Bersama Fakultas: boleh dipakai prodi mana pun di fakultas yang sama
        if ($cakupan === 'bersama_fakultas') {
            if (is_null($this->fakultas_id)) {
                return true;
            }

            if ($fakultasId && $this->fakultas_id) {
                return $this->fakultas_id === $fakultasId;
            }

            $prodi = ProgramStudi::find($prodiId);

            return $prodi && $prodi->fakultas_id === $this->fakultas_id;
        }

        return false;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama_ruang', 'kode_ruang', 'lokasi', 'kapasitas', 'tipe_ruang', 'cakupan_akses', 'fakultas_id', 'program_studi_id', 'status'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }
}
