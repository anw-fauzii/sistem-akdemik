<?php

namespace App\Services;

use App\Models\Penghasilan;
use Illuminate\Database\Eloquent\Collection;

class PenghasilanService
{
    public function getAll(): Collection
    {
        return Penghasilan::all();
    }

    public function store(array $data): Penghasilan
    {
        return Penghasilan::create($data);
    }

    public function update(Penghasilan $penghasilan, array $data): bool
    {
        return $penghasilan->update($data);
    }

    public function delete(Penghasilan $penghasilan): bool
    {
        return $penghasilan->delete();
    }
}