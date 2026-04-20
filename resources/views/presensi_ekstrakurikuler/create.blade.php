@extends('layouts.app2')

@section('title')
    <title>Isi Presensi Ekstrakurikuler</title>
@endsection

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-note2 icon-gradient bg-mean-fruit"></i>
                    </div>
                    <div>
                        Isi Presensi {{ $ekstrakurikuler->nama_ekstrakurikuler }}
                        <div class="page-title-subheading">
                            Formulir pengisian daftar hadir siswa hari ini.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger shadow-sm">
                <h6 class="font-weight-bold mb-2"><i class="pe-7s-attention mr-1"></i> Gagal Menyimpan!</h6>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="main-card card shadow-sm">
            <div class="card-header">
                Form Presensi Siswa
            </div>
            <div class="card-body">
                <form action="{{ route('presensi-ekstrakurikuler.store') }}" method="POST" id="createForm">
                    @csrf

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label for="tanggal" class="form-label font-weight-bold">Tanggal Pertemuan</label>
                            <input type="date" class="form-control @error('tanggal') is-invalid @enderror" name="tanggal"
                                value="{{ old('tanggal', \Carbon\Carbon::now()->format('Y-m-d')) }}" required>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle">
                            <thead class="bg-light text-center">
                                <tr>
                                    <th width="5%">No</th>
                                    <th class="text-left">Nama Siswa</th>
                                    <th width="40%">Status Kehadiran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($siswaList as $siswa)
                                    <tr>
                                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                        <td class="align-middle">
                                            <span
                                                class="font-weight-bold">{{ $siswa->anggotaKelas->siswa->nama_lengkap ?? '-' }}</span><br>
                                            <small class="text-muted">Kelas:
                                                {{ $siswa->anggotaKelas->kelas->nama_kelas ?? '-' }}</small>
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                                <label class="btn btn-outline-success btn-sm active w-25">
                                                    <input type="radio" name="presensi[{{ $siswa->id }}]"
                                                        value="hadir" checked> Hadir
                                                </label>
                                                <label class="btn btn-outline-warning btn-sm w-25">
                                                    <input type="radio" name="presensi[{{ $siswa->id }}]"
                                                        value="sakit"> Sakit
                                                </label>
                                                <label class="btn btn-outline-info btn-sm w-25">
                                                    <input type="radio" name="presensi[{{ $siswa->id }}]"
                                                        value="izin"> Izin
                                                </label>
                                                <label class="btn btn-outline-danger btn-sm w-25">
                                                    <input type="radio" name="presensi[{{ $siswa->id }}]"
                                                        value="alpha"> Alpha
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">
                                            Belum ada siswa di ekstrakurikuler ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <hr class="mt-4">
                    <div class="form-group mb-0 text-right">
                        <a href="{{ route('presensi-ekstrakurikuler.index') }}" class="btn btn-secondary mr-2">Batal</a>
                        @if ($siswaList->isNotEmpty())
                            <button type="submit" class="btn btn-primary font-weight-bold" id="submitBtn">
                                <i class="pe-7s-diskette mr-1"></i> Simpan Presensi
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById("createForm");
            const submitBtn = document.getElementById("submitBtn");

            if (form && submitBtn) {
                form.addEventListener("submit", function() {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML =
                        `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...`;
                });
                window.addEventListener('pageshow', function() {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = `<i class="pe-7s-diskette mr-1"></i> Simpan Presensi`;
                });
            }
        });
    </script>
@endsection
