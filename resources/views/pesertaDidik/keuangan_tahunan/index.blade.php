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
                <div>Pembayaran Tahunan {{$tahun_ajaran->nama_tahun_ajaran}}</div>
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
                            <button class="btn btn-primary dropdown" type="button" id="dropdownMenu2" data-bs-toggle="dropdown">
                                <i class="metismenu-icon pe-7s-refresh-2"></i> TAHUN AJARAN
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                @foreach($tahun_selama_belajar as $item)
                                    @if(optional($item->kelas)->tahun_ajaran)
                                        <li>
                                            <a href="{{ route('keuangan-tahunan.show', $item->kelas->tahun_ajaran_id) }}" class="dropdown-item">
                                                {{ $item->kelas->tahun_ajaran->nama_tahun_ajaran }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul> 
                        </div>

                        <div class="card-body">
                            <div class="table-responsive d-none d-md-block">
                                <table class="table table-hover table-striped mb-0" id="myTable2">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Jenis Tagihan</th>
                                            <th>Total</th>
                                            <th>Dibayar</th>
                                            <th>Sisa</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no = 1; @endphp
                                        @foreach ($hasil_tagihan as $item)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $item['jenis'] }}</td>
                                            <td>Rp {{ number_format($item['total_tagihan']) }}</td>
                                            <td>Rp {{ number_format($item['total_dibayar']) }}</td>
                                            <td>Rp {{ number_format($item['sisa_tagihan']) }}</td>
                                            <td>
                                                @if($item['status'] !== 'Lunas')
                                                    <button class="btn btn-sm btn-primary pay-button"
                                                        style="font-size: 0.75rem; padding: 2px 6px;"
                                                        data-tagihan-id="{{ $item['id'] }}"
                                                        data-sisa="{{ $item['sisa_tagihan'] }}">
                                                        <i class="pe-7s-cash" style="font-size: 0.85rem;"></i> Bayar
                                                    </button>
                                                @else
                                                    <div class="badge badge-pill badge-success text-white">Lunas</div>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-block d-md-none">
                                @php $no = 1; @endphp
                                @foreach ($hasil_tagihan as $item)
                                    <div class="card shadow-sm mb-3 border-0" style="border-left: 4px solid {{ $item['status'] === 'Lunas' ? '#28a745' : '#ffc107' }}; background: aliceblue">
                                        <div class="card-body py-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="mb-0">
                                                    <i class="bi bi-calendar-check-fill text-primary me-1"></i>
                                                    <strong>{{ $item['jenis'] }}</strong>
                                                </h6>
                                                <span class="badge badge-pill {{ $item['status'] === 'Lunas' ? 'bg-success text-white' : 'bg-warning text-dark' }}">
                                                    {{ $item['status'] === 'Lunas' ? "Lunas" : "Belum Lunas"}} 
                                                </span>
                                            </div>

                                            <div class="row text-muted small">
                                                <div class="col-6">Total Tagihan</div>
                                                <div class="col-6 text-end">Rp {{ number_format($item['total_tagihan']) }}</div>
                                                <div class="col-6">Sudah Dibayar</div>
                                                <div class="col-6 text-end">Rp {{ number_format($item['total_dibayar']) }}</div>
                                                <div class="col-6">Sisa Tagihan</div>
                                                <div class="col-6 text-end">Rp {{ number_format($item['sisa_tagihan']) }}</div>
                                            </div>
                                            
                                            @if($item['status'] !== 'Lunas')
                                                <div class="mt-3 text-end">
                                                    <button class="btn btn-sm btn-primary pay-button"
                                                        style="font-size: 0.75rem; padding: 2px 6px;"
                                                        data-tagihan-id="{{ $item['id'] }}"
                                                        data-sisa="{{ $item['sisa_tagihan'] }}">
                                                        <i class="pe-7s-cash" style="font-size: 0.85rem;"></i> Bayar Sekarang
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
        <div class="tab-pane tabs-animation fade show" id="riwayat" role="tabpanel">
            <div class="row">
                <div class="col-md-12">
                    <div class="main-card card">
                        <div class="card-header">
                            <button class="btn btn-primary dropdown" type="button" id="dropdownMenu2" data-bs-toggle="dropdown">
                                <i class="metismenu-icon pe-7s-refresh-2"></i> TAHUN AJARAN
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                @foreach($tahun_selama_belajar as $item)
                                    @if(optional($item->kelas)->tahun_ajaran)
                                        <li>
                                            <a href="{{ route('keuangan-tahunan.show', $item->kelas->tahun_ajaran_id) }}" class="dropdown-item">
                                                {{ $item->kelas->tahun_ajaran->nama_tahun_ajaran }}
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
                                            <th>Jenis Tagihan</th>
                                            <th>Jumlah Bayar</th>
                                            <th>Tanggal Bayar</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no = 1; @endphp
                                        @foreach ($riwayat_pembayaran as $item)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $item->order_id }}</td>
                                            <td>{{ $item->tagihanTahunan->jenis }}</td>
                                            <td>Rp. {{ number_format($item->jumlah_bayar) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->create_at)->format('d/m/Y') }}</td>
                                            <td>
                                                <a href="{{ route('keuangan-tahunan.invoice', $item->order_id) }}" class="btn btn-sm btn-primary mx-1 ml-1"><i class="pe-7s-print" style="font-size: 0.85rem;"></i></a>
                                                @if($item->keterangan == 'PENDING')
                                                <button class="btn btn-sm btn-primary lanjut-bayar-button"
                                                    data-id="{{ $item->id }}"
                                                    style="font-size: 0.75rem; padding: 2px 6px;">
                                                    <i class="pe-7s-cash" style="font-size: 0.85rem;"></i> Bayar
                                                </button>
                                                @elseif($item->keterangan == 'LUNAS')
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

                            <div class="d-block d-md-none">
                                @php $no = 1; @endphp
                                @foreach ($hasil_tagihan as $item)
                                    <div class="card shadow-sm mb-3 border-0" style="border-left: 4px solid {{ $item['status'] === 'Lunas' ? '#28a745' : '#ffc107' }}; background: aliceblue">
                                        <div class="card-body py-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="mb-0">
                                                    <i class="bi bi-calendar-check-fill text-primary me-1"></i>
                                                    <strong>{{ $item['jenis'] }}</strong>
                                                </h6>
                                                <span class="badge badge-pill {{ $item['status'] === 'Lunas' ? 'bg-success text-white' : 'bg-warning text-dark' }}">
                                                    {{ $item['status'] === 'Lunas' ? "Lunas" : "Belum Lunas"}} 
                                                </span>
                                            </div>

                                            <div class="row text-muted small">
                                                <div class="col-6">Total Tagihan</div>
                                                <div class="col-6 text-end">Rp {{ number_format($item['total_tagihan']) }}</div>
                                                <div class="col-6">Sudah Dibayar</div>
                                                <div class="col-6 text-end">Rp {{ number_format($item['total_dibayar']) }}</div>
                                                <div class="col-6">Sisa Tagihan</div>
                                                <div class="col-6 text-end">Rp {{ number_format($item['sisa_tagihan']) }}</div>
                                            </div>
                                            
                                            @if($item['status'] !== 'Lunas')
                                                <div class="mt-3 text-end">
                                                    <button class="btn btn-sm btn-primary pay-button"
                                                        style="font-size: 0.75rem; padding: 2px 6px;"
                                                        data-tagihan-id="{{ $item['id'] }}"
                                                        data-sisa="{{ $item['sisa_tagihan'] }}">
                                                        <i class="pe-7s-cash" style="font-size: 0.85rem;"></i> Bayar Sekarang
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

@include('pesertaDidik.keuangan_tahunan.modal')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> 
<script>
    document.querySelectorAll('.pay-button').forEach(button => {
        button.addEventListener('click', () => {
            const tagihanId = button.dataset.tagihanId;
            const sisa = button.dataset.sisa;
    
            $('#modalPembayaran').appendTo('body').modal('show');
            document.getElementById('tagihan_id').value = tagihanId;
            document.getElementById('sisaTagihanText').textContent = "Sisa tagihan: Rp " + parseInt(sisa).toLocaleString('id-ID');
            document.getElementById('nominal').max = sisa;
            document.getElementById('formPembayaran').reset();
            document.getElementById('nominalCicilGroup').style.display = 'none';
        });
    });
    
    document.getElementById('metode').addEventListener('change', function () {
        document.getElementById('nominalCicilGroup').style.display = (this.value === 'cicil') ? 'block' : 'none';
    });
    
    $('#formPembayaran').on('submit', function (e) {
        e.preventDefault();
        const form = $(this);
        const data = form.serialize();
        const btnBayar = $('#btnBayar');
        btnBayar.prop('disabled', true).text('Memproses...');
        $.ajax({
            url: "{{ route('keuangan-tahunan.store') }}",
            method: "POST",
            data: data,
            headers: {
                'Accept': 'application/json'
            },
            success: function (response) {
                $('#modalPembayaran').modal('hide');
                btnBayar.prop('disabled', false).text('Bayar');
                snap.pay(response.snap_token, {
                    onSuccess: function(result) {
                        toastr.success("Pembayaran berhasil!");
                        setTimeout(function() {
                            window.location.href = "/keuangan-tahunan";
                        }, 1500); 
                    },
                    onPending: function(result) {
                        toastr.info("Pembayaran menunggu konfirmasi.");
                        setTimeout(function() {
                            window.location.href = "/keuangan-tahunan";
                        }, 1500); 
                    },
                    onError: function(result) {
                        toastr.error("Pembayaran gagal!");
                        setTimeout(function() {
                            window.location.href = "/keuangan-tahunan";
                        }, 1500); 
                    },
                    onClose: function() {
                        window.location.reload();
                    }
                });
            },
            error: function (xhr) {

                let message = 'Terjadi kesalahan saat memproses pembayaran.';
                
                if (xhr.status === 422) {
                    try {
                        const res = JSON.parse(xhr.responseText);
                        if (res.message) {
                            message = res.message;
                        }
                    } catch (e) {
                        console.error('Gagal parsing responseText:', e);
                    }
                }

                toastr.error(message);
                btnBayar.prop('disabled', false).text('Bayar');
            }
        });
    });
    document.querySelectorAll('.lanjut-bayar-button').forEach(function(button) {
        button.addEventListener('click', function() {
            var tagihanId = this.getAttribute('data-id');
            fetch('/lanjut-pembayaran-tahunan/' + tagihanId)
                .then(response => response.json())
                .then(data => {
                    const snapToken = data.snap_token;
                    if (snapToken) {
                        snap.pay(snapToken, {
                            onSuccess: function(result) {
                                toastr.success("Pembayaran berhasil!");
                                setTimeout(function() {
                                    window.location.href = "/keuangan-tahunan";
                                }, 1500); 
                            },
                            onPending: function(result) {
                                toastr.info("Pembayaran menunggu konfirmasi.");
                                setTimeout(function() {
                                    window.location.href = "/keuangan-tahunan";
                                }, 1500); 
                            },
                            onError: function(result) {
                                toastr.error("Pembayaran gagal!");
                                setTimeout(function() {
                                    window.location.href = "/keuangan-tahunan";
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
@endsection
