@extends('layouts.app2')

@section('title')
    <title>Tahun Ajaran</title>
@endsection

@section('content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-rocket icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Update Tahun Ajaran
                    <div class="page-title-subheading">
                        Update tahun ajaran yang lama
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
            <form  method="post" action="{{route('tahun-ajaran.update', $tahun_ajaran->id)}}" id="editForm">
                @csrf
                @method('PUT')
                <div class="form-row">
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="nama_tahun_ajaran" class="">Tahun Ajaran</label>
                            <input name="nama_tahun_ajaran" id="nama_tahun_ajaran" placeholder="Masukkan tahun ajaran" type="text" class="form-control @error('nama_tahun_ajaran') is-invalid @enderror" value="{{ $tahun_ajaran->nama_tahun_ajaran ?? old('nama_tahun_ajaran') }}">
                            @error('nama_tahun_ajaran')
                                <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                    {{ strtolower($message) }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="semester">Semester</label>
                            <select name="semester" id="semester" class="form-control @error('semester') is-invalid @enderror">
                                <option value="" selected disabled>-- Pilih Semester --</option>
                                <option value="1" {{ old('semester') == '1' || $tahun_ajaran->semester == '1' ? 'selected' : '' }}>1 (Ganjil)</option>
                                <option value="2" {{ old('semester') == '2' || $tahun_ajaran->semester == '2' ? 'selected' : '' }}>2 (Genap)</option>
                            </select>
                            @error('semester')
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
