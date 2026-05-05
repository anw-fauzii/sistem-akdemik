@extends('layouts.app2')

@section('title')
    <title>Kedisiplinan Siswa</title>
@endsection

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-attention icon-gradient bg-mean-fruit"></i>
                    </div>
                    <div>Kedisiplinan Siswa
                        <div class="page-title-subheading">
                            Menampilkan riwayat poin pelanggaran dan prestasi kedisiplinan siswa.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card card">
            <div class="card-header">
                <a href="{{ route('kedisiplinan-siswa.create') }}" class="btn btn-primary mr-2">
                    Tambah Catatan Kedisiplinan
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="mb-0 table table-hover table-striped" id="myTable2">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Siswa</th>
                                <th>Aturan (Kejadian)</th>
                                <th>Kategori</th>
                                <th>Poin</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($riwayatSiswa as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($item->tanggal_kejadian)->translatedFormat('d M Y') }}
                                    </td>
                                    <td>
                                        {{-- Menampilkan nama siswa dan kelasnya (jika ada relasi kelas) --}}
                                        {{ $item->anggotaKelas->siswa->nama_lengkap ?? 'Nama Tidak Ditemukan' }}
                                        @if (isset($item->anggotaKelas->kelas))
                                            <br><small class="text-muted">Kelas:
                                                {{ $item->anggotaKelas->kelas->nama_kelas }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $item->kedisiplinanPoin->nama_aturan ?? '-' }}
                                        @if ($item->keterangan)
                                            <br><small class="text-muted">{{ $item->keterangan }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if (strtolower($item->kedisiplinanPoin->kategori ?? '') == 'pelanggaran')
                                            <span class="badge badge-danger">Pelanggaran</span>
                                        @else
                                            <span class="badge badge-success">Prestasi</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $item->kedisiplinanPoin->poin ?? 0 }}</strong>
                                    </td>
                                    <td class="d-flex">
                                        <a href="{{ route('kedisiplinan-siswa.edit', $item->id) }}"
                                            class="btn btn-sm btn-primary mx-1">
                                            <i class="pe-7s-note" style="font-size: 0.85rem;"></i>
                                        </a>

                                        <form action="{{ route('kedisiplinan-siswa.destroy', $item->id) }}" method="POST"
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
                                        Belum Ada Data Kedisiplinan Siswa
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
                    text: 'Apakah yakin riwayat ini akan dihapus?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
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
