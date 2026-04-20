<?php

namespace App\Services;

use App\Models\Jemputan;
use App\Models\AnggotaKelas;
use App\Models\TahunAjaran;
use Illuminate\Support\Collection;

class JemputanService
{
    public function getActiveTahunAjaran(): TahunAjaran
    {
        return TahunAjaran::latest()->first() 
            ?? abort(redirect()->route('tahun-ajaran.index')->with('warning', 'Isi tahun ajaran terlebih dahulu!'));
    }

    public function getAllJemputan(int $tahunAjaranId): Collection
    {
        return Jemputan::withCount('anggotaJemputan')
            ->whereTahunAjaranId($tahunAjaranId)
            ->orderBy('id', 'ASC')
            ->get();
    }

    public function getSiswaTersedia(int $tahunAjaranId): Collection
    {
        return AnggotaKelas::with(['siswa:nis,nama_lengkap', 'kelas:id,nama_kelas'])
            ->whereHas('kelas', fn($q) => $q->whereTahunAjaranId($tahunAjaranId))
            ->whereDoesntHave('anggotaJemputan')
            ->get();
    }

    public function store(array $data, int $tahunAjaranId): Jemputan
    {
        $data['tahun_ajaran_id'] = $tahunAjaranId;
        return Jemputan::create($data);
    }
}