<?php

namespace App\Services;

use App\Models\AnggotaT2Q;
use App\Models\BulanSpp;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\TargetCapaianTahsin;
use App\Models\YaumiyahTahsin;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class StatistikTahsinService
{
    public function generateDashboardDataDidik(string $guruNipy, string $tingkat, ?int $bulanSppId = null, ?TahunAjaran $tahunAjaran = null): array
    {
        $siswaQuery = AnggotaT2Q::where('guru_nipy', $guruNipy);
        return $this->buildDashboardMetrics($siswaQuery, $tingkat, $bulanSppId, $tahunAjaran);
    }

    public function generateDashboardDataLengkap(string $tingkat, ?int $bulanSppId = null, ?TahunAjaran $tahunAjaran = null): array
    {
        $siswaQuery = AnggotaT2Q::query(); 
        return $this->buildDashboardMetrics($siswaQuery, $tingkat, $bulanSppId, $tahunAjaran);
    }

    public function generateDashboardDataByKelas(?Kelas $kelas = null, ?int $bulanSppId = null, ?TahunAjaran $tahunAjaranAktif = null): array
    {
        $siswaQuery = AnggotaT2Q::whereHas('anggotaKelas', function($query) use ($kelas) {
            $query->where('kelas_id', $kelas->id);
        });

        $tingkatan = $kelas->tingkatan_kelas;

        return $this->buildDashboardMetrics($siswaQuery, $tingkatan, $bulanSppId, $tahunAjaranAktif);
    }

    private function resolveBulanSpp(?int $bulanSppId): BulanSpp
    {
        return $bulanSppId ? BulanSpp::findOrFail($bulanSppId) : BulanSpp::latest('bulan_angka')->firstOrFail();
    }

    private function buildDashboardMetrics(Builder $siswaQuery, string $tingkat, ?int $bulanSppId, ?TahunAjaran $tahunAjaranAktif): array
    {
        $bulanSpp = $this->resolveBulanSpp($bulanSppId);
        $parsedDate = Carbon::parse($bulanSpp->bulan_angka);

        $siswa = $siswaQuery->with(['anggotaKelas.siswa', 'anggotaKelas.kelas'])
            ->whereHas('anggotaKelas', fn (Builder $query) => $query->tahunAjaranAktif())
            ->where('tingkat', $tingkat)
            ->get();

        $siswaIds = $siswa->pluck('id')->toArray();
        $totalSiswa = $siswa->count();

        if (empty($siswaIds)) {
            return $this->emptyDashboardResponse($tingkat, $bulanSpp);
        }

        $baseYaumiyahQuery = YaumiyahTahsin::whereIn('anggota_t2q_id', $siswaIds)
            ->whereMonth('tanggal', $parsedDate->format('m'))
            ->whereYear('tanggal', $parsedDate->format('Y'));

        $aggregateStats = (clone $baseYaumiyahQuery)
            ->select(
                DB::raw('COALESCE(AVG(nilai), 0) as avg_bulan_ini'),
                DB::raw('COUNT(id) as total_setoran')
            )->first();

        $latestYaumiyahIds = (clone $baseYaumiyahQuery)
            ->select(DB::raw('MAX(id) as id'))
            ->groupBy('anggota_t2q_id')
            ->pluck('id');

        $latestYaumiyah = YaumiyahTahsin::with('daftarJilid')
            ->whereIn('id', $latestYaumiyahIds)
            ->get();

        $sebaranJilid = $latestYaumiyah->groupBy(fn($item) => $item->daftarJilid->jilid_latin ?? 'Belum Ada Jilid')->map->count();

        $targetCapaian = TargetCapaianTahsin::with('daftarJilid')
            ->where('tahun_ajaran_id', $tahunAjaranAktif->id ?? null)
            ->where('tingkat', $tingkat)
            ->first();

        $mencapaiTarget = 0;
        $belumMencapai = 0;
        $targetJilidNama = $targetCapaian->daftarJilid->jilid_latin ?? 'Belum Di-setting';
        $targetUrutan = $targetCapaian->daftarJilid->urutan ?? 999; 

        foreach ($siswaIds as $siswaId) {
            $record = $latestYaumiyah->firstWhere('anggota_t2q_id', $siswaId);
            if ($record && $record->daftarJilid && $record->daftarJilid->urutan >= $targetUrutan) {
                $mencapaiTarget++;
            } else {
                $belumMencapai++;
            }
        }

        $trenNilai = (clone $baseYaumiyahQuery)
            ->selectRaw('tanggal, avg(nilai) as rata_rata')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        $rankSiswa = (clone $baseYaumiyahQuery)
            ->selectRaw('anggota_t2q_id, avg(nilai) as rata_rata, count(*) as total_setoran')
            ->groupBy('anggota_t2q_id')
            ->get()
            ->keyBy('anggota_t2q_id');

        $siswaStats = $siswa->map(function($s) use ($rankSiswa) {
            $stat = $rankSiswa->get($s->id);
            $s->rata_rata = $stat ? round($stat->rata_rata, 1) : 0;
            $s->total_setoran = $stat ? $stat->total_setoran : 0;
            return $s;
        });

        $siswaYangSetor = $siswaStats->where('total_setoran', '>', 0);

        return [
            'tingkat'         => $tingkat,
            'bulanSpp'        => $bulanSpp,
            'namaBulan'       => $bulanSpp->nama_bulan,
            'totalSiswa'      => $totalSiswa,
            'avgBulanIni'     => $aggregateStats->avg_bulan_ini,
            'totalSetoran'    => $aggregateStats->total_setoran,
            'sebaranJilid'    => $sebaranJilid,
            'targetCapaian'   => $targetCapaian,
            'mencapaiTarget'  => $mencapaiTarget,
            'belumMencapai'   => $belumMencapai,
            'targetJilidNama' => $targetJilidNama,
            'trenNilai'       => $trenNilai,
            'topSiswa'        => $siswaYangSetor->sortByDesc('rata_rata')->take(5),
            'bottomSiswa'     => $siswaYangSetor->filter(fn($s) => $s->rata_rata < 75)->sortBy('rata_rata')->take(5),
            'perluPerhatian'  => $siswaYangSetor->filter(fn($s) => $s->rata_rata < 75)->count(),
        ];
    }

    private function emptyDashboardResponse(string $tingkat, BulanSpp $bulanSpp): array
    {
        return [
            'tingkat'         => $tingkat,
            'bulanSpp'        => $bulanSpp,
            'namaBulan'       => $bulanSpp->nama_bulan,
            'totalSiswa'      => 0,
            'avgBulanIni'     => 0,
            'totalSetoran'    => 0,
            'sebaranJilid'    => collect(),
            'targetCapaian'   => null,
            'mencapaiTarget'  => 0,
            'belumMencapai'   => 0,
            'targetJilidNama' => 'Belum Di-setting',
            'trenNilai'       => collect(),
            'topSiswa'        => collect(),
            'bottomSiswa'     => collect(),
            'perluPerhatian'  => 0,
        ];
    }
}