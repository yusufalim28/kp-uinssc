<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Dosen extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $table = 'dosen';

    protected $guarded = ['id'];

    public function jadwalPraktikum()
    {
        return $this->hasMany(JadwalPraktikum::class);
    }

    public function pengajuanPerubahan()
    {
        return $this->hasMany(PengajuanPerubahan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getKodeDosenAttribute(): ?string
    {
        return $this->attributes['nidn'] ?? null;
    }

    public function setKodeDosenAttribute(?string $value): void
    {
        $this->attributes['nidn'] = $value;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama', 'nidn', 'email', 'status']) // field yang dicatat
            ->logOnlyDirty()                                        // hanya catat yang berubah
            ->dontLogEmptyChanges();
    }
}
