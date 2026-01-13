@extends('layouts.app2')

@section('title')
    <title>Kelas</title>
@endsection

@section('content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-portfolio icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Administrasi Kelas
                    <div class="page-title-subheading">
                        Daftar administrasi kelas yang telah diupload
                    </div>
                </div>
            </div>  
        </div> 
    </div>

    <div class="main-card card">
        <div class="card-header">
            <a href="{{route('administrasi-kelas.create')}}" class="btn btn-primary">Tambah Baru</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="mb-0 table table-hover table-striped" id="myTable2">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kategori</th>
                            <th>Total File</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1;
                        @endphp
                        @forelse ($kategori as $item)
                        
                            @php
                                $files = $item->administrasi_kelas;
                                $sudah = $item->administrasi_kelas->where('status', 1)->count();
                                $belum = $item->administrasi_kelas->where('status', 0)->count();
                                $semester1 = $files->filter(fn($f) => str_contains($f->link, 'Semester 1'));
                                $semester2 = $files->filter(fn($f) => str_contains($f->link, 'Semester 2'));
                            @endphp
                            @include('administrasi.kelas.modalCreate')
                            <tr>
                                <td>{{$no++}}</td>
                                <td>{{$item->nama_kategori}}</td>
                                <td>{{$files->count()}} Files</td>
                                <td>
                                    @if($files->count() == 0)
                                        -
                                    @elseif($sudah == $files->count())
                                        <div class="badge badge-sm badge-pill badge-success">Semua Diperiksa</div>
                                    @elseif($belum == $files->count())
                                        <div class="badge badge-sm badge-pill badge-warning">Belum Diperiksa Semua</div>
                                    @else
                                        <div class="badge badge-sm badge-pill badge-success">{{$sudah}} Diperiksa</div>,
                                        <div class="badge badge-sm badge-pill badge-warning">{{$belum}} Belum</div>
                                    @endif
                                </td>
                                <td class="d-flex">
                                    <button type="button" class="btn btn-sm btn-primary mx-1" onclick="showCreateModal({{$item->id}})"><i class="pe-7s-info" style="font-size: 0.85rem;"></i></button>
                                    {{-- <form action="{{ route('administrasi-guru.destroy', $item->id) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-warning delete-button mx-1"><i class="pe-7s-trash" style="font-size: 0.85rem;"></i></a></button>
                                    </form> --}}
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
<style>
    .loader-overlay {
        position: fixed;
        top: 0; 
        left: 0;
        width: 100%; 
        height: 100%;
        background: rgba(2, 0, 15, 0.8);
        z-index: 9999;
        display: flex;
        align-items: center; 
        justify-content: center; 
    }

    .loader-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .loader {
        border: 5px solid #f3f3f3;
        border-top: 5px solid #007bff;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        animation: spin 0.8s linear infinite;
    }

    .loader-text {
        margin-top: 12px;
        font-size: 1rem;
        color: #ffffff;
        font-weight: 600;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
<script>
    const overlay = document.getElementById("loadingOverlay");
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
                    overlay.classList.remove("d-none");
                    this.closest('form').submit();
                }
            });
        });
    });
    overlay.classList.add("d-none");
</script>
@endsection
