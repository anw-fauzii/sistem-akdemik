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
                <div>Tambah Bulan SPP
                    <div class="page-title-subheading">
                        Membuat Bulan SPP yang baru
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
            <form  method="post" action="{{route('bulan-spp.store')}}" id="createForm">
                @csrf
                <div class="form-row">
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="nama_bulan" class="">Nama Bulan</label>
                            <input name="nama_bulan" id="nama_bulan" placeholder="nama_bulan" type="text" class="form-control @error('nama_bulan') is-invalid @enderror" value="{{ old('nama_bulan') }}">
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
                            <input name="bulan_angka" id="bulan_angka" placeholder="" type="date" class="form-control @error('bulan_angka') is-invalid @enderror" value="{{ old('bulan_angka') }}">
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
                            <input name="tambahan" id="tambahan" placeholder="tambahan" type="text" class="form-control @error('tambahan') is-invalid @enderror" value="{{ old('tambahan') }}">
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
    document.getElementById("createForm").addEventListener("submit", function(event) {
        document.getElementById("submitBtn").disabled = true;
        document.getElementById("submitBtn").innerText = "Menyimpan...";
    });
</script>

@endsection
