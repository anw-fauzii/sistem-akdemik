<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KedisiplinanPoin extends Model
{
    use HasFactory;
    protected $table = 'kedisiplinan_poin';

    protected $fillable = [
        'nama_aturan',
        'kategori',
        'tingkat',
        'poin',
    ];

    protected $casts = [
        'poin' => 'integer',
        'kategori' => 'string',
        'tingkat' => 'string',
    ];
}
