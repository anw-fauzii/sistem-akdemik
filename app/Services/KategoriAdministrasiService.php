<?php
namespace App\Services;

use App\Models\KategoriAdministrasi;
use Illuminate\Database\Eloquent\Collection;

class KategoriAdministrasiService
{
    public function getAll(): Collection
    {
        return KategoriAdministrasi::all();
    }

    public function store(array $data): KategoriAdministrasi
    {
        return KategoriAdministrasi::create($data);
    }

    public function update(KategoriAdministrasi $kategori, array $data): bool
    {
        return $kategori->update($data);
    }

    public function delete(KategoriAdministrasi $kategori): bool
    {
        return $kategori->delete();
    }
}