@extends('layouts.app2')

@section('title')
    <title>Kelas</title>
@endsection

@section('content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-users icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Anggota Kelas {{$kelas->nama_kelas}}
                    <div class="page-title-subheading">
                        Daftar siswa kelas
                    </div>
                </div>
            </div>  
        </div> 
    </div>

    <div class="main-card card">
        <div class="card-header">
            Data Siswa
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="mb-0 table table-hover table-striped" id="myTable2">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIS/NISN</th>
                            <th>Nama Siswa</th>
                            <th>Ekstrakurikuler</th>
                            <th>T2Q</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1;
                        @endphp
                        @forelse ($anggotaKelas as $item)
                            <tr>
                                <td>{{$no++}}</td>
                                <td>{{$item->siswa->nis}}/{{$item->siswa->nisn ?? '-'}}</td>
                                <td>{{$item->siswa->nama_lengkap}}</td>
                                <td>
                                    @if ($item->siswa->ekstrakurikuler_id == NULL)
                                        <span class="badge badge-warning">Belum masuk</span>
                                    @else
                                        {{$item->siswa->ekstrakurikuler->nama_ekstrakurikuler}}
                                    @endif
                                </td>
                                <td>
                                    @if ($item->siswa->guru_nipy == NULL)
                                        <span class="badge badge-warning">Belum masuk</span>
                                    @else
                                        {{$item->siswa->guru->nama_lengkap}}, {{$item->siswa->guru->gelar}}.
                                    @endif
                                </td>
                                <td class="d-flex">
                                    <a href="{{ route('siswa.show', $item->siswa->nis) }}" class="btn btn-sm btn-success mx-1"><i class="pe-7s-info" style="font-size: 0.85rem;"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <th colspan="4" class="text-center"> Belum Ada Data</th>
                            </tr>
                        @endforelse 
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
