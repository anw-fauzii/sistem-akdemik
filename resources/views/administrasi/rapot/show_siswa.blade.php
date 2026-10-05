@extends('layouts.app2')

@section('title')
    <title>Siswa Kelas {{ $rombel->nama_rombongan_belajar }}</title>
@endsection

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-users icon-gradient bg-mean-fruit"></i>
                    </div>
                    <div>Daftar Siswa Anda - Kelas {{ $rombel->nama_rombongan_belajar }}
                        <div class="page-title-subheading">
                            Arsip Tahun Ajaran: <strong>{{ $rombel->tahunAjaran->nama_tahun_ajaran }}</strong>
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <a href="{{ route('administrasi-rapot.index') }}" class="btn-shadow btn btn-secondary font-weight-bold">
                        <i class="pe-7s-angle-left mr-1" style="font-size: 1rem; vertical-align: middle;"></i> Kembali ke
                        Lemari Arsip
                    </a>
                </div>
            </div>
        </div>

        <div class="main-card mb-3 card">
            <div class="card-header font-weight-bold text-primary">
                <i class="pe-7s-id mr-2" style="font-size:1.2rem;"></i> Data Siswa Kelas Anda
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-left">
                        <thead class="bg-light">
                            <tr>
                                <th width="6%" class="pl-4">No</th>
                                <th width="15%">NISN</th>
                                <th>Nama Lengkap Siswa</th>
                                <th class="text-center" width="30%">Cetak Berkas Rapot</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rombel->siswa as $index => $siswa)
                                <tr>
                                    <td class="pl-4">{{ $index + 1 }}</td>
                                    <td><span class="text-muted">{{ $siswa->nisn ?? '-' }}</span></td>
                                    <td><strong>{{ $siswa->nama_lengkap }}</strong></td>
                                    <td class="text-center">
                                        <a href="#" class="btn btn-sm btn-outline-danger font-weight-bold mr-1">
                                            <i class="pe-7s-print mr-1"></i> Rapot Utama
                                        </a>
                                        <a href="#" class="btn btn-sm btn-outline-success font-weight-bold">
                                            <i class="pe-7s-print mr-1"></i> SP & Mulok
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-5">
                                        <i class="pe-7s-attention d-block mb-2" style="font-size: 2.5rem;"></i>
                                        Tidak ada data siswa dalam rekam medis kelas ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
