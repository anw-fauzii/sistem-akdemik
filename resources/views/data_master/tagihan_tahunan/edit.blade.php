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
                <div>Update Tagihan Tahunan
                    <div class="page-title-subheading">
                        Memperbarui Tagihan Tahunan yang baru
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
            <form  method="post" action="{{route('tagihan-tahunan.update', $tagihan_tahunan->id)}}" id="editForm">
                @csrf
                @method('PUT')
                <div class="form-row">
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="jenjang" class="">Jenjang</label>
                            <select name="jenjang" id="jenjang"  class="multiselect-dropdown form-control @error('jenjang') is-invalid @enderror">
                                <option value="" selected disabled>-- Pilih jenjang --</option>
                                <option value="SD" {{ old('jenjang') == '1' || $tagihan_tahunan->jenjang == 'SD' ? 'selected' : '' }}>SD GIS Prima Insani</option>
                                <option value="PG TK" {{ old('jenjang') == '1' || $tagihan_tahunan->jenjang == 'PG TK' ? 'selected' : '' }}>PG TK Islam Plus Prima Insani</option>
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
                            <input name="jenis" id="jenis" placeholder="" type="teks" class="form-control @error('jenis') is-invalid @enderror" value="{{ $tagihan_tahunan->jenis ?? old('jenis') }}">
                            @error('jenis')
                                <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                    {{ strtolower($message) }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="kelas" class="">Kelas</label>
                            <input name="kelas" id="kelas" placeholder="Untuk Kelas" type="number" class="form-control @error('kelas') is-invalid @enderror" value="{{ $tagihan_tahunan->kelas ?? old('kelas') }}">
                            @error('kelas')
                                <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                    {{ strtolower($message) }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="jumlah" class="">Total Biaya</label>
                            <input name="jumlah" id="jumlah" placeholder="jumlah" type="text" class="form-control @error('jumlah') is-invalid @enderror" value="{{ $tagihan_tahunan->jumlah ??  old('jumlah') }}">
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
        const form = document.getElementById("editForm");
        const submitBtn = document.getElementById("submitBtn");

        form.addEventListener("submit", function () {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...`;
        });
    });
</script>

@endsection
