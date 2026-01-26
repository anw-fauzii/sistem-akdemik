<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Hash;
use App\Models\Siswa;
use App\Models\User;

class SiswaImport implements ToCollection
{
    public function collection(Collection $collection)
    {
        foreach ($collection as $key => $row) {
            if ($key >= 10 && $key <= 300) {
                $user = User::where('email', $row[1])->first();

                if (!$user) {
                    $user = User::create([
                        'name' => $row[4],
                        'email' => $row[1],
                        'password' => Hash::make('pass1234'),
                    ]);
                    $user->assignRole($row['34']);
                }

                $tanggal_lahir = null;
                if (is_numeric($row[10])) {
                    $tanggal_lahir = gmdate('Y-m-d', ($row[10] - 25569) * 86400);
                }

                $existingSiswa = Siswa::where('nis', $row[1])->first();
                if (!$existingSiswa) {
                    Siswa::create([
                        'nis' => $row[1],
                        'kelas_id' => null,
                        'guru_nipy' => null,
                        'ekstrakurikuler_id' => null,
                        'nisn' => $row[2],
                        'jenis_pendaftaran' => $row[3],
                        'nama_lengkap' => $row[4],
                        'jenis_kelamin' => $row[5],
                        'nik' => $row[6],
                        'no_kk' => $row[7],
                        'akta_lahir' => $row[8],
                        'tempat_lahir' => $row[9],
                        'tanggal_lahir' => $tanggal_lahir,
                        'agama' => '1',
                        'kewarganegaraan' => 'WNI',
                        'nama_negara' => 'Indonesia',
                        'berkebutuhan_khusus_id' => null,
                        'anak_ke' => $row[11],
                        'jumlah_saudara' => $row[12],
                        'alamat' => $row[13],
                        'rt' => $row[14],
                        'rw' => $row[15],
                        'desa' => $row[16],
                        'kecamatan' => $row[17],
                        'kabupaten' => $row[18],
                        'provinsi' => $row[19],
                        'kode_pos' => $row[20],
                        'lintang' => null,
                        'bujur' => null,
                        'tempat_tinggal' => null,
                        'transportasi_id' => null,
                        'nik_ayah' => $row[21],
                        'nama_ayah' => $row[22],
                        'lahir_ayah' => $row[23],
                        'jenjang_pendidikan_ayah_id' => null,
                        'pekerjaan_ayah_id' => null,
                        'penghasilan_ayah_id' => null,
                        'berkebutuhan_khusus_ayah_id' => null,
                        'nik_ibu' => $row[24],
                        'nama_ibu' => $row[25],
                        'lahir_ibu' => $row[26],
                        'jenjang_pendidikan_ibu_id' => null,
                        'pekerjaan_ibu_id' => null,
                        'penghasilan_ibu_id' => null,
                        'berkebutuhan_khusus_ibu_id' => null,
                        'nik_wali' => $row[27],
                        'nama_wali' => $row[28],
                        'lahir_wali' => $row[29],
                        'jenjang_pendidikan_wali_id' => null,
                        'pekerjaan_wali_id' => null,
                        'penghasilan_wali_id' => null,
                        'berkebutuhan_khusus_wali_id' => null,
                        'nomor_hp' => $row[16],
                        'whatsapp' => null,
                        'email' => null,
                        'tinggi_badan' => null,
                        'berat_badan' => null,
                        'jarak' => null,
                        'waktu_tempuh' => null,
                        'lingkar_kepala' => null,
                        'avatar' => 'default.png',
                        'status' => '1',
                    ]);
                }
            }
        }
    }
}
