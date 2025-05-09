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
                <div>Pembayaran SPP {{$tahun_ajaran->nama_tahun_ajaran}}
                    <div class="page-title-subheading">
                        Merupakan pembayaran yang dilakukan secara tahunan dan bisa dicicil.
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
                                    <th>Jenis Tagihan</th>
                                    <th>Total Tagihan</th>
                                    <th>Total Dibayar</th>
                                    <th>Sisa Tagihan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            @php $no = 1; @endphp
                            <tbody>
                                @foreach ($hasil_tagihan as $item)
                                <tr>
                                    <td>{{$no++}}</td>
                                    <td>{{ $item['jenis'] }}</td>
                                    <td>Rp {{ number_format($item['total_tagihan'], 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item['total_dibayar'], 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item['sisa_tagihan'], 0, ',', '.') }}</td>
                                    <td>
                                        @if($item['status'] !== 'Lunas')
                                            <button class="btn btn-sm btn-primary pay-button"
                                                data-tagihan-id="{{ $item['id'] }}"
                                                data-sisa="{{ $item['sisa_tagihan'] }}"
                                                style="font-size: 0.75rem; padding: 2px 6px;">
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
@include('pesertaDidik.keuangan_tahunan.modal')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.querySelectorAll('.pay-button').forEach(function(button) {
        button.addEventListener('click', function() {
            $('#modalPembayaran').appendTo('body').modal('show');
            const tagihanId = this.getAttribute('data-tagihan-id');
            const sisaTagihan = this.getAttribute('data-sisa');

            document.getElementById('tagihan_id').value = tagihanId;
            document.getElementById('sisaTagihanText').textContent = "Sisa tagihan: Rp " + parseInt(sisaTagihan).toLocaleString('id-ID');
            document.getElementById('nominal').max = sisaTagihan;

            // Reset form
            document.getElementById('formPembayaran').reset();
            document.getElementById('nominalCicilGroup').style.display = 'none';

            new bootstrap.Modal(document.getElementById('modalPembayaran')).show();
        });
    });

    document.getElementById('metode').addEventListener('change', function () {
        const value = this.value;
        if (value === 'cicil') {
            document.getElementById('nominalCicilGroup').style.display = 'block';
            document.getElementById('nominal').required = true;
        } else {
            document.getElementById('nominalCicilGroup').style.display = 'none';
            document.getElementById('nominal').required = false;
        }
    });
</script>
@endsection
