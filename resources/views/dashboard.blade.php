@extends('layouts.app2')

@section('title')
    <title>Beranda</title>
@endsection

@section('content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-home icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Hallo {{Auth::user()->name}} !!
                    <div class="page-title-subheading">
                        Selamat datang di sistem Penerimaan Peserta Didik Baru
                    </div>
                </div>
            </div>  
        </div> 
    </div>
    <div class="row">
        <div class="col-md-7">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <h5 class="card-title">Alur Penerimaan peserta didik baru</h5>
                    <div class="vertical-time-icons vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
                        <div class="vertical-timeline-item vertical-timeline-element">
                            <div>
                                <div class="vertical-timeline-element-icon bounce-in">
                                    <div class="timeline-icon border-primary">
                                        <i class="pe-7s-cloud-upload"></i>
                                    </div>
                                </div>
                                <div class="vertical-timeline-element-content bounce-in">
                                    <p>Langkah Pertama</p>
                                    <h4 class="timeline-title mt-2">Ajukan Pendaftaran</h4>
                                    <p>Pilih gelombang pendaftaran dan pilih jursan yang anda inginkan di menu pendaftaran.</p>
                                </div>
                            </div>
                        </div>
                        <div class="vertical-timeline-item vertical-timeline-element">
                            <div>
                                <div class="vertical-timeline-element-icon bounce-in">
                                    <div class="timeline-icon border-primary">
                                        <i class="pe-7s-note2"></i>
                                    </div>
                                </div>
                                <div class="vertical-timeline-element-content bounce-in">
                                    <p>Langkah Kedua</p>
                                    <h4 class="timeline-title mt-2">Mengisi Formulir Pendaftaran</h4>
                                    <p>Isi setiap formulir dengan benar dan sesuai dengan identitas diri ananda.</p>
                                </div>
                            </div>
                        </div>
                        <div class="vertical-timeline-item vertical-timeline-element">
                            <div>
                                <div class="vertical-timeline-element-icon bounce-in">
                                    <div class="timeline-icon border-primary">
                                        <i class="pe-7s-id"></i>
                                    </div>
                                </div>
                                <div class="vertical-timeline-element-content bounce-in">
                                    <p>Langkah Ketiga</p>
                                    <h4 class="timeline-title mt-2">Unggah Dokumen Kependudukan</h4>
                                    <p>Unggah dokumen kependudukan dengan benar dan sesuai dengan identitas diri ananda.</p>
                                </div>
                            </div>
                        </div>
                        <div class="vertical-timeline-item vertical-timeline-element">
                            <div>
                                <div class="vertical-timeline-element-icon bounce-in">
                                    <div class="timeline-icon border-primary">
                                        <i class="pe-7s-print"></i>
                                    </div>
                                </div>
                                <div class="vertical-timeline-element-content bounce-in">
                                    <p>Langkah Keempat</p>
                                    <h4 class="timeline-title mt-2">Konfirmasi Pembayaran</h4>
                                    <p>Segera lakukan pembayaran pendaftaran ketika sudah selesai melakukan proses pendaftaran.</p>
                                </div>
                            </div>
                        </div>
                        <div class="vertical-timeline-item vertical-timeline-element">
                            <div>
                                <div class="vertical-timeline-element-icon bounce-in">
                                    <div class="timeline-icon border-primary">
                                        <i class="pe-7s-speaker"></i>
                                    </div>
                                </div>
                                <div class="vertical-timeline-element-content bounce-in">
                                    <p>Langkah Kelima</p>
                                    <h4 class="timeline-title mt-2">Tunggu Pengumuman</h4>
                                    <p>Panitia akan mengumumkan hasil seleksi penerimaan peserta didik baru di menu pendaftaran.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <h5 class="card-title">Kontak PPDB</h5>
                            <div class="table-responsive">
                                <table class="table table striped">
                                    <tr>
                                        <th>Konfirmasi Pembayaran</th>
                                        <td><a href="http://wa.me/+6281318490859" target="_blank" rel="noopener noreferrer">+6281318490859</a></td>
                                    </tr>
                                    <tr>
                                        <th>Admin Web PPDB</th>
                                        <td><a href="http://wa.me/+6289609592234" target="_blank" rel="noopener noreferrer">+6289609592234</a></td>
                                    </tr>
                                    <tr>
                                        <th>Info PPDB</th>
                                        <td><a href="http://wa.me/+6289624502559" target="_blank" rel="noopener noreferrer">+6289624502559</a></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection