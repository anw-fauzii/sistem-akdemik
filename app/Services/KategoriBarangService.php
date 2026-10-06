<?php
namespace App\Services;

use App\Models\KategoriBarang
;
use Illuminate\Database\Eloquent\Collection;

class KategoriBarangService
{
    public function getAll(): Collection
    {
        return KategoriBarang::all();
    }

    public function store(array $data): KategoriBarang
    {
        return KategoriBarang::create($data);
    }

    public function update(KategoriBarang $kategori, array $data): bool
    {
        return $kategori->update($data);
    }

    public function delete(KategoriBarang $kategori): bool
    {
        return $kategori->delete();
    }
}