@extends('layouts.app2')

@section('title')
    <title>Rekap Presensi Ekstrakurikuler</title>
@endsection

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-date icon-gradient bg-mean-fruit"></i>
                    </div>
                    <div>
                        Presensi
                        {{ is_object($bulan) ? $bulan->nama_bulan : \Carbon\Carbon::parse($bulan)->translatedFormat('F Y') }}
                        <div class="page-title-subheading">
                            Laporan Kehadiran Ekstrakurikuler <strong>{{ $ekstrakurikuler->nama_ekstrakurikuler }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-xl-4">
                <div class="card mb-3 widget-content border-bottom border-warning border-4 shadow-sm">
                    <div class="widget-content-wrapper">
                        <div class="widget-content-left">
                            <div class="widget-heading">Sakit</div>
                            <div class="widget-subheading">Total Izin Sakit</div>
                        </div>
                        <div class="widget-content-right">
                            <div class="widget-numbers text-warning">
                                <span>{{ $presensi->where('status', 'sakit')->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="card mb-3 widget-content border-bottom border-success border-4 shadow-sm">
                    <div class="widget-content-wrapper">
                        <div class="widget-content-left">
                            <div class="widget-heading">Izin</div>
                            <div class="widget-subheading">Total Izin Keperluan</div>
                        </div>
                        <div class="widget-content-right">
                            <div class="widget-numbers text-success">
                                <span>{{ $presensi->where('status', 'izin')->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="card mb-3 widget-content border-bottom border-danger border-4 shadow-sm">
                    <div class="widget-content-wrapper">
                        <div class="widget-content-left">
                            <div class="widget-heading">Alpha</div>
                            <div class="widget-subheading">Tanpa Keterangan</div>
                        </div>
                        <div class="widget-content-right">
                            <div class="widget-numbers text-danger">
                                <span>{{ $presensi->where('status', 'alpha')->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <a href="{{ route('presensi-ekstrakurikuler.create') }}" class="btn btn-primary font-weight-bold">
                    <i class="pe-7s-plus mr-1"></i> Isi Presensi Hari Ini
                </a>

                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle font-weight-bold" type="button"
                        id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="pe-7s-filter mr-1"></i> FILTER BULAN
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu2">
                        <li>
                            <a href="{{ route('presensi-ekstrakurikuler.index') }}"
                                class="dropdown-item font-weight-bold text-primary">
                                Bulan Ini Terkini
                            </a>
                        </li>
                        <div class="dropdown-divider"></div>
                        @foreach ($bulan_spp_all as $itemBulan)
                            <li>
                                <a href="{{ route('presensi-ekstrakurikuler.show', $itemBulan->id) }}"
                                    class="dropdown-item {{ is_object($bulan) && $itemBulan->id == $bulan->id ? 'active' : '' }}">
                                    {{ $itemBulan->nama_bulan }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="mb-0 table table-hover table-striped" id="myTable2">
                        <thead>
                            <tr>
                                <th class="text-center align-middle" width="5%">No</th>
                                <th class="align-middle">Nama Siswa</th>
                                @foreach ($tanggal_tercatat as $tanggal)
                                    <th class="text-center align-middle p-1"
                                        title="Klik untuk edit presensi {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d M Y') }}">
                                        <a href="{{ route('presensi-ekstrakurikuler.edit-harian', $tanggal) }}"
                                            class="btn btn-sm btn-outline-dark border-0 font-weight-bold d-block w-100">
                                            {{ \Carbon\Carbon::parse($tanggal)->format('d/m') }}
                                        </a>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @if ($tanggal_tercatat->isEmpty())
                                <tr>
                                    <td colspan="100%" class="text-center text-muted py-4">
                                        <i class="pe-7s-info fa-2x d-block mb-2"></i>
                                        Belum ada data presensi bulan ini.
                                    </td>
                                </tr>
                            @else
                                @foreach ($anggotaEkstrakurikuler as $anggota)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            {{ $anggota->anggotaKelas->siswa->nama_lengkap ?? '-' }}
                                            <strong
                                                class="text-muted">({{ $anggota->anggotaKelas->kelas->nama_kelas ?? '-' }})</strong>
                                        </td>

                                        @foreach ($tanggal_tercatat as $tanggal)
                                            @php
                                                // MEMBACA DARI RAM - SUPER CEPAT O(1)
                                                $presensiData = $rekapPresensi[$anggota->id][$tanggal] ?? null;
                                            @endphp

                                            @if ($presensiData)
                                                @if ($presensiData->status == 'hadir')
                                                    <td class="text-center text-success fw-bold" title="Hadir">
                                                        &#10003;</td>
                                                @elseif ($presensiData->status == 'sakit')
                                                    <td class="text-center bg-warning text-dark fw-bold" title="Sakit">S
                                                    </td>
                                                @elseif ($presensiData->status == 'izin')
                                                    <td class="text-center bg-success text-white fw-bold" title="Izin">I
                                                    </td>
                                                @elseif ($presensiData->status == 'alpha')
                                                    <td class="text-center bg-danger text-white fw-bold" title="Alpha">A
                                                    </td>
                                                @endif
                                            @else
                                                <td class="text-center bg-secondary text-white fw-bold"
                                                    title="Tidak Tercatat">-
                                                </td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                @if ($tanggal_tercatat->isNotEmpty())
                    <div class="mt-3 small text-muted">
                        <strong>Keterangan:</strong>
                        <span class="text-success ml-2">&#10003;</span> = Hadir,
                        <span class="badge badge-warning text-dark ml-2">S</span> = Sakit,
                        <span class="badge badge-success ml-2">I</span> = Izin,
                        <span class="badge badge-danger ml-2">A</span> = Alpha,
                        <span class="badge badge-secondary ml-2">-</span> = Kosong / Belum Diisi
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
