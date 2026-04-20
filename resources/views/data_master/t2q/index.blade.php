@extends('layouts.app2')

@section('title')
    <title>Kelompok T2Q</title>
@endsection

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-rocket icon-gradient bg-mean-fruit"></i>
                    </div>
                    <div>Kelompok T2Q Tahun Ajaran {{ $tahun_ajaran->nama ?? '-' }}
                        <div class="page-title-subheading">
                            Daftar Guru Pengampu Kelompok T2Q
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card card">
            <div class="card-header">
                Daftar Kelompok
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="mb-0 table table-hover table-striped w-100" id="myTable2">
                        <thead>
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th width="15%">Jenjang</th>
                                <th>Nama Guru</th>
                                <th class="text-center" width="15%">Aksi / Kelola</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data_guru as $item)
                                <tr>
                                    <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                    <td class="align-middle">{{ $item->unit ?? '-' }}</td>
                                    <td class="align-middle">
                                        {{ $item->nama_lengkap }}{{ $item->gelar ? ', ' . $item->gelar : '' }}
                                    </td>
                                    <td class="text-center align-middle">
                                        <a href="{{ route('anggota-t2q.show', $item->nipy) }}"
                                            class="btn btn-sm btn-success transition-3d-hover" title="Lihat Anggota Siswa">
                                            <i class="pe-7s-users mr-1"></i> {{ $item->anggota_t2q_count ?? 0 }} Siswa
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="pe-7s-info fa-2x mb-2 d-block"></i>
                                        Belum ada data guru pengampu T2Q.
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
