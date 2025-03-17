@extends('layouts.app2')

@section('title')
    <title>Ekstrakurikuler</title>
@endsection

@section('content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-cash icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Tambah Ekstrakurikuler
                    <div class="page-title-subheading">
                        Membuat Ekstrakurikuler yang baru
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
            <form  method="post" action="{{route('ekstrakurikuler.store')}}" id="createForm">
                @csrf
                <div class="form-row">
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="nama_ekstrakurikuler" class="">Nama Ekstrakurikuler</label>
                            <input name="nama_ekstrakurikuler" id="nama_ekstrakurikuler" placeholder="Masukan nama ekstrakurikuler" type="text" class="form-control @error('nama_ekstrakurikuler') is-invalid @enderror" value="{{ old('nama_ekstrakurikuler') }}">
                            @error('nama_ekstrakurikuler')
                                <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                    {{ strtolower($message) }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="guru_nipy">Guru Pendamping</label>
                            <select name="guru_nipy" id="guru_nipy"  class="multiselect-dropdown form-control @error('guru_nipy') is-invalid @enderror">
                                <option value="" selected disabled>-- Pilih Guru Pendamping --</option>
                                @foreach ($guru as $item)
                                    <option value="{{$item->nipy}}" {{ old('guru_nipy') == $item->nipy ? 'selected' : '' }}>{{$item->nama_lengkap}}, {{$item->gelar}}.</option>
                                @endforeach
                            </select>
                            @error('guru_nipy')
                                <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                    {{ strtolower($message) }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="biaya" class="">Biaya Per Bulan</label>
                            <input name="biaya" id="biaya" placeholder="Masukan biaya" type="text" class="form-control @error('biaya') is-invalid @enderror" value="{{ old('biaya') }}">
                            @error('biaya')
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
