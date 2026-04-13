@extends('layouts.app2')

@section('title')
    <title>Surat Izin</title>
@endsection

@section('content')
    <div class="app-main__inner">

        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-mail-open-file icon-gradient bg-mean-fruit"></i>
                    </div>
                    <div>Surat Izin Siswa
                        <div class="page-title-subheading">
                            Menampilkan data pengajuan izin siswa.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="mb-0 table table-hover table-striped" id="myTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Tanggal</th>
                                <th>Jenis</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($suratIzin as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td>
                                        {{ $item->anggotaKelas->siswa->nama_lengkap ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $item->anggotaKelas->kelas->nama_kelas ?? '-' }}
                                    </td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                    </td>

                                    <td>
                                        @if ($item->jenis == 'sakit')
                                            <span class="badge badge-danger">Sakit</span>
                                        @elseif($item->jenis == 'izin')
                                            <span class="badge badge-warning">Izin</span>
                                        @else
                                            <span class="badge badge-info">Lainnya</span>
                                        @endif
                                    </td>

                                    <td class="d-flex">
                                        <a href="{{ route('surat-izin.show', $item->id) }}"
                                            class="btn btn-sm btn-primary mx-1">
                                            <i class="pe-7s-look"></i>
                                        </a>

                                        <form action="{{ route('surat-izin.destroy', $item->id) }}" method="POST"
                                            class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-warning delete-button mx-1">
                                                <i class="pe-7s-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">
                                        Belum ada data surat izin
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    {{-- SweetAlert Delete --}}
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
