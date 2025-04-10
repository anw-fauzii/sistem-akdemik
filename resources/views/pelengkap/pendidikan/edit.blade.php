@extends('layouts.app2')

@section('title')
    <title>Jenjang Pendidikan</title>
@endsection

@section('content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-plugin icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Update Jenjang Pendidikan
                    <div class="page-title-subheading">
                        Update Jenjang Pendidikan yang lama
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
            <form  method="post" action="{{route('jenjang-pendidikan.update', $pendidikan->id)}}" id="editForm">
                @csrf
                @method('PUT')
                <div class="form-row">
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="nama_jenjang_pendidikan" class="">Jenjang Pendidikan</label>
                            <input name="nama_jenjang_pendidikan" id="nama_jenjang_pendidikan" placeholder="Masukkan jenjang_pendidikan" type="text" class="form-control @error('nama_jenjang_pendidikan') is-invalid @enderror" value="{{ $pendidikan->nama_jenjang_pendidikan ?? old('nama_jenjang_pendidikan') }}">
                            @error('nama_jenjang_pendidikan')
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
