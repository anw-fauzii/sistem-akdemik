@extends('layouts.app2')

@section('title')
    <title>Tambah Pengumuman</title>
@endsection

@section('content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-info icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Tambah Pengumuman
                    <div class="page-title-subheading">
                        Menambahkan Pengumuman Baru
                    </div>
                </div>
            </div>  
        </div> 
    </div>

    <div class="main-card card">
        <div class="card-header">
            Form Pengumuman
        </div>
        <div class="card-body">
            <form method="post" action="{{ route('pengumuman.store') }}" id="createForm">
                @csrf

                {{-- JUDUL --}}
                <div class="position-relative form-group">
                    <label for="judul">Judul</label>
                    <input type="text" name="judul" id="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul') }}">
                    @error('judul')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- ISI (TinyMCE) --}}
                <div class="position-relative form-group">
                    <label for="isi">Isi Pengumuman</label>
                    <textarea name="isi" id="isi" rows="10" class="form-control @error('isi') is-invalid @enderror">{{ old('isi') }}</textarea>
                    @error('isi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- TANGGAL --}}
                <div class="position-relative form-group">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal') }}">
                    @error('tanggal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- BUTTON --}}
                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary" id="submitBtn">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Spinner saat submit --}}
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
        <script src="https://cdn.ckeditor.com/ckeditor5/35.1.0/classic/ckeditor.js"></script>
        <script>
            ClassicEditor
                .create(document.querySelector('#isi'))
                .catch(error => {
                    console.error(error);
                });
        </script>
@endsection


