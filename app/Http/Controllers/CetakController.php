<?php

namespace App\Http\Controllers;

use App\Models\AngkaArab;
use App\Models\DaftarJilid;
use App\Models\SurahAlquran;
use Barryvdh\DomPDF\Facade\Pdf;
use ArPHP\I18N\Arabic; 

class CetakController extends Controller
{
    public function cetakTahsin()
    {
        $arabic = new Arabic();

        // 1. Ambil semua data surah dari database
        $dataSurah = DaftarJilid::all();

        // 2. Looping untuk memproses teks Arab
        foreach ($dataSurah as $surah) {
            
            $teksMentah = $surah->jilid_arab ?? ''; 

            if (trim($teksMentah) !== '') {
                // Pecah berdasarkan spasi untuk menghindari error PHP 8
                $words = explode(' ', $teksMentah);
                $processedWords = [];

                foreach ($words as $word) {
                    if (trim($word) !== '') {
                        $processedWords[] = @$arabic->utf8Glyphs($word);
                    }
                }

                // Balik urutan kata dan gabungkan
                // Kita simpan hasil prosesnya di properti baru bernama 'nama_arab_cetak'
                $surah->nama_arab_cetak = implode(' ', array_reverse($processedWords));
                
            } else {
                $surah->nama_arab_cetak = '-'; 
            }
        }
        $nilaiSiswa = 83; // Misal ambil dari database nilai siswa
    
    // Cari angka arabnya di tabel referensi
        $arab = AngkaArab::where('id', $nilaiSiswa)->first();

        // 3. Load view dan lempar datanya (nama variabelnya sekarang $dataSurah)
        $pdf = Pdf::loadView('pdf.daftar_surah', ['dataSurah' => $dataSurah,'arab' => $arab]);

        $pdf->setOptions([
            'isRemoteEnabled' => true,
            'chroot' => public_path(),
        ]);

        // Pakai portrait saja karena kolomnya sedikit
        $pdf->setPaper('a4', 'portrait'); 
        
        return $pdf->stream('Daftar_Surah_Alquran.pdf');
    }
}