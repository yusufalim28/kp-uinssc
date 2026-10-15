<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Kelas extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $table = 'kelas';

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
            ->logOnly(['nama_kelas', 'program_studi_id', 'semester', 'status']) // field yang dicatat
            ->logOnlyDirty()                                        // hanya catat yang berubah
            ->dontLogEmptyChanges();
    }
}
