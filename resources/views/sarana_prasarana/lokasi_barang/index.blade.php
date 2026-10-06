@extends('layouts.app2')

@section('title')
    <title>Lokasi Barang</title>
@endsection

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-box2 icon-gradient bg-mean-fruit"></i>
                    </div>
                    <div>Lokasi Barang
                        <div class="page-title-subheading">
                            Merupakan lokasi untuk pengelompokan barang yang ada di sarana dan prasarana.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card card">
            <div class="card-header">
                <a href="{{ route('lokasi-barang.create') }}" class="btn btn-primary">Tambah Baru</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="mb-0 table table-hover table-striped" id="myTable2">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Unit</th>
                                <th>Lokasi Barang</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @forelse ($lokasi as $item)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->unit }}</td>
                                    <td>{{ $item->nama_lokasi_barang }}</td>
                                    <td class="d-flex">
                                        <a href="{{ route('lokasi-barang.edit', $item->id) }}"
                                            class="btn btn-sm btn-primary mx-1"><i class="pe-7s-note"
                                                style="font-size: 0.85rem;"></i></a>

                                        <form action="{{ route('lokasi-barang.destroy', $item->id) }}"
                                            method="POST" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-warning delete-button mx-1"><i
                                                    class="pe-7s-trash" style="font-size: 0.85rem;"></i></a></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <th colspan="5" class="text-center"> Belum Ada Data</th>
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
                    text: 'Apakah yakin akan dihapus?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya',
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
@endsection
