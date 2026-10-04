<?php

namespace App\Models;

use App\Models\Dosen;
use App\Models\JadwalPraktikum;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class PengajuanPerubahan extends Model
{
    use LogsActivity;
    protected $table = 'pengajuan_perubahan';

    public function jadwalPraktikum()
    {
        return $this->belongsTo(JadwalPraktikum::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'catatan_penolakan']) // field yang dicatat
            ->logOnlyDirty()                                        // hanya catat yang berubah
            ->dontLogEmptyChanges();
    }
}
