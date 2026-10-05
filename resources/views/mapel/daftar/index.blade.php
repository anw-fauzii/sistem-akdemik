@extends('layouts.app2')

@section('title')
    <title>Daftar Mata Pelajaran</title>
@endsection

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-science icon-gradient bg-mean-fruit"></i>
                    </div>
                    <div>Daftar Mata Pelajaran
                        <div class="page-title-subheading">
                            Manajemen data mata pelajaran berdasarkan tahun ajaran dan kategori.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card card">
            <div class="card-header">
                <a href="{{ route('daftar-mata-pelajaran.create') }}" class="btn btn-primary mr-2">Tambah Baru</a>
                <form action="{{ route('daftar-mata-pelajaran.import-previous') }}" method="POST"
                    id="form-import-previous">
                    @csrf
                    <button type="button" class="btn btn-info text-white" id="btn-import-previous">
                        <i class="pe-7s-copy-file"></i> SALIN DATA
                    </button>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="mb-0 table table-hover table-striped" id="myTable2">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tahun Ajaran</th>
                                <th>Kategori</th>
                                <th>Nama Mata Pelajaran</th>
                                <th>Ringkasan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @forelse ($mataPelajaran as $item)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    {{-- Sesuaikan 'nama_tahun' dan 'kategori' dengan nama field di tabel aslinya --}}
                                    <td>{{ $item->tahunAjaran->nama_tahun_ajaran }}-{{ $item->tahunAjaran->semester }}</td>
                                    <td>{{ $item->kategori->kategori ?? '-' }}</td>
                                    <td>{{ $item->nama_mapel }}</td>
                                    <td>{{ $item->ringkasan_mapel ?? '-' }}</td>
                                    <td class="d-flex">
                                        <a href="{{ route('daftar-mata-pelajaran.edit', $item->id) }}"
                                            class="btn btn-sm btn-primary mx-1"><i class="pe-7s-note"
                                                style="font-size: 0.85rem;"></i></a>

                                        <form action="{{ route('daftar-mata-pelajaran.destroy', $item->id) }}"
                                            method="POST" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-warning delete-button mx-1"><i
                                                    class="pe-7s-trash" style="font-size: 0.85rem;"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <th colspan="6" class="text-center"> Belum Ada Data</th>
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
        document.getElementById('btn-import-previous').addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Salin Data Mapel?',
                text: 'Tindakan ini akan menyalin semua mata pelajaran dari semester sebelumnya ke semester yang baru aktif. Lanjutkan?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Salin Sekarang',
                cancelButtonText: 'Batal',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn-swal-confirm',
                    cancelButton: 'btn-swal-cancel'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    let btn = document.getElementById('btn-import-previous');
                    btn.innerHTML =
                        `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyalin...`;
                    btn.disabled = true;

                    document.getElementById('form-import-previous').submit();
                }
            });
        });
    </script>
@endsection
