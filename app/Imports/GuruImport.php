<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue;

class GuruImport implements ToCollection, WithStartRow, WithChunkReading, ShouldQueue
{
    public function startRow(): int
    {
        return 10; 
    }

    public function collection(Collection $rows)
    {
        $defaultPassword = Hash::make('pass1234');

        foreach ($rows as $row) {
            if (!isset($row[3])) continue; 

            $nipy = $row[3];
            $nama = $row[1];
            $role = $row[12] ?? 'guru_tk'; 

            $user = User::updateOrCreate(
                ['email' => $nipy],
                ['name' => $nama, 'password' => $defaultPassword]
            );

            // Cegah duplikasi role assignment
            if (!$user->hasRole($role)) {
                $user->assignRole($role);
            }

            // Parsing Tanggal Laravel Excel Standar
            $tanggal_lahir = null;
            if (is_numeric($row[7])) {
                $tanggal_lahir = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[7]);
            }

            Guru::updateOrCreate(
                ['nipy' => $nipy],
                [
                    'nama_lengkap'  => $nama,
                    'gelar'         => $row[2],
                    'jabatan'       => $row[4],
                    'jenis_kelamin' => $row[5],
                    'tempat_lahir'  => $row[6],
                    'tanggal_lahir' => $tanggal_lahir,
                    'nuptk'         => $row[8],
                    'alamat'        => $row[9],
                    'telepon'       => $row[10],
                    'unit'          => $row[11],
                    'avatar'        => 'default.png'
                ]
            );
        }
    }

    public function chunkSize(): int
    {
        return 100;
    }
}