@extends('layouts.app2')

@section('title')
    <title>Data Kesehatan</title>
@endsection

@section('content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-smile icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Kesehatan <strong>{{$kelas->nama_kelas}}</strong>, Bulan {{$bulanTerbaru->nama_bulan}}
                    <div class="page-title-subheading">
                        Merupakan data kesehatan siswa
                    </div>
                </div>
            </div>  
        </div> 
    </div>
    <div class="main-card card">
        <div class="card-header">
            <a href="{{route('kelas.pgtk.edit.kelas', ['bulan_spp_id' => $bulanTerbaru->id, 'kelas_id' => $kelas->id])}}" class="btn btn-primary">{{ $dataKesehatan->isEmpty() ? 'Tambah Data' : 'Update Data' }} </a>
                &nbsp;<button class="btn btn-warning dropdown" type="button" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="metismenu-icon pe-7s-refresh-2"></i> PERIODE
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                    @foreach($bulan_spp as $bulan)
                    <li>
                        <a href="{{ route('kelas.pgtk.detail.kelas', ['bulan_spp_id' => $bulan->id, 'kelas_id' => $kelas->id]) }}" class="dropdown-item">
                            {{ $bulan->nama_bulan }}
                        </a>
                    </li>
                    @endforeach
                </ul> 
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="mb-0 table table-hover table-striped" id="myTable2">
                    <thead>
                        <tr>
                        <th>NAMA</th>
                        <th>TB</th>
                        <th>BB</th>
                        <th>LILA</th>
                        <th>LIKA</th>
                        <th>LP</th>
                        <th>MATA</th>
                        <th>TELINGA</th>
                        <th>GIGI</th>
                        <th>KES UMUM</th>
                        <th>TENSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($anggotaKelasList as $anggota)
                        @php
                            $kesehatan = $dataKesehatan[$anggota->id] ?? null;
                        @endphp
                        <tr>
                            <td>{{ $anggota->siswa->nama_lengkap ?? '-' }}</td>
                            <td>{{ $kesehatan->tb ?? '0' }}</td>
                            <td>{{ $kesehatan->bb ?? '0' }}</td>
                            <td>{{ $kesehatan->lila ?? '0' }}</td>
                            <td>{{ $kesehatan->lika ?? '0' }}</td>
                            <td>{{ $kesehatan->lp ?? '0' }}</td>
                            <td>{{ $kesehatan->mata ?? '0' }}</td>
                            <td>{{ $kesehatan->telinga ?? '0' }}</td>
                            <td>{{ $kesehatan->gigi ?? '0' }}</td>
                            <td>{{ $kesehatan->hasil ?? '0' }}</td>
                            <td>{{ $kesehatan->tensi ?? '0' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> 
@endsection
