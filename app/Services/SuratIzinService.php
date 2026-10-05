<?php

namespace App\Services;

use App\Models\SuratIzin;
use App\Models\AnggotaKelas;
use App\Models\Presensi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class SuratIzinService
{
    /**
     * Dapatkan anggota kelas siswa yang sedang aktif.
     */
    public function getActiveStudentMember(string $email): ?AnggotaKelas
    {
        return AnggotaKelas::whereSiswaNis($email)
            ->tahunAjaranAktif()
            ->first();
    }

    /**
     * Kueri untuk Role Siswa: Hanya melihat surat izinnnya sendiri.
     */
    public function getListForSiswa(string $email): Collection
    {
        $anggota = $this->getActiveStudentMember($email);

        if (!$anggota) {
            return collect();
        }

        // Menggunakan Eager Loading agar UI Blade tidak terkena N+1 jika memanggil data kelas/siswa
        return SuratIzin::with(['anggotaKelas.siswa', 'anggotaKelas.kelas'])
            ->where('anggota_kelas_id', $anggota->id)
            ->latest()
            ->get(); 
    }

    /**
     * Kueri untuk Role Guru: Hanya melihat surat izin dari kelas yang diajarnya.
     */
    public function getListForGuru(string $email): Collection
    {
        // Eloquent Optimization: Menggunakan whereHas untuk memfilter relasi langsung di level database
        return SuratIzin::with(['anggotaKelas.siswa', 'anggotaKelas.kelas'])
            ->whereHas('anggotaKelas.kelas', function ($query) use ($email) {
                $query->where('guru_nipy', $email)
                      ->orWhere('pendamping_nipy', $email);
            })
            ->latest()
            ->get();
    }

    /**
     * Kueri untuk Role Admin: Melihat seluruh data surat izin.
     */
    public function getAllList(): Collection
    {
        return SuratIzin::with(['anggotaKelas.siswa', 'anggotaKelas.kelas'])
            ->latest()
            ->get();
    }

    public function store(array $data, ?UploadedFile $file): SuratIzin
    {
        return DB::transaction(function () use ($data, $file) {
            
            // 1. Upload File (jika ada)
            if ($file) {
                $data['file'] = $file->store('surat_izin', 'public');
            }

            // 2. Simpan Data Surat Izin
            $suratIzin = SuratIzin::create($data);

            // 3. INTEGRASI OTOMATIS KE TABEL PRESENSI
            $startDate = Carbon::parse($data['tanggal_mulai']);
            $endDate = Carbon::parse($data['tanggal_selesai']);

            // Buat rentang tanggal (Misal: 11 Mei s/d 14 Mei)
            $period = CarbonPeriod::create($startDate, $endDate);

            foreach ($period as $date) {
                // LOGIKA CERDAS: Skip otomatis jika hari Sabtu atau Minggu
                // isWeekend() akan mengecek hari Sabtu (6) dan Minggu (0)
                if ($date->isWeekend()) {
                    continue; 
                }

                Presensi::updateOrCreate(
                    [
                        'anggota_kelas_id' => $data['anggota_kelas_id'],
                        'tanggal'          => $date->format('Y-m-d'),
                    ],
                    [
                        'status'          => $data['jenis'],
                        'terlambat'       => 0,
                        'menit_terlambat' => 0,
                    ]
                );
            }

            return $suratIzin;
        });
    }

    public function update(SuratIzin $surat, array $data, ?UploadedFile $file): bool
    {
        if ($file) {
            if ($surat->file) Storage::disk('public')->delete($surat->file);
            $data['file'] = $file->store('surat_izin', 'public');
        }

        return $surat->update($data);
    }

    public function delete(SuratIzin $surat): bool
    {
        if ($surat->file) Storage::disk('public')->delete($surat->file);
        return $surat->delete();
    }
}