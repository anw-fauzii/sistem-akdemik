@extends('layouts.app2')

@section('title')
    <title>Pembayaran SPP</title>
@endsection

@section('content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-cash icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Pembayaran SPP
                    <div class="page-title-subheading">
                        Merupakan Pembayaran yang dilakukan pembayaran
                    </div>
                </div>
            </div>  
        </div> 
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="main-card card">
        <div class="card-header">
            Cek data pembayaran
        </div>
        <div class="card-body">
            <form action="{{ route('pembayaran.spp.cari') }}" method="POST">
                @csrf
                <div class="row align-items-center">
                    <div class="col-md-5 d-flex align-items-center">
                        <label class="mr-sm-2 text-nowrap" style="text-align: right;">Tahun Ajaran</label>
                        <select name="tahun_ajaran_id" class="form-control @error('tahun_ajaran_id') is-invalid @enderror">
                            <option value="" selected disabled>-- Tahun Ajaran --</option>
                            @foreach ($tahun_ajaran as $item)
                                <option value="{{ $item->id }}" {{ isset($tahun_ajaran_id) && $tahun_ajaran_id == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama_tahun_ajaran }} - {{ $item->semester }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5 d-flex align-items-center">
                        <label class="mr-sm-2 text-nowrap" style="text-align: right;">Siswa</label>
                        <select name="siswa_nis" class="form-control @error('siswa_nis') is-invalid @enderror">
                            <option value="" selected disabled>-- Pilih Siswa --</option>
                            @foreach ($siswa_list as $item)
                                <option value="{{ $item->nis }}" {{ isset($siswa_nis) && $siswa_nis == $item->nis ? 'selected' : '' }}>
                                    {{ $item->nis }} - {{ $item->nama_lengkap }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(isset($siswa) && isset($tagihan_spp))
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="main-card card">
                <div class="card-header">
                    Informasi Siswa
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <td><strong>NIS</strong></td>
                            <td>{{ $siswa->nis }}</td>
                        </tr>
                        <tr>
                            <td><strong>Nama</strong></td>
                            <td>{{ $siswa->nama_lengkap }}</td>
                        </tr>
                        <tr>
                            <td><strong>Kelas</strong></td>
                            <td>{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="main-card card">
                <div class="card-header">
                    Tagihan SPP
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Bulan</th>
                                <th>Nominal SPP</th>
                                <th>Biaya Makan</th>
                                <th>Total Pembayaran</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tagihan_spp as $tagihan)
                                <tr>
                                    <td>{{ $tagihan->bulan_spp->nama_bulan }}</td>
                                    <td>Rp {{ number_format($tagihan->nominal_spp, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($tagihan->biaya_makan, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($tagihan->total_pembayaran, 0, ',', '.') }}</td>
                                    <td>{{ $tagihan->keterangan }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
