<?php

namespace App\Services;

use App\Models\TahunAjaran;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class TahunAjaranService
{
    public function getAll(): Collection
    {
        return TahunAjaran::withCount('kelas')->get();
    }

    public function store(array $data): TahunAjaran
    {
        return DB::transaction(function () use ($data) {
            $tahun = TahunAjaran::create($data);
            $this->resetSiswaData();
            return $tahun;
        });
    }

    public function update(TahunAjaran $tahunAjaran, array $data): bool
    {
        return $tahunAjaran->update($data);
    }

    public function delete(TahunAjaran $tahunAjaran): bool
    {
        if ($tahunAjaran->kelas()->exists()) {
            throw new \Exception("Tahun ajaran tidak bisa dihapus karena masih memiliki kelas.");
        }

        return DB::transaction(function () use ($tahunAjaran) {
            $deleted = $tahunAjaran->delete();
            $this->resetSiswaData();
            return $deleted;
        });
    }

    /**
     * Mengosongkan data penempatan siswa saat pergantian tahun/semester.
     */
    protected function resetSiswaData(): void
    {
        // Gunakan chunk jika data sangat besar, atau query direct untuk kecepatan
        Siswa::where('status', true)->update([
            'kelas_id'           => null,
            'guru_nipy'          => null,
            'ekstrakurikuler_id' => null
        ]);
    }
}