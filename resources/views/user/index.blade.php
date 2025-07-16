@extends('layouts.app2')

@section('title')
    <title>Profil Siswa</title>
@endsection

@section('content')
<style>
    .profile-header {
        background: linear-gradient(135deg, #6a11cb, #2575fc);
        color: white;
        padding: 20px;
        border-radius: 8px 8px 0 0;
    }

    .profile-img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid white;
        box-shadow: 0 0 10px rgba(0,0,0,0.3);
        margin-top: -40px;
    }

    .section-title {
        font-weight: 600;
        color: #4a4a4a;
        border-bottom: 2px solid #eee;
        margin: 20px 0 10px;
        padding-bottom: 5px;
    }

    .table-profile td {
        padding: 4px 8px;
        vertical-align: top;
    }

    .card-profile {
        border: none;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
</style>

<div class="app-main__inner">
    <div class="card card-profile">
        <div class="profile-header text-center">
            <h3 class="mb-0">Profil Siswa</h3>
            <p class="mb-0">Detail informasi siswa yang sedang menempuh pendidikan</p>
        </div>

        <div class="card-body">
            <div class="text-center">
                <img class="profile-img" src="{{ $data->foto ? asset('storage/' . $data->foto) : asset('storage/logo/user.png') }}" alt="Foto Siswa">
                <h4 class="mt-3 mb-0">{{ $data->nama_lengkap }}</h4>
                <small class="text-muted">NIS: {{ $data->nis }} | Kelas: {{ $data->kelas->nama_kelas }}</small>
            </div>

            <!-- DATA DIRI -->
            <div class="section-title">🧑‍🎓 Data Diri</div>
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless table-profile">
                        <tr><td>NIK</td><td>: {{ $data->nik }}</td></tr>
                        <tr><td>Jenis Kelamin</td><td>: {{ $data->jenis_kelamin }}</td></tr>
                        <tr><td>Tempat Lahir</td><td>: {{ $data->tempat_lahir }}</td></tr>
                        <tr><td>Tanggal Lahir</td><td>: {{ \Carbon\Carbon::parse($data->tanggal_lahir)->translatedFormat('d F Y') }}</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless table-profile">
                        <tr><td>Agama</td><td>: {{ $data->agama }}</td></tr>
                        <tr><td>No HP</td><td>: {{ $data->no_hp }}</td></tr>
                        <tr><td>Email</td><td>: {{ $data->email }}</td></tr>
                    </table>
                </div>
            </div>

            <!-- DATA ORANG TUA -->
            <div class="section-title">👨‍👩‍👧 Orang Tua</div>
            <div class="row">
                @php
                    $colClass = ($data->nik_wali && $data->nama_wali) ? 'col-md-4' : 'col-md-6';
                @endphp

                <div class="{{ $colClass }}">
                    <table class="table table-borderless table-profile">
                        <tr><td>NIK Ayah</td><td>: {{ $data->nik_ayah }}</td></tr>
                        <tr><td>Nama Ayah</td><td>: {{ $data->nama_ayah }}</td></tr>
                        <tr><td>Tahun Lahir Ayah</td><td>: {{ $data->lahir_ayah }}</td></tr>
                        <tr><td>Pekerjaan Ayah</td><td>: {{ $data->pekerjaan_ayah->nama_pekerjaan }}</td></tr>
                        <tr><td>Penghasilan Ayah</td><td>: {{ $data->penghasilan_ayah->nama_penghasilan }}</td></tr>
                        <tr><td>Berkebutuhan Khusus</td><td>: {{ $data->berkebutuhan_khusus_ayah->nama_berkebutuhan_khusus }}</td></tr>
                        <tr><td>Pendidikan Terakhir</td><td>: {{ $data->jenjang_pendidikan_ayah->nama_jenjang_pendidikan}}</td></tr>
                    </table>
                </div>
                <div class="{{ $colClass }}">
                    <table class="table table-borderless table-profile">
                        <tr><td>NIK Ibu</td><td>: {{ $data->nik_ibu }}</td></tr>
                        <tr><td>Nama Ibu</td><td>: {{ $data->nama_ibu }}</td></tr>
                        <tr><td>Tahun Lahir Ibu</td><td>: {{ $data->lahir_ibu }}</td></tr>
                        <tr><td>Pekerjaan Ibu</td><td>: {{ $data->pekerjaan_ibu->nama_pekerjaan }}</td></tr>
                        <tr><td>Penghasilan Ibu</td><td>: {{ $data->penghasilan_ibu->nama_penghasilan }}</td></tr>
                        <tr><td>Berkebutuhan Khusus</td><td>: {{ $data->berkebutuhan_khusus_ibu->nama_berkebutuhan_khusus }}</td></tr>
                        <tr><td>Pendidikan Terakhir</td><td>: {{ $data->jenjang_pendidikan_ibu->nama_jenjang_pendidikan}}</td></tr>
                    </table>
                </div>
                @if ($data->nik_wali && $data->nama_wali)
                    <div class="col-md-4">
                        <table class="table table-borderless table-profile">
                            <tr><td>NIK Wali</td><td>: {{ $data->nik_wali }}</td></tr>
                            <tr><td>Nama Wali</td><td>: {{ $data->nama_wali }}</td></tr>
                            <tr><td>Tahun Lahir Wali</td><td>: {{ $data->lahir_wali }}</td></tr>
                            <tr><td>Pekerjaan Wali</td><td>: {{ $data->pekerjaan_wali?->nama_pekerjaan ?? '-' }}</td></tr>
                            <tr><td>Penghasilan Wali</td><td>: {{ $data->penghasilan_wali?->nama_penghasilan ?? '-' }}</td></tr>
                            <tr><td>Berkebutuhan Khusus</td><td>: {{ $data->berkebutuhan_khusus_wali?->nama_berkebutuhan_khusus ?? '-' }}</td></tr>
                            <tr><td>Pendidikan Terakhir</td><td>: {{ $data->jenjang_pendidikan_wali?->nama_jenjang_pendidikan ?? '-' }}</td></tr>
                        </table>
                    </div>
                @endif
            </div>

            <!-- ALAMAT -->
            <div class="section-title">📍 Alamat</div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-borderless table-profile">
                        <tr><td>Alamat Lengkap</td><td>: {{ $data->alamat }}</td></tr>
                        <tr><td>Desa/Kelurahan</td><td>: {{ $data->desa }}</td></tr>
                        <tr><td>Kecamatan</td><td>: {{ $data->kecamatan }}</td></tr>
                        <tr><td>Kota/Kabupaten</td><td>: {{ $data->kota }}</td></tr>
                        <tr><td>Provinsi</td><td>: {{ $data->provinsi }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
