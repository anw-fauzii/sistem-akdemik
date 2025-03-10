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
                <div>Update Siswa
                    <div class="page-title-subheading">
                        Memperbarui Siswa yang baru
                    </div>
                </div>
            </div>  
        </div> 
    </div>

    <div class="main-card card">
        <div class="card-header">
            Update Data
        </div>
        <div class="card-body">
            <form  method="post" action="{{route('siswa.update', $siswa->nis)}}" id="editForm">
                @csrf
                @method('PUT')
                <div id="smartwizard2" class="forms-wizard-alt">
                    <ul class="forms-wizard">
                        <li>
                            <a href="#step-12">
                                <em>1</em><span>Data Pribadi</span>
                            </a>
                        </li>
                        <li>
                            <a href="#step-22">
                                <em>2</em><span>Data Periodik</span>
                            </a>
                        </li>
                        <li>
                            <a href="#step-32">
                                <em>3</em><span>Kontak</span>
                            </a>
                        </li>
                        <li>
                            <a href="#step-42">
                                <em>4</em><span>Data Ayah Kandung</span>
                            </a>
                        </li>
                        <li>
                            <a href="#step-52">
                                <em>5</em><span>Data Ibu Kandung</span>
                            </a>
                        </li>
                        <li>
                            <a href="#step-62">
                                <em>6</em><span>Data Wali</span>
                            </a>
                        </li>
                        <li>
                            <a href="#step-72">
                                <em>7</em><span>Simpan</span>
                            </a>
                        </li>
                    </ul>
                    <div class="form-wizard-content">
                        <div id="step-12">
                            <div class="form-row">
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="jenis_pendaftaran" class="">1. Jenis Pendaftaran</label>
                                        <select name="jenis_pendaftaran" id="jenis_pendaftaran" class="form-control @error('jenis_pendaftaran') is-invalid @enderror">
                                            <option value="" selected disabled>-- Pilih jenis kelamin --</option>
                                            <option value="1" {{ old('jenis_pendaftaran') == '1' || $siswa->jenis_pendaftaran == '1' ? 'selected' : '' }}>Siswa Baru</option>
                                            <option value="2" {{ old('jenis_pendaftaran') == '2' || $siswa->jenis_pendaftaran == '2'? 'selected' : '' }}>Siswa Pindahan</option>
                                        </select>
                                        @error('jenis_pendaftaran')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="nis" class="">2. Nomor Induk Siswa</label>
                                        <input name="nis" id="nis" placeholder="Masukan NIS" type="number" class="form-control @error('nis') is-invalid @enderror" value="{{ $siswa->nis ?? old('nis') }}">
                                        @error('nis')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="kelas_id">3. Kelas</label>
                                        <select name="kelas_id" id="kelas_id"  class="multiselect-dropdown form-control @error('kelas_id') is-invalid @enderror">
                                            <option value="" selected disabled>-- Pilih Kelas --</option>
                                            @foreach ($kelas as $item)
                                                <option value="{{$item->id}}" {{ old('kelas_id') == $item->id || $siswa->kelas_id == $item->id ? 'selected' : '' }}>{{$item->nama_kelas}}</option>
                                            @endforeach
                                        </select>
                                        @error('kelas_id')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="nama_lengkap" class="">4. Nama Lengkap</label>
                                        <input name="nama_lengkap" id="nama_lengkap" placeholder="Masukan Nama Lengkap" type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" value="{{ $siswa->nama_lengkap ?? old('nama_lengkap') }}">
                                        @error('nama_lengkap')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="jenis_kelamin">5. Jenis Kelamin</label>
                                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror">
                                            <option value="" selected disabled>-- Pilih jenis kelamin --</option>
                                            <option value="L" {{ old('jenis_kelamin') == 'L' || $siswa->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                                            <option value="P" {{ old('jenis_kelamin') == 'P' || $siswa->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                        @error('jenis_kelamin')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>                        
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="nisn" class="">6. Nomor Induk Siswa Nasional</label>
                                        <input name="nisn" id="nisn" placeholder="Masukkan NISN" type="number" class="form-control @error('nisn') is-invalid @enderror" value="{{ $siswa->nisn ?? old('nisn') }}">
                                        @error('nisn')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="nik" class="">7. Nomor Induk Kependudukan</label>
                                        <input name="nik" id="nik" placeholder="Masukkan NIK Siswa" type="number" class="form-control @error('nik') is-invalid @enderror" value="{{ $siswa->nik ?? old('nik') }}">
                                        @error('nik')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="no_kk" class="">8. Nomor Kartu Keluarga</label>
                                        <input name="no_kk" id="no_kk" placeholder="Masukkan No Kartu Keluarga" type="number" class="form-control @error('no_kk') is-invalid @enderror" value="{{ $siswa->no_kk ?? old('no_kk') }}">
                                        @error('no_kk')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="tempat_lahir" class="">9. Tempat Lahir</label>
                                        <input name="tempat_lahir" id="tempat_lahir" placeholder="Masukkan tempat lahir" type="text" class="form-control @error('tempat_lahir') is-invalid @enderror" value="{{ $siswa->tempat_lahir ?? old('tempat_lahir') }}">
                                        @error('tempat_lahir')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="tanggal_lahir" class="">10. Tanggal lahir</label>
                                        <input name="tanggal_lahir" id="tanggal_lahir" placeholder="Masukkan tanggal lahir" type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" value="{{ $siswa->tanggal_lahir ?? old('tanggal_lahir') }}">
                                        @error('tanggal_lahir')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="akta_lahir" class="">11. Akta Lahir</label>
                                        <input name="akta_lahir" id="akta_lahir" placeholder="Masukkan Akta Lahir" type="text" class="form-control @error('akta_lahir') is-invalid @enderror" value="{{ $siswa->akta_lahir ?? old('akta_lahir') }}">
                                        @error('akta_lahir')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="kewarganegaraan">12. Kewarganegaraan</label>
                                        <select name="kewarganegaraan" id="kewarganegaraan" class="form-control @error('kewarganegaraan') is-invalid @enderror">
                                            <option value="" selected disabled>-- Pilih Jenis --</option>
                                            <option value="WNI" {{ old('kewarganegaraan') == 'WNI' || $siswa->kewarganegaraan == 'WNI' ? 'selected' : '' }}>WNI</option>
                                            <option value="WNA" {{ old('kewarganegaraan') == 'WNA' || $siswa->kewarganegaraan == 'WNA' ? 'selected' : '' }}>WNA</option>
                                        </select>
                                        @error('kewarganegaraan')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>                        
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="nama_negara" class="">13. Nama Negara</label>
                                        <input name="nama_negara" id="nama_negara" placeholder="Masukkan nama negara" type="text" class="form-control @error('nama_negara') is-invalid @enderror" value="{{ $siswa->nama_negara ?? old('nama_negara') }}">
                                        @error('nama_negara')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="berkebutuhan_khusus_id">14. Berkebutuhan Khusus</label>
                                        <select name="berkebutuhan_khusus_id" id="berkebutuhan_khusus_id"  class="multiselect-dropdown form-control @error('berkebutuhan_khusus_id') is-invalid @enderror">
                                            <option value="" selected disabled>-- Pilih Kelas --</option>
                                            @foreach ($berkebutuhan_khusus as $item)
                                                <option value="{{$item->id}}" {{ old('berkebutuhan_khusus_id') == $item->id || $siswa->berkebutuhan_khusus_id == $item->id ? 'selected' : '' }}>{{$item->nama_berkebutuhan_khusus}}</option>
                                            @endforeach
                                        </select>
                                        @error('berkebutuhan_khusus_id')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="alamat" class="">15. Alamat</label>
                                        <input name="alamat" id="alamat" placeholder="Masukkan Alamat" type="text" class="form-control @error('alamat') is-invalid @enderror" value="{{ $siswa->alamat ?? old('alamat') }}">
                                        @error('alamat')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="rt" class="">16. RT</label>
                                        <input name="rt" id="rt" placeholder="Masukkan RT" type="number" class="form-control @error('rt') is-invalid @enderror" value="{{ $siswa->rt ?? old('rt') }}">
                                        @error('rt')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="rw" class="">17. RW</label>
                                        <input name="rw" id="rw" placeholder="Masukkan RW" type="number" class="form-control @error('rw') is-invalid @enderror" value="{{ $siswa->rw ?? old('rw') }}">
                                        @error('rw')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="desa" class="">18. Desa</label>
                                        <input name="desa" id="desa" placeholder="Masukkan desa" type="text" class="form-control @error('desa') is-invalid @enderror" value="{{ $siswa->desa ?? old('desa') }}">
                                        @error('desa')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="kecamatan" class="">19. Kecamatan</label>
                                        <input name="kecamatan" id="kecamatan" placeholder="Masukkan kecamatan" type="text" class="form-control @error('kecamatan') is-invalid @enderror" value="{{ $siswa->kecamatan ?? old('kecamatan') }}">
                                        @error('kecamatan')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="kabupaten" class="">20. Kabupaten</label>
                                        <input name="kabupaten" id="kabupaten" placeholder="Masukkan kabupaten" type="text" class="form-control @error('kabupaten') is-invalid @enderror" value="{{ $siswa->kabupaten ?? old('kabupaten') }}">
                                        @error('kabupaten')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="provinsi" class="">21. Provinsi</label>
                                        <input name="provinsi" id="provinsi" placeholder="Masukkan provinsi" type="text" class="form-control @error('provinsi') is-invalid @enderror" value="{{ $siswa->provinsi ?? old('provinsi') }}">
                                        @error('provinsi')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="kode_pos" class="">22. Kode Pos</label>
                                        <input name="kode_pos" id="kode_pos" placeholder="Masukkan Kode Pos" type="text" class="form-control @error('kode_pos') is-invalid @enderror" value="{{ $siswa->kode_pos ?? old('kode_pos') }}">
                                        @error('kode_pos')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="lintang" class="">23. Lintang</label>
                                        <input name="lintang" id="lintang" placeholder="Masukkan lintang" type="text" class="form-control @error('lintang') is-invalid @enderror" value="{{ $siswa->lintang ?? old('lintang') }}">
                                        @error('lintang')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="bujur" class="">24. Bujur</label>
                                        <input name="bujur" id="bujur" placeholder="Masukkan bujur" type="text" class="form-control @error('bujur') is-invalid @enderror" value="{{ $siswa->bujur ?? old('bujur') }}">
                                        @error('bujur')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="tempat_tinggal">25. Jenis Tinggal</label>
                                        <select name="tempat_tinggal" id="tempat_tinggal" class="form-control @error('tempat_tinggal') is-invalid @enderror">
                                            <option value="" selected disabled>-- Pilih Jenis Tinggal --</option>
                                            <option value="1" {{ old('tempat_tinggal') == '1' || $siswa->tempat_tinggal == '1' ? 'selected' : '' }}>Bersama Orangtua</option>
                                            <option value="2" {{ old('tempat_tinggal') == '2' || $siswa->tempat_tinggal == '2' ? 'selected' : '' }}>Wali</option>
                                            <option value="3" {{ old('tempat_tinggal') == '3' || $siswa->tempat_tinggal == '3' ? 'selected' : '' }}>Kos</option>
                                            <option value="4" {{ old('tempat_tinggal') == '4' || $siswa->tempat_tinggal == '4' ? 'selected' : '' }}>Asrama</option>
                                            <option value="5" {{ old('tempat_tinggal') == '5' || $siswa->tempat_tinggal == '5' ? 'selected' : '' }}>Panti Asuhan</option>
                                        </select>
                                    
                                        @error('tempat_tinggal')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>                        
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="transportasi_id">26. Transportasi</label>
                                        <select name="transportasi_id" id="transportasi_id"  class="multiselect-dropdown form-control @error('transportasi_id') is-invalid @enderror">
                                            <option value="" selected disabled>-- Pilih Transportaso --</option>
                                            @foreach ($transportasi as $item)
                                                <option value="{{$item->id}}" {{ old('transportasi_id') == $item->id || $siswa->transportasi_id == $item->id ? 'selected' : '' }}>{{$item->nama_transportasi}}</option>
                                            @endforeach
                                        </select>
                                        @error('transportasi_id')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="step-22">
                            <div class="form-row">
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="anak_ke" class="">1. Anak Keberapa</label>
                                        <input name="anak_ke" id="anak_ke" placeholder="Masukkan Anak Keberapa" type="number" class="form-control @error('anak_ke') is-invalid @enderror" value="{{ $siswa->anak_ke ?? old('anak_ke') }}">
                                        @error('anak_ke')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="jumlah_saudara" class="">2. Jumlah Saudara</label>
                                        <input name="jumlah_saudara" id="jumlah_saudara" placeholder="Masukkan Jumlah Saudara" type="number" class="form-control @error('jumlah_saudara') is-invalid @enderror" value="{{ $siswa->jumlah_saudara ?? old('jumlah_saudara') }}">
                                        @error('jumlah_saudara')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="tinggi_badan" class="">3. Tinggi Badan</label>
                                        <input name="tinggi_badan" id="tinggi_badan" placeholder="Masukkan Tinggi Badan" type="number" class="form-control @error('tinggi_badan') is-invalid @enderror" value="{{ $siswa->tinggi_badan ?? old('tinggi_badan') }}">
                                        @error('tinggi_badan')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="berat_badan" class="">4. Berat Badan</label>
                                        <input name="berat_badan" id="berat_badan" placeholder="Masukkan Berat Badan" type="number" class="form-control @error('berat_badan') is-invalid @enderror" value="{{ $siswa->berat_badan ?? old('berat_badan') }}">
                                        @error('berat_badan')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="lingkar_kepala" class="">5. Lingkar Kepala</label>
                                        <input name="lingkar_kepala" id="lingkar_kepala" placeholder="Masukkan Lingkar Kepala" type="number" class="form-control @error('lingkar_kepala') is-invalid @enderror" value="{{ $siswa->lingkar_kepala ?? old('lingkar_kepala') }}">
                                        @error('lingkar_kepala')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="jarak" class="">6. Jarak ke Sekolah (Meter)</label>
                                        <input name="jarak" id="jarak" placeholder="Masukkan jarak ke Sekolah" type="number" class="form-control @error('jarak') is-invalid @enderror" value="{{ $siswa->jarak ?? old('jarak') }}">
                                        @error('jarak')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="waktu_tempuh" class="">7. Waktu Tempuh (Menit)</label>
                                        <input name="waktu_tempuh" id="waktu_tempuh" placeholder="Masukkan waktu tempuh" type="number" class="form-control @error('waktu_tempuh') is-invalid @enderror" value="{{ $siswa->waktu_tempuh ?? old('waktu_tempuh') }}">
                                        @error('waktu_tempuh')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="step-32">
                            <div class="form-row">
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="nomor_hp" class="">1. No Handphone</label>
                                        <input name="nomor_hp" id="nomor_hp" placeholder="Masukkan no handphone" type="text" class="form-control @error('nomor_hp') is-invalid @enderror" value="{{ $siswa->nomor_hp ?? old('nomor_hp') }}">
                                        @error('nomor_hp')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="whatsapp" class="">2. Whatsapp</label>
                                        <input name="whatsapp" id="whatsapp" placeholder="Masukkan whatsapp" type="text" class="form-control @error('whatsapp') is-invalid @enderror" value="{{ $siswa->whatsapp ?? old('whatsapp') }}">
                                        @error('whatsapp')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="email" class="">3. Email</label>
                                        <input name="email" id="email" placeholder="Masukkan email" type="text" class="form-control @error('email') is-invalid @enderror" value="{{ $siswa->email ?? old('email') }}">
                                        @error('email')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="step-42">
                            <div class="form-row">
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="nik_ayah" class="">1. NIK Ayah</label>
                                        <input name="nik_ayah" id="nik_ayah" placeholder="Masukkan NIK Ayah" type="number" class="form-control @error('nik_ayah') is-invalid @enderror" value="{{ $siswa->nik_ayah ?? old('nik_ayah') }}">
                                        @error('nik_ayah')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="nama_ayah" class="">2. Nama Ayah</label>
                                        <input name="nama_ayah" id="nama_ayah" placeholder="Masukkan Nama Ayah" type="text" class="form-control @error('nama_ayah') is-invalid @enderror" value="{{ $siswa->nama_ayah ?? old('nama_ayah') }}">
                                        @error('nama_ayah')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="lahir_ayah" class="">3. Tahun Lahir Ayah</label>
                                        <input name="lahir_ayah" id="lahir_ayah" placeholder="Masukkan Tahun Lahir Ayah" type="number" class="form-control @error('lahir_ayah') is-invalid @enderror" value="{{ $siswa->lahir_ayah ?? old('lahir_ayah') }}">
                                        @error('lahir_ayah')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="penghasilan_ayah_id">4. Penghasilan Ayah</label>
                                        <select name="penghasilan_ayah_id" id="penghasilan_ayah_id"  class="multiselect-dropdown form-control @error('penghasilan_ayah_id') is-invalid @enderror">
                                            <option value="" selected disabled>-- Pilih Penghasilan --</option>
                                            @foreach ($penghasilan as $item)
                                                <option value="{{$item->id}}" {{ old('penghasilan_ayah_id') == $item->id || $siswa->penghasilan_ayah_id == $item->id ? 'selected' : '' }}>{{$item->nama_penghasilan}}</option>
                                            @endforeach
                                        </select>
                                        @error('penghasilan_ayah_id')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="pekerjaan_ayah_id">5. Pekerjaan Ayah</label>
                                        <select name="pekerjaan_ayah_id" id="pekerjaan_ayah_id"  class="multiselect-dropdown form-control @error('pekerjaan_ayah_id') is-invalid @enderror">
                                            <option value="" selected disabled>-- Pilih Pekerjaan --</option>
                                            @foreach ($pekerjaan as $item)
                                                <option value="{{$item->id}}" {{ old('pekerjaan_ayah_id') == $item->id || $siswa->pekerjaan_ayah_id == $item->id ? 'selected' : '' }}>{{$item->nama_pekerjaan}}</option>
                                            @endforeach
                                        </select>
                                        @error('pekerjaan_ayah_id')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="berkebutuhan_khusus_ayah_id">6. Berkebutuhan Khusus</label>
                                        <select name="berkebutuhan_khusus_ayah_id" id="berkebutuhan_khusus_ayah_id"  class="multiselect-dropdown form-control @error('berkebutuhan_khusus_ayah_id') is-invalid @enderror">
                                            <option value="" selected disabled>-- Pilih Kebutuhan Khusus --</option>
                                            @foreach ($berkebutuhan_khusus as $item)
                                                <option value="{{$item->id}}" {{ old('berkebutuhan_khusus_ayah_id') == $item->id || $siswa->berkebutuhan_khusus_ayah_id == $item->id ? 'selected' : '' }}>{{$item->nama_berkebutuhan_khusus}}</option>
                                            @endforeach
                                        </select>
                                        @error('berkebutuhan_khusus_ayah_id')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="jenjang_pendidikan_ayah_id">7. Pendidikan Ayah</label>
                                        <select name="jenjang_pendidikan_ayah_id" id="jenjang_pendidikan_ayah_id"  class="multiselect-dropdown form-control @error('jenjang_pendidikan_ayah_id') is-invalid @enderror">
                                            <option value="" selected disabled>-- Pilih Pendidikan --</option>
                                            @foreach ($pendidikan as $item)
                                                <option value="{{$item->id}}" {{ old('jenjang_pendidikan_ayah_id') == $item->id || $siswa->jenjang_pendidikan_ayah_id == $item->id ? 'selected' : '' }}>{{$item->nama_jenjang_pendidikan}}</option>
                                            @endforeach
                                        </select>
                                        @error('jenjang_pendidikan_ayah_id')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="step-52">
                            <div class="form-row">
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="nik_ibu" class="">1. NIK ibu</label>
                                        <input name="nik_ibu" id="nik_ibu" placeholder="Masukkan NIK ibu" type="number" class="form-control @error('nik_ibu') is-invalid @enderror" value="{{ $siswa->nik_ibu ?? old('nik_ibu') }}">
                                        @error('nik_ibu')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="nama_ibu" class="">2. Nama ibu</label>
                                        <input name="nama_ibu" id="nama_ibu" placeholder="Masukkan Nama ibu" type="text" class="form-control @error('nama_ibu') is-invalid @enderror" value="{{ $siswa->nama_ibu ?? old('nama_ibu') }}">
                                        @error('nama_ibu')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="lahir_ibu" class="">3. Tahun Lahir ibu</label>
                                        <input name="lahir_ibu" id="lahir_ibu" placeholder="Masukkan Tahun Lahir ibu" type="number" class="form-control @error('lahir_ibu') is-invalid @enderror" value="{{ $siswa->lahir_ibu ?? old('lahir_ibu') }}">
                                        @error('lahir_ibu')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="penghasilan_ibu_id">4. Penghasilan ibu</label>
                                        <select name="penghasilan_ibu_id" id="penghasilan_ibu_id"  class="multiselect-dropdown form-control @error('penghasilan_ibu_id') is-invalid @enderror">
                                            <option value="" selected disabled>-- Pilih Penghasilan --</option>
                                            @foreach ($penghasilan as $item)
                                                <option value="{{$item->id}}" {{ old('penghasilan_ibu_id') == $item->id || $siswa->penghasilan_ibu_id == $item->id ? 'selected' : '' }}>{{$item->nama_penghasilan}}</option>
                                            @endforeach
                                        </select>
                                        @error('penghasilan_ibu_id')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="pekerjaan_ibu_id">5. Pekerjaan ibu</label>
                                        <select name="pekerjaan_ibu_id" id="pekerjaan_ibu_id"  class="multiselect-dropdown form-control @error('pekerjaan_ibu_id') is-invalid @enderror">
                                            <option value="" selected disabled>-- Pilih Pekerjaan --</option>
                                            @foreach ($pekerjaan as $item)
                                                <option value="{{$item->id}}" {{ old('pekerjaan_ibu_id') == $item->id || $siswa->pekerjaan_ibu_id == $item->id ? 'selected' : '' }}>{{$item->nama_pekerjaan}}</option>
                                            @endforeach
                                        </select>
                                        @error('pekerjaan_ibu_id')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="berkebutuhan_khusus_ibu_id">6. Berkebutuhan Khusus</label>
                                        <select name="berkebutuhan_khusus_ibu_id" id="berkebutuhan_khusus_ibu_id"  class="multiselect-dropdown form-control @error('berkebutuhan_khusus_ibu_id') is-invalid @enderror">
                                            <option value="" selected disabled>-- Pilih Kebutuhan Khusus --</option>
                                            @foreach ($berkebutuhan_khusus as $item)
                                                <option value="{{$item->id}}" {{ old('berkebutuhan_khusus_ibu_id') == $item->id || $siswa->berkebutuhan_khusus_ibu_id == $item->id ? 'selected' : '' }}>{{$item->nama_berkebutuhan_khusus}}</option>
                                            @endforeach
                                        </select>
                                        @error('berkebutuhan_khusus_ibu_id')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="jenjang_pendidikan_ibu_id">7. Pendidikan ibu</label>
                                        <select name="jenjang_pendidikan_ibu_id" id="jenjang_pendidikan_ibu_id"  class="multiselect-dropdown form-control @error('jenjang_pendidikan_ibu_id') is-invalid @enderror">
                                            <option value="" selected disabled>-- Pilih Pendidikan --</option>
                                            @foreach ($pendidikan as $item)
                                                <option value="{{$item->id}}" {{ old('jenjang_pendidikan_ibu_id') == $item->id || $siswa->jenjang_pendidikan_ibu_id == $item->id ? 'selected' : '' }}>{{$item->nama_jenjang_pendidikan}}</option>
                                            @endforeach
                                        </select>
                                        @error('jenjang_pendidikan_ibu_id')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="step-62">
                            <div class="form-row">
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="nik_wali" class="">1. NIK wali</label>
                                        <input name="nik_wali" id="nik_wali" placeholder="Masukkan NIK wali" type="number" class="form-control @error('nik_wali') is-invalid @enderror" value="{{ $siswa->nik_wali ?? old('nik_wali') }}">
                                        @error('nik_wali')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="nama_wali" class="">2. Nama wali</label>
                                        <input name="nama_wali" id="nama_wali" placeholder="Masukkan Nama wali" type="text" class="form-control @error('nama_wali') is-invalid @enderror" value="{{ $siswa->nama_wali ?? old('nama_wali') }}">
                                        @error('nama_wali')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="lahir_wali" class="">3. Tahun Lahir wali</label>
                                        <input name="lahir_wali" id="lahir_wali" placeholder="Masukkan Tahun Lahir wali" type="number" class="form-control @error('lahir_wali') is-invalid @enderror" value="{{ $siswa->lahir_wali ?? old('lahir_wali') }}">
                                        @error('lahir_wali')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="penghasilan_wali_id">4. Penghasilan wali</label>
                                        <select name="penghasilan_wali_id" id="penghasilan_wali_id"  class="multiselect-dropdown form-control @error('penghasilan_wali_id') is-invalid @enderror">
                                            <option value="" selected disabled>-- Pilih Penghasilan --</option>
                                            @foreach ($penghasilan as $item)
                                                <option value="{{$item->id}}" {{ old('penghasilan_wali_id') == $item->id || $siswa->penghasilan_wali_id == $item->id ? 'selected' : '' }}>{{$item->nama_penghasilan}}</option>
                                            @endforeach
                                        </select>
                                        @error('penghasilan_wali_id')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="pekerjaan_wali_id">5. Pekerjaan wali</label>
                                        <select name="pekerjaan_wali_id" id="pekerjaan_wali_id"  class="multiselect-dropdown form-control @error('pekerjaan_wali_id') is-invalid @enderror">
                                            <option value="" selected disabled>-- Pilih Pekerjaan --</option>
                                            @foreach ($pekerjaan as $item)
                                                <option value="{{$item->id}}" {{ old('pekerjaan_wali_id') == $item->id || $siswa->pekerjaan_wali_id == $item->id ? 'selected' : '' }}>{{$item->nama_pekerjaan}}</option>
                                            @endforeach
                                        </select>
                                        @error('pekerjaan_wali_id')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="berkebutuhan_khusus_wali_id">6. Berkebutuhan Khusus</label>
                                        <select name="berkebutuhan_khusus_wali_id" id="berkebutuhan_khusus_wali_id"  class="multiselect-dropdown form-control @error('berkebutuhan_khusus_wali_id') is-invalid @enderror">
                                            <option value="" selected disabled>-- Pilih Kebutuhan Khusus --</option>
                                            @foreach ($berkebutuhan_khusus as $item)
                                                <option value="{{$item->id}}" {{ old('berkebutuhan_khusus_wali_id') == $item->id || $siswa->berkebutuhan_khusus_wali_id == $item->id ? 'selected' : '' }}>{{$item->nama_berkebutuhan_khusus}}</option>
                                            @endforeach
                                        </select>
                                        @error('berkebutuhan_khusus_wali_id')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="jenjang_pendidikan_wali_id">7. Pendidikan wali</label>
                                        <select name="jenjang_pendidikan_wali_id" id="jenjang_pendidikan_wali_id"  class="multiselect-dropdown form-control @error('jenjang_pendidikan_wali_id') is-invalid @enderror">
                                            <option value="" selected disabled>-- Pilih Pendidikan --</option>
                                            @foreach ($pendidikan as $item)
                                                <option value="{{$item->id}}" {{ old('jenjang_pendidikan_wali_id') == $item->id || $siswa->jenjang_pendidikan_wali_id == $item->id  ? 'selected' : '' }}>{{$item->nama_jenjang_pendidikan}}</option>
                                            @endforeach
                                        </select>
                                        @error('jenjang_pendidikan_wali_id')
                                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                                {{ strtolower($message) }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="step-72">
                            <div class="no-results">
                                <div class="swal2-icon swal2-success swal2-animate-success-icon">
                                    <div class="swal2-success-circular-line-left" style="background-color: rgb(255, 255, 255);"></div>
                                    <span class="swal2-success-line-tip"></span>
                                    <span class="swal2-success-line-long"></span>
                                    <div class="swal2-success-ring"></div>
                                    <div class="swal2-success-fix" style="background-color: rgb(255, 255, 255);"></div>
                                    <div class="swal2-success-circular-line-right" style="background-color: rgb(255, 255, 255);"></div>
                                </div>
                                <div class="results-subtitle mt-4">Selesai!</div>
                                <div class="results-title">Cek kembali inputan anda! Jika sudah klik tombol simpan.</div>
                                <div class="mt-3 mb-3"></div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary"value="Simpan" id="submitBtn">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="divider"></div>
                <div class="clearfix">
                    <button type="button" id="next-btn2" class="btn-shadow btn-wide float-right btn-pill btn-hover-shine btn btn-primary">Next</button>
                    <button type="button" id="prev-btn2" class="btn-shadow float-right btn-wide btn-pill mr-3 btn btn-outline-secondary">Previous</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById("editForm").addEventListener("submit", function(event) {
        document.getElementById("submitBtn").disabled = true;
        document.getElementById("submitBtn").innerText = "Menyimpan...";
    });
</script>

@endsection
