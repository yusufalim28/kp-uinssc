<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\JadwalPraktikum;
use App\Models\User;

class Dosen extends Model
{
    use HasFactory;

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
}
