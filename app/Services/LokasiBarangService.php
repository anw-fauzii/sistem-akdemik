<?php
namespace App\Services;

use App\Models\LokasiBarang
;
use Illuminate\Database\Eloquent\Collection;

class LokasiBarangService
{
    public function getAll(): Collection
    {
        return LokasiBarang::all();
    }

    public function store(array $data): LokasiBarang
    {
        return LokasiBarang::create($data);
    }

    public function update(LokasiBarang $lokasiBarang, array $data): bool
    {
        return $lokasiBarang->update($data);
    }

    public function delete(LokasiBarang $lokasiBarang): bool
    {
        return $lokasiBarang->delete();
    }
}