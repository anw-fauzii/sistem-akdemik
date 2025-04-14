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
                <div class="card mb-3">
                    <div class="card-body">
                        <h5>{{ $item->judul }}</h5>
                        <p>{{ $item->isi }}</p>
                        <small>Tanggal: {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</small>
                        <a href="{{ route('pengumuman.edit', $item->id) }}" class="btn btn-warning btn-sm mt-2">Edit</a>
                        <form action="{{ route('pengumuman.destroy', $item->id) }}" method="POST" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm delete-button">Hapus</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="alert alert-info">
                    Belum ada pengumuman.
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
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
            }).then((result) => {
                if (result.isConfirmed) {
                    this.closest('form').submit();
                }
            });
        });
    });
</script>
@endsection
