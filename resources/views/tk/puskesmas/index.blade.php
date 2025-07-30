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
                <div>Data Kesehatan {{$bulanTerbaru->nama_bulan}}
                    <div class="page-title-subheading">
                        Merupakan data kesehatan siswa
                    </div>
                </div>
            </div>  
        </div> 
    </div>
    <div class="main-card card">
        <div class="card-header">
            <button class="btn btn-warning dropdown" type="button" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="metismenu-icon pe-7s-refresh-2"></i> PERIODE
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                @foreach($bulan_spp as $bulan)
                <li>
                    <a href="{{ route('kelas.pgtk.show.kelas', $bulan->id) }}" class="dropdown-item">
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
                            <th>Kelas</th>
                            <th>Total Siswa</th>
                            <th>Sudah Diisi</th>
                            <th>Persentase</th>
                            <th>Isi Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($progresKesehatan as $progres)
                            <tr>
                                <td>{{ $progres['kelas']->nama_kelas }}</td>
                                <td>{{ $progres['total'] }}</td>
                                <td>{{ $progres['terisi'] }}</td>
                                <td>
                                    @php
                                        $persen = $progres['persen'];
                                        $warnaTeks = $persen < 15 ? '#000' : '#fff';
                                    @endphp

                                    <div class="progress-bar progress-bar-animated progress-bar-striped"
                                        role="progressbar"
                                        style="width: {{ $persen }}%; color: {{ $warnaTeks }};height:15px;"
                                        aria-valuenow="{{ $persen }}"
                                        aria-valuemin="0"
                                        aria-valuemax="100">
                                        {{ $persen }}%
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('kelas.pgtk.detail.kelas', ['bulan_spp_id' => $bulanTerbaru->id, 'kelas_id' => $progres['kelas']->id]) }}"
                                        class="btn btn-sm btn-info text-center">
                                        Detail
                                    </a>
                                </td>
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
