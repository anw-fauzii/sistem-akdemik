@extends('layouts.app2')

@section('title')
    <title>Data Barang</title>
@endsection

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-box2 icon-gradient bg-mean-fruit"></i>
                    </div>
                    <div>
                        Data Barang
                        <div class="page-title-subheading">
                            Merupakan data untuk pengelolaan barang yang ada di sarana dan prasarana.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card card">
            <div class="card-header">
                <div>
                    <a href="{{ route('barang.create') }}" class="btn btn-primary mr-2">
                        <i class="pe-7s-plus"></i>
                        Tambah Baru
                    </a>
                </div>
                <button type="button" class="btn btn-success" onclick="showCreateModal()">IMPORT EXCEL</button>
                @include('sarana_prasarana.barang.import')
            </div>

            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4 mb-2">
                        <label for="searchBarang">
                            Cari Barang
                        </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="pe-7s-search"></i>
                                </span>
                            </div>
                            <input type="text"
                                id="searchBarang"
                                class="form-control"
                                placeholder="Cari ID atau nama barang...">
                        </div>
                    </div>

                    <div class="col-md-3 mb-2">
                        <label for="filterKategori">
                            Kategori
                        </label>
                        <select id="filterKategori" class="form-control">
                            <option value="">Semua Kategori</option>
                            @foreach ($kategori as $item)
                                <option value="{{ $item->nama_kategori_barang }}">
                                    {{ $item->nama_kategori_barang }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 mb-2">
                        <label for="filterLokasi">
                            Lokasi
                        </label>
                        <select id="filterLokasi" class="form-control">
                            <option value="">Semua Lokasi</option>
                            @foreach ($lokasi as $item)
                                <option value="{{ $item->nama_lokasi_barang }}">
                                    {{ $item->nama_lokasi_barang }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 mb-2">
                        <label for="filterKondisi">
                            Kondisi
                        </label>
                        <select id="filterKondisi" class="form-control">
                            <option value="">Semua Kondisi</option>
                            <option value="baik">Baik</option>
                            <option value="rusak_ringan">Rusak Ringan</option>
                            <option value="rusak_berat">Rusak Berat</option>
                        </select>
                    </div>

                </div>
                <div class="table-responsive">
                    <table class="mb-0 table table-hover table-striped" id="myTable10">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Lokasi</th>
                                <th>Kondisi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @forelse ($barang as $item)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td><strong>{{ $item->id }}</strong></td>
                                    <td>{{ $item->nama_barang }}</td>
                                    <td>{{ $item->kategori->nama_kategori_barang ?? 'Tidak Ada' }}</td>
                                    <td>{{ $item->lokasi->nama_lokasi_barang ?? 'Tidak Ada' }}</td>
                                    <td>
                                        @if ($item->kondisi == 'baik')
                                            <span class="badge badge-success">
                                                Baik
                                            </span>
                                        @elseif ($item->kondisi == 'rusak_ringan')
                                            <span class="badge badge-warning">
                                                Rusak Ringan
                                            </span>
                                        @elseif ($item->kondisi == 'rusak_berat')
                                            <span class="badge badge-danger">
                                                Rusak Berat
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">
                                                {{ $item->kondisi }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="{{ route('barang.edit', $item->id) }}"
                                                class="btn btn-sm btn-primary mx-1"
                                                title="Edit">
                                                <i class="pe-7s-note"
                                                    style="font-size: 0.85rem;">
                                                </i>
                                            </a>
                                            <form action="{{ route('barang.destroy', $item->id) }}"
                                                method="POST"
                                                class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="btn btn-sm btn-warning delete-button mx-1"
                                                    title="Hapus">
                                                    <i class="pe-7s-trash"
                                                        style="font-size: 0.85rem;">
                                                    </i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        Belum Ada Data
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah yakin akan menghapus barang ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Tidak',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn-swal-confirm',
                        cancelButton: 'btn-swal-cancel'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.closest('form').submit();
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {

            let table = $('#myTable10').DataTable({
                dom: 'lrtip',
                language: {
                    lengthMenu: "Tampilkan _MENU_ data",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Tidak ada data",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Berikutnya",
                        previous: "Sebelumnya"
                    }
                }
            });
            $('#searchBarang').on('keyup', function() {
                table
                    .search(this.value)
                    .draw();
            });

            $('#filterKategori').on('change', function() {
                table
                    .column(3)
                    .search(this.value)
                    .draw();

            });

            $('#filterLokasi').on('change', function() {
                table
                    .column(4)
                    .search(this.value)
                    .draw();

            });

            $('#filterKondisi').on('change', function() {
                let kondisi = this.value;
                if (kondisi === 'baik') {
                    table
                        .column(5)
                        .search('^Baik$', true, false)
                        .draw();
                } else if (kondisi === 'rusak_ringan') {
                    table
                        .column(5)
                        .search('^Rusak Ringan$', true, false)
                        .draw();
                } else if (kondisi === 'rusak_berat') {
                    table
                        .column(5)
                        .search('^Rusak Berat$', true, false)
                        .draw();
                } else {
                    table
                        .column(5)
                        .search('')
                        .draw();
                }
            });

        });
    </script>

@endsection