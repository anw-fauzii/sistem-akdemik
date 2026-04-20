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
                            Laporan Presensi Hari Ini (Auto-Refresh setiap 30 detik)
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{ route('laporan.presensi.ambil_harian') }}" class="btn btn-primary">Ambil Data Presensi</a>
                </div>
                <div style="width: 300px;">
                    <select id="select-periode" class="form-control" required>
                        <option></option>
                        @foreach ($kelas as $p)
                            <option value="{{ $p->id }}">{{ $p->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="mb-0 table table-hover table-striped w-100" id="tabel-presensi">
                        <thead>
                            <tr>
                                <th>Kelas</th>
                                <th class="text-center">Total Siswa</th>
                                <th class="text-center">Sudah Scan</th>
                                <th class="text-center">Belum Scan</th>
                                <th class="text-center">Tidak Masuk (S/I/A)</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    <!-- jQuery (sudah kamu pakai versi 3.6.0) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {

            // 1. Inisialisasi Select2
            $('#select-periode').select2({
                placeholder: "Pilih / Cari Kelas",
                allowClear: true,
                width: '100%',
            }).on('change', function() {
                const periode = $(this).val();
                if (periode) {
                    // Asumsi ini mengarah ke detail kelas
                    window.location.href = `/presensi-kelas/${periode}`;
                }
            });

            // 2. Inisialisasi DataTables Bawaan AJAX (Best Practice)
            let tabelPresensi = $('#tabel-presensi').DataTable({
                processing: true,
                serverSide: false, // Set false karena data diolah di client-side (kecil)
                ajax: {
                    url: "{{ route('laporan.presensi.hari_ini') }}", // Asumsi ini nama route untuk presensiHariIni()
                    dataSrc: "" // Kosongkan karena respons API Anda langsung berupa Array `[...]`
                },
                columns: [{
                        data: 'nama_kelas'
                    }, // Sesuaikan dengan alias SQL dari Controller baru
                    {
                        data: 'total_siswa',
                        className: 'text-center font-weight-bold'
                    },
                    {
                        data: 'sudah_scan',
                        className: 'text-center',
                        render: function(data) {
                            return `<span class="badge bg-success" style="font-size: 14px;">${data}</span>`;
                        }
                    },
                    {
                        data: 'belum_scan',
                        className: 'text-center',
                        render: function(data) {
                            return `<span class="badge bg-secondary" style="font-size: 14px;color: white;">${data}</span>`;
                        }
                    },
                    {
                        data: 'tidak_masuk',
                        className: 'text-center',
                        render: function(data) {
                            return `<span class="badge bg-danger" style="font-size: 14px;color: white;">${data}</span>`;
                        }
                    }
                ],
                lengthMenu: [
                    [25, 50, 100, -1],
                    [25, 50, 100, "All"]
                ],
                language: {
                    emptyTable: "Tidak ada data presensi hari ini.",
                    processing: "Memuat data presensi...",
                    search: "Cari Kelas:"
                }
            });

            // 3. Auto-Refresh DataTables setiap 30 detik (Tanpa reload halaman)
            setInterval(function() {
                // parameter (null, false) agar posisi pagination tidak kembali ke halaman 1 saat refresh
                tabelPresensi.ajax.reload(null, false);
            }, 30000);

        });
    </script>
@endsection
