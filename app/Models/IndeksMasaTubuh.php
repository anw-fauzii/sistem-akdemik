<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndeksMasaTubuh extends Model
{
    protected $table = 'indeks_masa_tubuh';

    protected $fillable = [
        'jenis_kelamin',
        'umur_bulan',
        'minus_3_sd',
        'minus_2_sd',
        'minus_1_sd',
        'median',
        'plus_1_sd',
        'plus_2_sd',
        'plus_3_sd',
    ];

    protected $casts = [
        'umur_bulan' => 'integer',
        'minus_3_sd' => 'float',
        'minus_2_sd' => 'float',
        'minus_1_sd' => 'float',
        'median'     => 'float',
        'plus_1_sd'  => 'float',
        'plus_2_sd'  => 'float',
        'plus_3_sd'  => 'float',
    ];
}