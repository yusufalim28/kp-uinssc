<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class PenanggungJawabLab extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $table = 'penanggung_jawab_lab';

    protected $guarded = ['id'];

    public function fakultas(): BelongsTo
    {
        return $this->belongsTo(Fakultas::class);
    }

    public function ruangPraktikum(): BelongsTo
    {
        return $this->belongsTo(RuangPraktikum::class);
    }

    public function getFotoUrlAttribute(): ?string
    {
        if ($this->foto && Storage::disk('public')->exists($this->foto)) {
            return Storage::url($this->foto);
        }

        return null;
    }

    public function getInisialAttribute(): string
    {
        $parts = preg_split('/\s+/', trim($this->nama));
        $first = strtoupper(substr($parts[0] ?? 'P', 0, 1));
        $second = strtoupper(substr($parts[1] ?? '', 0, 1));

        return $first.$second;
    }

    public function getWhatsappUrlAttribute(): ?string
    {
        if (! $this->no_telepon) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $this->no_telepon);
        if (! $digits) {
            return null;
        }

        if (str_starts_with($digits, '0')) {
            $digits = '62'.substr($digits, 1);
        }

        return 'https://wa.me/'.$digits;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['fakultas_id', 'ruang_praktikum_id', 'nama', 'nip_nidn', 'jabatan', 'tingkat', 'urutan', 'email', 'no_telepon', 'status'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }
}
