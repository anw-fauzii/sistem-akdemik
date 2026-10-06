<?php

namespace App\Imports;

use App\Models\Barang;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue;

class BarangImport implements ToCollection, WithStartRow, WithChunkReading, ShouldQueue
{
    public function startRow(): int
    {
        return 10;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (!isset($row[1]) || empty($row[1])) {
                continue;
            }

            $id = trim($row[1]);

            Barang::updateOrCreate(
                ['id' => $id],
                [
                    'nama_barang'        => $row[2] ?? null,
                    'kategori_barang_id' => $row[3] ?? null,
                    'lokasi_barang_id'   => $row[4] ?? null,
                    'merk'               => $row[5] ?? null,
                    'tipe'               => $row[6] ?? null,
                    'nomor_seri'         => $row[7] ?? null,
                    'tahun_perolehan'    => $row[8] ?? null,
                    'kondisi'            => $row[9] ?? 'baik',
                    'status'             => $row[10] ?? 'aktif',
                    'keterangan'         => $row[11] ?? null,
                ]
            );
        }
    }

    public function chunkSize(): int
    {
        return 100;
    }
}