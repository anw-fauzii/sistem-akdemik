@extends('layouts.app2')

@section('title')
    <title>Pembayaran Tahunan</title>
@endsection

@section('content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-cash icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Pembayaran Tahunan
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
            <form action="{{ route('pembayaran-tagihan-tahunan.cari') }}" method="POST">
                @csrf
                <div class="row align-items-center">
                    <div class="col-md-5 d-flex align-items-center">
                        <label class="mr-sm-2 text-nowrap" style="text-align: right;">Tahun Ajaran</label>
                        <select name="tahun_ajaran_id" class="multiselect-dropdown form-control @error('tahun_ajaran_id') is-invalid @enderror">
                            <option value="" selected disabled>-- Tahun Ajaran --</option>
                            @foreach ($tahun_ajaran as $item)
                                <option value="{{ $item->id }}" {{ isset($tahun_ajaran_id) && $tahun_ajaran_id == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama_tahun_ajaran }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5 d-flex align-items-center">
                        <label class="mr-sm-2 text-nowrap" style="text-align: right;">Siswa</label>
                        <select name="siswa_nis" class="multiselect-dropdown form-control @error('siswa_nis') is-invalid @enderror">
                            <option value="" selected disabled>-- Pilih Siswa --</option>
                            @foreach ($siswa_list as $item)
                                <option value="{{ $item->nis }}" {{ isset($siswa_nis) && $siswa_nis == $item->nis ? 'selected' : '' }}>
                                    {{ $item->nis }} - {{ $item->nama_lengkap }}
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

    @if(isset($siswa))
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="main-card card">
                    <div class="card-header">
                        Informasi Siswa
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <td><strong>NIS</strong></td>
                                <td>{{ $siswa->nis }}</td>
                            </tr>
                            <tr>
                                <td><strong>Nama</strong></td>
                                <td>{{ $siswa->nama_lengkap }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kelas</strong></td>
                                <td>{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="main-card card">
                    <div class="card-header">
                        Tagihan Tahunan
                    </div>
                    <div class="card-body">
                        <table class="table table-hover table-striped" id="myTable2">
                            <thead>
                                <tr>
                                    <th>Jenis Tagihan</th>
                                    <th>Total Tagihan</th>
                                    <th>Total Dibayar</th>
                                    <th>Sisa Tagihan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($hasil_tagihan) && count($hasil_tagihan))
                                    @foreach ($hasil_tagihan as $item)
                                        <tr>
                                            <td>{{ $item['jenis'] }}</td>
                                            <td>Rp {{ number_format($item['total_tagihan'], 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($item['total_dibayar'], 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($item['sisa_tagihan'], 0, ',', '.') }}</td>
                                            <td>
                                                <span class="badge {{ $item['status'] == 'Lunas' ? 'bg-success' : 'bg-warning' }}">
                                                    {{ $item['status'] }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($item['status'] != 'Lunas')
                                                    <!-- Tombol buka modal -->
                                                    <button type="button" onclick="showCreateModal('modalBayar{{ $loop->index }}')" class="btn btn-sm btn-primary">
                                                        Bayar
                                                    </button>
                                                    @include('pembayaran_tagihan_tahunan.modal')
                                                @else
                                                    <span class="text-muted">Selesai</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
<script>
    document.querySelectorAll('.submit-button').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah yakin akan untuk dilunasi?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#dd3333",
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
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
