<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulanSpp extends Model
{
    use HasFactory;
    protected $table = 'bulan_spp';
    protected $fillable = [
        'nama_bulan',
        'bulan_angka',
        'tambahan',
    ];

    protected $dates = ['bulan_angka'];
}
