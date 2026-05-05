@extends('layouts.app2')

@section('title')
    <title>Aturan Kedisiplinan</title>
@endsection

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-rocket icon-gradient bg-mean-fruit"></i>
                    </div>
                    <div>Tambah Aturan Kedisiplinan
                        <div class="page-title-subheading">
                            Membuat aturan kedisiplinan yang baru
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
                <form method="post" action="{{ route('kedisiplinan-poin.store') }}" id="createForm">
                    @csrf
                    <div class="form-row">
                        <!-- Input Nama Aturan -->
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label for="nama_aturan" class="">Nama Aturan</label>
                                <input name="nama_aturan" id="nama_aturan" placeholder="Contoh: Terlambat masuk sekolah"
                                    type="text" class="form-control @error('nama_aturan') is-invalid @enderror"
                                    value="{{ old('nama_aturan') }}">
                                @error('nama_aturan')
                                    <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Select Kategori -->
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="kategori" class="">Kategori</label>
                                <select name="kategori" id="kategori"
                                    class="form-control @error('kategori') is-invalid @enderror">
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="pelanggaran" {{ old('kategori') == 'pelanggaran' ? 'selected' : '' }}>
                                        Pelanggaran</option>
                                    <option value="prestasi" {{ old('kategori') == 'prestasi' ? 'selected' : '' }}>Prestasi
                                    </option>
                                </select>
                                @error('kategori')
                                    <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Select Tingkat -->
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="tingkat" class="">Tingkat</label>
                                <select name="tingkat" id="tingkat"
                                    class="form-control @error('tingkat') is-invalid @enderror">
                                    <option value="">-- Pilih Tingkat --</option>
                                    <option value="ringan" {{ old('tingkat') == 'ringan' ? 'selected' : '' }}>Ringan
                                    </option>
                                    <option value="sedang" {{ old('tingkat') == 'sedang' ? 'selected' : '' }}>Sedang
                                    </option>
                                    <option value="berat" {{ old('tingkat') == 'berat' ? 'selected' : '' }}>Berat</option>
                                </select>
                                @error('tingkat')
                                    <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Input Poin -->
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label for="poin" class="">Poin</label>
                                <input name="poin" id="poin" placeholder="Contoh: 10" type="number"
                                    class="form-control @error('poin') is-invalid @enderror" value="{{ old('poin') }}">
                                @error('poin')
                                    <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-primary" id="submitBtn">Simpan</button>
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
