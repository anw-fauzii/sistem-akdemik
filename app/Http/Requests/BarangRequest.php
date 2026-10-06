<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BarangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|string|max:30',
            'nama_barang' => 'required|string|max:255',
            'kategori_barang_id' => 'required|string|exists:kategori_barang,id',
            'lokasi_barang_id' => 'required|string|exists:lokasi_barang,id',
            'merk' => 'nullable|string|max:255',
            'tipe' => 'nullable|string|max:255',
            'nomor_seri' => 'nullable|string|max:255',
            'tahun_perolehan' => 'nullable|integer|digits:4',
            'harga_perolehan' => 'nullable|numeric|min:0',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'status' => 'required|in:aktif,dipinjam,hilang,dihapus',
            'keterangan' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'ID barang wajib diisi.',
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'kategori_barang_id.required' => 'Kategori barang wajib dipilih.',
            'kategori_barang_id.exists' => 'Kategori barang tidak ditemukan.',
            'lokasi_barang_id.required' => 'Lokasi barang wajib dipilih.',
            'lokasi_barang_id.exists' => 'Lokasi barang tidak ditemukan.',
            'tahun_perolehan.digits' => 'Tahun perolehan harus terdiri dari 4 angka.',
            'harga_perolehan.numeric' => 'Harga perolehan harus berupa angka.',
            'harga_perolehan.min' => 'Harga perolehan tidak boleh kurang dari 0.',
            'kondisi.required' => 'Kondisi barang wajib dipilih.',
            'kondisi.in' => 'Kondisi barang tidak valid.',
            'status.required' => 'Status barang wajib dipilih.',
            'status.in' => 'Status barang tidak valid.',
        ];
    }
}