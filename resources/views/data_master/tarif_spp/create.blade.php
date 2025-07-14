@extends('layouts.app2')

@section('title')
    <title>Tarif SPP</title>
@endsection

@section('content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-cash icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Tambah Tarif SPP
                    <div class="page-title-subheading">
                        Membuat Tarif SPP yang baru
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
            <form  method="post" action="{{route('tarif-spp.store')}}" id="createForm">
                @csrf
                <div class="form-row">
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="unit" class="">Nama Jenjang</label>
                            <input name="unit" id="unit" placeholder="Masukan Nama Jenjang" type="text" class="form-control @error('unit') is-invalid @enderror" value="{{ old('unit') }}">
                            @error('unit')
                                <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                    {{ strtolower($message) }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="tahun_masuk" class="">Tahun Masuk</label>
                            <input name="tahun_masuk" id="tahun_masuk" placeholder="Tahun Masuk" type="number" class="form-control @error('tahun_masuk') is-invalid @enderror" value="{{ old('tahun_masuk') }}">
                            @error('tahun_masuk')
                                <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                    {{ strtolower($message) }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="spp" class="">Nominal SPP</label>
                            <input name="spp" id="spp" placeholder="Rp. " type="text" class="form-control autonumeric @error('spp') is-invalid @enderror" value="{{ old('spp') }}">
                            @error('spp')
                                <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                    {{ strtolower($message) }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="biaya_makan" class="">Biaya Makan</label>
                            <input name="biaya_makan" id="biaya_makan" placeholder="Rp. " type="text" class="form-control autonumeric @error('biaya_makan') is-invalid @enderror" value="{{ old('biaya_makan') }}">
                            @error('biaya_makan')
                                <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                    {{ strtolower($message) }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="snack" class="">Biaya Snack</label>
                            <input name="snack" id="snack" placeholder="Rp. " type="text" class="form-control autonumeric @error('snack') is-invalid @enderror" value="{{ old('snack') }}">
                            @error('snack')
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
<script src="https://cdn.jsdelivr.net/npm/autonumeric@4.5.4/dist/autoNumeric.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        AutoNumeric.multiple('.autonumeric', {
            digitGroupSeparator: '.',
            decimalCharacter: ',',
            decimalPlaces: 0,
            minimumValue: '0'
        });
    });
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
