@extends('layouts.app2')

@section('title')
    <title>Dashboard Yayasan</title>
@endsection

@section('content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-home icon-gradient bg-mean-fruit"></i>
                </div>
                <div>
                    Halo {{ Auth::user()->name }}!
                    <div class="page-title-subheading">
                        Selamat datang di Sistem Informasi Akademik Yayasan Pendidikan Prima Insani
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    Pengumuman
                </div>
                <div class="card-body">
                    <div class="vertical-time-icons vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
                        @forelse ($pengumuman as $item)
                            <div class="vertical-timeline-item vertical-timeline-element">
                                <div>
                                    <div class="vertical-timeline-element-icon bounce-in">
                                        <div class="timeline-icon border-primary">
                                            <i class="pe-7s-speaker"></i>
                                        </div>
                                    </div>
                                    <div class="vertical-timeline-element-content bounce-in">
                                        <p>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</p>
                                        <h4 class="timeline-title mt-2">{{ $item->judul }}</h4>
                                        <p>{!! $item->isi !!}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            
                        @endforelse
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    Pengumuman
                </div>
                <div class="card-body">
                    <table class="table table-hover table-striped" id="myTable2">
                        <thead>
                            <tr>
                                <th>Kelas</th>
                                <th>Jumlah Diperiksa</th>
                                <th>Belum Diperiksa</th>
                                <th>Rata-rata TB</th>
                                <th>Rata-rata BB</th>
                                <th>Jumlah Masalah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($statistik as $data)
                                <tr>
                                    <td>{{ $data['nama_kelas'] }}</td>
                                    <td>{{ $data['jumlah_diperiksa'] }}</td>
                                    <td>{{ $data['belum_diperiksa'] }}</td>
                                    <td>{{ $data['rata_tb'] }} cm</td>
                                    <td>{{ $data['rata_bb'] }} kg</td>
                                    <td>
                                        <span class="badge bg-{{ $data['jumlah_masalah'] > 0 ? 'warning' : 'success' }}">
                                            {{ $data['jumlah_masalah'] }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">Tidak ada data kelas</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
