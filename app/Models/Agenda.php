<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasFactory;
    protected $table = 'agenda';
    protected $fillable = [
        'tahun_ajaran_id',
        'kegiatan',
        'tanggal',
        'jam',
        'unit',
    ];
}
