<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurahAlquran extends Model
{
    use HasFactory;

    protected $table = 'surah_alquran';
    protected $fillable = [
        'nama_surah',
        'nama_surah_arab',
        'jumlah_ayat',
    ];

    /**
     * Jika Anda ingin menambahkan atribut virtual (accessor) 
     * untuk menampung teks Arab yang sudah diproses oleh ar-php
     * tanpa menyimpannya ke database.
     */
    public $nama_arab_cetak;
}