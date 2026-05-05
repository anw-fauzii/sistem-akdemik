@extends('layouts.app2')

@section('title')
    <title>Catat Kedisiplinan Siswa</title>
@endsection

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-target icon-gradient bg-mean-fruit"></i>
                    </div>
                    <div>Catat Kedisiplinan Siswa
                        <div class="page-title-subheading">
                            Mencatat riwayat pelanggaran atau prestasi siswa.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card card">
            <div class="card-header">
                Tambah Catatan
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('kedisiplinan-siswa.store') }}" id="createForm">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label for="anggota_kelas_id" class="">Siswa</label>
                                <select name="anggota_kelas_id" id="anggota_kelas_id"
                                    class="multiselect-dropdown form-control @error('anggota_kelas_id') is-invalid @enderror">
                                    <option value="">-- Cari / Pilih Siswa --</option>
                                    @foreach ($siswaList as $anggota)
                                        <option value="{{ $anggota->id }}"
                                            {{ old('anggota_kelas_id') == $anggota->id ? 'selected' : '' }}>
                                            {{ $anggota->siswa->nama_lengkap ?? 'Tanpa Nama' }}
                                            @if ($anggota->kelas)
                                                - {{ $anggota->kelas->nama_kelas }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('anggota_kelas_id')
                                    <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label for="kedisiplinan_poin_id" class="">Aturan (Pelanggaran / Prestasi)</label>
                                <select name="kedisiplinan_poin_id" id="kedisiplinan_poin_id"
                                    class="multiselect-dropdown form-control @error('kedisiplinan_poin_id') is-invalid @enderror">
                                    <option value="">-- Pilih Aturan --</option>
                                    @foreach ($aturanList as $aturan)
                                        <option value="{{ $aturan->id }}"
                                            {{ old('kedisiplinan_poin_id') == $aturan->id ? 'selected' : '' }}>
                                            [{{ ucfirst($aturan->kategori) }}] {{ $aturan->nama_aturan }}
                                            ({{ $aturan->poin }} Poin)
                                        </option>
                                    @endforeach
                                </select>
                                @error('kedisiplinan_poin_id')
                                    <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label for="tanggal_kejadian" class="">Tanggal Kejadian</label>
                                <input name="tanggal_kejadian" id="tanggal_kejadian" type="date"
                                    class="form-control @error('tanggal_kejadian') is-invalid @enderror"
                                    value="{{ old('tanggal_kejadian', date('Y-m-d')) }}">
                                @error('tanggal_kejadian')
                                    <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label for="keterangan" class="">Keterangan (Opsional)</label>
                                <textarea name="keterangan" id="keterangan" placeholder="Tambahkan detail kejadian jika diperlukan..."
                                    class="form-control @error('keterangan') is-invalid @enderror" rows="3">{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                    </div>
                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-primary" id="submitBtn">Simpan Catatan</button>
                        <a href="{{ route('kedisiplinan-siswa.index') }}" class="btn btn-secondary">Kembali</a>
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
