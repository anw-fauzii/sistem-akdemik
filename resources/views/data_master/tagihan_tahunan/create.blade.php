@extends('layouts.app2')

@section('title')
    <title>Tagihan Tahunan</title>
@endsection

@section('content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-cash icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Tambah Tagihan Tahunan
                    <div class="page-title-subheading">
                        Membuat Tagihan Tahunan yang baru
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
            <form  method="post" action="{{route('tagihan-tahunan.store')}}" id="createForm">
                @csrf
                <div class="form-row">
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="jenjang" class="">Jenjang</label>
                            <select name="jenjang" id="jenjang"  class="multiselect-dropdown form-control @error('jenjang') is-invalid @enderror">
                                <option value="" selected disabled>-- Pilih jenjang --</option>
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
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="jenis" class="">Jenis Pembayaran</label>
                            <input name="jenis" id="jenis" placeholder="Masukan jenis pembayaran" type="text" class="form-control @error('jenis') is-invalid @enderror" value="{{ old('jenis') }}">
                            @error('jenis')
                                <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                    {{ strtolower($message) }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="jumlah" class="">Total Biaya</label>
                            <input name="jumlah" id="jumlah" placeholder="Masukan total biaya" type="text" class="form-control @error('jumlah') is-invalid @enderror" value="{{ old('jumlah') }}">
                            @error('jumlah')
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
