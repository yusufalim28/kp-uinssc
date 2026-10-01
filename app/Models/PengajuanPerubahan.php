<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanPerubahan extends Model
{
    protected $table = 'pengajuan_perubahan';

    public function jadwalPraktikum()
    {
        return $this->belongsTo(JadwalPraktikum::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }
}
