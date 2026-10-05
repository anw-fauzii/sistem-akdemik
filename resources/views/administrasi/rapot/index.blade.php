@extends('layouts.app2')

@section('title')
    <title>Arsip Rapot Kelas</title>
@endsection

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-print icon-gradient bg-mean-fruit"></i>
                    </div>
                    <div>Arsip Rapot Kelas Saya
                        <div class="page-title-subheading">
                            Melihat riwayat mengajar dan mencetak berkas rapot dari tahun-tahun sebelumnya.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="pe-7s-attention mr-2"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="main-card mb-3 card">
            <div class="card-header bg-heavy-rain text-dark font-weight-bold">
                <i class="pe-7s-box2 mr-2 text-primary font-icon-lg"></i> Lemari Arsip Rapot Siswa
            </div>
            <div class="card-body p-0">
                <div id="accordionTahun" class="accordion-wrapper">

                    @forelse ($tahunAjaran as $ta)
                        <div class="card border-0 border-bottom">
                            <div class="card-header p-0" id="heading{{ $ta->id }}">
                                <button type="button" data-toggle="collapse" data-target="#collapse{{ $ta->id }}"
                                    aria-expanded="false" aria-controls="collapse{{ $ta->id }}"
                                    data-ta-id="{{ $ta->id }}"
                                    class="btn btn-link btn-block text-left text-dark font-weight-bold d-flex justify-content-between align-items-center py-3 px-4 btn-accordion-ta collapse-trigger">
                                    <span>
                                        <i class="pe-7s-folder mr-2 text-warning font-icon-lg"></i>
                                        Tahun Ajaran {{ $ta->nama_tahun_ajaran }} -
                                        {{ $ta->semester == 1 ? 'Ganjil' : 'Genap' }}
                                    </span>
                                    <i class="pe-7s-angle-down text-muted icon-arrow transition"></i>
                                </button>
                            </div>

                            <div id="collapse{{ $ta->id }}" class="collapse"
                                aria-labelledby="heading{{ $ta->id }}" data-parent="#accordionTahun">
                                <div class="card-body p-4">
                                    <div class="text-center py-4 text-muted init-loader">
                                        <div class="spinner-border spinner-border-sm text-primary mr-2" role="status">
                                        </div>
                                        Memuat daftar siswa Anda...
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="pe-7s-news-paper text-muted d-block mb-2" style="font-size: 3rem;"></i>
                            <h6 class="text-muted font-weight-bold">Belum ada riwayat mengajar yang terarsip.</h6>
                        </div>
                    @endforelse

                </div>
            </div>
        </div>
    </div>

    <style>
        .btn-accordion-ta {
            text-decoration: none !important;
            background: #ffffff;
            border-radius: 0;
            font-size: 1.05rem;
        }

        .btn-accordion-ta:hover {
            background: #f8fafc;
        }

        .btn-accordion-ta[aria-expanded="true"] {
            background: #e0f2fe;
            color: #0369a1 !important;
        }

        .btn-accordion-ta[aria-expanded="true"] .icon-arrow {
            transform: rotate(180deg);
            color: #0369a1 !important;
        }

        .transition {
            transition: transform 0.2s ease-in-out;
        }

        .font-icon-lg {
            font-size: 1.3rem;
            vertical-align: middle;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.collapse-trigger').on('click', function() {
                const targetCollapse = $($(this).data('target'));
                const taId = $(this).data('ta-id');
                const contentBody = targetCollapse.find('.card-body');

                if (!targetCollapse.hasClass('show') && !contentBody.hasClass('data-loaded')) {
                    // Tembak URL baru yang langsung mengarah ke method pengambilan siswa
                    const targetUrl = "{{ url('administrasi-rapot/get-siswa') }}/" + taId;

                    $.ajax({
                        url: targetUrl,
                        type: 'GET',
                        beforeSend: function() {
                            contentBody.find('.init-loader').show();
                        },
                        success: function(response) {
                            contentBody.html(response);
                            contentBody.addClass('data-loaded');
                        },
                        error: function() {
                            contentBody.html(`
                                <div class="table-responsive">
    <table class="table table-hover table-striped w-100 datatable-siswa">
        <thead class="">
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="15%">NIS/NISN</th>
                <th>Nama Siswa</th>
                <th width="15%">Kelas</th>
                <th width="20%" class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1</td>
                <td>100123 / 0012345678</td>
                <td>Siswa 1</td>
                <td>1X</td>
                <td class="text-center">
                    <!-- Tombol ini bisa memunculkan Modal berisi data Rapot yang kita buat sebelumnya -->
                    <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#modalRapotSiswa1">
                        <i class="pe-7s-look mr-1"></i> Detail
                    </button>
                    <button class="btn btn-sm btn-primary">
                        <i class="pe-7s-print mr-1"></i> Cetak
                    </button>
                </td>
            </tr>
            <tr>
                <td class="text-center">2</td>
                <td>100124 / 0012345679</td>
                <td>Siswa 2</td>
                <td>1F</td>
                <td class="text-center">
                    <button class="btn btn-sm btn-info">
                        <i class="pe-7s-look mr-1"></i> Detail
                    </button>
                    <button class="btn btn-sm btn-primary">
                        <i class="pe-7s-print mr-1"></i> Cetak
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
                            `);
                        }
                    });
                }
            });
        });
    </script>
@endsection
