<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;

class GuruImport implements ToCollection
{
    public function collection(Collection $collection)
    {
        foreach ($collection as $key => $row) {
            if ($key >= 9 && $key <= 100) {
                $user = User::where('email', $row[3])->first();

                if (!$user) {
                    $user = User::create([
                        'name' => $row[1],
                        'email' => $row[3],
                        'password' => Hash::make('pass1234'),
                    ]);
                    $user->assignRole($row['12']);
                        
                }

                $tanggal_lahir = null;
                if (is_numeric($row[7])) {
                    $tanggal_lahir = gmdate('Y-m-d', ($row[7] - 25569) * 86400);
                }

                $existingGuru = Guru::where('nipy', $row[3])->first();
                if (!$existingGuru) {
                    Guru::create([
                        'nama_lengkap' => $row[1],
                        'gelar' => $row[2],
                        'nipy' => $row[3],
                        'jabatan' => $row[4],
                        'jenis_kelamin' => $row[5],
                        'tempat_lahir' => $row[6],
                        'tanggal_lahir' => $tanggal_lahir,
                        'nuptk' => $row[8],
                        'alamat' => $row[9],
                        'telepon' => $row['10'],
                        'unit' => $row['11'],
                        'avatar' => 'default.png'
                    ]);
                }
            }
        }
    }
}