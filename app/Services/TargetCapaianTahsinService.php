<?php
namespace App\Services;

use App\Models\TargetCapaianTahsin;
use Illuminate\Database\Eloquent\Collection;

class TargetCapaianTahsinService
{
    public function getAll(): Collection
    {
        return TargetCapaianTahsin::with(['tahunAjaran','daftarJilid','surahAlquran'])->get();
    }

    public function store(array $data): TargetCapaianTahsin
    {
        return TargetCapaianTahsin::create($data);
    }

    public function update(TargetCapaianTahsin $target, array $data): bool
    {
        return $target->update($data);
    }

    public function delete(TargetCapaianTahsin $target): bool
    {
        return $target->delete();
    }
}