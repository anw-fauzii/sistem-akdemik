<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DaftarJilid extends Model
{
    protected $table = 'daftar_jilid';

    protected $fillable = [
        'jilid_latin',
        'jilid_arab',
        'jenis',
        'urutan'
    ];
}