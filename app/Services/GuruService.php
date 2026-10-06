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
        return Guru::all();
    }

    public function store(array $data): Guru
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name'     => $data['nama_lengkap'],
                'email'    => $data['nipy'],
                'password' => Hash::make('pass1234'),
            ]);
            $user->assignRole('guru_tk'); 
            return Guru::create($data);
        });
    }

    public function update(Guru $guru, array $data): bool
    {
        return DB::transaction(function () use ($guru, $data) {
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
            User::where('email', $guru->nipy)->delete();
            $guru->delete();
        });
    }
}