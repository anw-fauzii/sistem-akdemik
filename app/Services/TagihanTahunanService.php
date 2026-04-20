<?php

namespace App\Services;

use App\Models\TagihanTahunan;
use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Collection;

class TagihanTahunanService
{
    /**
     * Sentralisasi logika Tahun Ajaran agar tidak ada inkonsistensi query
     */
    public function getActiveTahunAjaran(): TahunAjaran
    {
        // Saya menstandarkan menggunakan latest() sesuai dengan method store Anda sebelumnya.
        // Jika aturan bisnis mewajibkan semester 1, tambahkan whereSemester('1') di sini.
        $tahun = TahunAjaran::latest()->first();

        if (!$tahun) {
            abort(400, 'Data Tahun Ajaran belum diatur di sistem.');
        }

        return $tahun;
    }

    public function getAllActive(): Collection
    {
        $tahun = $this->getActiveTahunAjaran();
        
        return TagihanTahunan::where('tahun_ajaran_id', $tahun->id)
            ->orderBy('jenjang', 'asc')
            ->get();
    }

    public function store(array $data): TagihanTahunan
    {
        $tahun = $this->getActiveTahunAjaran();
        $data['tahun_ajaran_id'] = $tahun->id;
        
        return TagihanTahunan::create($data);
    }

    public function update(TagihanTahunan $tagihanTahunan, array $data): bool
    {
        return $tagihanTahunan->update($data);
    }

    public function delete(TagihanTahunan $tagihanTahunan): bool
    {
        // Proteksi integritas data
        if ($tagihanTahunan->pembayaranTagihanTahunan()->exists()) {
            throw new \Exception('Tagihan tidak bisa dihapus karena sudah ada riwayat pembayaran siswa.');
        }

        return $tagihanTahunan->delete();
    }
}