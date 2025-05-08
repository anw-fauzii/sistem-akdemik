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
                <div>Pembayaran SPP {{$tahunAjaran->nama_tahun_ajaran}}-{{$tahunAjaran->semester}}
                    <div class="page-title-subheading">
                        Merupakan Pembayaran yang dilakukan pembayaran
                    </div>
                </div>
            </div>  
        </div> 
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="main-card card">
                <div class="card-header">
                    <button class="btn btn-primary dropdown" type="button" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="metismenu-icon pe-7s-refresh-2"></i> TAHUN AJARAN
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                            @foreach($tahun_selama_belajar as $item)
                            <li>
                                <a href="{{ route('keuangan-spp.show', $item->tahun_ajaran_id) }}" class="dropdown-item">
                                    {{ $item->tahun_ajaran->nama_tahun_ajaran }}-{{$item->tahun_ajaran->semester}}
                                </a>
                            </li>
                            @endforeach
                        </ul> 
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="mb-0 table table-hover table-striped" id="myTable2">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Bulan</th>
                                    <th>Nominal SPP</th>
                                    <th>Biaya Makan</th>
                                    <th>Ekstrakurikuler</th>
                                    <th>Total Pembayaran</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            @php
                                $no = 1;
                            @endphp
                            <tbody>
                                @foreach ($tagihan_spp as $tagihan)
                                    <tr>
                                        <td>{{$no++}}</td>
                                        <td>{{ $tagihan->nama_bulan }}</td>
                                        <td>Rp {{ number_format($spp, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($tagihan->total_biaya_makan + $tagihan->tambahan, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($tagihan->biaya_ekskul, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($tagihan->total_biaya_makan + $tagihan->biaya_ekskul + $tagihan->tambahan + $spp, 0, ',', '.') }}</td>
                                        <td>
                                            @if($tagihan->keterangan !== 'Lunas')
                                                <button class="btn btn-sm btn-primary pay-button" data-tagihan-id="{{ $tagihan->id }}" style="font-size: 0.75rem; padding: 2px 6px;">
                                                    <i class="pe-7s-cash" style="font-size: 0.85rem;"></i> Bayar
                                                </button>
                                            @else
                                                <div class="badge badge-pill badge-warning">Lunas</div>
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
</div>
<script type="text/javascript">
    document.querySelectorAll('.pay-button').forEach(function(button) {
        button.addEventListener('click', function() {
            var tagihanId = this.getAttribute('data-tagihan-id');
            fetch('/keuangan-spp/' + tagihanId)
                .then(response => response.json())
                .then(data => {
                    const snapToken = data.snap_token;
                    if (snapToken) {
                        snap.pay(snapToken, {
                            onSuccess: function(result) {
                                toastr.success("Pembayaran berhasil!");
                                setTimeout(function() {
                                    window.location.href = "/keuangan";
                                }, 1500); 
                            },
                            onPending: function(result) {
                                toastr.info("Pembayaran menunggu konfirmasi.");
                                setTimeout(function() {
                                    window.location.href = "/keuangan";
                                }, 1500); 
                            },
                            onError: function(result) {
                                toastr.error("Pembayaran gagal!");
                                setTimeout(function() {
                                    window.location.href = "/keuangan";
                                }, 1500); 
                            }
                        });
                    } else {
                        alert("Token pembayaran tidak valid.");
                    }
                })
                .catch(error => {
                    console.error("Error fetching Snap Token:", error);
                    alert("Terjadi kesalahan.");
                });
        });
    });
</script>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}">
    </script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> 
@endsection
