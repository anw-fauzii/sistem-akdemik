@extends('layouts.app2')

@section('title')
    <title>Ekstrakurikuler</title>
@endsection

@section('content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-cash icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Ekstrakurikuler
                    <div class="page-title-subheading">
                        Merupakan Bulan yang dilakukan pembayaran
                    </div>
                </div>
            </div>  
        </div> 
    </div>

    <div class="main-card card">
        <div class="card-header">
            <a href="{{route('ekstrakurikuler.create')}}" class="btn btn-primary">Tambah Baru</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="mb-0 table table-hover table-striped" id="myTable2">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Ekstrakurikuler</th>
                            <th>Guru Pendamping</th>
                            <th>Biaya</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1;
                        @endphp
                        @forelse ($ekstrakurikuler as $item)
                            <tr>
                                <td>{{$no++}}</td>
                                <td>{{$item->nama_ekstrakurikuler}}</td>
                                <td>{{$item->guru->nama_lengkap}}, {{$item->guru->gelar}}.</td>
                                <td>Rp. {{ number_format($item->biaya, 0, ',', '.') }}</td>
                                <td class="d-flex">
                                    <a href="{{ route('ekstrakurikuler.edit', $item->id) }}" class="btn btn-sm btn-primary mx-1"><i class="pe-7s-note" style="font-size: 1rem;"></i></a>
                                
                                    <form action="{{ route('ekstrakurikuler.destroy', $item->id) }}" method="POST" class="delete-form">
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
