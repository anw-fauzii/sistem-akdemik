<?php

namespace App\Services;

use App\Models\JenjangPendidikan;
use Illuminate\Database\Eloquent\Collection;

class JenjangPendidikanService
{
    public function getAll(): Collection
    {
        return JenjangPendidikan::orderBy('nama_jenjang_pendidikan')->get();
    }

    public function store(array $data): JenjangPendidikan
    {
        return JenjangPendidikan::create($data);
    }

    public function update(JenjangPendidikan $jenjang, array $data): bool
    {
        return $jenjang->update($data);
    }

    public function delete(JenjangPendidikan $jenjang): bool
    {
        return $jenjang->delete();
    }
}