@extends('layouts.app2')

@section('title')
    <title>Laporan Presensi</title>
@endsection

@section('content')

<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-graph2 icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Presensi Pekanan
                    <div class="page-title-subheading">
                        Laporan keterlambatan untuk di share ke orangtua.
                    </div>
                </div>
            </div>  
        </div> 
    </div>
    <div class="main-card card mb-4">
        <div class="card-body">
            <form action="{{ route('laporan.presensi.pekanan.cari') }}" method="POST">
                @csrf
                <div class="row align-items-center">
                    <div class="col-md-9 d-flex align-items-center">
                        <label class="mr-sm-2 text-nowrap" style="text-align: right;">Tanggal</label>
                    <input type="date" class="form-control" name="tanggal" value="{{isset($tanggal)  ? $tanggal : ''}}" required>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary w-100">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @if(isset($dataChart))
        <div class="main-card card">
            <div class="card-header">
                <button class="btn btn-success" onclick="downloadJPG()">Download JPG</button>
            </div>
            <div class="card-body">

                <!-- Wrapper dengan latar belakang -->
                <div id="wrapper" style="position: relative; width: 620px; margin: auto;">
                    <!-- Gambar background -->
                    <img src="{{ asset('bg-pekanan.jpeg') }}" style="position: absolute; top: 0; left: 0; width: 100%; z-index: 1;" />

                    <div style="position: relative; z-index: 2; text-align: center; padding-top: 40px;">
                        <div style="font-size: 17px; font-weight: bold; line-height: 1.0;margin-top:18px;margin-right:30px">
                            REKAPITULASI KETERLAMBATAN SISWA<br>
                            SD GIS PRIMA INSANI {{$tahunAjaran->nama_tahun_ajaran}}
                        </div>
                        <div style="font-size: 14px;">
                            {{$judul}}
                        </div>
                    </div>
                    <div id="chart-container" style="position: relative; z-index: 2; padding: 35px 40px 40px;">
                    </div>
                </div>
            </div>
        </div>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>

    <!-- html2canvas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <script>
        // Pie Chart
        Highcharts.chart('chart-container', {
            chart: {
                type: 'pie',
                height: 430, 
                backgroundColor: 'transparent',
                spacingTop: 0,  
                spacingBottom: 80,
                spacingLeft: 0,
                spacingRight: 0,
            },
            exporting: {
                enabled: false 
            },
            title: {
                text: null
            },
            tooltip: {
                pointFormat: '{point.name}: <b>{point.x}</b> kali terlambat<br>({point.percentage:.1f}%)'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            credits: {
                enabled: false
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}: {point.percentage:.1f}%',
                        style: {
                            fontSize: '14px',
                            fontWeight: 'bold'
                        }
                    }
                }
            },
            series: [{
                name: 'Keterlambatan',
                colorByPoint: true,
                data: {!! json_encode($dataChart) !!}
            }]
        });

        function downloadJPG() {
            html2canvas(document.getElementById('wrapper')).then(canvas => {
                let link = document.createElement('a');
                link.download = 'rekapitulasi-keterlambatan.jpg';
                link.href = canvas.toDataURL('image/jpeg');
                link.click();
            });
        }
    </script>
    @endif
</div>
@endsection
