<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class MataKuliah extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'mata_kuliah';

    protected $guarded = ['id'];

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class);
    }

    public function jadwalPraktikum()
    {
        return $this->hasMany(JadwalPraktikum::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama_mk', 'kode_mk', 'program_studi_id', 'sks', 'semester', 'status']) // field yang dicatat
            ->logOnlyDirty()                                        // hanya catat yang berubah
            ->dontLogEmptyChanges();
    }
}
