<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BerkebutuhanKhusus extends Model
{
    use HasFactory;
    protected $table = 'berkebutuhan_khusus';
    protected $fillable = [
        'nama_berkebutuhan_khusus',
    ];
}
