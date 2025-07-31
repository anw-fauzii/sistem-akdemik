@extends('layouts.app2')

@section('title')
    <title>Data Kesehatan</title>
@endsection

@section('content')
<style>
    @media (max-width: 768px) {
        .tabel-kesehatan {
            display: none;
        }

        .kesehatan-mobile {
            display: block;
        }
    }

    @media (min-width: 769px) {
        .kesehatan-mobile {
            display: none;
        }
    }
</style>
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-smile icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Data Kesehatan {{$tahunAjaran->nama_tahun_ajaran}} - {{$tahunAjaran->semester}}
                    <div class="page-title-subheading">
                        Rekap Data Kesehatan siswa
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
                @foreach($tahun_selama_belajar as $item)
                    @if(optional($item->kelas)->tahun_ajaran)
                        <li>
                            <a href="{{ route('kesehatan-siswa.show', $item->kelas->tahun_ajaran_id) }}" class="dropdown-item">
                                {{ $item->kelas->tahun_ajaran->nama_tahun_ajaran }}-{{$item->kelas->tahun_ajaran->semester}}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul> 
        </div>
        <div class="card-body">
            <div class="table-responsive tabel-kesehatan">
                <table class="table table-hover table-striped mb-0" id="myTable2">
                    <thead class="text-center">
                        <tr>
                            <th>Bulan</th>
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
                        @forelse ($kesehatan as $item)
                            <tr>
                                <td>{{ $item->bulanSpp->nama_bulan }}</td>
                                <td>{{ $item->tb ?? '0' }}</td>
                                <td>{{ $item->bb ?? '0' }}</td>
                                <td>{{ $item->lila ?? '0' }}</td>
                                <td>{{ $item->lika ?? '0' }}</td>
                                <td>{{ $item->lp ?? '0' }}</td>
                                <td>{{ $item->mata ?? '-' }}</td>
                                <td>{{ $item->telinga ?? '-' }}</td>
                                <td>{{ $item->gigi ?? '-' }}</td>
                                <td>{{ $item->hasil ?? '-' }}</td>
                                <td>{{ $item->tensi ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">Belum ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="kesehatan-mobile">
                @forelse ($kesehatan as $item)
                    <div class="card mb-3 shadow-sm">
                        <div class="card-header bg-light">
                            <strong>{{ $item->bulanSpp->nama_bulan }}</strong>
                        </div>
                        <div class="card-body p-3">
                            <p class="mb-1">TB: <strong>{{ $item->tb ?? '0' }}</strong></p>
                            <p class="mb-1">BB: <strong>{{ $item->bb ?? '0' }}</strong></p>
                            <p class="mb-1">LILA: <strong>{{ $item->lila ?? '0' }}</strong></p>
                            <p class="mb-1">LIKA: <strong>{{ $item->lika ?? '0' }}</strong></p>
                            <p class="mb-1">LP: <strong>{{ $item->lp ?? '0' }}</strong></p>
                            <p class="mb-1">MATA: <strong>{{ $item->mata ?? '-' }}</strong></p>
                            <p class="mb-1">TELINGA: <strong>{{ $item->telinga ?? '-' }}</strong></p>
                            <p class="mb-1">GIGI: <strong>{{ $item->gigi ?? '-' }}</strong></p>
                            <p class="mb-1">KES UMUM: <strong>{{ $item->hasil ?? '-' }}</strong></p>
                            <p class="mb-0">TENSI: <strong>{{ $item->tensi ?? '-' }}</strong></p>
                        </div>
                    </div>
                @empty
                    <p class="text-center">Belum ada data</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> 
@endsection
