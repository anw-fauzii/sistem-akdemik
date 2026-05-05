<?php

namespace App\Services;

use App\Models\KedisiplinanPoin;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Collection;
use Exception;

class KedisiplinanPoinService
{
    public function getAll(): Collection
    {
        return KedisiplinanPoin::orderBy('kategori')
                            ->orderBy('tingkat')
                            ->get();
}

    public function createRule(array $data): KedisiplinanPoin
    {
        try {
            return KedisiplinanPoin::create($data);
        } catch (QueryException $e) {
            report($e);
            throw new Exception('Terjadi kesalahan saat menyimpan aturan ke database.');
        }
    }

    public function updateRule(KedisiplinanPoin $kedisiplinanPoin, array $data): KedisiplinanPoin
    {
        try {
            $kedisiplinanPoin->update($data);
            return $kedisiplinanPoin;
        } catch (QueryException $e) {
            report($e);
            throw new Exception('Terjadi kesalahan saat mengupdate aturan di database.');
        }
    }

    public function deleteRule(KedisiplinanPoin $kedisiplinanPoin): void
    {
        try {
            $kedisiplinanPoin->delete();
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1451) {
                throw new Exception('Aturan ini tidak bisa dihapus karena sudah digunakan dalam riwayat kedisiplinan siswa.');
            }
            report($e);
            throw new Exception('Terjadi kesalahan sistem saat menghapus data.');
        }
    }
}