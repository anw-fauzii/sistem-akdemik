@extends('layouts.app2')

@section('title')
    <title>Guru</title>
@endsection

@section('content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-rocket icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Update Guru
                    <div class="page-title-subheading">
                        Memperbarui Guru yang baru
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
            <form  method="post" action="{{route('guru.update', $guru->nipy)}}" id="editForm">
                @csrf
                @method('PUT')
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="nuptk" class="">NUPTK</label>
                            <input name="nuptk" id="nuptk" placeholder="nuptk" type="text" class="form-control @error('nuptk') is-invalid @enderror" value="{{ $guru->nuptk ?? old('nuptk') }}">
                            @error('nuptk')
                                <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                    {{ strtolower($message) }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="nipy" class="">NIPY</label>
                            <input name="nipy" id="nipy" placeholder="NIPY" type="text" class="form-control @error('nipy') is-invalid @enderror" value="{{ $guru->nipy ?? old('nipy') }}">
                            @error('nipy')
                                <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                    {{ strtolower($message) }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="position-relative form-group">
                            <label for="nama_lengkap" class="">Nama Lengkap</label>
                            <input name="nama_lengkap" id="nama_lengkap" placeholder="Masukkan tahun ajaran" type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" value="{{ $guru->nama_lengkap ?? old('nama_lengkap') }}">
                            @error('nama_lengkap')
                                <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                    {{ strtolower($message) }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="position-relative form-group">
                            <label for="gelar" class="">Gelar</label>
                            <input name="gelar" id="gelar" placeholder="Masukkan Gelar" type="text" class="form-control @error('gelar') is-invalid @enderror" value="{{ $guru->gelar ?? old('gelar') }}">
                            @error('gelar')
                                <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                    {{ strtolower($message) }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="position-relative form-group">
                            <label for="jenis_kelamin">Jenis Kelamin</label>
                            <select name="jenis_kelamin" id="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror">
                                <option value="" selected disabled>-- Pilih jenis_kelamin --</option>
                                <option value="L" {{ old('jenis_kelamin') == 'L' || $guru->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' || $guru->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                    {{ strtolower($message) }}
                                </div>
                            @enderror
                        </div>                        
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="jabatan" class="">Jabatan</label>
                            <input name="jabatan" id="jabatan" placeholder="jabatan" type="text" class="form-control @error('jabatan') is-invalid @enderror" value="{{ $guru->jabatan ?? old('jabatan') }}">
                            @error('jabatan')
                                <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                    {{ strtolower($message) }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="telepon" class="">Telepon/Whatsapp</label>
                            <input name="telepon" id="telepon" placeholder="telepon" type="text" class="form-control @error('telepon') is-invalid @enderror" value="{{ $guru->telepon ?? old('telepon') }}">
                            @error('telepon')
                                <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                    {{ strtolower($message) }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="tempat_lahir" class="">Tempat Lahir</label>
                            <input name="tempat_lahir" id="tempat_lahir" placeholder="Masukan Tempat Lahir" type="text" class="form-control @error('tempat_lahir') is-invalid @enderror" value="{{ $guru->tempat_lahir ?? old('tempat_lahir') }}">
                            @error('tempat_lahir')
                                <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                    {{ strtolower($message) }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="tanggal_lahir" class="">Tanggal Lahir</label>
                            <input name="tanggal_lahir" id="tanggal_lahir" placeholder="tanggal_lahir" type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" value="{{ $guru->tanggal_lahir ?? old('tanggal_lahir') }}">
                            @error('tanggal_lahir')
                                <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                    {{ strtolower($message) }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="alamat" class="">Alamat Lengkap</label>
                            <input name="alamat" id="alamat" placeholder="alamat" type="text" class="form-control @error('alamat') is-invalid @enderror" value="{{ $guru->alamat ?? old('alamat') }}">
                            @error('alamat')
                                <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                    {{ strtolower($message) }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary"value="Simpan" id="submitBtn">Simpan</button>
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
