<?php
namespace App\Services;

use App\Models\KategoriMataPelajaran;
use Illuminate\Database\Eloquent\Collection;

class KategoriMataPelajaranService
{
    public function getAll(): Collection
    {
        return KategoriMataPelajaran::all();
    }

    public function store(array $data): KategoriMataPelajaran
    {
        return KategoriMataPelajaran::create($data);
    }

    public function update(KategoriMataPelajaran $kategori, array $data): bool
    {
        return $kategori->update($data);
    }

    public function delete(KategoriMataPelajaran $kategori): bool
    {
        return $kategori->delete();
    }
}