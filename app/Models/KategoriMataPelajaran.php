<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriMataPelajaran extends Model
{
    use HasFactory;
    protected $table = 'kategori_mata_pelajaran';
    protected $fillable = [
        'kategori',
    ];
}
