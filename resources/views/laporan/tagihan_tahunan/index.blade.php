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
                    <div class="h5 font-weight-bold mb-0">Rp {{ number_format($total_tagihan_semua, 0, ',', '.') }}</div>
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
                    <div class="h5 font-weight-bold mb-0">Rp {{ number_format($total_dibayar_semua, 0, ',', '.') }}</div>
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
                    <div class="h5 font-weight-bold mb-0">Rp {{ number_format($total_sisa_semua, 0, ',', '.') }}</div>
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
                    <div class="h5 font-weight-bold mb-0">{{ $jumlah_siswa_belum_lunas }} siswa</div>
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
                            <th>Nama Kelas</th>
                            <th>Total Tagihan</th>
                            <th>Total Dibayar</th>
                            <th>Sisa Tagihan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($hasil as $row)
                        <tr>
                            <td>{{ $row['nama_kelas'] }}</td>
                            <td>Rp {{ number_format($row['total_tagihan'], 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($row['total_dibayar'], 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($row['total_sisa'], 0, ',', '.') }}</td>
                            <td>
                                <span class="badge {{ $row['status'] == 'Lunas' ? 'badge-success' : 'badge-danger' }}">
                                    {{ $row['status'] }}
                                </span>
                            </td>
                            <td><a href="{{route('laporan-tagihan-tahunan.show', $row['id'])}}" class="btn btn-sm btn-primary"><i class="pe-7s-info" style="font-size: 0.85rem;"></i></a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
