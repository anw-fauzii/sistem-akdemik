<?php

namespace App\Services;

use App\Models\Agenda;
use App\Models\AnggotaKelas;
use App\Models\BulanSpp;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Kesehatan;
use App\Models\Pengumuman;
use App\Models\Presensi;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DashboardService
{
    protected function getPengumumanTerbaru(): Collection
    {
        return Pengumuman::latest('id')->take(3)->get();
    }

    protected function formatAgenda(string $unit = null): Collection
    {
        $query = Agenda::query();
        if ($unit) {
            $query->where('unit', $unit);
        }

        return $query->get()->map(function ($agenda) {
            return [
                'title' => "{$agenda->unit}: {$agenda->kegiatan}",
                'start' => $agenda->tanggal,
                'color' => $agenda->unit === 'SD' ? '#007bff' : '#f39c12',
            ];
        });
    }

    public function getAdminDashboardData(): array
    {
        $tahunAjaran = TahunAjaran::latest()->first();

        return [
            'agenda'   => $this->formatAgenda(),
            'siswa_tk' => Siswa::whereHas('kelas', fn($q) => $q->where('jenjang', 'PG TK'))->where('status', '1')->count(),
            'siswa_sd' => Siswa::whereHas('kelas', fn($q) => $q->where('jenjang', 'SD'))->where('status', '1')->count(),
            'kelas'    => Kelas::where('tahun_ajaran_id', $tahunAjaran->id)->count(),
            'guru'     => Guru::where('status', '1')->count(),
        ];
    }

    public function getSiswaDashboardData(string $email): array
    {
        $tahunAjaran = TahunAjaran::latest()->firstOrFail();
        
        // Eager load relasi kelas untuk mencegah N+1
        $kelas = AnggotaKelas::with('kelas')
            ->where('siswa_nis', $email)
            ->whereHas('kelas', fn($q) => $q->where('tahun_ajaran_id', $tahunAjaran->id))
            ->firstOrFail();

        $riwayatKesehatan = Kesehatan::with('bulanSpp')
            ->where('anggota_kelas_id', $kelas->id)
            ->orderBy('bulan_spp_id')
            ->get();

        return [
            'pengumuman' => $this->getPengumumanTerbaru(),
            'agenda'     => $this->formatAgenda($kelas->kelas->jenjang),
            'bulanLabels'=> $riwayatKesehatan->map(fn($k) => $k->bulanSpp->nama_bulan),
            'tbData'     => $riwayatKesehatan->pluck('tb')->map(fn($val) => (float) $val)->values()->all(),
            'bbData'     => $riwayatKesehatan->pluck('bb')->map(fn($val) => (float) $val)->values()->all(),
        ];
    }

    public function getGuruDashboardData(string $email): array
    {
        $guru = Guru::findOrFail($email);
        $tahunAjaran = TahunAjaran::latest()->firstOrFail();
        
        $kelas = Kelas::where('tahun_ajaran_id', $tahunAjaran->id)
            ->where(function ($q) use ($email) {
                $q->where('guru_nipy', $email)->orWhere('pendamping_nipy', $email);
            })->firstOrFail();

        $anggotaKelasIds = AnggotaKelas::where('kelas_id', $kelas->id)->pluck('id');
        
        $presensiAll = Presensi::whereIn('anggota_kelas_id', $anggotaKelasIds)->get();
        $daftarBulan = BulanSpp::where('tahun_ajaran_id', $tahunAjaran->id)->get();

        $dataChart = $daftarBulan->map(function ($bulan) use ($presensiAll) {
            $parsedBulan = Carbon::parse($bulan->bulan_angka);
            $awal = $parsedBulan->copy()->startOfMonth()->format('Y-m-d');
            $akhir = $parsedBulan->copy()->endOfMonth()->format('Y-m-d');

            // Optimasi RAM: Gunakan string comparison untuk tanggal
            $presensiBulan = $presensiAll->filter(fn($p) => $p->tanggal >= $awal && $p->tanggal <= $akhir);
            
            $hariEfektif = $presensiBulan->count();
            $dataTepatWaktu = $presensiBulan->where('terlambat', false)->count();
            $dataHadir = $presensiBulan->where('status', 'hadir')->count();

            $persentaseHadir = $hariEfektif > 0 ? round(($dataHadir / $hariEfektif) * 100, 1) : 0;
            $persentaseTepatWaktu = $hariEfektif > 0 ? round(($dataTepatWaktu / $hariEfektif) * 100, 1) : 0;

            return [
                'name'      => $parsedBulan->translatedFormat('F Y'),
                'tepat_waktu'=> $persentaseTepatWaktu,
                'hadir'     => $persentaseHadir,
                'absen'     => round(100 - $persentaseHadir, 1),
                'terlambat' => round(100 - $persentaseTepatWaktu, 1),
            ];
        })->toArray();

        return [
            'pengumuman' => $this->getPengumumanTerbaru(),
            'agenda'     => $this->formatAgenda($guru->unit),
            'dataChart'  => $dataChart,
        ];
    }

    public function getPuskesmasDashboardData(): array
    {
        $bulanAktif = BulanSpp::latest('id')->firstOrFail();
        
        $kelasList = Kelas::with(['anggotaKelas.dataKesehatan' => function ($q) use ($bulanAktif) {
            $q->where('bulan_spp_id', $bulanAktif->id);
        }])->get();

        $statistik = $kelasList->map(function ($kelas) {
            $koleksiKesehatan = $kelas->anggotaKelas->map->dataKesehatan->filter();
            
            $jumlah = $koleksiKesehatan->count();
            $belumDiperiksa = $kelas->anggotaKelas->count() - $jumlah;
            $tbTotal = $koleksiKesehatan->sum('tb');
            $bbTotal = $koleksiKesehatan->sum('bb');
            $jumlahMasalah = $koleksiKesehatan->where('hasil', '!=', 'Normal')->count();

            return [
                'nama_kelas'       => $kelas->nama_kelas,
                'jumlah_diperiksa' => $jumlah,
                'belum_diperiksa'  => $belumDiperiksa,
                'rata_tb'          => $jumlah > 0 ? round($tbTotal / $jumlah, 1) : 0,
                'rata_bb'          => $jumlah > 0 ? round($bbTotal / $jumlah, 1) : 0,
                'jumlah_masalah'   => $jumlahMasalah,
            ];
        });

        return [
            'pengumuman' => $this->getPengumumanTerbaru(),
            'statistik'  => $statistik,
        ];
    }
}