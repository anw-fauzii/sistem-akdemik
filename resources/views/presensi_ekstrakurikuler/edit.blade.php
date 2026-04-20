@extends('layouts.app2')

@section('title')
    <title>Edit Presensi Ekstrakurikuler</title>
@endsection

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-pen icon-gradient bg-mean-fruit"></i>
                    </div>
                    <div>
                        Edit Presensi {{ $ekstrakurikuler->nama_ekstrakurikuler }}
                        <div class="page-title-subheading">
                            Memperbarui data kehadiran untuk tanggal
                            <strong>{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger shadow-sm">
                <h6 class="font-weight-bold mb-2"><i class="pe-7s-attention mr-1"></i> Gagal Memperbarui!</h6>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="main-card card shadow-sm">
            <div class="card-header bg-light">
                Edit Data Presensi
            </div>
            <div class="card-body">
                <form action="{{ route('presensi-ekstrakurikuler.update-harian', $tanggal) }}" method="POST"
                    id="editForm">
                    @csrf
                    @method('PUT')

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label font-weight-bold">Tanggal Pertemuan</label>
                            <input type="text" class="form-control bg-light"
                                value="{{ \Carbon\Carbon::parse($tanggal)->format('d / m / Y') }}" readonly>

                            <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-striped align-middle">
                            <thead class="bg-light text-center">
                                <tr>
                                    <th width="5%">No</th>
                                    <th class="text-left">Nama Siswa</th>
                                    <th width="40%">Status Kehadiran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($siswaList as $siswa)
                                    @php
                                        // Tarik status siswa saat ini dari database, jika kosong set 'hadir'
                                        $statusAktif = old(
                                            'presensi.' . $siswa->id,
                                            $presensiHariIni[$siswa->id] ?? 'hadir',
                                        );
                                    @endphp
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
                                                <label
                                                    class="btn btn-outline-success btn-sm w-25 {{ $statusAktif == 'hadir' ? 'active' : '' }}">
                                                    <input type="radio" name="presensi[{{ $siswa->id }}]"
                                                        value="hadir" {{ $statusAktif == 'hadir' ? 'checked' : '' }}>
                                                    Hadir
                                                </label>
                                                <label
                                                    class="btn btn-outline-warning btn-sm w-25 {{ $statusAktif == 'sakit' ? 'active' : '' }}">
                                                    <input type="radio" name="presensi[{{ $siswa->id }}]"
                                                        value="sakit" {{ $statusAktif == 'sakit' ? 'checked' : '' }}>
                                                    Sakit
                                                </label>
                                                <label
                                                    class="btn btn-outline-info btn-sm w-25 {{ $statusAktif == 'izin' ? 'active' : '' }}">
                                                    <input type="radio" name="presensi[{{ $siswa->id }}]"
                                                        value="izin" {{ $statusAktif == 'izin' ? 'checked' : '' }}> Izin
                                                </label>
                                                <label
                                                    class="btn btn-outline-danger btn-sm w-25 {{ $statusAktif == 'alpha' ? 'active' : '' }}">
                                                    <input type="radio" name="presensi[{{ $siswa->id }}]"
                                                        value="alpha" {{ $statusAktif == 'alpha' ? 'checked' : '' }}>
                                                    Alpha
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <hr class="mt-4">
                    <div class="form-group mb-0 text-right">
                        <button type="button" class="btn btn-secondary mr-2" onclick="history.back()">Batal</button>
                        <button type="submit" class="btn btn-primary font-weight-bold" id="submitBtn">
                            <i class="pe-7s-diskette mr-1"></i> Perbarui Presensi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById("editForm");
            const submitBtn = document.getElementById("submitBtn");

            if (form && submitBtn) {
                form.addEventListener("submit", function() {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML =
                        `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memperbarui...`;
                });
                window.addEventListener('pageshow', function() {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = `<i class="pe-7s-diskette mr-1"></i> Perbarui Presensi`;
                });
            }
        });
    </script>
@endsection
