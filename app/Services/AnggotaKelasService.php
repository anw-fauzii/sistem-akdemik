<?php 

namespace App\Services;

use App\Models\AnggotaKelas;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AnggotaKelasService
{
    public function addStudentsToClass(array $data): void
    {
        DB::transaction(function () use ($data) {
            $siswaNis = $data['siswa_nis'];
            $kelasId = $data['kelas_id'];

            $insertData = collect($siswaNis)->map(fn($nis) => [
                'siswa_nis'   => $nis,
                'kelas_id'    => $kelasId,
                'pendaftaran' => $data['pendaftaran'] ?? null,
                'created_at'  => now(),
                'updated_at'  => now(),
            ])->toArray();

            // 1. Insert ke tabel Anggota Kelas
            AnggotaKelas::insert($insertData);

            // 2. Update field kelas_id di tabel Siswa
            Siswa::whereIn('nis', $siswaNis)->update(['kelas_id' => $kelasId]);
        });
    }

    public function removeStudentFromClass(AnggotaKelas $anggota): void
    {
        DB::transaction(function () use ($anggota) {
            // Update siswa kembali ke null
            Siswa::where('nis', $anggota->siswa_nis)->update(['kelas_id' => null]);
            $anggota->delete();
        });
    }

    public function getGuruClassData(): array
    {
        $tahun = TahunAjaran::latest()->first() ?? abort(404, 'Tahun ajaran belum diatur');

        $kelas = Kelas::where('tahun_ajaran_id', $tahun->id)
            ->where(fn($q) => 
                $q->where('guru_nipy', Auth::user()->email)
                  ->orWhere('pendamping_nipy', Auth::user()->email)
            )->firstOrFail();

        $anggotaKelas = AnggotaKelas::with(['siswa.ekstrakurikuler', 'siswa.guru'])
            ->where('kelas_id', $kelas->id)
            ->get();

        return compact('kelas', 'anggotaKelas');
    }
}