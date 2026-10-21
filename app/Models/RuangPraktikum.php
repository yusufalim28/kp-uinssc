<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama_ruang', 'kode_ruang', 'lokasi', 'kapasitas', 'tipe_ruang', 'fakultas_id', 'program_studi_id', 'status'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }
}
