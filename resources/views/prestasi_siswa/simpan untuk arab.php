@extends('layouts.app2')

@section('title')
    <title>Prestasi Siswa</title>
@endsection

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-star icon-gradient bg-mean-fruit"></i>
                    </div>
                    <div>Tambah Prestasi
                        <div class="page-title-subheading">
                            Input data prestasi siswa baik akademik maupun non-akademik.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style>
            /* 1. Deklarasi font Trado */
            @font-face {
                font-family: 'Traditional Arabic';
                src: url("{{ asset('fonts/trado.ttf') }}") format("truetype");
            }

            /* Terapkan pada select dan option */
            select.multiselect-dropdown,
            select.multiselect-dropdown option {
                font-family: 'Traditional Arabic', sans-serif !important;
                font-size: 20px;
                /* Saya naikkan sedikit biar lebih enak dibaca di layar */
                direction: rtl;
                /* Arah baca kanan ke kiri */
                padding-right: 10px;
            }

            /* 2. Target tulisan di dalam kotak pilihan yang sudah dipilih (Tag Biru) */
            body .select2-container .select2-selection--multiple .select2-selection__choice,
            body .select2-container .select2-selection--multiple .select2-selection__choice__display {
                font-family: 'Traditional Arabic', sans-serif !important;
                font-size: 22px !important;
                /* Jarak agar tidak mepet dengan tombol silang (x) */
            }

            /* 3. Target tulisan di dalam daftar dropdown (saat kotak diklik) */
            body .select2-container .select2-results__option {
                font-family: 'Traditional Arabic', sans-serif !important;
                font-size: 24px !important;
                text-align: right !important;
                /* Rata kanan khas Arab */
                direction: rtl !important;
            }

            /* 4. Target input pencariannya */
            body .select2-container .select2-search__field {
                font-family: 'Traditional Arabic', sans-serif !important;
                font-size: 18px !important;
                direction: rtl !important;
            }

            /* 5. Target pesan "No results found" (jika ada) */
            body .select2-container .select2-results__message {
                font-family: 'Helvetica', sans-serif !important;
                /* Kembalikan ke latin untuk pesan sistem */
            }
        </style>

        <div class="main-card card">
            <div class="card-header">
                Tambah Data Prestasi
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('prestasi-siswa.store') }}" enctype="multipart/form-data"
                    id="createForm">
                    @csrf

                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label>Siswa</label>
                                <select name="anggota_kelas_id[]" multiple
                                    class="multiselect-dropdown form-control @error('anggota_kelas_id') is-invalid @enderror">

                                    @foreach ($anggotaKelas as $item)
                                        {{-- Logika untuk mengecek apakah ID item ada di dalam array old() --}}
                                        @php
                                            $oldValues = old('anggota_kelas_id', []); // Defaultnya array kosong jika belum ada input
                                        @endphp

                                        <option value="{{ $item->id }}"
                                            {{ in_array($item->id, $oldValues) ? 'selected' : '' }}>
                                            {{-- Teks Arab yang sudah gundul dari database --}}
                                            {{ $item->nama_surah_arab }}
                                        </option>
                                    @endforeach

                                </select>

                                @error('anggota_kelas_id')
                                    <div class="invalid-feedback" style="font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label>Kategori</label>
                                <select name="kategori" class="form-control @error('kategori') is-invalid @enderror">
                                    <option value="" disabled selected>-- Pilih Kategori --</option>
                                    <option value="akademik" {{ old('kategori') == 'akademik' ? 'selected' : '' }}>Akademik
                                    </option>
                                    <option value="non_akademik" {{ old('kategori') == 'non_akademik' ? 'selected' : '' }}>
                                        Non Akademik</option>
                                </select>
                                @error('kategori')
                                    <div class="invalid-feedback" style="font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label>Nama Prestasi</label>
                                <input type="text" name="nama_prestasi"
                                    class="form-control @error('nama_prestasi') is-invalid @enderror"
                                    value="{{ old('nama_prestasi') }}">
                                @error('nama_prestasi')
                                    <div class="invalid-feedback" style="font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label>Tingkat</label>
                                <input type="text" name="tingkat"
                                    class="form-control @error('tingkat') is-invalid @enderror"
                                    value="{{ old('tingkat') }}" placeholder="Kecamatan / Kabupaten">
                                @error('tingkat')
                                    <div class="invalid-feedback" style="font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label>Peringkat</label>
                                <input type="text" name="peringkat"
                                    class="form-control @error('peringkat') is-invalid @enderror"
                                    value="{{ old('peringkat') }}" placeholder="Juara 1">
                                @error('peringkat')
                                    <div class="invalid-feedback" style="font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label>Tanggal</label>
                                <input type="date" name="tanggal"
                                    class="form-control @error('tanggal') is-invalid @enderror"
                                    value="{{ old('tanggal', date('Y-m-d')) }}">
                                @error('tanggal')
                                    <div class="invalid-feedback" style="font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="position-relative form-group">
                                <label>Penyelenggara</label>
                                <input type="text" name="penyelenggara" class="form-control"
                                    value="{{ old('penyelenggara') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label>Upload Sertifikat</label>
                                <input type="file" name="file_sertifikat" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label>Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            Simpan
                        </button>
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
                submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Menyimpan...`;
            });
        });
    </script>
@endsection
