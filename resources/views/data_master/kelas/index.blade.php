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
                    <i class="pe-7s-rocket icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Kelas
                    <div class="page-title-subheading">
                        Daftar Kelas
                    </div>
                </div>
            </div>  
        </div> 
    </div>

    <div class="main-card card">
        <div class="card-header">
            <a href="{{route('kelas.create')}}" class="btn btn-primary">Tambah Baru</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="mb-0 table table-hover table-striped" id="myTable2">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenjang</th>
                            <th>Kelas</th>
                            <th>Wali Kelas</th>
                            <th>Guru Pendamping</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1;
                        @endphp
                        @forelse ($data_kelas as $item)
                            <tr>
                                <td>{{$no++}}</td>
                                <td>{{$item->jenjang}}</td>
                                <td>{{$item->nama_kelas}}</td>
                                <td>{{$item->guru->nama_lengkap}}, {{$item->guru->gelar}}.</td>
                                <td>{{$item->pendamping->nama_lengkap}}, {{$item->pendamping->gelar}}.</td>
                                <td class="d-flex">
                                    <a href="{{ route('kelas.show', $item->id) }}" class="btn btn-sm btn-success mx-1">{{$item->anggota_kelas_count}} Siswa</a>
                                    <a href="{{ route('kelas.edit', $item->id) }}" class="btn btn-sm btn-primary mx-1"><i class="pe-7s-note" style="font-size: 0.85rem;"></i></a>
                                    <form action="{{ route('kelas.destroy', $item->id) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-warning delete-button mx-1"><i class="pe-7s-trash" style="font-size: 0.85rem;"></i></a></button>
                                    </form>
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
