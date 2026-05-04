@extends('layouts.app2')

@section('title')
    <title>Detail Surat Izin</title>
@endsection

@section('content')
    <div class="app-main__inner">

        <div class="app-page-title">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-mail-open-file icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Detail Surat Izin
                    <div class="page-title-subheading">
                        Informasi lengkap pengajuan izin siswa.
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card card">
            <div class="card-body">

                <table class="table table-bordered">
                    <tr>
                        <th>Nama Siswa</th>
                        <td>{{ $suratIzin->anggotaKelas->siswa->nama_lengkap ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Kelas</th>
                        <td>{{ $suratIzin->anggotaKelas->kelas->nama_kelas ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Tanggal</th>
                        <td>{{ $suratIzin->tanggal->format('d M Y') }}</td>
                    </tr>

                    <tr>
                        <th>Jenis</th>
                        <td>
                            @if ($suratIzin->jenis == 'sakit')
                                <span class="badge badge-danger">Sakit</span>
                            @elseif($suratIzin->jenis == 'izin')
                                <span class="badge badge-warning">Izin</span>
                            @else
                                <span class="badge badge-info">Lainnya</span>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Keterangan</th>
                        <td>{{ $suratIzin->keterangan ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>File</th>
                        <td>
                            @if ($suratIzin->file)
                                <a href="{{ asset('storage/' . $suratIzin->file) }}" target="_blank"
                                    class="btn btn-info btn-sm">
                                    Lihat File
                                </a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>

                </table>

                <a href="{{ route('surat-izin.index') }}" class="btn btn-secondary">
                    Kembali
                </a>

            </div>
        </div>

    </div>
@endsection
