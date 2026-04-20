<?php

namespace App\Services;

use App\Models\SuratIzin;
use App\Models\AnggotaKelas;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class SuratIzinService
{
    public function getActiveStudentMember(string $email): ?AnggotaKelas
    {
        return AnggotaKelas::whereSiswaNis($email)
            ->tahunAjaranAktif()
            ->first();
    }

    public function store(array $data, ?UploadedFile $file): SuratIzin
    {
        if ($file) {
            $data['file'] = $file->store('surat_izin', 'public');
        }

        return SuratIzin::create($data);
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