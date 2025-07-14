<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jemputan extends Model
{
    use HasFactory;
    protected $table = 'jemputan';
    protected $fillable = [
        'tahun_ajaran_id',
        'driver',
        'harga_pp',
        'harga_setengah'
    ];
}
