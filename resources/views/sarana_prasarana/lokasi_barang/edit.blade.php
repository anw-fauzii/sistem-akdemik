@extends('layouts.app2')

@section('title')
    <title>Lokasi Barang</title>
@endsection

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-box2 icon-gradient bg-mean-fruit"></i>
                    </div>
                        <div>Edit Lokasi Barang
                        <div class="page-title-subheading">
                            Mengedit Lokasi Barang yang ada
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card card">
            <div class="card-header">
                Edit Data
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('lokasi-barang.update', $lokasi->id) }}" id="createForm">
                    @csrf
                    @method('PUT')
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label for="id" class="">Kode Lokasi</label>
                                <input name="id" id="id" placeholder="Contoh: YYS-KTR-01" type="text"
                                    class="form-control @error('id') is-invalid @enderror"
                                    value="{{ old('id', $lokasi->id) }}">
                                @error('id')
                                    <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                            <div class="position-relative form-group">
                                <label for="nama_lokasi_barang" class="">Nama Lokasi</label>
                                <input name="nama_lokasi_barang" id="nama_lokasi_barang" placeholder="nama_lokasi_barang" type="text"
                                    class="form-control @error('nama_lokasi_barang') is-invalid @enderror"
                                    value="{{ old('nama_lokasi_barang', $lokasi->nama_lokasi_barang) }}">
                                @error('nama_lokasi_barang')
                                    <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                            <div class="position-relative form-group">
                                <label for="unit" class="">Nama Kategori</label>
                                <select name="unit" id="unit" class="form-control @error('unit') is-invalid @enderror">
                                    <option value="" disabled selected>---- Pilih Unit ----</option>
                                    <option value="Yayasan Prima Insani" {{ old('unit') == 'Yayasan Prima Insani' || $lokasi->unit == 'Yayasan Prima Insani' ? 'selected' : '' }}>Yayasan Prima Insani</option>
                                    <option value="SD GIS Prima Insani" {{ old('unit') == 'SD GIS Prima Insani' || $lokasi->unit == 'SD GIS Prima Insani' ? 'selected' : '' }}>SD GIS Prima Insani</option>
                                    <option value="PG TK Islam Plus Prima Insani" {{ old('unit') == 'PG TK Islam Plus Prima Insani' || $lokasi->unit == 'PG TK Islam Plus Prima Insani' ? 'selected' : '' }}>PG TK Islam Plus Prima Insani</option>
                                </select>
                                @error('unit')
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
