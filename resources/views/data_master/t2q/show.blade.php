@extends('layouts.app2')

@section('title')
    <title>Kelompok T2Q</title>
@endsection

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-rocket icon-gradient bg-mean-fruit"></i>
                    </div>
                    <div>Kelompok T2Q {{ $guru->nama_lengkap }}{{ $guru->gelar ? ', ' . $guru->gelar : '' }}
                        <div class="page-title-subheading">
                            Daftar Anggota Siswa Kelompok T2Q
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-primary" onclick="showCreateModal()">
                    <i class="pe-7s-plus mr-1"></i> TAMBAH SISWA
                </button>
                @include('data_master.t2q.modalCreate')
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="mb-0 table table-hover table-striped w-100" id="myTable2">
                        <thead>
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th>Nama Siswa</th>
                                <th class="text-center" width="15%">Kelas</th>
                                <th class="text-center" width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($anggota_t2q as $item)
                                <tr>
                                    <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                    <td class="align-middle">{{ $item->anggotaKelas->siswa->nama_lengkap ?? '-' }}</td>
                                    <td class="text-center align-middle">{{ $item->anggotaKelas->kelas->nama_kelas ?? '-' }}
                                    </td>
                                    <td class="text-center align-middle">
                                        <form action="{{ route('anggota-t2q.destroy', $item->id) }}" method="POST"
                                            class="delete-form d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger delete-button"
                                                title="Hapus dari T2Q">
                                                <i class="pe-7s-trash" style="font-size: 1rem;"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="pe-7s-info fa-2x mb-2 d-block"></i>
                                        Belum ada siswa di kelompok ini.
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
