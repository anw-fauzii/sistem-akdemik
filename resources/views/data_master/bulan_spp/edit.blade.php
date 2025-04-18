@extends('layouts.app2')

@section('title')
    <title>Bulan SPP</title>
@endsection

@section('content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-cash icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Update Bulan SPP
                    <div class="page-title-subheading">
                        Memperbarui Bulan SPP yang baru
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
            <form  method="post" action="{{route('bulan-spp.update', $bulan_spp->id)}}" id="editForm">
                @csrf
                @method('PUT')
                <div class="form-row">
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="nama_bulan" class="">Nama Bulan</label>
                            <input name="nama_bulan" id="nama_bulan" placeholder="nama_bulan" type="text" class="form-control @error('nama_bulan') is-invalid @enderror" value="{{ $bulan_spp->nama_bulan ?? old('nama_bulan') }}">
                            @error('nama_bulan')
                                <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                    {{ strtolower($message) }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="bulan_angka" class="">bulan_angka</label>
                            <input name="bulan_angka" id="bulan_angka" placeholder="" type="date" class="form-control @error('bulan_angka') is-invalid @enderror" value="{{ $bulan_spp->bulan_angka ?? old('bulan_angka') }}">
                            @error('bulan_angka')
                                <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                    {{ strtolower($message) }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="tambahan" class="">Biaya Tambahan</label>
                            <input name="tambahan" id="tambahan" placeholder="tambahan" type="text" class="form-control @error('tambahan') is-invalid @enderror" value="{{ $bulan_spp->tambahan ??  old('tambahan') }}">
                            @error('tambahan')
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
        const form = document.getElementById("editForm");
        const submitBtn = document.getElementById("submitBtn");

        form.addEventListener("submit", function () {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...`;
        });
    });
</script>

@endsection
