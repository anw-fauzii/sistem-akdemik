@extends('layouts.app2')

@section('title')
    <title>Jenjang Pendidikan</title>
@endsection

@section('content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-plugin icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Jenjang Pendidikan
                    <div class="page-title-subheading">
                        Merupakan kategori untuk jenis Jenjang Pendidikan
                    </div>
                </div>
            </div>  
        </div> 
    </div>

    <div class="main-card card">
        <div class="card-header">
            <a href="{{route('jenjang-pendidikan.create')}}" class="btn btn-primary">Tambah Baru</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="mb-0 table table-hover table-striped" id="myTable2">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tahun Ajaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1;
                        @endphp
                        @forelse ($pendidikan as $item)
                            <tr>
                                <td>{{$no++}}</td>
                                <td>{{$item->nama_jenjang_pendidikan}}</td>
                                <td class="d-flex">
                                    <a href="{{ route('jenjang-pendidikan.edit', $item->id) }}" class="btn btn-sm btn-primary mx-1"><i class="pe-7s-note" style="font-size: 1rem;"></i></a>
                                
                                    <form action="{{ route('jenjang-pendidikan.destroy', $item->id) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-warning delete-button mx-1"><i class="pe-7s-trash" style="font-size: 1rem;"></i></a></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <th colspan="4" class="text-center"> Belum Ada Data</th>
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
