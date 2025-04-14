@extends('layouts.app2')

@section('title')
    <title>Dashboard Yayasan</title>
@endsection

@section('content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-home icon-gradient bg-mean-fruit"></i>
                </div>
                <div>
                    Halo {{ Auth::user()->name }}!
                    <div class="page-title-subheading">
                        Selamat datang di Sistem Informasi Akademik Yayasan Pendidikan
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Ringkasan Data --}}
    <div class="row">
        <div class="col-md-3 col-lg-3">
            <div class="card mb-3 widget-chart text-left" style="border-bottom: 4px solid var(--orange);">
                <div class="icon-wrapper rounded-circle">
                    <div class="icon-wrapper-bg bg-warning"></div>
                    <i class="pe-7s-users text-warning"></i>
                </div>
                <div class="widget-chart-content">
                    <div class="widget-subheading">Siswa TK</div>
                    <div class="h5 font-weight-bold mb-0">{{ $siswa_tk }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-lg-3">
            <div class="card mb-3 widget-chart text-left" style="border-bottom: 4px solid var(--blue);">
                <div class="icon-wrapper rounded-circle">
                    <div class="icon-wrapper-bg bg-info"></div>
                    <i class="pe-7s-users text-info"></i>
                </div>
                <div class="widget-chart-content">
                    <div class="widget-subheading">Siswa SD</div>
                    <div class="h5 font-weight-bold mb-0">{{ $siswa_sd }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-lg-3">
            <div class="card mb-3 widget-chart text-left" style="border-bottom: 4px solid var(--green);">
                <div class="icon-wrapper rounded-circle">
                    <div class="icon-wrapper-bg bg-success"></div>
                    <i class="pe-7s-id text-success"></i>
                </div>
                <div class="widget-chart-content">
                    <div class="widget-subheading">Total Guru</div>
                    <div class="h5 font-weight-bold mb-0">{{ $guru }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-lg-3">
            <div class="card mb-3 widget-chart text-left" style="border-bottom: 4px solid var(--red);">
                <div class="icon-wrapper rounded-circle">
                    <div class="icon-wrapper-bg bg-danger"></div>
                    <i class="pe-7s-door-lock text-danger"></i>
                </div>
                <div class="widget-chart-content">
                    <div class="widget-subheading">Jumlah Kelas</div>
                    <div class="h5 font-weight-bold mb-0">{{ $kelas }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    Kalender Kegiatan Yayasan
                </div>
                <div class="card-body">
                    <div id="kalender"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js'></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('kalender');
    
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 500,
                locale: 'id',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                buttonText: {
                    today: 'Hari ini',
                    month: 'Bulan',
                    week: 'Minggu',
                    day: 'Hari',
                    list: 'Agenda'
                },
                noEventsContent: 'Tidak ada kegiatan yang ditampilkan',
    
                events: @json($agenda)
            });
    
            calendar.render();
        });
    </script>
    
@endsection
