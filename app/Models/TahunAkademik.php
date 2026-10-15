<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class TahunAkademik extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $table = 'tahun_akademik';

    protected $guarded = ['id'];

    public function jadwalPraktikum()
    {
        return $this->hasMany(JadwalPraktikum::class);
    }

    public function setStatusAktifAttribute($value): void
    {
        $this->attributes['is_aktif'] = (bool) $value;
    }

    public function getStatusAktifAttribute(): bool
    {
        return (bool) ($this->attributes['is_aktif'] ?? false);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama', 'tahun_mulai', 'is_aktif']) // field yang dicatat
            ->logOnlyDirty()                                        // hanya catat yang berubah
            ->dontLogEmptyChanges();
    }
}
