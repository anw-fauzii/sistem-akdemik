@extends('layouts.app2')

@section('title')
    <title>Pembayaran Jemputan</title>
@endsection

@section('content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-cash icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Pembayaran Jemputan
                    <div class="page-title-subheading">
                        Merupakan Pembayaran yang dilakukan pembayaran
                    </div>
                </div>
            </div>  
        </div> 
    </div>

    <div class="main-card card">
        <div class="card-header">
            Cek data pembayaran
        </div>
        <div class="card-body">
            <form action="{{ route('pembayaran-jemputan.cari') }}" method="POST">
                @csrf
                <div class="row align-items-center">
                    <div class="col-md-10 d-flex align-items-center">
                        <select name="jemputan_id" class="multiselect-dropdown form-control @error('jemputan_id') is-invalid @enderror">
                            <option value="" selected disabled>-- Pilih Driver --</option>
                            @foreach ($jemputan as $item)
                                <option value="{{ $item->id }}" {{ isset($jemputan_id) && $jemputan_id == $item->id ? 'selected' : '' }}>
                                    {{ $item->driver }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(isset($jemputan_id))
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="main-card card">
                <div class="card-header">
                    Tagihan SPP
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <form action="{{ route('pembayaran-jemputan.store') }}" method="POST">
                        @csrf
                            <table class="table table-hover table-striped" id="myTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Siswa</th>
                                        <th>Jumlah Bayar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($siswa as $s)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{ $s->anggotaKelas->siswa->nama_lengkap ?? '-' }}</td>
                                            <td>
                                                @php
                                                    $pembayaran = optional($s->pembayaranBulan->first())->jumlah_bayar ?? 0;
                                                @endphp

                                                <input type="text" 
                                                    name="pembayaran[{{ $s->id }}]" 
                                                    class="form-control autonumeric" 
                                                    value="{{ $pembayaran  }}" 
                                                    placeholder="Rp">

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td class="text-center" colspan="3">
                                            <button class="btn btn-success">Simpan Pembayaran</button>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
<script src="https://cdn.jsdelivr.net/npm/autonumeric@4.5.4/dist/autoNumeric.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        AutoNumeric.multiple('.autonumeric', {
            digitGroupSeparator: '.',
            decimalCharacter: ',',
            decimalPlaces: 0,
            minimumValue: '0'
        });
    });
    document.querySelectorAll('.submit-button').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah yakin akan untuk dilunasi?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                buttonsStyling: false, // Mematikan styling default
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
                buttonsStyling: false, // Mematikan styling default
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
