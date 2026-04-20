<?php

namespace App\Services;

use App\Models\TarifSpp;
use Illuminate\Database\Eloquent\Collection;

class TarifSppService
{
    public function getAll(): Collection
    {

        return TarifSpp::orderBy('tahun_masuk', 'desc')->get();
    }

    public function store(array $data): TarifSpp
    {
        return TarifSpp::create($data);
    }

    public function update(TarifSpp $tarifSpp, array $data): bool
    {
        return $tarifSpp->update($data);
    }

    public function delete(TarifSpp $tarifSpp): bool
    {
        if ($tarifSpp->siswa()->exists()) {
            throw new \Exception('Tarif ini tidak bisa dihapus karena sedang digunakan oleh data Siswa.');
        }
        return $tarifSpp->delete();
    }
}