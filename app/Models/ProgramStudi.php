<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class ProgramStudi extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'program_studi';

    protected $guarded = ['id'];

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class);
    }

    public function mataKuliah()
    {
        return $this->hasMany(MataKuliah::class);
    }

    public function kelas()
    {
        return $this->hasMany(Kelas::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama_program_studi', 'kode_program_studi', 'fakultas_id', 'status']) // field yang dicatat
            ->logOnlyDirty()                                        // hanya catat yang berubah
            ->dontLogEmptyChanges();
    }
}
