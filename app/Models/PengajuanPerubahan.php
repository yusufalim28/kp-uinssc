<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class PengajuanPerubahan extends Model
{
    use LogsActivity;

    protected $table = 'pengajuan_perubahan';

    protected $guarded = ['id'];

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
            ->logOnly(['status', 'catatan_penolakan', 'jenis_perubahan', 'perubahan', 'alasan'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }
}
