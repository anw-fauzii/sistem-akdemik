<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FingerprintMap extends Model
{
    use HasFactory;
    protected $table = 'fingerprint_map';
    protected $fillable = [
        'pin',
        'tipe',
        'ref_id',
        'aktif',
    ];
}
