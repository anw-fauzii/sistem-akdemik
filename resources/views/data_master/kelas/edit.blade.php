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
                <div>Update Kelas
                    <div class="page-title-subheading">
                        Update kelas yang lama
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
            <form  method="post" action="{{route('kelas.update', $kelas->id)}}" id="editForm">
                @csrf
                @method('PUT')
                <div class="form-row">
                    <div class="col-md-4">
                        <div class="position-relative form-group">
                            <label for="jenjang" class="">Jenjang</label>
                            <select name="jenjang" id="jenjang"  class="multiselect-dropdown form-control @error('jenjang') is-invalid @enderror" value="{{ $kelas->jenjang ?? old('jenjang') }}">
                                <option value="" selected disabled>-- Pilih Jenjang --</option>
                                <option value="SD" {{ old('jenjang') == '1' || $kelas->jenjang == 'SD' ? 'selected' : '' }}>SD GIS Prima Insani</option>
                                <option value="PG TK" {{ old('jenjang') == '1' || $kelas->jenjang == 'PG TK' ? 'selected' : '' }}>PG TK Islam Plus Prima Insani</option>
                            </select>
                            @error('jenjang')
                                <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                    {{ strtolower($message) }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="position-relative form-group">
                            <label for="tingkatan_kelas" class="">Tingkat Kelas</label>
                            <input name="tingkatan_kelas" id="tingkatan_kelas" placeholder="Masukkan tinkat kelas" type="number" class="form-control @error('tingkatan_kelas') is-invalid @enderror" value="{{ $kelas->tingkatan_kelas ?? old('tingkatan_kelas') }}">
                            @error('tingkatan_kelas')
                                <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                    {{ strtolower($message) }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="nama_kelas" class="">Nama Kelas</label>
                            <input name="nama_kelas" id="nama_kelas" placeholder="Masukkan nama kelas" type="text" class="form-control @error('nama_kelas') is-invalid @enderror" value="{{ $kelas->nama_kelas ?? old('nama_kelas') }}">
                            @error('nama_kelas')
                                <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                    {{ strtolower($message) }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="romawi" class="">Romawi</label>
                            <input name="romawi" id="romawi" placeholder="Masukkan dalam format romawi" type="text" class="form-control @error('romawi') is-invalid @enderror" value="{{ $kelas->romawi ?? old('romawi') }}">
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
                                    <option value="{{$item->nipy}}" {{ old('guru_nipy') == $item->nipy || $kelas->guru_nipy == $item->nipy ? 'selected' : '' }}>{{$item->nama_lengkap}}, {{$item->gelar}}.</option>
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
                                    <option value="{{$item->nipy}}" {{ old('pendamping_nipy') == $item->nipy || $kelas->pendamping_nipy == $item->nipy ? 'selected' : '' }}>{{$item->nama_lengkap}}, {{$item->gelar}}.</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="spp" class="">SPP</label>
                            <input name="spp" id="spp" placeholder="Masukkan spp" type="number" class="form-control @error('spp') is-invalid @enderror" value="{{ $kelas->spp ?? old('spp') }}">
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
                            <input name="biaya_makan" id="biaya_makan" placeholder="Masukkan biaya makan" type="number" class="form-control @error('biaya_makan') is-invalid @enderror" value="{{ $kelas->biaya_makan ?? old('biaya_makan') }}">
                            @error('biaya_makan')
                                <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                    {{ strtolower($message) }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" name="signup" value="Simpan" id="submitBtn">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById("editForm");
        const submitBtn = document.getElementById("submitBtn");

        form.addEventListener("submit", function () {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...`;
        });
    });
</script>

@endsection
