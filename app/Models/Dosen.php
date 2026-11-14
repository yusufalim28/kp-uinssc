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

    public function getLabelKategoriAttribute(): string
    {
        return match ($this->kategori) {
            'tutor' => 'Tutor Lab',
            'asisten' => 'Asisten Lab',
            default => 'Dosen Pengampu',
        };
    }

    public function isDosen(): bool
    {
        return ($this->kategori ?? 'dosen') === 'dosen';
    }

    public function isTutor(): bool
    {
        return in_array($this->kategori ?? 'dosen', ['tutor', 'asisten']);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama', 'nidn', 'email', 'kategori', 'status']) // field yang dicatat
            ->logOnlyDirty()                                        // hanya catat yang berubah
            ->dontLogEmptyChanges();
    }
}
