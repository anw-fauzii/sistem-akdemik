@php
    // Ambil ID tahun ajaran secara dinamis dari baris data pertama, jika tidak ada fallback ke random string
    $currentTaId = $riwayatAnggota->first()->tahun_ajaran_id ?? Str::random(5);
@endphp

<div class="table-responsive bg-white p-2 rounded shadow-sm">
    <table class="table table-hover table-striped table-bordered mb-0 text-left"
        id="table-arsip-siswa-{{ $currentTaId }}" style="width:100%">
        <thead class="bg-light">
            <tr>
                <th width="5%">No</th>
                <th width="20%">NIS/NISN</th>
                <th>Nama Siswa</th>
                <th width="15%">Kelas</th>
                <th class="text-center" width="10%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riwayatAnggota as $index => $anggota)
                @if ($anggota->siswa)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <span class="text-muted">
                                {{ $anggota->siswa->nis ?? '-' }}/{{ $anggota->siswa->nisn ?? '-' }}
                            </span>
                        </td>
                        <td><strong>{{ $anggota->siswa->nama_lengkap }}</strong></td>
                        <td>
                            <span class="badge badge-pill badge-warning text-dark font-weight-bold">
                                {{ $anggota->kelas->nama_kelas ?? '-' }}
                            </span>
                        </td>
                        <td class="text-center" style="overflow: visible;">
                            <div class="dropdown d-inline-block">
                                <button type="button" data-toggle="dropdown" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false"
                                    class="btn btn-link text-muted p-0 border-0 shadow-none dropdown-toggle-nocaret">
                                    <i class="fa fa-ellipsis-v" style="font-size: 1.2rem; width: 20px;"></i>
                                </button>

                                <div class="dropdown-menu dropdown-menu-right border-0 shadow"
                                    style="border-radius: 8px; min-width: 160px; margin-top: 5px;">

                                    <a href="{{ route('yaumiyah-tahsin.create', ['tingkat' => 1]) }}"
                                        class="dropdown-item py-2 mb-1 d-flex align-items-center">
                                        <i class="fa fa-edit text-primary mr-3 me-3"
                                            style="width: 20px; text-align: center;"></i>
                                        <span class="text-dark" style="font-weight: 500;">Input</span>
                                    </a>

                                    <a href="{{ route('yaumiyah-tahsin.show', 1) }}"
                                        class="dropdown-item py-2 mb-1 d-flex align-items-center">
                                        <i class="fa fa-eye text-info mr-3 me-3"
                                            style="width: 20px; text-align: center;"></i>
                                        <span class="text-dark" style="font-weight: 500;">Detail</span>
                                    </a>

                                    <a href="{{ route('yaumiyah-tahsin.statistik', 1) }}"
                                        class="dropdown-item py-2 d-flex align-items-center">
                                        <i class="fa fa-chart-area text-success mr-3 me-3"
                                            style="width: 20px; text-align: center;"></i>
                                        <span class="text-dark" style="font-weight: 500;">Statistik</span>
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        <i class="pe-7s-attention mr-1"></i> Tidak ada riwayat siswa Anda di tahun ajaran ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<style>
    /* Hilangkan panah default bootstrap dropdown */
    .dropdown-toggle-nocaret::after {
        display: none !important;
    }

    /* Biar menu dropdown tidak terpotong container table */
    .table-responsive {
        overflow: visible !important;
    }
</style>

<script>
    $(document).ready(function() {
        var tableId = `#table-arsip-siswa-{{ $currentTaId }}`;

        // Hancurkan instansi lama jika mendeteksi duplikasi re-render
        if ($.fn.DataTable.isDataTable(tableId)) {
            $(tableId).DataTable().destroy();
        }

        $(tableId).DataTable({
            "language": {
                "search": "Cari Murid:",
                "lengthMenu": "Tampilkan _MENU_ data",
                "zeroRecords": "Siswa tidak ditemukan",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ siswa",
                "paginate": {
                    "next": "Lanjut",
                    "previous": "Kembali"
                }
            },

            "drawCallback": function(settings) {
                // Jalankan inisialisasi manual untuk Bootstrap 5 jika tersedia
                var dropdownElementList = [].slice.call(document.querySelectorAll(tableId +
                    ' [data-bs-toggle="dropdown"]'));
                dropdownElementList.map(function(dropdownToggleEl) {
                    if (typeof bootstrap !== 'undefined' && bootstrap.Dropdown) {
                        return new bootstrap.Dropdown(dropdownToggleEl);
                    }
                });

                // Jalankan fallback/cadangan untuk Bootstrap 4 bawaan tema lama
                if ($.fn.dropdown) {
                    $(tableId + ' [data-toggle="dropdown"]').dropdown();
                }
            }
        });
    });
</script>
