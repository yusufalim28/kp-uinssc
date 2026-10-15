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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama_ruang', 'kode_ruang', 'lokasi', 'kapasitas', 'status']) // field yang dicatat
            ->logOnlyDirty()                                        // hanya catat yang berubah
            ->dontLogEmptyChanges();
    }
}
