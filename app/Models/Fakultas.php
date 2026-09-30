<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    use HasFactory;

    protected $table = 'fakultas';

    protected $guarded = ['id'];

    public function programStudi()
    {
        return $this->hasMany(ProgramStudi::class);
    }
}
