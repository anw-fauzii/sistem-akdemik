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
                    <!-- Tabel untuk desktop -->
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-hover table-striped mb-0" id="myTable2">
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
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach ($tagihan_spp as $tagihan)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $tagihan->nama_bulan }}</td>
                                        <td>Rp {{ number_format($spp, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($tagihan->total_biaya_makan + $tagihan->tambahan, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($tagihan->biaya_ekskul, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($tagihan->total_biaya_makan + $tagihan->biaya_ekskul + $tagihan->tambahan + $spp, 0, ',', '.') }}</td>
                                        <td>
                                            @if($tagihan->keterangan !== 'Lunas')
                                                <button class="btn btn-sm btn-primary pay-button" style="font-size: 0.75rem; padding: 2px 6px;" data-tagihan-id="{{ $tagihan->id }}">
                                                    <i class="pe-7s-cash"></i> Bayar
                                                </button>
                                            @else
                                                <span class="badge badge-pill badge-success text-white">Lunas</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Tampilan list/card untuk mobile -->
                    <div class="d-block d-md-none" >
                        @php $no = 1; @endphp
                        @foreach ($tagihan_spp as $tagihan)
                            @php
                                $total = $tagihan->total_biaya_makan + $tagihan->tambahan + $tagihan->biaya_ekskul + $spp;
                            @endphp
                            <div class="card shadow-sm mb-3 border-0" style="border-left: 4px solid {{ $tagihan->keterangan === 'Lunas' ? '#28a745' : '#ffc107' }}; background: aliceblue">
                                <div class="card-body py-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0">
                                            <i class="bi bi-calendar-check-fill text-primary me-1"></i>
                                            <strong>{{ $tagihan->nama_bulan }}</strong>
                                        </h6>
                                        <span class="badge badge-pill {{ $tagihan->keterangan === 'Lunas' ? 'bg-success text-white' : 'bg-warning text-dark' }}">
                                            {{ $tagihan->keterangan === 'Lunas' ? "Lunas" : "Belum Lunas"}} 
                                        </span>
                                    </div>

                                    <div class="row text-muted small">
                                        <div class="col-6">SPP</div>
                                        <div class="col-6 text-end">Rp {{ number_format($spp, 0, ',', '.') }}</div>
                                        <div class="col-6">Biaya Makan</div>
                                        <div class="col-6 text-end">Rp {{ number_format($tagihan->total_biaya_makan + $tagihan->tambahan, 0, ',', '.') }}</div>
                                        <div class="col-6">Ekstrakurikuler</div>
                                        <div class="col-6 text-end">Rp {{ number_format($tagihan->biaya_ekskul, 0, ',', '.') }}</div>
                                        <div class="col-6 fw-bold">Total</div>
                                        <div class="col-6 text-end fw-bold text-dark">Rp {{ number_format($total, 0, ',', '.') }}</div>
                                    </div>

                                    @if($tagihan->keterangan !== 'Lunas')
                                        <div class="mt-3 text-end">
                                            <button class="btn btn-sm btn-primary px-3 pay-button" data-tagihan-id="{{ $tagihan->id }}">
                                                <i class="pe-7s-cash"></i> Bayar Sekarang
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('services.midtrans.client_key') }}">
</script>
<script type="text/javascript">
    document.querySelectorAll('.pay-button').forEach(function(button) {
        button.addEventListener('click', function() {
            var tagihanId = this.getAttribute('data-tagihan-id');
            fetch('/keuangan-spp/bayar/' + tagihanId)
                .then(response => response.json())
                .then(data => {
                    const snapToken = data.snap_token;
                    if (snapToken) {
                        snap.pay(snapToken, {
                            onSuccess: function(result) {
                                toastr.success("Pembayaran berhasil!");
                                setTimeout(function() {
                                    window.location.href = "/keuangan-spp";
                                }, 1500); 
                            },
                            onPending: function(result) {
                                toastr.info("Pembayaran menunggu konfirmasi.");
                                setTimeout(function() {
                                    window.location.href = "/keuangan-spp";
                                }, 1500); 
                            },
                            onError: function(result) {
                                toastr.error("Pembayaran gagal!");
                                setTimeout(function() {
                                    window.location.href = "/keuangan-spp";
                                }, 1500); 
                            }
                        });
                    } else {
                        alert("Token pembayaran tidak valid.");
                    }
                })
                .catch(error => {
                    console.log(response.json());
                    alert("Terjadi kesalahan.");
                });
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> 
@endsection
