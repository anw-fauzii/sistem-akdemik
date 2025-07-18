@extends('layouts.app2')

@section('title', 'Laporan Presensi Hari Ini')

@section('content')
<div class="container">
    <h3 class="mb-4">📅 Laporan Presensi Hari Ini</h3>

    <div class="table-responsive">
        <table class="table table-bordered" id="tabel-presensi">
            <thead class="table-dark">
                <tr>
                    <th>Kelas</th>
                    <th>Sudah Scan</th>
                    <th>Belum Scan</th>
                    <th>Tidak Masuk</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="4" class="text-center">Memuat data...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                            <td class="text-center">${item.sudah_scan}</td>
                            <td class="text-center">${item.belum_scan}</td>
                            <td class="text-center">${item.tidak_masuk}</td>
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
        loadPresensiHariIni();
        setInterval(loadPresensiHariIni, 30000);
    });

</script>
@endsection

