@extends('layouts.app2')

@section('title')
    <title>Pengumuman</title>
@endsection

@section('content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-info icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Pengumuman Kegiatan
                    <div class="page-title-subheading">
                        Merupakan semua pengumuman kegiatan yayasan dan unit
                    </div>
                </div>
            </div>  
        </div> 
    </div>

    <div class="main-card card">
        <div class="card-header">
            <a href="{{route('pengumuman.create')}}" class="btn btn-primary">Tambah Baru</a>
        </div>
        <div class="card-body">
            @forelse ($pengumuman as $item)
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title mb-1">{{ $item->judul }}</h5>
                                <p class="card-text">{!! Str::limit($item->isi, 150, '...') !!}</p>
                                <small class="text-muted">Tanggal: {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</small>
                            </div>
                            <div class="text-end">
                                <a href="{{ route('pengumuman.edit', $item->id) }}" class="btn btn-sm btn-warning me-2">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('pengumuman.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger delete-button">
                                        <i class="fas fa-trash-alt"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-info text-center">
                    Belum ada pengumuman yang tersedia.
                </div>
            @endforelse
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
