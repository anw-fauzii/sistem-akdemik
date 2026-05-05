<?php

namespace App\Services;

use App\Models\AnggotaKelas;
use App\Models\KedisiplinanPoin;
use App\Models\KedisiplinanSiswa;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Collection;
use Exception;

class KedisiplinanSiswaService
{
    /**
     * Mengambil semua data dengan Eager Loading untuk relasi
     */
    public function getAll(): Collection
    {
        return KedisiplinanSiswa::with(['anggotaKelas', 'kedisiplinanPoin'])
                                ->orderBy('tanggal_kejadian', 'desc')
                                ->get();
    }

    public function getAturanList(): Collection
    {
        return KedisiplinanPoin::orderBy('kategori')
                            ->orderBy('nama_aturan')
                            ->get();
    }

    public function getSiswaList()
    {
        return AnggotaKelas::with(['siswa', 'kelas'])
            ->tahunAjaranAktif()
            ->get();
    }

    public function recordPoint(array $data): KedisiplinanSiswa
    {
        try {
            return KedisiplinanSiswa::create($data);
        } catch (QueryException $e) {
            report($e);
            throw new Exception('Gagal menyimpan data kedisiplinan siswa ke database.');
        }
    }

    public function updateRecord(KedisiplinanSiswa $kedisiplinanSiswa, array $data): KedisiplinanSiswa
    {
        try {
            $kedisiplinanSiswa->update($data);
            return $kedisiplinanSiswa;
        } catch (QueryException $e) {
            report($e);
            throw new Exception('Gagal memperbarui data kedisiplinan siswa.');
        }
    }

    public function deleteRecord(KedisiplinanSiswa $kedisiplinanSiswa): void
    {
        try {
            $kedisiplinanSiswa->delete();
        } catch (QueryException $e) {
            report($e);
            throw new Exception('Terjadi kesalahan sistem saat menghapus data.');
        }
    }
}