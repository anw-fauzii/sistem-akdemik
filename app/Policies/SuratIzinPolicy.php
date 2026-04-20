<?php

namespace App\Policies;

use App\Models\SuratIzin;
use App\Models\User;

class SuratIzinPolicy
{
    public function view(User $user, SuratIzin $surat): bool
    {
        if ($user->hasRole('admin')) return true;
        if ($user->hasRole('ortu')) return $user->anggota_kelas_id === $surat->anggota_kelas_id;
        return $user->email === $surat->anggotaKelas->siswa_nis;
    }

    public function update(User $user, SuratIzin $surat): bool
    {
        return $surat->tanggal->isFuture() || $surat->tanggal->isToday();
    }

    public function delete(User $user, SuratIzin $surat): bool
    {
        return $this->update($user, $surat);
    }
}