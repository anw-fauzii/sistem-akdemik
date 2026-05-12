@extends('layouts.app2')

@section('title')
    <title>Target Capaian T2Q</title>
@endsection

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title mb-4">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon shadow-sm bg-white">
                        <i class="pe-7s-flag text-success"></i>
                    </div>
                    <div>Target Capaian T2Q
                        <div class="page-title-subheading">
                            Mengatur target standar jilid dan hafalan yang harus dicapai siswa pada masing-masing tingkat.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card card border-0 shadow-sm">
            <div class="card-header">
                <a href="{{ route('target-capaian-tahsin.create') }}" class="btn btn-primary">
                    Tambah Target Baru
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="align-middle mb-0 table table-hover table-striped" id="myTable2">
                        <thead>
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th>Tahun Ajaran</th>
                                <th class="text-center">Tingkat</th>
                                <th>Target Jilid</th>
                                <th>Target Hafalan</th>
                                <th class="text-center" width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @forelse ($target as $item)
                                <tr>
                                    <td class="text-center text-muted">{{ $no++ }}</td>
                                    <td>
                                        <span class="font-weight-bold text-dark">
                                            {{ $item->tahunAjaran->nama_tahun_ajaran }} - {{ $item->tahunAjaran->semester }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-info px-3 py-2" style="font-size: 0.85rem;">Tingkat
                                            {{ $item->tingkat }}</span>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold text-success" style="font-size: 1.1rem;">
                                            {{ $item->daftarJilid->jilid_latin ?? '-' }}
                                        </div>
                                        <div class="small text-muted font-weight-bold">
                                            {{ $item->daftarJilid->jilid_arab ?? '' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold text-success" style="font-size: 1.1rem;">
                                            {{ $item->surahAlquran->nama_surah ?? '-' }}
                                        </div>
                                        <div class="small text-muted font-weight-bold">
                                            {{ $item->surahAlquran->nama_surah_arab ?? '' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center align-items-center">
                                            <a href="{{ route('target-capaian-tahsin.edit', $item->id) }}"
                                                class="btn btn-sm btn-primary mx-1" title="Edit">
                                                <i class="pe-7s-note" style="font-size: 1rem;"></i>
                                            </a>

                                            <form action="{{ route('target-capaian-tahsin.destroy', $item->id) }}"
                                                method="POST" class="delete-form m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-warning delete-button mx-1"
                                                    title="Hapus">
                                                    <i class="pe-7s-trash" style="font-size: 1rem;"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="pe-7s-info d-block mb-2" style="font-size: 2rem;"></i>
                                        Belum ada data target capaian yang ditambahkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.delete-button').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Konfirmasi Hapus',
                        text: 'Apakah Anda yakin ingin menghapus target capaian ini?',
                        icon: 'warning',
                        showCancelButton: true,
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
        });
    </script>
@endsection
