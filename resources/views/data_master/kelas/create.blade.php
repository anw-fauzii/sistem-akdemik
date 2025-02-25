@extends('layouts.app2')

@section('title')
    <title>Kelas</title>
@endsection

@section('content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-rocket icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Tambah Kelas
                    <div class="page-title-subheading">
                        Membuat kelas yang baru
                    </div>
                </div>
            </div>  
        </div> 
    </div>

    <div class="main-card card">
        <div class="card-header">
            Tambah Data
        </div>
        <div class="card-body">
            <form  method="post" action="{{route('kelas.store')}}" id="createForm">
                @csrf
                <div class="form-row">
                    <div class="col-md-2">
                        <div class="position-relative form-group">
                            <label for="tingkatan_kelas" class="">Tingkat Kelas</label>
                            <input name="tingkatan_kelas" id="tingkatan_kelas" placeholder="Masukkan tinkat kelas" type="number" class="form-control @error('tingkatan_kelas') is-invalid @enderror" value="{{ old('tingkatan_kelas') }}">
                            @error('tingkatan_kelas')
                                <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                    {{ strtolower($message) }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="position-relative form-group">
                            <label for="nama_kelas" class="">Nama Kelas</label>
                            <input name="nama_kelas" id="nama_kelas" placeholder="Masukkan nama kelas" type="text" class="form-control @error('nama_kelas') is-invalid @enderror" value="{{ old('nama_kelas') }}">
                            @error('nama_kelas')
                                <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                    {{ strtolower($message) }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="position-relative form-group">
                            <label for="romawi" class="">Romawi</label>
                            <input name="romawi" id="romawi" placeholder="Masukkan dalam format romawi" type="text" class="form-control @error('romawi') is-invalid @enderror" value="{{ old('romawi') }}">
                            @error('romawi')
                                <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                    {{ strtolower($message) }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="guru_nipy">Wali Kelas</label>
                            <select name="guru_nipy" id="guru_nipy"  class="multiselect-dropdown form-control @error('guru_nipy') is-invalid @enderror">
                                <option value="" selected disabled>-- Pilih Wali Kelas --</option>
                                @foreach ($guru as $item)
                                    <option value="{{$item->nipy}}" {{ old('guru_nipy') == $item->id ? 'selected' : '' }}>{{$item->nama_lengkap}}, {{$item->gelar}}.</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="pendamping_nipy">Pendamping</label>
                            <select name="pendamping_nipy" id="pendamping_nipy"  class="multiselect-dropdown form-control @error('pendamping_nipy') is-invalid @enderror">
                                <option value="" selected disabled>-- Pilih Wali Kelas --</option>
                                @foreach ($guru as $item)
                                <option value="{{$item->nipy}}" {{ old('pendamping_nipy') == $item->id ? 'selected' : '' }}>{{$item->nama_lengkap}}, {{$item->gelar}}.</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="spp" class="">SPP</label>
                            <input name="spp" id="spp" placeholder="Masukkan spp" type="number" class="form-control @error('spp') is-invalid @enderror" value="{{ old('spp') }}">
                            @error('spp')
                                <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                    {{ strtolower($message) }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="biaya_makan" class="">Biaya Makan</label>
                            <input name="biaya_makan" id="biaya_makan" placeholder="Masukkan biaya makan" type="number" class="form-control @error('biaya_makan') is-invalid @enderror" value="{{ old('biaya_makan') }}">
                            @error('biaya_makan')
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
    document.getElementById("kebutuhanForm").addEventListener("submit", function(event) {
        document.getElementById("submitBtn").disabled = true;
        document.getElementById("submitBtn").innerText = "Menyimpan...";
    });
</script>

@endsection
