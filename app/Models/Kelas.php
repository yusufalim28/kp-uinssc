<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';
    protected $guarded = ['id'];

    public function programStudi() {
        return $this->belongsTo(ProgramStudi::class);
    }
}
