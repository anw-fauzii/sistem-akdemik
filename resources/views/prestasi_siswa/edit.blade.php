@extends('layouts.app2')

@section('title')
    <title>Edit Prestasi Siswa</title>
@endsection

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-star icon-gradient bg-mean-fruit"></i>
                    </div>
                    <div>Edit Prestasi
                        <div class="page-title-subheading">
                            Mengubah data prestasi siswa baik akademik maupun non-akademik.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card card">
            <div class="card-header">
                Edit Data Prestasi
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('prestasi-siswa.update', $prestasi->id) }}"
                    enctype="multipart/form-data" id="createForm">
                    @csrf
                    @method('PUT')

                    <div class="form-row">

                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label>Siswa</label>
                                <select name="anggota_kelas_id[]" multiple
                                    class="multiselect-dropdown form-control @error('anggota_kelas_id') is-invalid @enderror">

                                    @foreach ($anggotaKelas as $item)
                                        <option value="{{ $item->id }}"
                                            {{ collect(old('anggota_kelas_id', $prestasi->anggotaKelas->pluck('id')->toArray()))->contains($item->id)
                                                ? 'selected'
                                                : '' }}>
                                            {{ $item->siswa->nama_lengkap }} ({{ $item->kelas->nama_kelas ?? '-' }})
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

                        {{-- Kategori --}}
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label>Kategori</label>
                                <select name="kategori" class="form-control @error('kategori') is-invalid @enderror">
                                    <option value="" disabled>-- Pilih Kategori --</option>
                                    <option value="akademik"
                                        {{ old('kategori', $prestasi->kategori) == 'akademik' ? 'selected' : '' }}>Akademik
                                    </option>
                                    <option value="non_akademik"
                                        {{ old('kategori', $prestasi->kategori) == 'non_akademik' ? 'selected' : '' }}>Non
                                        Akademik</option>
                                </select>
                                @error('kategori')
                                    <div class="invalid-feedback" style="font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        {{-- Nama Prestasi --}}
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label>Nama Prestasi</label>
                                <input type="text" name="nama_prestasi"
                                    class="form-control @error('nama_prestasi') is-invalid @enderror"
                                    value="{{ old('nama_prestasi', $prestasi->nama_prestasi) }}">
                                @error('nama_prestasi')
                                    <div class="invalid-feedback" style="font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        {{-- Tingkat --}}
                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label>Tingkat</label>
                                <input type="text" name="tingkat"
                                    class="form-control @error('tingkat') is-invalid @enderror"
                                    value="{{ old('tingkat', $prestasi->tingkat) }}">
                                @error('tingkat')
                                    <div class="invalid-feedback" style="font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        {{-- Peringkat --}}
                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label>Peringkat</label>
                                <input type="text" name="peringkat"
                                    class="form-control @error('peringkat') is-invalid @enderror"
                                    value="{{ old('peringkat', $prestasi->peringkat) }}">
                                @error('peringkat')
                                    <div class="invalid-feedback" style="font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        {{-- Tanggal --}}
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label>Tanggal</label>
                                <input type="date" name="tanggal"
                                    class="form-control @error('tanggal') is-invalid @enderror"
                                    value="{{ old('tanggal', $prestasi->tanggal->format('Y-m-d')) }}">
                                @error('tanggal')
                                    <div class="invalid-feedback" style="font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        {{-- Penyelenggara --}}
                        <div class="col-md-8">
                            <div class="position-relative form-group">
                                <label>Penyelenggara</label>
                                <input type="text" name="penyelenggara" class="form-control"
                                    value="{{ old('penyelenggara', $prestasi->penyelenggara) }}">
                            </div>
                        </div>

                        {{-- File --}}
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label>Upload Sertifikat</label>
                                <input type="file" name="file_sertifikat" class="form-control">

                                @if ($prestasi->file_sertifikat)
                                    <small class="text-muted">
                                        File saat ini:
                                        <a href="{{ asset('storage/' . $prestasi->file_sertifikat) }}" target="_blank">
                                            Lihat File
                                        </a>
                                    </small>
                                @endif
                            </div>
                        </div>

                        {{-- Keterangan --}}
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label>Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $prestasi->keterangan) }}</textarea>
                            </div>
                        </div>

                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            Update
                        </button>
                        <a href="{{ route('prestasi-siswa.index') }}" class="btn btn-secondary">
                            Kembali
                        </a>
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
