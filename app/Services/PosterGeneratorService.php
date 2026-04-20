<?php

namespace App\Services;

use App\Models\PrestasiSiswa;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Interfaces\ImageInterface;

class PosterGeneratorService
{
    protected ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    public function generate(PrestasiSiswa $prestasi): ImageInterface
    {
        // 1. Olah Foto Siswa
        $foto = $this->manager->read(Storage::disk('public')->path($prestasi->file_sertifikat));
        $foto->resize(512, 512);

        // 2. Siapkan Kanvas & Template
        $template = $this->manager->read(public_path('frame.png'));
        $canvas = $this->manager->create(1000, 1000);

        // Susun Gambar (Foto di bawah, Frame di atas)
        $canvas->place($foto, 'center', 0, -100);
        $canvas->place($template, 'center');

        // 3. Tentukan Nama & Posisi
        $nama = $this->getNamaLabel($prestasi);
        $isLongName = (strlen($nama) > 50);
        $fontSizeNama = $isLongName ? 17 : 22;
        $startY = ($prestasi->keterangan || $prestasi->anggotaKelas->count() === 1) ? 700 : 700;

        // 4. Gambar Nama
        $canvas->text($nama, 270, $startY, function ($font) use ($fontSizeNama) {
            $font->filename(public_path('fonts/Poppins-Bold.ttf'));
            $font->size($fontSizeNama);
            $font->color('#ffffff');
            $font->align('left');
            $font->valign('top');
            $font->angle(-1.5);
            $font->lineHeight(1.6);
            $font->wrap(650);
        });

        // 5. Gambar Deskripsi & Tanggal
        $this->drawDeskripsi($canvas, $prestasi, $nama, $startY, $fontSizeNama);

        return $canvas;
    }

    protected function getNamaLabel(PrestasiSiswa $prestasi): string
    {
        if ($prestasi->keterangan) return $prestasi->keterangan;
        return $prestasi->anggotaKelas->map(fn($item) => $item->siswa->nama_lengkap)->implode(', ');
    }

    protected function drawDeskripsi($canvas, $prestasi, $nama, $startY, $fontSizeNama): void
    {
        $lineHeightNama = 1.6;
        $estimasiKarakterPerBaris = 65; 
        $jumlahBarisNama = ceil(strlen($nama) / $estimasiKarakterPerBaris);
        
        $tinggiTotalNama = ($jumlahBarisNama > 1) 
            ? (($jumlahBarisNama - 1) * ($fontSizeNama * $lineHeightNama)) + ($fontSizeNama * 0.4)
            : $fontSizeNama;

        $currentYDeskripsi = $startY + $tinggiTotalNama + 8;

        // Logika Teks Kelas
        $kelasList = $prestasi->anggotaKelas->map(fn($item) => $item->kelas->tingkatan_kelas)->unique()->sort()->values();
        if ($kelasList->count() == 1) {
            $kelasText = 'Kelas ' . $kelasList->first();
        } elseif ($kelasList->count() == 2) {
            $kelasText = 'Kelas ' . $kelasList->implode(' dan ');
        } else {
            $last = $kelasList->pop();
            $kelasText = 'Kelas ' . $kelasList->implode(', ') . ', dan ' . $last;
        }

        $deskripsi = "Siswa " . $kelasText . " SD Garut Islamic School Prima Insani yang meraih ". $prestasi->peringkat . " Pada ". $prestasi->nama_prestasi .".";

        $canvas->text($deskripsi, 270, $currentYDeskripsi, function ($font) {
            $font->filename(public_path('fonts/Poppins-Regular.ttf'));
            $font->size(14);
            $font->color('#ffffff');
            $font->align('left');
            $font->valign('top');
            $font->angle(-1.5);
            $font->lineHeight(1.9);
            $font->wrap(610);
        });

        $tanggal = $prestasi->tanggal->translatedFormat('d F Y');
        $canvas->text($tanggal, 780, 810, function ($font) {
            $font->filename(public_path('fonts/Poppins-Bold.ttf'));
            $font->size(22);
            $font->color('#ffffff');
            $font->align('right');
        });
    }
}