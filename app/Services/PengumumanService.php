<?php

namespace App\Services;

use App\Models\Pengumuman;
use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Collection;

class PengumumanService
{
    public function getActiveAnnouncements(): Collection
    {
        $tahunAktif = TahunAjaran::latest()->first();

        return Pengumuman::where('tahun_ajaran_id', $tahunAktif?->id)
            ->latest('tanggal')
            ->get();
    }

    public function store(array $data): Pengumuman
    {
        $tahunAktif = TahunAjaran::latest()->first();
        $data['tahun_ajaran_id'] = $tahunAktif?->id;

        return Pengumuman::create($data);
    }

    public function update(Pengumuman $pengumuman, array $data): bool
    {
        return $pengumuman->update($data);
    }

    public function delete(Pengumuman $pengumuman): bool
    {
        return $pengumuman->delete();
    }
}