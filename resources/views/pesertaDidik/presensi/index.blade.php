@extends('layouts.app2')

@section('title')
    <title>Presensi Kehadiran</title>
@endsection

@section('content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-clock icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Presensi Kehadiran {{ $bulan->nama_bulan }}
                    <div class="page-title-subheading">
                        Rekap presensi kehadiran siswa
                    </div>
                </div>
            </div>  
        </div> 
    </div>

    <div class="row">
        <div class="col-md-6 col-xl-3">
            <div class="card mb-3 widget-content"  style="border-bottom: 4px solid var(--green);"">
                <div class="widget-content-wrapper">
                    <div class="widget-content-left">
                        <div class="widget-heading">Hadir</div>
                        <div class="widget-subheading">Siswa yang Sakit</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers"><span>{{$presensi->where('status', 'hadir')->count()}}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card mb-3 widget-content"  style="border-bottom: 4px solid var(--yellow);"">
                <div class="widget-content-wrapper">
                    <div class="widget-content-left">
                        <div class="widget-heading">Sakit</div>
                        <div class="widget-subheading">Siswa yang Sakit</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers"><span>{{$presensi->where('status', 'sakit')->count()}}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card mb-3 widget-content" style="border-bottom: 4px solid var(--orange);">
                <div class="widget-content-wrapper">
                    <div class="widget-content-left">
                        <div class="widget-heading">Izin</div>
                        <div class="widget-subheading">Total Clients Profit</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers"><span>{{$presensi->where('status', 'izin')->count()}}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card mb-3 widget-content" style="border-bottom: 4px solid var(--red);"">
                <div class="widget-content-wrapper">
                    <div class="widget-content-left">
                        <div class="widget-heading">Alpha</div>
                        <div class="widget-subheading">People Interested</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers"><span>{{$presensi->where('status', 'alpha')->count()}}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-card card">
        <div class="card-header">
            <button class="btn btn-primary dropdown" type="button" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="metismenu-icon pe-7s-refresh-2"></i> PERIODE
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                @foreach($bulan_spp as $bulan)
                <li>
                    <a href="{{ route('presensi.show', $bulan->id) }}" class="dropdown-item">
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
                        <tr class="text-center">
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Terlambat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($tanggal_tercatat->isEmpty()) 
                        <tr>
                            <td colspan="4" class="text-center">Belum ada data </td>
                        </tr>
                        @else
                        @php
                            $no = 1;
                        @endphp
                            @foreach ($tanggal_tercatat as $tanggal)
                            <tr>
                                <td>{{$no++}}</td>
                                <td>{{ \Carbon\Carbon::parse($tanggal)->format('d/m') }}</td>
                                @php
                                    $presensiData = $presensi->where('anggota_kelas_id', $anggotaKelas->id)->where('tanggal', $tanggal)->first();
                                @endphp
                                    @if ($presensiData)
                                        @if ($presensiData->status == 'hadir')
                                        <td class="text-center"> &#10003; </td>
                                        <td class="text-center">
                                            @if ($presensiData->terlambat)
                                                <span class="text-danger">{{ $presensiData->menit_terlambat }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        @elseif ($presensiData->status == 'sakit')
                                        <td class="text-center" style="background-color: yellow;"> S </td>
                                        <td class="text-center" style="background-color: yellow;"> - </td>
                                        @elseif ($presensiData->status == 'izin')
                                        <td class="text-center" style="background-color: green; color:white"> I </td>
                                        <td class="text-center" style="background-color: green; color:white"> - </td>
                                        @elseif ($presensiData->status == 'alpha')
                                        <td class="text-center" style="background-color: red; color:white"> A </td>
                                        <td class="text-center" style="background-color: red; color:white"> - </td>
                                        @else
                                            {{ $presensiData->status }} 
                                        @endif
                                    @else
                                        <td class="text-center" style="background-color: black;"> -</td>
                                        <td class="text-center" style="background-color: black;"> -</td>
                                    @endif
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> 
@endsection
