@extends('layouts.app2')

@section('title')
    <title>QRCode</title>
@endsection

@section('content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-cash icon-gradient bg-mean-fruit"></i>
                </div>
                <div>QR Code siswa
                    <div class="page-title-subheading">
                        Merupakan kode unique untuk melakukan presensi
                    </div>
                </div>
            </div>  
        </div> 
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="main-card card">
                <div class="card-body" style="display: flex;
                    justify-content: center;
                    align-items: center;">
                    @php
                        echo DNS2D::getBarcodeHTML(strval(Auth::user()->email), 'QRCODE', 10, 10);
                    @endphp
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
