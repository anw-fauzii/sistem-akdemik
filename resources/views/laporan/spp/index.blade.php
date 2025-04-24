@extends('layouts.app2')

@section('title')
    <title>Laporan Tagihan Tahunan</title>
@endsection

@section('content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-display1 icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Laporan Tagihan Tahunan
                    <div class="page-title-subheading">
                        Merupakan Pembayaran yang dilakukan pembayaran
                    </div>
                </div>
            </div>  
        </div> 
    </div>
    <div class="row">
        <div class="col-md-6 col-lg-6">
            <div class="card mb-3 widget-chart text-left" style="border-bottom: 4px solid var(--info);">
                <div class="icon-wrapper rounded-circle">
                    <div class="icon-wrapper-bg bg-info"></div>
                    <i class="pe-7s-cash text-info"></i>
                </div>
                <div class="widget-chart-content">
                    <div class="widget-subheading">Total Tagihan</div>
                    <div class="h5 font-weight-bold mb-0">Rp </div>
                </div>
            </div>
        </div>
    
        <div class="col-md-6 col-lg-6">
            <div class="card mb-3 widget-chart text-left" style="border-bottom: 4px solid var(--success);">
                <div class="icon-wrapper rounded-circle">
                    <div class="icon-wrapper-bg bg-success"></div>
                    <i class="pe-7s-wallet text-success"></i>
                </div>
                <div class="widget-chart-content">
                    <div class="widget-subheading">Total Dibayar</div>
                    <div class="h5 font-weight-bold mb-0">Rp </div>
                </div>
            </div>
        </div>
    
        <div class="col-md-6 col-lg-6">
            <div class="card mb-3 widget-chart text-left" style="border-bottom: 4px solid var(--danger);">
                <div class="icon-wrapper rounded-circle">
                    <div class="icon-wrapper-bg bg-danger"></div>
                    <i class="pe-7s-close-circle text-danger"></i>
                </div>
                <div class="widget-chart-content">
                    <div class="widget-subheading">Total Sisa Tagihan</div>
                    <div class="h5 font-weight-bold mb-0">Rp </div>
                </div>
            </div>
        </div>
    
        <div class="col-md-6 col-lg-6">
            <div class="card mb-3 widget-chart text-left" style="border-bottom: 4px solid var(--orange);">
                <div class="icon-wrapper rounded-circle">
                    <div class="icon-wrapper-bg bg-warning"></div>
                    <i class="pe-7s-attention text-warning"></i>
                </div>
                <div class="widget-chart-content">
                    <div class="widget-subheading">Siswa Belum Lunas</div>
                    <div class="h5 font-weight-bold mb-0"> siswa</div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-card card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped" id="myTable2">
                    <thead>
                        <tr>
                            <th>Kelas</th>
                            <th>Total Tagihan</th>
                            <th>Total Pembayaran</th>
                            <th>Total Belum Bayar</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($laporan as $dataKelas)
                            <tr>
                                <td>{{ $dataKelas['kelas'] }}</td>
                                <td>Rp {{ number_format($dataKelas['total_tagihan'], 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($dataKelas['total_bayar'], 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($dataKelas['total_belum_bayar'], 0, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('laporan-tagihan-spp.show', $dataKelas['id']) }}" class="btn btn-info">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
