<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class MataKuliah extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $table = 'mata_kuliah';

    protected $guarded = ['id'];

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class);
    }

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class);
    }

    public function isBersamaFakultas(): bool
    {
        return $this->cakupan_mk === 'bersama_fakultas';
    }

    public function getNamaFakultasAttribute(): ?string
    {
        return $this->fakultas?->nama_fakultas ?? $this->programStudi?->fakultas?->nama_fakultas;
    }

    public function jadwalPraktikum()
    {
        return $this->hasMany(JadwalPraktikum::class);
    }

    public function getUrlModulAttribute(): ?string
    {
        if ($this->tautan_modul) {
            return $this->tautan_modul;
        }

        if ($this->file_modul) {
            return asset('storage/'.$this->file_modul);
        }

        return null;
    }

    public function hasModul(): bool
    {
        return ! empty($this->file_modul) || ! empty($this->tautan_modul);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama_mk', 'kode_mk', 'cakupan_mk', 'fakultas_id', 'program_studi_id', 'sks', 'semester', 'status', 'file_modul', 'tautan_modul']) // field yang dicatat
            ->logOnlyDirty()                                        // hanya catat yang berubah
            ->dontLogEmptyChanges();
    }
}
