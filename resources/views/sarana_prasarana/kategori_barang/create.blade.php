@extends('layouts.app2')

@section('title')
    <title>Kategori Barang</title>
@endsection

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-box2 icon-gradient bg-mean-fruit"></i>
                    </div>
                    <div>Tambah Kategori Barang
                        <div class="page-title-subheading">
                            Membuat Kategori Barang yang baru
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
                <form method="post" action="{{ route('kategori-barang.store') }}" id="createForm">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label for="id" class="">Kode Kategori</label>
                                <input name="id" id="id" placeholder="Contoh: YYS-KTR-01" type="text"
                                    class="form-control @error('id') is-invalid @enderror"
                                    value="{{ old('id') }}">
                                @error('id')
                                    <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                            <div class="position-relative form-group">
                                <label for="nama_kategori_barang" class="">Nama Kategori</label>
                                <input name="nama_kategori_barang" id="nama_kategori_barang" placeholder="nama_kategori_barang" type="text"
                                    class="form-control @error('nama_kategori_barang') is-invalid @enderror"
                                    value="{{ old('nama_kategori_barang') }}">
                                @error('nama_kategori_barang')
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
