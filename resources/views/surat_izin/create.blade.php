@extends('layouts.app2')

@section('title')
    <title>Surat Izin</title>
@endsection

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-mail-open-file icon-gradient bg-mean-fruit"></i>
                    </div>
                    <div>Ajukan Surat Izin
                        <div class="page-title-subheading">
                            Input izin siswa jika tidak dapat mengikuti kegiatan sekolah.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card card">
            <div class="card-header">
                Form Pengajuan Izin
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('surat-izin.store') }}" enctype="multipart/form-data" id="createForm">
                    @csrf

                    <div class="form-row">

                        {{-- Nama Siswa (readonly biar UX bagus) --}}
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label>Nama Siswa</label>
                                <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                            </div>
                        </div>

                        {{-- Tanggal --}}
                        <div class="col-md-6">
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

                        {{-- Jenis --}}
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label>Jenis Izin</label>
                                <select name="jenis" class="form-control @error('jenis') is-invalid @enderror">
                                    <option value="" disabled selected>-- Pilih Jenis --</option>
                                    <option value="sakit" {{ old('jenis') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                    <option value="izin" {{ old('jenis') == 'izin' ? 'selected' : '' }}>Izin</option>
                                    <option value="lainnya" {{ old('jenis') == 'lainnya' ? 'selected' : '' }}>Lainnya
                                    </option>
                                </select>
                                @error('jenis')
                                    <div class="invalid-feedback" style="font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        {{-- Keterangan --}}
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label>Keterangan</label>
                                <input type="text" name="keterangan"
                                    class="form-control @error('keterangan') is-invalid @enderror"
                                    value="{{ old('keterangan') }}" placeholder="Contoh: Demam, acara keluarga, dll">
                                @error('keterangan')
                                    <div class="invalid-feedback" style="font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        {{-- Upload File --}}
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label>Upload Surat / Bukti</label>
                                <input type="file" name="file"
                                    class="form-control @error('file') is-invalid @enderror">
                                <small class="text-muted">
                                    Format: JPG, PNG, PDF (Max 2MB)
                                </small>
                                @error('file')
                                    <div class="invalid-feedback" style="font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            Kirim Izin
                        </button>
                        <a href="{{ route('surat-izin.index') }}" class="btn btn-secondary">
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
                submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Mengirim...`;
            });
        });
    </script>
@endsection
