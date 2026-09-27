<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPraktikum extends Model
{
    protected $table = 'jadwal_praktikum';
    protected $guarded = ['id'];

    public function mataKuliah() {
        return $this->belongsTo(MataKuliah::class);
    }

    public function kelas() {
        return $this->belongsTo(Kelas::class);
    }

    public function dosen() {
        return $this->belongsTo(Dosen::class);
    }

    public function ruangPraktikum() {
        return $this->belongsTo(RuangPraktikum::class);
    }

    public function tahunAkademik() {
        return $this->belongsTo(TahunAkademik::class);
    }
}
