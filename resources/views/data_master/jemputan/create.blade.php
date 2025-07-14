@extends('layouts.app2')

@section('title')
    <title>Jemputan</title>
@endsection

@section('content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-rocket icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Tambah Jemputan
                    <div class="page-title-subheading">
                        Membuat Jemputan yang baru
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
            <form  method="post" action="{{route('jemputan.store')}}" id="createForm">
                @csrf
                <div class="form-row">
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="driver" class="">Driver</label>
                            <input name="driver" id="driver" placeholder="Masukan Nama Driver" type="text" class="form-control @error('driver') is-invalid @enderror" value="{{ old('driver') }}">
                            @error('driver')
                                <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                    {{ strtolower($message) }}
                                </div>
                            @enderror
                        </div>
                        <div class="position-relative form-group">
                            <label for="harga_pp" class="">Harga Pulang Pergi</label>
                            <input name="harga_pp" id="harga_pp" placeholder="Masukan harga pulang pergi" type="number" class="form-control @error('harga_pp') is-invalid @enderror" value="{{ old('harga_pp') }}">
                            @error('harga_pp')
                                <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                    {{ strtolower($message) }}
                                </div>
                            @enderror
                        </div>
                        <div class="position-relative form-group">
                            <label for="harga_setengah" class="">Harga Setengah</label>
                            <input name="harga_setengah" id="harga_setengah" placeholder="Masukan harga setengah" type="number" class="form-control @error('harga_setengah') is-invalid @enderror" value="{{ old('harga_setengah') }}">
                            @error('harga_setengah')
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
