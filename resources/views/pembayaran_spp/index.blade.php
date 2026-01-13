@extends('layouts.app2')

@section('title')
    <title>Pembayaran SPP</title>
@endsection

@section('content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-cash icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Pembayaran SPP
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
            <form action="{{ route('pembayaran-spp.cari') }}" method="POST">
                @csrf
                <div class="row align-items-center">
                    <div class="col-md-5 d-flex align-items-center">
                        <label class="mr-sm-2 text-nowrap" style="text-align: right;">Tahun Ajaran</label>
                        <select name="tahun_ajaran_id" class="multiselect-dropdown form-control @error('tahun_ajaran_id') is-invalid @enderror">
                            <option value="" selected disabled>-- Tahun Ajaran --</option>
                            @foreach ($tahun_ajaran as $item)
                                <option value="{{ $item->id }}" {{ isset($tahun_ajaran_id) && $tahun_ajaran_id == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama_tahun_ajaran }} - {{ $item->semester }}
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

    @if(isset($siswa) && isset($tagihan_spp))
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
                    Informasi Keuangan
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <td><strong>Tunggakan</strong></td>
                            <td>Rp {{ number_format($total_tunggakan, 0, ',', '.') }}</td>
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
        <div class="col-md-12 mt-4">
            <div class="main-card card">
                <div class="card-header">
                    Tagihan SPP
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="myTable2">
                            <thead>
                                <tr>
                                    <th>Bulan</th>
                                    <th>Nominal SPP</th>
                                    <th>Biaya Makan</th>
                                    <th>Snack</th>
                                    <th>Ekstrakurikuler</th>
                                    <th>Jemputan</th>
                                    <th>Total Pembayaran</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tagihan_spp as $tagihan)
                                    <tr>
                                        <td>{{ $tagihan->nama_bulan }}</td>

                                        <td>Rp {{ number_format($spp, 0, ',', '.') }}</td>

                                        <td>Rp {{ number_format($tagihan->total_biaya_makan + $tagihan->tambahan, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($tagihan->total_snack, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($tagihan->biaya_ekskul, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($tagihan->biaya_jemputan, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($tagihan->biaya_jemputan + $tagihan->total_snack + $tagihan->total_biaya_makan + $tagihan->biaya_ekskul + $tagihan->tambahan + $spp, 0, ',', '.') }}</td>

                                        <td>
                                            @if($tagihan->keterangan === 'LUNAS')
                                                <div class="badge badge-pill badge-warning">Lunas</div>
                                            @else
                                                <div class="badge badge-pill badge-danger">Belum Lunas</div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($tagihan->keterangan === 'LUNAS' && isset($tagihan->pembayaran_id))
                                                <form action="{{ route('pembayaran-spp.destroy', $tagihan->pembayaran_id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm delete-button">Hapus</button>
                                                </form>
                                            @else
                                                <!-- Tombol Bayar -->
                                                <form action="{{ route('pembayaran-spp.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="siswa_nis" value="{{ $siswa_nis }}">
                                                    <input type="hidden" name="tahun_ajaran_id" value="{{ $tahun_ajaran_id }}">
                                                    <input type="hidden" name="bulan_spp_id" value="{{ $tagihan->id }}">
                                                    <button type="submit" class="btn btn-success btn-sm submit-button">Bayar</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
