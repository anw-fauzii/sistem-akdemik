<?php
namespace App\Services;

use App\Models\MataPelajaran;
use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Collection;

class MataPelajaranService
{
    public function getAll(): Collection
    {
        $tahunAjaranAktif = TahunAjaran::latest()->first();
        return MataPelajaran::where('tahun_ajaran_id', $tahunAjaranAktif->id)->with(['tahunAjaran', 'kategori'])->get();
    }

    public function store(array $data): MataPelajaran
    {
        $latestTahunAjaran = TahunAjaran::latest()->first();
        $data['tahun_ajaran_id'] = $latestTahunAjaran ? $latestTahunAjaran->id : null;

        return MataPelajaran::create($data);
    }

    public function update(MataPelajaran $mataPelajaran, array $data): bool
    {
        $latestTahunAjaran = TahunAjaran::latest()->first();
        
        if ($latestTahunAjaran) {
            $data['tahun_ajaran_id'] = $latestTahunAjaran->id;
        }

        return $mataPelajaran->update($data);
    }

    public function delete(MataPelajaran $mataPelajaran): bool
    {
        return $mataPelajaran->delete();
    }

    public function duplicateFromPreviousSemester(): array
    {
        $semesters = TahunAjaran::latest()->take(2)->get();

        if ($semesters->count() < 2) {
            return [
                'status' => 'error', 
                'message' => 'Minimal harus ada 2 data semester (Tahun Ajaran) di sistem untuk melakukan penyalinan.'
            ];
        }

        $semesterBaru = $semesters[0];
        $semesterLama = $semesters[1];

        $mapelSudahAda = MataPelajaran::where('tahun_ajaran_id', $semesterBaru->id)->exists();
        if ($mapelSudahAda) {
            return [
                'status' => 'warning', 
                'message' => 'Gagal menyalin. Mata pelajaran untuk semester saat ini sudah ada.'
            ];
        }
        $mapelLama = MataPelajaran::where('tahun_ajaran_id', $semesterLama->id)->get();

        if ($mapelLama->isEmpty()) {
            return [
                'status' => 'warning', 
                'message' => 'Tidak ada mata pelajaran di semester sebelumnya untuk disalin.'
            ];
        }

        $dataInsert = [];
        $now = now();

        foreach ($mapelLama as $mapel) {
            $dataInsert[] = [
                'tahun_ajaran_id'            => $semesterBaru->id,
                'kategori_mata_pelajaran_id' => $mapel->kategori_mata_pelajaran_id,
                'nama_mapel'                 => $mapel->nama_mapel,
                'ringkasan_mapel'            => $mapel->ringkasan_mapel,
                'created_at'                 => $now,
                'updated_at'                 => $now,
            ];
        }

        MataPelajaran::insert($dataInsert);

        return [
            'status' => 'success', 
            'message' => count($dataInsert) . ' mata pelajaran berhasil disalin ke semester baru.'
        ];
    }
}