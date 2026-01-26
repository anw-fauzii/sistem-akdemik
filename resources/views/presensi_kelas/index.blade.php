@extends('layouts.app2')

@section('title')
    <title>Presensi</title>
@endsection

@section('content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-smile icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Presensi @role('admin') <strong>Kelas {{$kelas->nama_kelas}}</strong> @endrole {{$bulan->nama_bulan}}
                    <div class="page-title-subheading">
                        Merupakan Presensi yang Berada di sekolah
                    </div>
                </div>
            </div>  
        </div> 
    </div>
    <div class="row">
        <div class="col-md-6 col-xl-4">
            <div class="card mb-3 widget-content"  style="border-bottom: 4px solid var(--red);"">
                <div class="widget-content-wrapper">
                    <div class="widget-content-left">
                        <div class="widget-heading">Sakit</div>
                        <div class="widget-subheading">Kumulasi siswa sakit</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers"><span>{{$presensi->where('status', 'sakit')->count()}}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-4">
            <div class="card mb-3 widget-content" style="border-bottom: 4px solid var(--red);">
                <div class="widget-content-wrapper">
                    <div class="widget-content-left">
                        <div class="widget-heading">Izin</div>
                        <div class="widget-subheading">Kumulasi siswa izin</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers"><span>{{$presensi->where('status', 'izin')->count()}}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-4">
            <div class="card mb-3 widget-content" style="border-bottom: 4px solid var(--red);"">
                <div class="widget-content-wrapper">
                    <div class="widget-content-left">
                        <div class="widget-heading">Alpha</div>
                        <div class="widget-subheading">Kumulasi tanapa keterangan</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers"><span>{{$presensi->where('status', 'alpha')->count()}}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card mb-3 widget-content"  style="border-bottom: 4px solid var(--green);"">
                <div class="widget-content-wrapper">
                    <div class="widget-content-left">
                        <div class="widget-heading">Masuk</div>
                        <div class="widget-subheading">Siswa yang hadir sekolah</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers"><span>{{$persentaseHadir}}%</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card mb-3 widget-content"  style="border-bottom: 4px solid var(--yellow);"">
                <div class="widget-content-wrapper">
                    <div class="widget-content-left">
                        <div class="widget-heading">Absen</div>
                        <div class="widget-subheading">Siswa yang tidak hadir</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers"><span>{{$persentaseTidakHadir}}%</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card mb-3 widget-content"  style="border-bottom: 4px solid var(--green);"">
                <div class="widget-content-wrapper">
                    <div class="widget-content-left">
                        <div class="widget-heading">Tepat Waktu</div>
                        <div class="widget-subheading">Ketepatan waktu masuk</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers"><span>{{$persentaseTepatWaktu}}%</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card mb-3 widget-content"  style="border-bottom: 4px solid var(--yellow);"">
                <div class="widget-content-wrapper">
                    <div class="widget-content-left">
                        <div class="widget-heading">Keterlambatan</div>
                        <div class="widget-subheading">Terlambat waktu masuk</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers"><span>{{$persentaseTerlambat}}%</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="main-card card">
        <div class="card-header">
            @role('guru_sd')
                <a href="{{route('presensi-kelas.create')}}" class="btn btn-primary mr-2">Tambah Baru</a>
                <button class="btn btn-warning dropdown" type="button" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="metismenu-icon pe-7s-refresh-2"></i> PERIODE
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                    @foreach($bulan_spp as $bulan)
                    <li>
                        <a href="{{ route('presensi-kelas.show', $bulan->id) }}" class="dropdown-item">
                            {{ $bulan->nama_bulan }}
                        </a>
                    </li>
                    @endforeach
                </ul>
                <a href="{{route('export.laporan.bulanan.kelas.pdf', [
                    'kelas_id' => "0",
                    'bulan_id' => $bulan->id
                ])}}" class="btn btn-success ml-2">Download</a>
            @endrole
            @role(['admin','dapur'])
                <select id="select-periode" class="form-control multiple-select" required>
                    <option disabled selected>Pilih Kelas</option>
                    @foreach($data_kelas as $p)
                        <option value="{{ $p->id }}">{{ $p->nama_kelas }}</option>
                    @endforeach
                </select>
                <a href="{{route('export.laporan.bulanan.kelas.pdf', [
                    'kelas_id' => $kelas->id,
                    'bulan_id' => $bulan->id
                ])}}" class="btn btn-success ml-2">Download</a>
            @endrole
            
        </div> 
        <div class="card-body">
            <div class="table-responsive">
                <table class="mb-0 table table-hover table-striped" id="myTable2">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            @foreach ($tanggal_tercatat as $tanggal)
                                <th class="text-center">
                                    <a href="{{route('presensi-kelas.edit', \Carbon\Carbon::parse($tanggal)->format('Y-m-d'))}}" class="btn btn-sm border-0 btn-transition btn btn-outline-dark"><strong>{{ \Carbon\Carbon::parse($tanggal)->format('d/m') }}</strong></a>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @if ($tanggal_tercatat->isEmpty()) 
                        <tr>
                            <td colspan="2" class="text-center">Belum ada data </td>
                        </tr>
                        @else
                        @php
                            $no = 1;
                        @endphp
                            @foreach ($anggotaKelas as $anggota)
                                <tr>
                                    <td>{{$no++}}</td>
                                    <td>{{ $anggota->siswa->nama_lengkap }}</td>
                                    @foreach ($tanggal_tercatat as $tanggal)
                                        @php
                                            $presensiData = $presensi
                                                ->where('anggota_kelas_id', $anggota->id)
                                                ->first(function ($item) use ($tanggal) {
                                                    return \Carbon\Carbon::parse($item->tanggal)->toDateString() === $tanggal;
                                                });
                                        @endphp
                                            @if ($presensiData)
                                                @if ($presensiData->status == 'hadir')
                                                <td class="text-center"> &#10003; </td>
                                                @elseif ($presensiData->status == 'sakit')
                                                <td class="text-center" style="background-color: yellow;"> S </td>
                                                @elseif ($presensiData->status == 'izin')
                                                <td class="text-center" style="background-color: green; color:white"> I </td>
                                                @elseif ($presensiData->status == 'alpha')
                                                <td class="text-center" style="background-color: red; color:white"> A </td>
                                                @else
                                                    {{ $presensiData->status }} 
                                                @endif
                                            @else
                                                <td class="text-center" style="background-color: black;"> -</td>
                                            @endif
                                    @endforeach
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> 
<script>
    $(document).ready(function() {
        $('#select-periode').select2({
            placeholder: "Pilih Kelas",
            allowClear: true,
            width: '100%',
            dropdownAutoWidth: true,
            closeOnSelect: true
        });

        $('#select-periode').on('change', function () {
            const periode = $(this).val(); 

            if (periode) {
                const url = `/presensi-kelas/${periode}`;
                window.location.href = url;
            }
        });

    }); 
</script> 

@endsection
