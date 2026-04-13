@extends('layouts.app2')

@section('title')
    <title>Surat Izin</title>
@endsection

@section('content')
    <div class="app-main__inner">

        <div class="app-page-title">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-mail-open-file icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Surat Izin
                    <div class="page-title-subheading">
                        Riwayat pengajuan izin siswa.
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card card">
            <div class="card-header">
                <a href="{{ route('surat-izin.create') }}" class="btn btn-primary">
                    Ajukan Izin
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="myTable2">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Jenis</th>
                                <th>Keterangan</th>
                                <th>File</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($suratIzin as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->tanggal->format('d M Y') }}</td>

                                    <td>
                                        <span class="badge badge-info">
                                            {{ ucfirst($item->jenis) }}
                                        </span>
                                    </td>

                                    <td>{{ $item->keterangan ?? '-' }}</td>

                                    <td>
                                        @if ($item->file)
                                            <a href="{{ asset('storage/' . $item->file) }}" target="_blank">
                                                Lihat File
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td class="d-flex">
                                        @if ($item->tanggal >= now()->toDateString())
                                            <a href="{{ route('surat-izin.edit', $item->id) }}"
                                                class="btn btn-sm btn-primary mx-1">
                                                <i class="pe-7s-note"></i>
                                            </a>
                                        @else
                                            <button class="btn btn-sm btn-secondary mx-1" disabled>
                                                <i class="pe-7s-note"></i>
                                            </button>
                                        @endif

                                        <form action="{{ route('surat-izin.destroy', $item->id) }}" method="POST"
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
                                    <td colspan="6" class="text-center">
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
