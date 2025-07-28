@extends('layouts.app2')

@section('title')
    <title>Laporan Presensi</title>
@endsection

@section('content')

<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-graph2 icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Presensi {{$bulan->nama_bulan}}
                    <div class="page-title-subheading">
                        Laporan bulanan untuk operator sekolah.
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
                @foreach($bulan_spp as $data)
                <li>
                    <a href="{{ route('laporan.presensi.bulanan.show', $data->id) }}" class="dropdown-item">
                        {{ $data->nama_bulan }}
                    </a>
                </li>
                @endforeach
            </ul>
            <button class="btn btn-info dropdown ml-2" type="button" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="metismenu-icon pe-7s-print"></i> DOWNLOAD
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                <li>
                    <a href="{{route('export.laporan.bulanan.pdf', $bulan->id)}}" target="_blank" class="dropdown-item">PDF</a>
                </li>
                <li>
                    <a href="{{route('export.laporan.bulanan.excel', $bulan->id)}}" target="_blank" class="dropdown-item">Excel</a>
                </li>
            </ul>
            
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="mb-0 table table-hover table-striped" id="myTable2">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kelas</th>
                            <th>S</th>
                            <th>I</th>
                            <th>A</th>
                            <th>Tidak Hadir</th>
                            <th>Hadir</th>
                            <th>Terlambat</th>
                            <th>Tepat Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1;
                        @endphp
                       @foreach ($statistikPerKelas as $data)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $data['kelas']->nama_kelas }}</td>
                            <td>{{ $data['presensi']->where('status', 'sakit')->count() }}</td>
                            <td>{{ $data['presensi']->where('status', 'izin')->count() }}</td>
                            <td>{{ $data['presensi']->where('status', 'alpha')->count() }}</td>
                            <td>{{ $data['persentaseTidakHadir'] }}%</td>
                            <td>{{ $data['persentaseHadir'] }}%</td>
                            <td>{{ $data['persentaseTerlambat'] }}%</td>
                            <td>{{ $data['persentaseTepatWaktu'] }}%</td>
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
