@extends('layouts.app2')

@section('title')
    <title>Tambah Target Capaian T2Q</title>
@endsection

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title mb-4">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon shadow-sm bg-white">
                        <i class="pe-7s-flag text-success"></i>
                    </div>
                    <div>Tambah Target Capaian T2Q
                        <div class="page-title-subheading">
                            Menetapkan standar target jilid dan hafalan untuk tingkat kelas tertentu.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card card border-0 shadow-sm">
            <div class="card-header bg-white pt-4 pb-3 border-bottom-0">
                Form Input Target Capaian
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('target-capaian-tahsin.store') }}" id="createForm">
                    @csrf
                    <div class="form-row">
                        <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAjaran->id }}">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label for="tahun_ajaran_id" class="font-weight-bold">Tahun Ajaran <span
                                        class="text-danger">*</span></label>
                                <input type="text"
                                    value="{{ $tahunAjaran->nama_tahun_ajaran }} - {{ $tahunAjaran->semester }}"
                                    class="form-control" readonly>
                                @error('tahun_ajaran_id')
                                    <div class="invalid-feedback" style="font-style: italic; font-size: 0.75rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label for="tingkat" class="font-weight-bold">Tingkat Kelas <span
                                        class="text-danger">*</span></label>
                                <input name="tingkat" id="tingkat" placeholder="Misal: 1, 2, 3, PG, A, B" type="text"
                                    class="form-control @error('tingkat') is-invalid @enderror" value="{{ old('tingkat') }}"
                                    required>
                                @error('tingkat')
                                    <div class="invalid-feedback" style="font-style: italic; font-size: 0.75rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label for="daftar_jilid_id" class="font-weight-bold">Target Jilid <span
                                        class="text-danger">*</span></label>
                                <select name="daftar_jilid_id" id="daftar_jilid_id"
                                    class="multiselect-dropdown form-control @error('daftar_jilid_id') is-invalid @enderror"
                                    required>
                                    <option value="" selected disabled>-- Pilih Target Jilid --</option>
                                    @foreach ($daftarJilid as $jilid)
                                        <option value="{{ $jilid->id }}"
                                            {{ old('daftar_jilid_id') == $jilid->id ? 'selected' : '' }}>
                                            {{ $jilid->jilid_latin }} ({{ $jilid->jilid_arab }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('daftar_jilid_id')
                                    <div class="invalid-feedback" style="font-style: italic; font-size: 0.75rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label for="surah_alquran_id" class="font-weight-bold">Target Hafalan <span
                                        class="text-danger">*</span></label>
                                <select name="surah_alquran_id" id="surah_alquran_id"
                                    class="multiselect-dropdown form-control @error('surah_alquran_id') is-invalid @enderror"
                                    required>
                                    <option value="" selected disabled>-- Pilih Target Hafalan --</option>
                                    @foreach ($surahAlquran as $jilid)
                                        <option value="{{ $jilid->id }}"
                                            {{ old('surah_alquran_id') == $jilid->id ? 'selected' : '' }}>
                                            {{ $jilid->nama_surah }} ({{ $jilid->nama_surah_arab }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('surah_alquran_id')
                                    <div class="invalid-feedback" style="font-style: italic; font-size: 0.75rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <div class="form-group mt-4 mb-0">
                        <a href="{{ route('target-capaian-tahsin.index') }}" class="btn btn-secondary mr-2">Batal</a>
                        <button type="submit" class="btn btn-primary shadow-sm" id="submitBtn">
                            <i class="pe-7s-diskette mr-1"></i> Simpan Target
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
                submitBtn.innerHTML =
                    `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...`;
            });
        });
    </script>
@endsection
