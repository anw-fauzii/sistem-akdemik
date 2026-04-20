<?php

namespace App\Services;

use App\Models\BulanSpp;
use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Collection;

class BulanSppService
{
    private function getActiveYearId(): int
    {
        // Logika pusat untuk menentukan tahun ajaran mana yang sedang diproses
        $tahun = TahunAjaran::latest()->first();
        return $tahun ? $tahun->id : abort(404, 'Tahun Ajaran tidak ditemukan.');
    }

    public function getAllInActiveYear(): Collection
    {
        return BulanSpp::where('tahun_ajaran_id', $this->getActiveYearId())
            ->orderBy('bulan_angka', 'asc')
            ->get();
    }

    public function store(array $data): BulanSpp
    {
        $data['tahun_ajaran_id'] = $this->getActiveYearId();
        return BulanSpp::create($data);
    }

    public function update(BulanSpp $bulanSpp, array $data): bool
    {
        return $bulanSpp->update($data);
    }

    public function delete(BulanSpp $bulanSpp): bool
    {
        // Tambahkan logika proteksi jika bulan sudah memiliki transaksi pembayaran
        if ($bulanSpp->pembayaranSpp()->exists()) {
            throw new \Exception("Bulan ini tidak bisa dihapus karena sudah memiliki data transaksi.");
        }
        return $bulanSpp->delete();
    }
}