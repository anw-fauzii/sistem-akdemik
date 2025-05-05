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
                        Selamat datang di Sistem Informasi Akademik Yayasan Pendidikan Prima Insani
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    Pengumuman
                </div>
                <div class="card-body">
                    <div class="vertical-time-icons vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
                        @forelse ($pengumuman as $item)
                            <div class="vertical-timeline-item vertical-timeline-element">
                                <div>
                                    <div class="vertical-timeline-element-icon bounce-in">
                                        <div class="timeline-icon border-primary">
                                            <i class="pe-7s-speaker"></i>
                                        </div>
                                    </div>
                                    <div class="vertical-timeline-element-content bounce-in">
                                        <p>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</p>
                                        <h4 class="timeline-title mt-2">{{ $item->judul }}</h4>
                                        <p>{!! $item->isi !!}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            
                        @endforelse
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    Kalender Kegiatan Sekolah
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
