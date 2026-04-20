<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Proteksi role admin sudah ditangani di Route Middleware
    }

    public function rules(): array
    {
        // Mengambil primary key dari route (nis) untuk pengecekan unique saat update
        $siswaNis = $this->route('siswa'); 

        return [
            // Identitas Utama
            'nis'               => ['required', 'numeric', Rule::unique('siswa', 'nis')->ignore($siswaNis, 'nis')],
            'kelas_id'          => 'required|exists:kelas,id',
            'jenis_pendaftaran' => 'required|string',
            'nama_lengkap'      => 'required|string|max:255',
            'jenis_kelamin'     => 'required|in:L,P',
            'nisn'              => ['nullable', 'numeric', 'digits:10', Rule::unique('siswa', 'nisn')->ignore($siswaNis, 'nis')],
            'nik'               => ['nullable', 'numeric', 'digits:16', Rule::unique('siswa', 'nik')->ignore($siswaNis, 'nis')],
            'no_kk'             => 'nullable|numeric|digits:16',
            'tempat_lahir'      => 'required|string',
            'tanggal_lahir'     => 'required|date',
            'akta_lahir'        => ['nullable', Rule::unique('siswa', 'akta_lahir')->ignore($siswaNis, 'nis')],
            'kewarganegaraan'   => 'required|in:WNI,WNA',
            'nama_negara'       => 'required_if:kewarganegaraan,WNA',
            'berkebutuhan_khusus_id' => 'required|exists:berkebutuhan_khusus,id',
            
            // Alamat & Domisili
            'alamat'            => 'required|string|max:500',
            'rt'                => 'required|numeric|max:999',
            'rw'                => 'required|numeric|max:999',
            'desa'              => 'required|string|max:100',
            'kecamatan'         => 'required|string|max:100',
            'kabupaten'         => 'required|string|max:100',
            'provinsi'          => 'required|string|max:100',
            'kode_pos'          => 'required|numeric|digits:5',
            'tempat_tinggal'    => 'required|string',
            'transportasi_id'   => 'required|exists:transportasi,id',
            
            // Keluarga
            'anak_ke'           => 'required|numeric|min:1',
            'jumlah_saudara'    => 'required|numeric|min:0',
            
            // Ayah Kandung
            'nik_ayah'          => 'required|numeric|digits:16', 
            'nama_ayah'         => 'required|string|max:255', 
            'lahir_ayah'        => 'required|numeric|digits:4',
            'jenjang_pendidikan_ayah_id' => 'required|exists:jenjang_pendidikan,id', 
            'pekerjaan_ayah_id' => 'required|exists:pekerjaan,id',
            'penghasilan_ayah_id' => 'required|exists:penghasilan,id',
            'berkebutuhan_khusus_ayah_id' => 'required|exists:berkebutuhan_khusus,id',
    
            // Ibu Kandung
            'nik_ibu'           => 'required|numeric|digits:16', 
            'nama_ibu'          => 'required|string|max:255', 
            'lahir_ibu'         => 'required|numeric|digits:4',
            'jenjang_pendidikan_ibu_id' => 'required|exists:jenjang_pendidikan,id',
            'pekerjaan_ibu_id'  => 'required|exists:pekerjaan,id',
            'penghasilan_ibu_id' => 'required|exists:penghasilan,id',
            'berkebutuhan_khusus_ibu_id' => 'required|exists:berkebutuhan_khusus,id',
    
            // Kontak & Media
            'nomor_hp'          => 'required|numeric|digits_between:10,15',
            'whatsapp'          => 'required|numeric|digits_between:10,15',
            'email'             => 'required|email|max:255',
    
            // Data Fisik & Administrasi
            'tinggi_badan'      => 'required|numeric|min:30',
            'berat_badan'       => 'required|numeric|min:2',
            'jarak'             => 'required|numeric|min:0',
            'waktu_tempuh'      => 'required|numeric|min:0',
            'tarif_spp_id'      => 'required|exists:tarif_spp,id',
        ];
    }

    public function messages(): array
    {
        return [
            // Identitas
            'nis.required'          => 'NIS wajib diisi sebagai identitas utama.',
            'nis.unique'            => 'NIS ini sudah digunakan oleh siswa lain.',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi sesuai Akta Kelahiran.',
            'jenis_kelamin.required'=> 'Silakan pilih jenis kelamin.',
            'nisn.digits'           => 'NISN harus berjumlah tepat 10 digit.',
            'nik.digits'            => 'NIK harus berjumlah tepat 16 digit.',
            'tanggal_lahir.date'    => 'Format tanggal lahir tidak valid.',
            'nama_negara.required_if' => 'Nama negara wajib diisi jika kewarganegaraan adalah WNA.',

            // Alamat
            'alamat.required'       => 'Alamat domisili lengkap wajib diisi.',
            'kode_pos.digits'       => 'Kode pos harus terdiri dari 5 digit angka.',
            'desa.required'         => 'Nama desa/kelurahan wajib diisi.',
            'kecamatan.required'    => 'Kecamatan wajib diisi.',

            // Orang Tua (Ayah)
            'nik_ayah.required'     => 'NIK Ayah wajib diisi.',
            'nik_ayah.digits'       => 'NIK Ayah harus 16 digit.',
            'nama_ayah.required'    => 'Nama Ayah wajib diisi.',
            'lahir_ayah.digits'     => 'Tahun lahir Ayah harus 4 digit (contoh: 1985).',
            'pekerjaan_ayah_id.required' => 'Pilih pekerjaan Ayah.',

            // Orang Tua (Ibu)
            'nik_ibu.required'      => 'NIK Ibu wajib diisi.',
            'nik_ibu.digits'        => 'NIK Ibu harus 16 digit.',
            'nama_ibu.required'     => 'Nama Ibu wajib diisi.',
            'lahir_ibu.digits'      => 'Tahun lahir Ibu harus 4 digit.',
            'pekerjaan_ibu_id.required' => 'Pilih pekerjaan Ibu.',

            // Kontak & Teknis
            'nomor_hp.required'     => 'Nomor HP aktif wajib diisi.',
            'whatsapp.required'     => 'Nomor WhatsApp wajib diisi untuk koordinasi.',
            'email.email'           => 'Format alamat email tidak valid.',
            'tarif_spp_id.required' => 'Silakan tentukan tarif SPP siswa ini.',
            
            // Relasi
            'exists'                => 'Pilihan :attribute tidak ditemukan dalam data master.',
            'numeric'               => ':attribute harus berupa angka.',
        ];
    }
}