@extends('layouts.app2')

@section('title')
    <title>Prestasi Saya</title>
@endsection

@section('content')
    <div class="app-main__inner">

        <div class="app-page-title">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-star icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Prestasi Saya
                    <div class="page-title-subheading">
                        Daftar prestasi yang telah diraih siswa.
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 col-lg-4">
                <div class="card mb-3 widget-chart text-left" style="border-bottom: 4px solid var(--orange);">
                    <div class="icon-wrapper rounded-circle">
                        <div class="icon-wrapper-bg bg-warning"></div>
                        <i class="pe-7s-medal text-warning"></i>
                    </div>
                    <div class="widget-chart-content">
                        <div class="widget-subheading">Total</div>
                        <div class="h5 font-weight-bold mb-0">{{ $prestasi->count() }}</div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-lg-4">
                <div class="card mb-3 widget-chart text-left" style="border-bottom: 4px solid var(--blue);">
                    <div class="icon-wrapper rounded-circle">
                        <div class="icon-wrapper-bg bg-info"></div>
                        <i class="pe-7s-study text-info"></i>
                    </div>
                    <div class="widget-chart-content">
                        <div class="widget-subheading">Akademik</div>
                        <div class="h5 font-weight-bold mb-0">{{ $prestasi->where('kategori', 'akademik')->count() }}</div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-lg-4">
                <div class="card mb-3 widget-chart text-left" style="border-bottom: 4px solid var(--green);">
                    <div class="icon-wrapper rounded-circle">
                        <div class="icon-wrapper-bg bg-success"></div>
                        <i class="pe-7s-world text-success"></i>
                    </div>
                    <div class="widget-chart-content">
                        <div class="widget-subheading">Non Akademik</div>
                        <div class="h5 font-weight-bold mb-0">{{ $prestasi->where('kategori', 'non_akademik')->count() }}
                        </div>
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
                                <th>No</th>
                                <th>Prestasi</th>
                                <th>Kategori</th>
                                <th>Tingkat</th>
                                <th>Peringkat</th>
                                <th>Tanggal</th>
                                <th>File</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($prestasi as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td>{{ $item->nama_prestasi }}</td>

                                    <td>
                                        @if ($item->kategori == 'akademik')
                                            <span class="badge badge-primary">Akademik</span>
                                        @else
                                            <span class="badge badge-success">Non Akademik</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->tingkat ?? '-' }}</td>
                                    <td>{{ $item->peringkat ?? '-' }}</td>
                                    <td>
                                        {{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d M Y') : '-' }}
                                    </td>
                                    <td>
                                        @if ($item->file_sertifikat)
                                            <a href="{{ asset('storage/' . $item->file_sertifikat) }}" target="_blank"
                                                class="btn btn-sm btn-info">
                                                Lihat
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        Belum ada data prestasi
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
@endsection
