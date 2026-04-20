<?php

namespace App\Services;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\AnggotaKelas;
use App\Models\TahunAjaran;
use Illuminate\Support\Collection;

class KelasService
{
    public function getActiveTahunAjaran(): ?TahunAjaran
    {
        return TahunAjaran::latest()->first();
    }

    public function getSiswaTanpaKelas(): Collection
    {
        $siswa = Siswa::whereNull('kelas_id')->where('status', true)->get();
        
        if ($siswa->isEmpty()) return collect();

        $nisCollection = $siswa->pluck('nis');

        $riwayat = AnggotaKelas::with('kelas')
            ->whereIn('siswa_nis', $nisCollection)
            ->latest('id')
            ->get()
            ->groupBy('siswa_nis');

        return $siswa->map(function ($s) use ($riwayat) {
            $lastClass = $riwayat->get($s->nis)?->first();
            $s->kelas_sebelumnya = $lastClass?->kelas?->nama_kelas ?? 'Siswa Baru';
            return $s;
        });
    }

    public function storeKelas(array $data): Kelas
    {
        $tahun = $this->getActiveTahunAjaran();
        if (!$tahun) throw new \Exception("Tahun ajaran aktif belum diatur.");

        $data['tahun_ajaran_id'] = $tahun->id;
        return Kelas::create($data);
    }
}