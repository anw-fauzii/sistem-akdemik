@extends('layouts.app2')

@section('title')
    <title>Laporan Presensi</title>
@endsection

@section('content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-smile icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Presensi {{ \Carbon\Carbon::today()->translatedFormat('d F Y') }}
                    <div class="page-title-subheading">
                        Laporan Presensi Hari Ini
                    </div>
                </div>
            </div>  
        </div> 
    </div>

    <div class="main-card card">
        <div class="card-header">
            <a href="{{route('laporan.presensi.ambil_harian')}}" class="btn btn-primary">Ambil Data Presensi</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="mb-0 table table-hover table-striped" id="tabel-presensi">
                    <thead>
                        <tr>
                            <th>Kelas</th>
                            <th>Sudah Scan</th>
                            <th>Belum Scan</th>
                            <th>Tidak Masuk</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

<!-- jQuery (sudah kamu pakai versi 3.6.0) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script>
    function loadPresensiHariIni() {
    $.ajax({
        url: "{{ route('laporan.presensi.hari_ini') }}",
        method: "GET",
        dataType: "json",
        success: function (response) {
            let tbody = '';
            if (response.length === 0) {
                tbody = `<tr><td colspan="4" class="text-center">Tidak ada data presensi hari ini.</td></tr>`;
            } else {
                response.forEach(item => {
                    tbody += `<tr>
                        <td>${item.kelas}</td>
                        <td>${item.sudah_scan}</td>
                        <td>${item.belum_scan}</td>
                        <td>${item.tidak_masuk}</td>
                    </tr>`;
                });
            }

            // Masukkan isi ke tbody dulu
            $('#tabel-presensi tbody').html(tbody);

            // Baru inisialisasi DataTables setelah datanya ada
            if (!$.fn.DataTable.isDataTable('#tabel-presensi')) {
                $('#tabel-presensi').DataTable();
            }
        },
        error: function () {
            $('#tabel-presensi tbody').html(`<tr><td colspan="4">Gagal memuat data.</td></tr>`);
        }
    });
}

        function simpanNantiButuhWkwkw() {
        $.ajax({
            url: "{{ route('laporan.presensi.hari_ini') }}",
            method: "GET",
            dataType: "json",
            success: function (response) {
                let tbody = '';
                if (response.length === 0) {
                    tbody = `<tr><td colspan="4" class="text-center">Tidak ada data presensi hari ini.</td></tr>`;
                } else {
                    response.forEach(item => {
                        tbody += `<tr>
                            <td>${item.kelas}</td>
                            <td>${item.sudah_scan.join('<br>')}</td>
                            <td>${item.belum_scan.join('<br>')}</td>
                            <td>${item.tidak_masuk.join('<br>')}</td>
                        </tr>`;
                    });
                }
                $('#tabel-presensi tbody').html(tbody);
            },
            error: function (xhr) {
                $('#tabel-presensi tbody').html(`<tr><td colspan="4" class="text-danger text-center">Gagal memuat data presensi.</td></tr>`);
            }
        });
    }

    $(document).ready(function () {
        $('#tabel-presensi').dataTable({ 
            "ordering": false,
            "processing": true,
            "lengthMenu": [
            [ 25, 50, 100, 1000, -1 ],
            [ '25', '50', '100', '1000', 'All' ]
        ],
        });
        loadPresensiHariIni();
        setInterval(loadPresensiHariIni, 30000);
    });

</script>
@endsection
