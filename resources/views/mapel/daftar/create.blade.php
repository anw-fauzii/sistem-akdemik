@extends('layouts.app2')

@section('title')
    <title>Tambah Mata Pelajaran</title>
@endsection

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-science icon-gradient bg-mean-fruit"></i>
                    </div>
                    <div>Tambah Mata Pelajaran
                        <div class="page-title-subheading">
                            Membuat data mata pelajaran baru
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
                <form method="post" action="{{ route('daftar-mata-pelajaran.store') }}" id="createForm">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="tahun_ajaran" class="">Tahun Ajaran</label>
                                <input name="tahun_ajaran" id="tahun_ajaran" type="text"
                                    class="form-control @error('tahun_ajaran') is-invalid @enderror"
                                    value="{{ $tahunAjaran->nama_tahun_ajaran }}-{{ $tahunAjaran->semester }}" readonly>
                                @error('tahun_ajaran')
                                    <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="kategori_mata_pelajaran_id" class="">Kategori Mata Pelajaran</label>
                                <select name="kategori_mata_pelajaran_id" id="kategori_mata_pelajaran_id"
                                    class="multiselect-dropdown form-control @error('kategori_mata_pelajaran_id') is-invalid @enderror">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($kategori as $kat)
                                        <option value="{{ $kat->id }}"
                                            {{ old('kategori_mata_pelajaran_id') == $kat->id ? 'selected' : '' }}>
                                            {{ $kat->kategori }} {{-- Sesuaikan field nama kategori --}}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kategori_mata_pelajaran_id')
                                    <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label for="nama_mapel" class="">Nama Mata Pelajaran</label>
                                <input name="nama_mapel" id="nama_mapel" placeholder="Contoh: Matematika Lanjut"
                                    type="text" class="form-control @error('nama_mapel') is-invalid @enderror"
                                    value="{{ old('nama_mapel') }}">
                                @error('nama_mapel')
                                    <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label for="ringkasan_mapel" class="">Ringkasan (Opsional)</label>
                                <textarea name="ringkasan_mapel" id="ringkasan_mapel"
                                    class="form-control @error('ringkasan_mapel') is-invalid @enderror" rows="3">{{ old('ringkasan_mapel') }}</textarea>
                                @error('ringkasan_mapel')
                                    <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-primary" id="submitBtn">Simpan</button>
                        <a href="{{ route('daftar-mata-pelajaran.index') }}" class="btn btn-secondary">Batal</a>
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
