<?php

namespace App\Services;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Collection;

class GuruService
{
    public function getAll(): Collection
    {
        return Guru::all(); // Tambahkan pagination jika data di atas 1000
    }

    public function store(array $data): Guru
    {
        return DB::transaction(function () use ($data) {
            // 1. Buat User (Gunakan NIPY sebagai email/username)
            $user = User::create([
                'name'     => $data['nama_lengkap'],
                'email'    => $data['nipy'],
                'password' => Hash::make('pass1234'),
            ]);
            
            // Catatan: Di kode lama hardcode 'guru_tk', sebaiknya ini dinamis dari request
            $user->assignRole('guru_tk'); 

            // 2. Buat Guru
            return Guru::create($data);
        });
    }

    public function update(Guru $guru, array $data): bool
    {
        return DB::transaction(function () use ($guru, $data) {
            // Jika NIPY berubah atau Nama berubah, update User terkait
            if ($guru->nipy !== $data['nipy'] || $guru->nama_lengkap !== $data['nama_lengkap']) {
                User::where('email', $guru->nipy)->update([
                    'name'  => $data['nama_lengkap'],
                    'email' => $data['nipy'],
                ]);
            }

            return $guru->update($data);
        });
    }

    public function delete(Guru $guru): void
    {
        DB::transaction(function () use ($guru) {
            // Karena relasi User dan Guru terikat via NIPY (di kolom email)
            User::where('email', $guru->nipy)->delete();
            $guru->delete();
        });
    }
}