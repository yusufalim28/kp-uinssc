<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    protected $table = 'pengaturan';

    protected $guarded = ['id'];

    public static function get(string $kunci, mixed $default = null): mixed
    {
        $setting = static::where('kunci', $kunci)->first();

        return $setting ? $setting->nilai : $default;
    }

    public static function set(string $kunci, mixed $nilai, ?string $keterangan = null): static
    {
        return static::updateOrCreate(
            ['kunci' => $kunci],
            array_filter([
                'nilai' => (string) $nilai,
                'keterangan' => $keterangan,
            ])
        );
    }
}
