@extends('layouts.app2')

@section('title')
    <title>Yaumiyah Tahfiz</title>
@endsection

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-notebook icon-gradient bg-mean-fruit"></i>
                    </div>
                    <div>Yaumiyah Tahfiz
                        <div class="page-title-subheading">
                            Pilih tingkat atau kelompok tahfiz Anda untuk mulai mengisi penilaian harian (Yaumiyah).
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card card">
            <div class="card-body">
                @if ($grupTingkat->isEmpty())
                    <div class="alert alert-warning mb-0">
                        <i class="pe-7s-info mr-2"></i>
                        <strong>Informasi:</strong> Anda belum memiliki jadwal mengajar atau anggota T2Q pada tahun ajaran
                        aktif ini.
                    </div>
                @else
                    <div class="table">
                        <table class="mb-0 table table-hover table-striped" id="myTable2">
                            <thead>
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th>Tingkat (Kelompok)</th>
                                    <th>Total Siswa</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($grupTingkat as $grup)
                                    <tr>
                                        <td class="text-center">{{ $no++ }}</td>
                                        <td class="font-weight-bold">Kelas {{ $grup->tingkat }}</td>
                                        <td>
                                            <span class="badge badge-info">{{ $grup->total_siswa }} Siswa</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown d-inline-block">
                                                <button type="button" data-bs-toggle="dropdown"
                                                    class="btn btn-link text-muted p-0 border-0 shadow-none">
                                                    <i class="fa fa-ellipsis-v" style="font-size: 1.2rem;"></i>
                                                </button>

                                                <div class="dropdown-menu dropdown-menu-right border-0 shadow"
                                                    style="border-radius: 8px; min-width: 160px; margin-top: 10px;">

                                                    <a href="{{ route('yaumiyah-tahfiz.create', ['tingkat' => $grup->tingkat]) }}"
                                                        class="dropdown-item py-2 mb-1 d-flex align-items-center">
                                                        <i class="fa fa-edit text-primary me-3"
                                                            style="width: 20px; text-align: center;"></i>
                                                        <span class="text-dark" style="font-weight: 500;">Input</span>
                                                    </a>

                                                    <a href="{{ route('yaumiyah-tahfiz.show', $grup->tingkat) }}"
                                                        class="dropdown-item py-2 mb-1 d-flex align-items-center">
                                                        <i class="fa fa-eye text-info me-3"
                                                            style="width: 20px; text-align: center;"></i>
                                                        <span class="text-dark" style="font-weight: 500;">Detail</span>
                                                    </a>
                                                    <a href="{{ route('yaumiyah-tahfiz.statistik', $grup->tingkat) }}"
                                                        class="dropdown-item py-2 d-flex align-items-center">
                                                        <i class="fa fa-chart-area text-success me-3"
                                                            style="width: 20px; text-align: center;"></i>
                                                        <span class="text-dark" style="font-weight: 500;">Statistik</span>
                                                    </a>

                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
