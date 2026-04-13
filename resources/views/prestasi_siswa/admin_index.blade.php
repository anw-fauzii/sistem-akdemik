@extends('layouts.app2')

@section('title')
    <title>Prestasi Siswa</title>
@endsection

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-star icon-gradient bg-mean-fruit"></i>
                    </div>
                    <div>Prestasi Siswa
                        <div class="page-title-subheading">
                            Menampilkan data prestasi siswa baik akademik maupun non-akademik.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card card">
            <div class="card-header">
                <a href="{{ route('prestasi-siswa.create') }}" class="btn btn-primary mr-2">
                    Tambah Prestasi
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="mb-0 table table-hover table-striped" id="myTable2">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Siswa</th>
                                <th>Prestasi</th>
                                <th>Kategori</th>
                                <th>Tingkat</th>
                                <th>Peringkat</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($prestasi as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ $item->anggotaKelas->map(function ($a) {
                                                return $a->siswa->nama_lengkap . ' (' . ($a->kelas->nama_kelas ?? '-') . ')';
                                            })->implode(', ') }}
                                    </td>
                                    <td>{{ $item->nama_prestasi }}</td>
                                    <td>
                                        <span class="badge badge-info">
                                            {{ ucfirst($item->kategori) }}
                                        </span>
                                    </td>
                                    <td>{{ $item->tingkat }}</td>
                                    <td>
                                        <span class="badge badge-success">
                                            {{ $item->peringkat }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $item->tanggal->format('d M Y') }}
                                    </td>
                                    <td class="d-flex">
                                        <a href="{{ route('prestasi-siswa.edit', $item->id) }}"
                                            class="btn btn-sm btn-primary mx-1">
                                            <i class="pe-7s-note" style="font-size: 0.85rem;"></i>
                                        </a>
                                        <a href="{{ route('prestasi-siswa.show', $item->id) }}"
                                            class="btn btn-sm btn-success mx-1">
                                            <i class="pe-7s-print" style="font-size: 0.85rem;"></i>
                                        </a>
                                        <form action="{{ route('prestasi-siswa.destroy', $item->id) }}" method="POST"
                                            class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-warning delete-button mx-1">
                                                <i class="pe-7s-trash" style="font-size: 0.85rem;"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">
                                        Belum Ada Data Prestasi
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
