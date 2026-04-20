<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuruRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan membuat request ini.
     */
    public function authorize(): bool
    {
        return true; // Otorisasi sudah ditangani oleh Middleware Route (Role Admin)
    }

    /**
     * Aturan validasi yang diterapkan ke request.
     */
    public function rules(): array
    {
        // Mengambil primary key (NIPY) dari route jika sedang dalam proses Update.
        // Jika route parameter mengirimkan Model Guru (Route Model Binding), kita ambil properti nipy-nya.
        // Jika sedang proses Create (Store), nilainya akan null.
        $guru = $this->route('guru');
        $guruNipy = $guru ? $guru->nipy : null;

        return [
            'nama_lengkap'  => 'required|string|max:255',
            'gelar'         => 'required|string|max:50',
            'jabatan'       => 'required|string|max:100',
            
            // NIPY harus unik di tabel guru, kecuali untuk NIPY milik guru yang sedang diupdate
            'nipy'          => [
                'required', 
                'string', 
                Rule::unique('guru', 'nipy')->ignore($guruNipy, 'nipy')
            ],
            
            'telepon'       => 'required|string|max:20',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir'  => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            
            // NUPTK boleh kosong, tapi jika diisi harus unik
            'nuptk'         => [
                'nullable', 
                'string', 
                Rule::unique('guru', 'nuptk')->ignore($guruNipy, 'nipy')
            ],
            
            'alamat'        => 'required|string',
            'unit'          => 'nullable|string|max:100',
            'status'        => 'nullable|boolean',
        ];
    }

    /**
     * Pesan error kustom dalam Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            // Field: Nama Lengkap
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_lengkap.string'   => 'Nama lengkap harus berupa format teks.',
            'nama_lengkap.max'      => 'Nama lengkap tidak boleh lebih dari 255 karakter.',

            // Field: Gelar
            'gelar.required'        => 'Gelar wajib diisi (misal: S.Pd).',
            'gelar.string'          => 'Gelar harus berupa teks.',
            'gelar.max'             => 'Gelar tidak boleh lebih dari 50 karakter.',

            // Field: Jabatan
            'jabatan.required'      => 'Jabatan wajib diisi (misal: Wali Kelas, Guru Mapel).',
            'jabatan.string'        => 'Jabatan harus berupa teks.',
            'jabatan.max'           => 'Jabatan tidak boleh lebih dari 100 karakter.',

            // Field: NIPY
            'nipy.required'         => 'Nomor Induk Pegawai Yayasan (NIPY) wajib diisi.',
            'nipy.string'           => 'NIPY harus berupa teks/karakter.',
            'nipy.unique'           => 'NIPY ini sudah terdaftar. NIPY juga digunakan sebagai username login, sehingga tidak boleh ganda.',

            // Field: Telepon
            'telepon.required'      => 'Nomor telepon/WhatsApp wajib diisi.',
            'telepon.string'        => 'Nomor telepon harus berupa angka/teks yang valid.',
            'telepon.max'           => 'Nomor telepon terlalu panjang (maksimal 20 karakter).',

            // Field: Jenis Kelamin
            'jenis_kelamin.required'=> 'Silakan pilih jenis kelamin (Laki-laki atau Perempuan).',
            'jenis_kelamin.in'      => 'Pilihan jenis kelamin tidak valid.',

            // Field: Tempat Lahir
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tempat_lahir.string'   => 'Tempat lahir harus berupa teks.',
            'tempat_lahir.max'      => 'Tempat lahir maksimal 100 karakter.',

            // Field: Tanggal Lahir
            'tanggal_lahir.required'=> 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date'    => 'Format tanggal lahir tidak valid.',

            // Field: NUPTK
            'nuptk.unique'          => 'NUPTK ini sudah terdaftar pada guru lain.',
            'nuptk.string'          => 'NUPTK harus berupa teks/angka yang valid.',

            // Field: Alamat
            'alamat.required'       => 'Alamat tempat tinggal wajib diisi.',
            'alamat.string'         => 'Alamat harus berupa teks.',
        ];
    }
}