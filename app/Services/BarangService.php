<?php
namespace App\Services;

use App\Models\Barang
;
use Illuminate\Database\Eloquent\Collection;

class BarangService
{
    public function getAll(): Collection
    {
        return Barang::with(['kategori', 'lokasi'])->get();
    }

    public function store(array $data): Barang
    {
        return Barang::create($data);
    }

    public function update(Barang $barang, array $data): bool
    {
        return $barang->update($data);
    }

    public function delete(Barang $barang): bool
    {
        return $barang->delete();
    }
}