@extends('layouts.app2')

@section('title')
    <title>Kategori Administasi</title>
@endsection

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-rocket icon-gradient bg-mean-fruit"></i>
                    </div>
                    <div>Tambah Kategori Administasi
                        <div class="page-title-subheading">
                            Membuat Kategori Administasi yang baru
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
                <form method="post" action="{{ route('kategori-administrasi.store') }}" id="createForm">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label for="nama_kategori" class="">Nama Kategori</label>
                                <input name="nama_kategori" id="nama_kategori" placeholder="nama_kategori" type="text"
                                    class="form-control @error('nama_kategori') is-invalid @enderror"
                                    value="{{ old('nama_kategori') }}">
                                @error('nama_kategori')
                                    <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label for="jenis">Jenis Administasi</label>
                                <select name="jenis" id="jenis"
                                    class="form-control @error('jenis') is-invalid @enderror">
                                    <option value="" selected disabled>-- Pilih jenis --</option>
                                    <option value="guru" {{ old('jenis') == 'guru' ? 'selected' : '' }}>Guru
                                    </option>
                                    <option value="kelas" {{ old('jenis') == 'kelas' ? 'selected' : '' }}>Kelas
                                    </option>
                                </select>
                                @error('jenis')
                                    <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label for="semester">Berlaku di setiap semester?</label>
                                <select name="semester" id="semester"
                                    class="form-control @error('semester') is-invalid @enderror">
                                    <option value="" selected disabled>-- Pilih Jawaban --</option>
                                    <option value="1" {{ old('semester') == '1' ? 'selected' : '' }}>Ya
                                    </option>
                                    <option value="0" {{ old('semester') == '0' ? 'selected' : '' }}>Tidak
                                    </option>
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
                        <button type="submit" class="btn btn-primary"value="Simpan" id="submitBtn">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById("createForm");
            const submitBtn = document.getElementById("submitBtn");

            form.addEventListener("submit", function() {
                submitBtn.disabled = true;
                submitBtn.innerHTML =
                    `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...`;
            });
        });
    </script>
@endsection
