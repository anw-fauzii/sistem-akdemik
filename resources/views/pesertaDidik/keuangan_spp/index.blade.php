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
    <div class="row">
        <div class="col-md-12">
            <div class="mb-2 card">
                    <ul class="tabs-animated nav nav-justified flex-column flex-md-row">
                        <li class="nav-item">
                            <a role="tab" class="nav-link active" id="tab-0" data-toggle="tab" href="#spp">
                                <span>Pembayaran</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#riwayat">
                                <span>Riwayat Pembayaran</span>
                            </a>
                        </li>
                    </ul>
            </div>
        </div>
    </div>

    <div class="tab-content">
        <div class="tab-pane tabs-animation fade show active" id="spp" role="tabpanel">
            <div class="row">
                <div class="col-md-12">
                    <div class="main-card card">
                        <div class="card-header">
                            <button class="btn btn-primary dropdown" type="button" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="metismenu-icon pe-7s-refresh-2"></i> TAHUN AJARAN
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                    @foreach($tahun_selama_belajar as $item)
                                        @if(optional($item->kelas)->tahun_ajaran)
                                            <li>
                                                <a href="{{ route('keuangan-spp.show', $item->kelas->tahun_ajaran_id) }}" class="dropdown-item">
                                                    {{ $item->kelas->tahun_ajaran->nama_tahun_ajaran }}-{{$item->kelas->tahun_ajaran->semester}}
                                                </a>
                                            </li>
                                        @endif
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
                                            <th>Makan</th>
                                            <th>Snack</th>
                                            <th>Ekstrakurikuler</th>
                                            <th>Jemputan</th>
                                            <th>Total Pembayaran</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            if($tagihan_spp->count() === 6){
                                                $filteredTagihan = $tagihan_spp;
                                            }else{
                                                $filteredTagihan = $tagihan_spp->slice(0, count($tagihan_spp) - 1);
                                            }
                                            
                                            $no = 1;
                                        @endphp
                                        @foreach ($filteredTagihan as $tagihan)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $tagihan->nama_bulan }}</td>
                                                <td>Rp {{ number_format($spp, 0, ',', '.') }}</td>
                                                <td>Rp {{ number_format($tagihan->total_biaya_makan + $tagihan->tambahan, 0, ',', '.') }}</td>
                                                <td>Rp {{ number_format($tagihan->total_snack, 0, ',', '.') }}</td>
                                                <td>Rp {{ number_format($tagihan->biaya_ekskul, 0, ',', '.') }}</td>
                                                <td>Rp {{ number_format($tagihan->biaya_jemputan, 0, ',', '.') }}</td>
                                                <td>Rp {{ number_format($tagihan->total_snack + $tagihan->biaya_jemputan + $tagihan->total_biaya_makan + $tagihan->biaya_ekskul + $tagihan->tambahan + $spp, 0, ',', '.') }}</td>
                                                <td>
                                                    @if($tagihan->keterangan !== 'LUNAS')
                                                        <button class="btn btn-sm btn-primary pay-button" style="font-size: 0.75rem; padding: 2px 6px;" data-tagihan-id="{{ $tagihan->id }}">
                                                            <i class="pe-7s-cash"></i> Bayar
                                                        </button>
                                                    @else
                                                        <span class="badge badge-pill badge-success text-white">LUNAS</span>
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
                                        $total = $tagihan->total_biaya_makan + $tagihan->tambahan + $tagihan->biaya_ekskul + $spp + $tagihan->total_snack + $tagihan->biaya_jemputan;
                                    @endphp
                                    <div class="card shadow-sm mb-3 border-0" style="border-left: 4px solid {{ $tagihan->keterangan === 'Lunas' ? '#28a745' : '#ffc107' }}; background: aliceblue">
                                        <div class="card-body py-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="mb-0">
                                                    <i class="bi bi-calendar-check-fill text-primary me-1"></i>
                                                    <strong>{{ $tagihan->nama_bulan }}</strong>
                                                </h6>
                                                <span class="badge badge-pill {{ $tagihan->keterangan === 'LUNAS' ? 'bg-success text-white' : 'bg-warning text-dark' }}">
                                                    {{ $tagihan->keterangan === 'LUNAS' ? "LUNAS" : "BELUM LUNAS"}} 
                                                </span>
                                            </div>

                                            <div class="row text-muted small">
                                                <div class="col-6">SPP</div>
                                                <div class="col-6 text-end">Rp {{ number_format($spp, 0, ',', '.') }}</div>
                                                <div class="col-6">Biaya Makan</div>
                                                <div class="col-6 text-end">Rp {{ number_format($tagihan->total_biaya_makan + $tagihan->tambahan, 0, ',', '.') }}</div>
                                                <div class="col-6">Snack</div>
                                                <div class="col-6 text-end">Rp {{ number_format($tagihan->total_snack, 0, ',', '.') }}</div>
                                                <div class="col-6">Ekstrakurikuler</div>
                                                <div class="col-6 text-end">Rp {{ number_format($tagihan->biaya_ekskul, 0, ',', '.') }}</div>
                                                <div class="col-6">Jemputan</div>
                                                <div class="col-6 text-end">Rp {{ number_format($tagihan->biaya_jemputan, 0, ',', '.') }}</div>
                                                <div class="col-6 fw-bold">Total</div>
                                                <div class="col-6 text-end fw-bold text-dark">Rp {{ number_format($total, 0, ',', '.') }}</div>
                                            </div>

                                            @if($tagihan->keterangan !== 'LUNAS')
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
        <div class="tab-pane tabs-animation fade" id="riwayat" role="tabpanel">
            <div class="row">
                <div class="col-md-12">
                    <div class="main-card card">
                        <div class="card-header">
                            <button class="btn btn-primary dropdown" type="button" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="metismenu-icon pe-7s-refresh-2"></i> TAHUN AJARAN
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                    @foreach($tahun_selama_belajar as $item)
                                        @if(optional($item->kelas)->tahun_ajaran)
                                            <li>
                                                <a href="{{ route('keuangan-spp.show', $item->kelas->tahun_ajaran_id) }}" class="dropdown-item">
                                                    {{ $item->kelas->tahun_ajaran->nama_tahun_ajaran }}-{{$item->kelas->tahun_ajaran->semester}}
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul> 
                        </div>
                        <div class="card-body">
                            <div class="table-responsive d-none d-md-block">
                                <table class="table table-hover table-striped mb-0" id="myTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Invoice</th>
                                            <th>Bulan</th>
                                            <th>SPP</th>
                                            <th>Makan</th>
                                            <th>Ekstrakurikuler</th>
                                            <th>Total Pembayaran</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no = 1; @endphp
                                        @foreach ($riwayat_pembayaran as $tagihan)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $tagihan->bulanSpp->nama_bulan}}</td>
                                                <td>{{ $tagihan->order_id }}</td>
                                                <td>Rp {{ number_format($tagihan->nominal_spp, 0, ',', '.') }}</td>
                                                <td>Rp {{ number_format($tagihan->biaya_makan, 0, ',', '.') }}</td>
                                                <td>Rp {{ number_format($tagihan->ekstrakurikuler, 0, ',', '.') }}</td>
                                                <td>Rp {{ number_format($tagihan->total_pembayaran, 0, ',', '.') }}</td>
                                                <td>
                                                    <a href="{{ route('keuangan-spp.invoice', $tagihan->order_id) }}" class="btn btn-sm btn-primary mx-1"><i class="pe-7s-print" style="font-size: 0.85rem;"></i></a>
                                                    @if($tagihan->keterangan == 'PENDING')
                                                    <button class="btn btn-sm btn-primary lanjut-bayar-button"
                                                        data-id="{{ $tagihan->id }}"
                                                        style="font-size: 0.75rem; padding: 2px 6px;">
                                                        <i class="pe-7s-cash" style="font-size: 0.85rem;"></i> Bayar
                                                    </button>
                                                    @elseif($tagihan->keterangan == 'LUNAS')
                                                        <div class="badge badge-pill badge-success text-white">LUNAS</div>
                                                    @else
                                                        <div class="badge badge-pill badge-danger text-white">KEDALUARSA</div>
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
                                    <div class="card shadow-sm mb-3 border-0" style="border-left: 4px solid {{ $tagihan->keterangan === 'LUNAS' ? '#28a745' : '#ffc107' }}; background: aliceblue">
                                        <div class="card-body py-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="mb-0">
                                                    <i class="bi bi-calendar-check-fill text-primary me-1"></i>
                                                    <strong>{{ $tagihan->nama_bulan }}</strong>
                                                </h6>
                                                <span class="badge badge-pill {{ $tagihan->keterangan === 'LUNAS' ? 'bg-success text-white' : 'bg-warning text-dark' }}">
                                                    {{ $tagihan->keterangan === 'LUNAS' ? "LUNAS" : "BELUM LUNAS"}} 
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

                                            @if($tagihan->keterangan !== 'LUNAS')
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
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('services.midtrans.client_key') }}">
</script>
<script type="text/javascript">
    document.querySelectorAll('.pay-button').forEach(function(button) {
        button.addEventListener('click', function () {
            const $btn = $(this);
            const originalHtml = $btn.html();
            $btn.prop('disabled', true);
            $btn.html('<span class="spinner-border spinner-border-sm"></span> Memproses...');

            const tagihanId = this.getAttribute('data-tagihan-id');
            fetch('/keuangan-spp/bayar/' + tagihanId)
                .then(response => response.json())
                .then(data => {
                    const snapToken = data.snap_token;
                    if (snapToken) {
                        snap.pay(snapToken, {
                            onSuccess: function(result) {
                                toastr.success("Pembayaran berhasil!");
                                setTimeout(function () {
                                    window.location.href = "/keuangan-spp";
                                }, 1500);
                            },
                            onPending: function(result) {
                                toastr.info("Pembayaran menunggu konfirmasi.");
                                setTimeout(function () {
                                    window.location.href = "/keuangan-spp";
                                }, 1500);
                            },
                            onError: function(result) {
                                toastr.error("Pembayaran gagal!");
                                $btn.prop('disabled', false).html(originalHtml); 
                                setTimeout(function () {
                                    window.location.href = "/keuangan-spp";
                                }, 1500);
                            },
                            onClose: function() {
                                toastr.warning("Pembayaran dibatalkan.");
                                $btn.prop('disabled', false).html(originalHtml); 
                                setTimeout(function () {
                                    window.location.href = "/keuangan-spp";
                                }, 1500);
                            }
                        });
                    } else {
                        toastr.error("Selesaikan terlebih dahulu pembayaran sebelumnya!");
                        $btn.prop('disabled', false).html(originalHtml); 
                    }
                })
                .catch(error => {
                    console.error(error);
                    alert("Terjadi kesalahan.");
                    $btn.prop('disabled', false).html(originalHtml); 
                });
        });
    });

    document.querySelectorAll('.lanjut-bayar-button').forEach(function(button) {
        button.addEventListener('click', function() {
            const $btn = $(this);
            const originalHtml = $btn.html();
            $btn.prop('disabled', true);
            $btn.html('<span class="spinner-border spinner-border-sm"></span> Memproses...');
            var tagihanId = this.getAttribute('data-id');
            fetch('/lanjut-pembayaran-spp/' + tagihanId)
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
                                $btn.prop('disabled', false).html(originalHtml); 
                            },
                            onPending: function(result) {
                                toastr.info("Pembayaran menunggu konfirmasi.");
                                setTimeout(function() {
                                    window.location.href = "/keuangan-spp";
                                }, 1500); 
                                $btn.prop('disabled', false).html(originalHtml); 
                            },
                            onError: function(result) {
                                toastr.error("Pembayaran gagal!");
                                setTimeout(function() {
                                    window.location.href = "/keuangan-spp";
                                }, 1500); 
                                $btn.prop('disabled', false).html(originalHtml); 
                            },
                            onClose: function() {
                                toastr.warning("Pembayaran dibatalkan.");
                                $btn.prop('disabled', false).html(originalHtml); 
                            }
                        });
                    } else {
                        alert("Token pembayaran tidak valid.");
                        $btn.prop('disabled', false).html(originalHtml); 
                    }
                })
                .catch(error => {
                    console.log(response.json());
                    alert("Terjadi kesalahan.");
                    $btn.prop('disabled', false).html(originalHtml); 
                });
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> 
@endsection
