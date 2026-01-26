@extends('layouts.app2')

@section('title')
    <title>Siswa</title>
@endsection

@section('content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-rocket icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Siswa
                    <div class="page-title-subheading">
                        Merupakan siswa yang Berada di sekolah
                    </div>
                </div>
            </div>  
        </div> 
    </div>

    <div class="main-card card">
        <div class="container mt-4">
    <div class="card shadow-sm mb-4">
        <div class="card-body text-center">
            <!-- Avatar & Nama -->
            @if($siswa->avatar)
                <img src="{{ asset('storage/logo/user.png') }}" alt="Avatar" class="rounded-circle mb-3" style="width:150px; height:150px;">
            @else
                <img src="{{ asset('images/default-avatar.png') }}" alt="Avatar" class="rounded-circle mb-3" style="width:150px; height:150px;">
            @endif
            <h3 class="mb-1">{{ $siswa->nama_lengkap }}</h3>
            <p class="mb-0"><strong>NIS:</strong> {{ $siswa->nis }} | <strong>NISN:</strong> {{ $siswa->nisn ?? '-' }}</p>
            <p class="mb-0"><strong>Kelas:</strong> {{ $siswa->kelas->nama_kelas ?? '-' }} | <strong>Guru Wali:</strong> {{ $siswa->kelas->guru->nama_lengkap ?? '-' }}, {{ $siswa->kelas->guru->gelar ?? '-' }}. | <strong>Guru Pendamping:</strong> {{ $siswa->kelas->pendamping->nama_lengkap ?? '-' }}, {{ $siswa->kelas->pendamping->gelar ?? '-' }}.</p> 
            <p class="mb-0"><strong>Ekstrakurikuler:</strong> {{ $siswa->ekstrakurikuler->nama ?? '-' }}</p>
        </div>
    </div>

    <!-- Data Pribadi -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Data Pribadi</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-2">
                    <strong>Tempat, Tanggal Lahir:</strong> 
                    {{ $siswa->tempat_lahir }}, {{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->translatedFormat('j F Y') }}
                </div>
                <div class="col-md-6 mb-2"><strong>Jenis Kelamin:</strong> {{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                <div class="col-md-6 mb-2"><strong>Agama:</strong> Islam</div>
                <div class="col-md-6 mb-2"><strong>Kewarganegaraan:</strong> {{ $siswa->kewarganegaraan }} {{ $siswa->nama_negara ?? '' }}</div>
                <div class="col-12 mb-2"><strong>Alamat:</strong> {{ $siswa->alamat }}, RT {{ $siswa->rt }}/RW {{ $siswa->rw }}, Desa {{ $siswa->desa }}, Kecamatan {{ $siswa->kecamatan }}, Kabupaten {{ $siswa->kabupaten }}, Provinsi {{ $siswa->provinsi }}, {{ $siswa->kode_pos }}</div>
            </div>
        </div>
    </div>

    <!-- Data Orang Tua / Wali -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Data Orang Tua / Wali</h5>
        </div>
        <div class="card-body">
            <h6 class="text-primary">Ayah</h6>
            <div class="row mb-2">
                <div class="col-md-6"><strong>Nama:</strong> {{ $siswa->nama_ayah ?? '-' }}</div>
                <div class="col-md-6"><strong>NIK:</strong> {{ $siswa->nik_ayah ?? '-' }}</div>
                <div class="col-md-6"><strong>Pendidikan:</strong> {{ $siswa->pendidikanAyah->nama ?? '-' }}</div>
                <div class="col-md-6"><strong>Pekerjaan:</strong> {{ $siswa->pekerjaanAyah->nama ?? '-' }}</div>
                <div class="col-md-6"><strong>Penghasilan:</strong> {{ $siswa->penghasilanAyah->nama ?? '-' }}</div>
            </div>

            <h6 class="text-primary">Ibu</h6>
            <div class="row mb-2">
                <div class="col-md-6"><strong>Nama:</strong> {{ $siswa->nama_ibu ?? '-' }}</div>
                <div class="col-md-6"><strong>NIK:</strong> {{ $siswa->nik_ibu ?? '-' }}</div>
                <div class="col-md-6"><strong>Pendidikan:</strong> {{ $siswa->pendidikanIbu->nama ?? '-' }}</div>
                <div class="col-md-6"><strong>Pekerjaan:</strong> {{ $siswa->pekerjaanIbu->nama ?? '-' }}</div>
                <div class="col-md-6"><strong>Penghasilan:</strong> {{ $siswa->penghasilanIbu->nama ?? '-' }}</div>
            </div>

            @if($siswa->nama_wali)
            <h6 class="text-primary">Wali</h6>
            <div class="row mb-2">
                <div class="col-md-6"><strong>Nama:</strong> {{ $siswa->nama_wali }}</div>
                <div class="col-md-6"><strong>NIK:</strong> {{ $siswa->nik_wali ?? '-' }}</div>
                <div class="col-md-6"><strong>Pendidikan:</strong> {{ $siswa->pendidikanWali->nama ?? '-' }}</div>
                <div class="col-md-6"><strong>Pekerjaan:</strong> {{ $siswa->pekerjaanWali->nama ?? '-' }}</div>
                <div class="col-md-6"><strong>Penghasilan:</strong> {{ $siswa->penghasilanWali->nama ?? '-' }}</div>
            </div>
            @endif
        </div>
    </div>

    <!-- Data Kesehatan & Kontak -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">Data Kesehatan & Kontak</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-2"><strong>Nomor HP:</strong> {{ $siswa->nomor_hp ?? '-' }}</div>
                <div class="col-md-4 mb-2"><strong>Whatsapp:</strong> {{ $siswa->whatsapp ?? '-' }}</div>
                <div class="col-md-4 mb-2"><strong>Email:</strong> {{ $siswa->email ?? '-' }}</div>
                <div class="col-md-4 mb-2"><strong>Tinggi / Berat Badan:</strong> {{ $siswa->tinggi_badan ?? '-' }} cm / {{ $siswa->berat_badan ?? '-' }} kg</div>
                <div class="col-md-4 mb-2"><strong>Jarak / Waktu Tempuh:</strong> {{ $siswa->jarak ?? '-' }} km / {{ $siswa->waktu_tempuh ?? '-' }} menit</div>
                <div class="col-md-4 mb-2"><strong>Lingkar Kepala:</strong> {{ $siswa->lingkar_kepala ?? '-' }} cm</div>
            </div>
        </div>
    </div>
</div>
    </div>
</div>
@endsection
