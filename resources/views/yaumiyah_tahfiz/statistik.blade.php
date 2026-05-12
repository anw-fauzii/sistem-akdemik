@extends('layouts.app2')

@section('title')
    <title>Statistik Tahfiz - Tingkat {{ $tingkat }}</title>
    <script src="https://code.highcharts.com/highcharts.js"></script>
@endsection

@section('content')
    <div class="app-main__inner">

        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon shadow-sm bg-white">
                        <i class="pe-7s-graph2 text-success"></i>
                    </div>
                    <div>Dashboard Statistik Tahfiz - Tingkat {{ $tingkat }}
                        <div class="page-title-subheading">
                            Ringkasan performa kelas dan pencapaian siswa periode <strong>{{ $namaBulan }}</strong>.
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <a href="{{ route('yaumiyah-tahfiz.index') }}"
                        class="btn btn-outline-secondary btn-sm shadow-sm rounded-pill px-3">
                        <i class="pe-7s-back mr-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-body p-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center">

                        <div class="d-flex align-items-center mb-3 mb-md-0">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mr-3"
                                style="width: 45px; height: 45px;">
                                <i class="pe-7s-date text-primary" style="font-size: 1.5rem;"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 font-weight-bold">Filter Periode Bulan</h6>
                                <small class="text-muted">Pilih bulan spesifik untuk menyesuaikan data statistik.</small>
                            </div>
                        </div>

                        <div>
                            <form id="form-filter-statistik"
                                action="{{ route('yaumiyah-tahfiz.statistik', ['tingkat' => $tingkat]) }}" method="GET">
                                <div class="input-group shadow-sm" style="border-radius: 8px; overflow: hidden;">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-light text-muted">
                                            <i class="pe-7s-menu"></i>
                                        </span>
                                    </div>

                                    <select name="tahun_ajaran_id" id="tahun_ajaran_id"
                                        class="custom-select custom-select-lg border-light text-dark font-weight-bold"
                                        style="min-width: 150px; font-size: 0.95rem; cursor: pointer;"
                                        onchange="fetchBulan(this.value)">
                                        <option value="">-- Pilih Tahun Ajaran --</option>
                                        @foreach ($tahunajaran as $tahun)
                                            <option value="{{ $tahun->id }}"
                                                {{ request('tahun_ajaran_id', $tahunAjaranAktif->id ?? '') == $tahun->id ? 'selected' : '' }}>
                                                {{ $tahun->nama_tahunajaran ?? $tahun->nama_tahun_ajaran }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <select name="bulan_spp_id" id="bulan_spp_id"
                                        class="custom-select custom-select-lg border-light text-dark font-weight-bold"
                                        style="min-width: 150px; font-size: 0.95rem; cursor: pointer;"
                                        onchange="this.form.submit()">
                                        <option value="">-- Pilih Bulan --</option>
                                        @foreach ($listBulan as $bulan)
                                            <option value="{{ $bulan->id }}"
                                                {{ request('bulan_spp_id', $bulanSpp->id ?? '') == $bulan->id ? 'selected' : '' }}>
                                                {{ $bulan->nama_bulan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="card mb-3 widget-content border-0 shadow-sm">
                    <div class="widget-content-wrapper">
                        <div class="widget-content-left">
                            <div class="widget-heading">Rata-Rata Nilai</div>
                            <div class="widget-subheading">Bulan {{ $namaBulan }}</div>
                        </div>
                        <div class="widget-content-right">
                            <div class="widget-numbers text-success"><span>{{ number_format($avgBulanIni, 1) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mb-3 widget-content border-0 shadow-sm">
                    <div class="widget-content-wrapper">
                        <div class="widget-content-left">
                            <div class="widget-heading">Total Siswa</div>
                            <div class="widget-subheading">Tingkat {{ $tingkat }}</div>
                        </div>
                        <div class="widget-content-right">
                            <div class="widget-numbers text-primary"><span>{{ $totalSiswa }}</span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mb-3 widget-content border-0 shadow-sm">
                    <div class="widget-content-wrapper">
                        <div class="widget-content-left">
                            <div class="widget-heading">Total Setoran</div>
                            <div class="widget-subheading">Di Bulan {{ $namaBulan }}</div>
                        </div>
                        <div class="widget-content-right">
                            <div class="widget-numbers text-info"><span>{{ $totalSetoran }}</span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mb-3 widget-content border-0 shadow-sm">
                    <div class="widget-content-wrapper">
                        <div class="widget-content-left">
                            <div class="widget-heading">Perlu Perhatian</div>
                            <div class="widget-subheading">Rata-rata < 75</div>
                            </div>
                            <div class="widget-content-right">
                                <div class="widget-numbers text-danger"><span>{{ $perluPerhatian }}</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="main-card mb-3 card border-0 shadow-sm">
                        <div
                            class="card-header bg-white border-bottom-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
                            <h5 class="card-title font-weight-bold mb-0">Persentase Capaian Target</h5>
                            <span class="badge badge-info px-3 py-2">Target:
                                {{ $targetJilidNama ?? 'Belum Diatur' }}</span>
                        </div>
                        <div class="card-body">
                            @if (isset($targetCapaian) && $targetCapaian)
                                <div id="chart-target-capaian" style="min-height: 350px;"></div>
                                <div class="text-center mt-3 border-top pt-3">
                                    <div class="row">
                                        <div class="col-6 border-right">
                                            <div class="text-muted small font-weight-bold">MENCAPAI TARGET</div>
                                            <h4 class="text-primary font-weight-bold mb-0">{{ $mencapaiTarget }} <small
                                                    class="text-muted">Siswa</small></h4>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-muted small font-weight-bold">BELUM MENCAPAI</div>
                                            <h4 class="text-warning font-weight-bold mb-0">{{ $belumMencapai }} <small
                                                    class="text-muted">Siswa</small></h4>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-5 text-muted">
                                    <i class="pe-7s-attention d-block mb-2" style="font-size: 3rem; opacity: 0.5;"></i>
                                    <p class="mb-0">Target Capaian untuk Tingkat {{ $tingkat }} belum diatur oleh
                                        Admin.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="main-card mb-3 card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
                            <h5 class="card-title font-weight-bold">Sebaran Posisi Jilid Siswa</h5>
                        </div>
                        <div class="card-body d-flex justify-content-center align-items-center">
                            <div id="chart-sebaran-jilid" style="min-height: 350px; width: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="main-card mb-3 card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
                            <h5 class="card-title font-weight-bold">Tren Rata-Rata Nilai Harian</h5>
                        </div>
                        <div class="card-body">
                            <div id="chart-tren-nilai" style="min-height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="main-card mb-3 card border-0 shadow-sm">
                        <div class="card-header bg-white pt-3 pb-2 text-success font-weight-bold">
                            <i class="pe-7s-medal mr-2" style="font-size: 1.2rem;"></i> Top 5 Siswa Terbaik (Bulan Ini)
                        </div>
                        <div class="table-responsive">
                            <table class="align-middle mb-0 table table-borderless table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-center" width="10%">#</th>
                                        <th>Nama Siswa</th>
                                        <th class="text-center" width="20%">Rata-rata</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $rank = 1; @endphp
                                    @forelse($topSiswa as $siswaTop)
                                        <tr>
                                            <td class="text-center text-muted">{{ $rank++ }}</td>
                                            <td>
                                                <div class="font-weight-bold text-dark">
                                                    {{ $siswaTop->anggotaKelas->siswa->nama_lengkap ?? '-' }}</div>
                                                <div class="small text-muted">Kelas
                                                    {{ $siswaTop->anggotaKelas->kelas->nama_kelas ?? '-' }} •
                                                    {{ $siswaTop->total_setoran }}x Setoran</div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-success badge-pill px-3 py-2"
                                                    style="font-size: 0.9rem;">{{ $siswaTop->rata_rata }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-4 text-muted">Belum ada data nilai
                                                yang cukup bulan ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="main-card mb-3 card border-0 shadow-sm">
                        <div class="card-header bg-white pt-3 pb-2 text-danger font-weight-bold">
                            <i class="pe-7s-attention mr-2" style="font-size: 1.2rem;"></i> Perlu Bimbingan Khusus
                        </div>
                        <div class="table-responsive">
                            <table class="align-middle mb-0 table table-borderless table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Nama Siswa</th>
                                        <th class="text-center" width="30%">Nilai (Rata-rata)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($bottomSiswa as $siswaBot)
                                        <tr>
                                            <td>
                                                <div class="font-weight-bold text-dark">
                                                    {{ $siswaBot->anggotaKelas->siswa->nama_lengkap ?? '-' }}</div>
                                                <div class="small text-muted">Kelas
                                                    {{ $siswaBot->anggotaKelas->kelas->nama_kelas ?? '-' }}</div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-danger badge-pill px-3 py-2"
                                                    style="font-size: 0.9rem;">{{ $siswaBot->rata_rata }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center py-4 text-muted">Alhamdulillah, tidak
                                                ada siswa yang berada di bawah KKM bulan ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function fetchBulan(tahunAjaranId) {
                const bulanSelect = document.getElementById('bulan_spp_id');

                // Ubah teks sementara jadi loading dan nonaktifkan dropdown
                bulanSelect.innerHTML = '<option value="">-- Memuat Data... --</option>';
                bulanSelect.disabled = true;

                if (!tahunAjaranId) {
                    bulanSelect.innerHTML = '<option value="">-- Pilih Bulan --</option>';
                    return;
                }

                // Ambil URL dasar dari aplikasi Laravel
                const url = `{{ url('/get-bulan-spp') }}/${tahunAjaranId}`;

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        bulanSelect.innerHTML = '<option value="">-- Pilih Bulan --</option>';

                        if (data.length > 0) {
                            data.forEach(bulan => {
                                const option = document.createElement('option');
                                option.value = bulan.id;
                                option.textContent = bulan.nama_format;
                                bulanSelect.appendChild(option);
                            });
                            // Aktifkan kembali dropdown jika ada data
                            bulanSelect.disabled = false;
                        } else {
                            bulanSelect.innerHTML = '<option value="">-- Data Bulan Kosong --</option>';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                        bulanSelect.innerHTML = '<option value="">-- Gagal Memuat --</option>';
                    });
            }
            document.addEventListener("DOMContentLoaded", function() {

                // --- 1. RENDER CHART PIE (TARGET VS REALISASI) ---
                @if (isset($targetCapaian) && $targetCapaian)
                    var mencapai = {{ $mencapaiTarget ?? 0 }};
                    var belumMencapai = {{ $belumMencapai ?? 0 }};

                    Highcharts.chart('chart-target-capaian', {
                        chart: {
                            type: 'pie',
                            height: 350,
                            backgroundColor: 'transparent'
                        },
                        title: {
                            text: null
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b> ({point.y} Siswa)'
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '{point.percentage:.1f} %',
                                    distance: -40, // Angka persentase ada di dalam lingkaran
                                    style: {
                                        fontWeight: 'bold',
                                        color: 'white',
                                        fontSize: '14px',
                                        textOutline: 'none'
                                    }
                                },
                                showInLegend: true
                            }
                        },
                        // Warna persis Excel: Biru (#4472c4) dan Oranye (#ed7d31)
                        colors: ['#4472c4', '#ed7d31'],
                        series: [{
                            name: 'Jumlah Siswa',
                            colorByPoint: true,
                            data: [{
                                    name: 'Siswa Mencapai Target',
                                    y: mencapai
                                },
                                {
                                    name: 'Siswa Belum Mencapai Target',
                                    y: belumMencapai
                                }
                            ]
                        }],
                        legend: {
                            position: 'bottom'
                        },
                        credits: {
                            enabled: false
                        }
                    });
                @endif

                // --- 2. RENDER CHART BAR/COLUMN (SEBARAN JILID) ---
                var sebaranData = @json($sebaranJilid);
                var categoriesBar = [];
                var dataBar = [];

                // Memisahkan key (nama jilid) dan value (jumlah siswa) untuk Bar Chart
                for (var key in sebaranData) {
                    categoriesBar.push(key);
                    dataBar.push(Number(sebaranData[key]));
                }

                // Cegah error jika data kosong
                if (categoriesBar.length === 0) {
                    categoriesBar = ['Belum Ada Data'];
                    dataBar = [0];
                }

                Highcharts.chart('chart-sebaran-jilid', {
                    chart: {
                        type: 'column', // Ubah menjadi 'bar' jika ingin baloknya memanjang ke samping (horizontal)
                        height: 350,
                        backgroundColor: 'transparent'
                    },
                    title: {
                        text: null
                    },
                    xAxis: {
                        categories: categoriesBar,
                        crosshair: true,
                        labels: {
                            style: {
                                fontSize: '11px',
                                fontWeight: 'bold'
                            }
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Jumlah Siswa'
                        },
                        allowDecimals: false // Angka jumlah orang tidak mungkin koma
                    },
                    tooltip: {
                        pointFormat: '<b>{point.y} Siswa</b> berada di tingkat ini'
                    },
                    plotOptions: {
                        column: {
                            borderRadius: 4, // Membuat ujung bar sedikit melengkung (modern)
                            dataLabels: {
                                enabled: true, // Munculkan angka di atas bar
                                format: '{point.y}',
                                style: {
                                    fontWeight: 'bold',
                                    color: '#495057',
                                    textOutline: 'none'
                                }
                            }
                        }
                    },
                    // Warna-warni bar persis seperti donat
                    colors: ['#3ac47d', '#f7b924', '#d92550', '#16aaff', '#6f42c1', '#f83e70'],
                    series: [{
                        name: 'Total Siswa',
                        colorByPoint: true, // Buat setiap balok berbeda warna
                        data: dataBar,
                        showInLegend: false // Disembunyikan karena nama jilid sudah ada di teks bawah (xAxis)
                    }],
                    credits: {
                        enabled: false
                    }
                });

                // --- 3. RENDER CHART AREA (TREN NILAI) ---
                var trenData = @json($trenNilai);
                var labelsArea = [];
                var seriesArea = [];

                trenData.forEach(function(item) {
                    var date = new Date(item.tanggal);
                    labelsArea.push(date.getDate() + '/' + (date.getMonth() + 1));
                    seriesArea.push(parseFloat(parseFloat(item.rata_rata).toFixed(1)));
                });

                Highcharts.chart('chart-tren-nilai', {
                    chart: {
                        type: 'areaspline',
                        height: 300,
                        backgroundColor: 'transparent'
                    },
                    title: {
                        text: null
                    },
                    xAxis: {
                        categories: labelsArea,
                        title: {
                            text: 'Tanggal Penilaian',
                            style: {
                                color: '#8c98a4',
                                fontSize: '12px'
                            }
                        }
                    },
                    yAxis: {
                        min: 0,
                        max: 100,
                        title: {
                            text: 'Nilai Rata-rata',
                            style: {
                                color: '#8c98a4'
                            }
                        }
                    },
                    tooltip: {
                        valueSuffix: ' Poin'
                    },
                    plotOptions: {
                        areaspline: {
                            fillOpacity: 0.2,
                            color: '#16aaff',
                            marker: {
                                enabled: false
                            }
                        }
                    },
                    series: [{
                        name: 'Rata-rata Nilai',
                        data: seriesArea
                    }],
                    legend: {
                        enabled: false
                    },
                    credits: {
                        enabled: false
                    }
                });

            });
        </script>
    @endsection
