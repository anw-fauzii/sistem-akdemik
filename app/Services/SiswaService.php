<?php

namespace App\Services;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SiswaService
{
    public function getAll(): Collection
    {
        return Siswa::with('kelas')->get();
    }

    public function createSiswa(array $data): Siswa
    {
        return DB::transaction(function () use ($data) {
            // 1. Buat Akun User
            $user = User::create([
                'name'     => $data['nama_lengkap'],
                'email'    => $data['nis'], // Menggunakan NIS sebagai username/email login
                'password' => Hash::make('pass1234'),
            ]);
            $user->assignRole('siswa');

            // 2. Buat Data Siswa
            $data['agama'] = $data['agama'] ?? 1;
            return Siswa::create($data);
        });
    }

    public function updateSiswa(Siswa $siswa, array $data): bool
    {
        return DB::transaction(function () use ($siswa, $data) {
            // Update User terkait jika NIS berubah
            if ($siswa->nis !== $data['nis']) {
                User::where('email', $siswa->nis)->update([
                    'email' => $data['nis'],
                    'name'  => $data['nama_lengkap']
                ]);
            }

            return $siswa->update($data);
        });
    }

    public function deleteSiswa(Siswa $siswa): void
    {
        DB::transaction(function () use ($siswa) {
            User::where('email', $siswa->nis)->delete();
            $siswa->delete();
        });
    }
}