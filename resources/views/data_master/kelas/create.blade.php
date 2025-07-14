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
                    <div class="col-md-4">
                        <div class="position-relative form-group">
                            <label for="jenjang" class="">Jenjang</label>
                            <select name="jenjang" id="jenjang"  class="multiselect-dropdown form-control @error('jenjang') is-invalid @enderror">
                                <option value="" selected disabled>-- Pilih Jenjang --</option>
                                <option value="SD">SD GIS Prima Insani</option>
                                <option value="PG TK">PG TK Islam Plus Prima Insani</option>
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
                            <input name="tingkatan_kelas" id="tingkatan_kelas" placeholder="Masukkan tinkat kelas" type="number" class="form-control @error('tingkatan_kelas') is-invalid @enderror" value="{{ old('tingkatan_kelas') }}">
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
                            <input name="nama_kelas" id="nama_kelas" placeholder="Masukkan nama kelas" type="text" class="form-control @error('nama_kelas') is-invalid @enderror" value="{{ old('nama_kelas') }}">
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
                                    <option value="{{$item->nipy}}" {{ old('guru_nipy') == $item->nipy ? 'selected' : '' }}>{{$item->nama_lengkap}}, {{$item->gelar}}.</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="pendamping_nipy">Pendamping</label>
                            <select name="pendamping_nipy" id="pendamping_nipy"  class="multiselect-dropdown form-control @error('pendamping_nipy') is-invalid @enderror">
                                <option value="" selected disabled>-- Pilih Pendamping --</option>
                                @foreach ($guru as $item)
                                <option value="{{$item->nipy}}" {{ old('pendamping_nipy') == $item->nipy ? 'selected' : '' }}>{{$item->nama_lengkap}}, {{$item->gelar}}.</option>
                                @endforeach
                            </select>
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
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById("createForm");
        const submitBtn = document.getElementById("submitBtn");

        form.addEventListener("submit", function () {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...`;
        });
    });
</script>

@endsection
