<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class JadwalPraktikum extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $table = 'jadwal_praktikum';

    protected $guarded = ['id'];

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    public function ruangPraktikum()
    {
        return $this->belongsTo(RuangPraktikum::class);
    }

    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class);
    }

    public function PengajuanPerubahan()
    {
        return $this->hasMany(PengajuanPerubahan::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['hari', 'jam_mulai', 'jam_selesai', 'status', 'keterangan', 'ruang_praktikum_id', 'dosen_id']) // field yang dicatat
            ->logOnlyDirty()                                        // hanya catat yang berubah
            ->dontLogEmptyChanges();
    }
}
