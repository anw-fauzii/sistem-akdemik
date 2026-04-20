<?php

namespace App\Services;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserProfileService
{
    /**
     * Mengambil profil lengkap siswa beserta seluruh relasi keluarganya.
     */
    public function getSiswaProfile(string $nis): ?Siswa
    {
        return Siswa::with([
            'pekerjaan_ayah', 'penghasilan_ayah', 'berkebutuhan_khusus_ayah', 'jenjang_pendidikan_ayah',
            'pekerjaan_ibu', 'penghasilan_ibu', 'berkebutuhan_khusus_ibu', 'jenjang_pendidikan_ibu',
            'pekerjaan_wali', 'penghasilan_wali', 'berkebutuhan_khusus_wali', 'jenjang_pendidikan_wali'
        ])->findOrFail($nis);
    }

    /**
     * Memperbarui password user.
     */
    public function updatePassword(User $user, string $newPassword): bool
    {
        $user->password = Hash::make($newPassword);
        return $user->save();
    }
}