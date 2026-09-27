<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramStudi extends Model
{
    protected $table = 'program_studi';
    protected $guarded = ['id'];

    public function fakultas() {
        return $this->belongsTo(Fakultas::class);
    }

    public function mataKuliah() {
        return $this->hasMany(MataKuliah::class);
    }

    public function kelas() {
        return $this->hasMany(Kelas::class);
    }
}
